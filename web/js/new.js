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
    $("input#donateur").on( "click", function(){
        var donateur_dialog = $( "<div title='Choisissez un donateur'></div>" )
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
    });

    var adresse_id;
    $("#gesdon2_gesdon2bundle_don_adresse").on( "click", function(){
        var adresse_dialog = $( "<div title='Choisissez une adresse'></div>" )
            .load('../Adresse')
            .dialog({
                autoOpen: false,
                height: 800,
                width: 800,
                modal: true
            });
        adresse_dialog
            .dialog( "open" )
            .on('click', '#adresse', function(){
                adresse_id = $(this).find(".id").text();
                adresse_dialog.dialog("close");
                $("#gesdon2_gesdon2bundle_don_adresse").val(adresse_id);
            });
    });

});