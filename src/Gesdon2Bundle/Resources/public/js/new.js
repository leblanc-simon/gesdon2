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

    var donateur_dialog = $( "div.content#donateur" )
        .load('{{ path("donateur_list") }}')
        .dialog({
            autoOpen: false,
            height: 500,
            width: 800,
            modal: true
    });
    $("input#donateur").on( "click", function(){
        donateur_dialog.dialog( "open" );
    });

    var adresse_dialog = $( "div.content#adresse" )
        .load('../Adresse/list')
        .dialog({
        autoOpen: false,
        height: 500,
        width: 800,
        modal: true
    });
    $("#gesdon2_gesdon2bundle_don_adresse").on( "click", function(){
        adresse_dialog.dialog( "open" );
    });

    /**
     * Modifier le comportement du tableau pour renvoyer le donateur
     */
    $('#iframe').contents().find("body").html("blah");

    // Remplissage du donateur
/*    $("tr#donateur").click(function (){
        // replissage du type
        // filtre par le texte, et pas par la valeur de la liste qui est un entier
        var type = $(this).find("td.type").html();
        $("select#gesdon2_gesdon2bundle_don_adresse_donateur_type option").filter(
            function(){return $(this).text() == type}
        ).prop('selected', true);
        $("input#gesdon2_gesdon2bundle_don_adresse_donateur_nom").val(
            $(this).find("td.nom").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_donateur_prenom").val(
            $(this).find("td.prenom").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_donateur_courriel").val(
            $(this).find("td.courriel").html()
        );
    });*/

});