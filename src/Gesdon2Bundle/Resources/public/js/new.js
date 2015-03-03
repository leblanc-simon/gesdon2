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
     * Rendre les tableaux réductibles
     */
    // Sur l'élément "légende"
    $("legend.collapsible")
        // ajouter una action sur le click
        .click(function () {
            var $content = $(this).next();
            $content.slideToggle(200)
        })
        // changer le curseur à "pointeur"
        .css('cursor','pointer');
    $("div.content").toggle();

    /**
     * Fonction de remplissage dynamique des champs à partir de la liste
     */
    // Remplissage du donateur
    $("tr#donateur").click(function (){
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
    });

    // Remplissage de l'adresse
    $("tr#adresse").click(function (){
        $("input#gesdon2_gesdon2bundle_don_adresse_adresse1").val(
            $(this).find("td.adresse1").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_adresse2").val(
            $(this).find("td.adresse2").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_codePostal").val(
            $(this).find("td.codePostal").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_ville").val(
            $(this).find("td.ville").html()
        );
        $("input#gesdon2_gesdon2bundle_don_adresse_pays").val(
            $(this).find("td.pays").html()
        );
    });

    /**
     * Modification du bouton de filtrage
     */
    $("#filtrer :button").on('submit', function(e){
        e.preventDefault();
    })

});