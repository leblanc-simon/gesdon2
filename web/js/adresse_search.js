$(document).ready(function( ) {

    /**
     * Sur click du bouton Rechercher
     */
    $("#adresse_search")
        .click(function( event ){
            // empêcher le submit
            event.preventDefault();
            // récupérer le formulaire (le nom du formulaire est défini dans le Type)
            var form = $('#adresse_form');
            //requête ajax, appel de la route adresse_table
            $.ajax({
                type    : 'POST',
                url     : "./Adresse/table",
                data    : form.serialize(),
                //afficher l'erreur en cas de problème
                error:function(msg, string){
                    alert( "Error !: " + string );
                },
                success:function(htmlResponse){
                    //vider la div et mettre à jour la div avec les données reçues
                    $("#adresse_table")
                        .empty()
                        .hide()
                        .append(htmlResponse)
                        .fadeIn(100);
                }
            });
        })
        /*activer le bouton une fois que l'action est affectée*/
       /* .removeAttr('disabled')*/
    ;

    /**
     * Sur click du champ Donateur,
     * ouvrir une fenêtre modale contenant la liste des donateurs
     */
    $("#donateur").on( "click", function(){
        var donateur_id;
        var donateur_dialog;
        // si la fenêtre n'a pas été initialisée...
        if (typeof donateur_dialog === "undefined") {
            // créer une fenêtre à partir d'une nouvelle div
            donateur_dialog = $("<div title='Choisissez un donateur'></div>")
                .load('../Donateur')
                .dialog({
                    autoOpen: false,
                    height: 800,
                    width: 800,
                    modal: true
                });
            donateur_dialog
                .dialog("open")
                .on('click', '#donateur', function () {
                    donateur_id = $(this).find(".id").text();
                    donateur_dialog.dialog("close");
                    $("#donateur").val(donateur_id);
                });
        }
        // si la fenêtre existe déjà, l'ouvrir
        else{
            donateur_dialog.dialog( "open" );
        }
    });
});

