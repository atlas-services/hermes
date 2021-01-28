import $ from 'jquery';
const jQuery = $;

(function ($) {
    "use strict"; // Start of use strict

    // afficher l'upload d'image uniquement si le modèle est différent de 'libre'
    $('.select2').on("select2:select", function(e) {
        var modeleselect =  $(this).find(':selected').text();

        if (modeleselect == "Libre") {
            $('.vich-image').hide();
        } else {
            $('.vich-image').show();
        }
        // Nb col et filter seulement pour le slide multi-image
        if (modeleselect == "Carousel par groupe") {
            $('.templateNbCol').parent().parent().children().show();
            // $('.templateImageFilter').parent().parent().children().show();
        } else {
            $('.templateNbCol').parent().parent().children().hide();
            // $('.templateImageFilter').parent().parent().children().hide();
        }
    });

    if ($('#libre').length > 0) {
        $('.vich-image').parent().parent().children().hide();
    } else {
        var modeleselect = $("[id$=template]  option:selected").eq(0);
        if (modeleselect.text() != "Libre") {
            $('.vich-image').parent().parent().children().show();
        } else {
            $('.vich-image').parent().parent().children().hide();
        }
        var idmodele = '#' + $("[id$=template]").eq(0).prop('id');
        $(idmodele).change(function () {
            var modeleselect = $("[id$=template] option:selected").text();
            if (modeleselect != "Libre") {
                $('.vich-image').parent().parent().children().show();
            } else {
                $('.vich-image').parent().parent().children().hide();
            }
        });
    }

    // afficher/cacher la saisie de la couleur si transparent n'est pas séle ctionné
    var config_transparent = $("#config_transparent");
    if (config_transparent.length > 0) {
        config_transparent. change(function(){
            var transparent = $(this). children("option:selected"). val();
            if(1 == transparent){
                $('#config_value').parent().parent().children().hide();
            }else{
                $('#config_value').parent().parent().children().show();
            }
        });
    }


    // selectionner la page dans le menu "sections"
    var select_page = $("#select_page");
    select_page.change(function(){
        var page = $(this).val();
        $('tbody > tr').each(function(){
            if( (page == 'All') ||  $(this).hasClass(page)){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });

})(jQuery); // End of use strict
