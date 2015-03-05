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
        var donateur_dialog = $( "div.content#donateur" )
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
        var adresse_dialog = $( "div.content#adresse" )
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