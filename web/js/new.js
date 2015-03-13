$(document).ready(function()
{
    /**
     * Ajoute des calendriers pour les dates
     */
    $("form input.date").datepicker({
        dateFormat: 'dd/mm/yy'
    }).attr("readonly","readonly");
    // changer la locale
    $.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );

    /**
     * Ouvrir des fenêtres modales contenant la liste des donateurs
     */
    var donateur_id;
    var donateur_dialog;
    // sur click du champ donateur
    $("input#donateur").on( "click", function(){
        // si la fenêtre n'a pas été initialisée...
        if (typeof donateur_dialog === "undefined"){
            // créer une fenêtre à partir d'une nouvelle div
            donateur_dialog = $(
                "<div id='donateur_content' title='Choisissez un donateur'></div>"
            )
                // charger la page de recherche des donateurs
                .load('../Donateur')
                .dialog({
                    autoOpen: false,
                    height: 800,
                    width: 800,
                    modal: true
                });
            donateur_dialog
                .dialog( "open" )
                .on('click', '#donateur', function(){
                    donateur_id = $(this).find(".id").text();
                    donateur_dialog.dialog("close");
                    $("input#donateur").val(donateur_id);
                });
        }
        // si la fenêtre existe déjà, l'ouvrir
        else{
            donateur_dialog.dialog( "open" );
        }
    });

    var adresse_id;
    var adresse_dialog;
    $("#gesdon2_gesdon2bundle_don_adresse").on( "click", function(){
        // si la fenêtre n'a pas été initialisée...
        if (typeof adresse_dialog === "undefined") {
            // créer une fenêtre à partir d'une nouvelle div
            adresse_dialog = $(
                "<div id='adresse_content' title='Choisissez une adresse'></div>"
            )
                .load('../Adresse')
                .dialog({
                    autoOpen: false,
                    height: 800,
                    width: 800,
                    modal: true
                });
            adresse_dialog
                .dialog("open")
                .on('click', '#adresse', function () {
                    adresse_id = $(this).find(".id").text();
                    adresse_dialog.dialog("close");
                    $("#gesdon2_gesdon2bundle_don_adresse").val(adresse_id);
                });
        }        // si la fenêtre existe déjà, l'ouvrir
        else{
            adresse_dialog.dialog( "open" );
        }
    });

});