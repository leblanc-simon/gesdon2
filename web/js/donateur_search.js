$(document).ready(function( ) {
    $("#donateur_search")
        .click(function( event ){
            // empêcher le submit
            event.preventDefault();
            // récupérer le formulaire (le nom du formulaire est défini dans le Type)
            var form = $('#donateur_form');
            //requête ajax, appel de la route donateur_table
            $.ajax({
                type    : 'POST',
                url     : "./Donateur/table",
                data    : form.serialize(),
                //afficher l'erreur en cas de problème
                error:function(msg, string){
                    alert( "Error !: " + string );
                },
                success:function(htmlResponse){
                    //mettre à jour le div avec les données reçues
                    //vider la div et on le cache
                    $("#donateur_table")
                        .empty()
                        .hide()
                        .append(htmlResponse)
                        .fadeIn(100);
                }
            });
        })
        /*activer le bouton une fois que l'action est affectée*/
        .removeAttr('disabled');
})