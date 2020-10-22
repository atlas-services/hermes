(function ($) {
    "use strict"; // Start of use strict

    // afficher l'upload d'image uniquement si le modèle est différent de 'libre'
    if ($('#libre').length > 0) {
        $('.vich-image').parent().parent().children().hide();
    } else {
        var modeleselect = $("[id$=template]  option:selected").eq(0);
        if (modeleselect.text() != "libre") {
            $('.vich-image').parent().parent().children().show();
        } else {
            $('.vich-image').parent().parent().children().hide();
        }
        var idmodele = '#' + $("[id$=template]").eq(0).prop('id');
        $(idmodele).change(function () {
            var modeleselect = $("[id$=template] option:selected").text();
            if (modeleselect != "libre") {
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

    // select2
jQuery(document).ready(function () {
    $( ".select2").select2({
        theme: "bootstrap",
        // templateResult: formatMenu
    }).maximizeSelect2Height('10');
    $('.select2-selection').css('margin-top', '12px');
    $('.select2-selection').css('border-color', '#a4c52e');
    $(".select2-results__option").css("background-color", "#123456");
    // $('.select2-selection').css('color', '#a4c52e');

});

function formatMenu (menu) {

    var $menu = $(
        '<span class="px-0 mx-0  h-text-success">'+ menu.text + '</span>'
    );
    return $menu;
};



})(jQuery); // End of use strict
