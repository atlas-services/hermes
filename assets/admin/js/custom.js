import $ from 'jquery';
const jQuery = $;

window.addEventListener('load', (event) => {
    var uri = window.location.pathname;
    var urls = ['/nouveau-menu/nouveau-contenu', '/nouvelle-section/nouveau-contenu'];
    urls.forEach(element => hiddeNew(element, uri));

});

function hiddeNew(element, uri) {
    if(uri.includes(element)){
    if(document.getElementById('menu_sections_0_templateWidth') != null){
        document.getElementById('menu_sections_0_templateWidth').parentElement.parentElement.hidden = true;
        document.getElementById('menu_sections_0_templateImageFilter').parentElement.parentElement.hidden = true;
        document.getElementById('menu_sections_0_templateNbCol').parentElement.parentElement.hidden = true;
        document.getElementById('menu_sections_0_template2Width').parentElement.parentElement.hidden = true;
        document.getElementById('menu_sections_0_posts_0_url').parentElement.parentElement.hidden = true;
    }
    }
    if(uri.includes(element)){
        if(document.getElementById('section_template_transparent') != null){
        document.getElementById('section_template_transparent').parentElement.parentElement.hidden = true;
        document.getElementById('section_template_templateBgcolor').parentElement.parentElement.hidden = true;
        document.getElementById('section_template_templateWidth').parentElement.parentElement.hidden = true;
        }
    }
}

// selectionner la page dans le menu "sections"
var select = document.querySelector('#select_page');

if(null !=select){
    select.addEventListener('click', function() {
        var selected = select.options[select.selectedIndex].value;
        var options = select.getElementsByTagName('option');
        var tbody = document.getElementById('tbody');
        var cells = tbody.getElementsByTagName('tr');
    
        if(options.length < 3 && 'All' == selected ){
            var name = select.getAttribute('name');
            window.location.href = "/fr/admin/"+ name + "/";
        } 
        for (var k = 0; k < cells.length; ++k) {
            if ( 'All' == selected || cells[k].classList.contains(selected)) {
                cells[k].classList.remove("d-none");
                cells[k].setAttribute("selected", true);
            }else{
                cells[k].classList.add("d-none");
            }
        }    
    });
}



(function ($) {
    "use strict"; // Start of use strict

    // afficher l'upload d'image uniquement si le modèle est différent de 'libre'
    $('.select2').on("select2:select", function(e) {
        var modeleselect =  $(this).find(':selected').text();
        if('menu_sections_0_template' == $(this).attr('id')){
            if (modeleselect == "Libre" ) {
                // $('.vich-image').hide();
                $('.templateNbCol').parent().parent().children().hide();
                $('#menu_sections_0_templateImageFilter').parent().parent().children().hide();
                $('#menu_sections_0_template2').parent().parent().children().hide();
                $('#menu_sections_0_template2Width').parent().parent().children().hide();
            } else {
                // $('.vich-image').show();
                $('#menu_sections_0_templateImageFilter').parent().parent().children().show();
                $('#menu_sections_0_template2').parent().parent().children().show();
                $('#menu_sections_0_template2Width').parent().parent().children().show();
            }
            if (modeleselect == "Folio video classique") {
                $('#menu_sections_0_posts_0_url').parent().parent().show();
                $('#cke_menu_sections_0_posts_0_content').parent().parent().hide();
                $('#menu_sections_0_template2').parent().parent().hide();
                $('#menu_sections_0_template2Width').parent().parent().hide();
            }else {
                $('#menu_sections_0_posts_0_url').parent().parent().hide();
                $('#cke_menu_sections_0_posts_0_content').parent().parent().show();
            }
        }

        if('section_template_template' == $(this).attr('id')){
            if (modeleselect == "Libre" ) {
                $('.vich-image').hide();
                $('#section_template_templateImageFilter').parent().parent().children().hide();
                $('#section_template_template2').parent().parent().children().hide();
                $('#section_template_template2Width').parent().parent().children().hide();
                $('#section_template_posts_0_url').parent().parent().children().hide();
            } else {
                $('.vich-image').show();
                $('#section_template_templateImageFilter').parent().parent().children().show();
                $('#section_template_template2').parent().parent().children().show();
                $('#section_template_template2Width').parent().parent().children().show();
            }
            if (modeleselect == "Folio video classique") {
                $('#section_template_template2').parent().parent().children().hide();
                $('#section_template_template2Width').parent().parent().children().hide();
                $('#section_template_content').parent().parent().children().hide();
                $('#section_template_posts_0_url').parent().parent().children().show();
            }
            // Nb col et filter seulement pour le slide multi-image
            if (modeleselect == "Carousel par groupe") {
                $('.templateNbCol').parent().parent().children().show();
                // $('.templateImageFilter').parent().parent().children().show();
            } else {
                $('.templateNbCol').parent().parent().children().hide();
                // $('.templateImageFilter').parent().parent().children().hide();
            }
        }
    });

    // if ($('#libre').length > 0) {
    //     $('.vich-image').parent().parent().children().hide();
    // } else {
    //     var modeleselect = $("[id$=template]  option:selected").eq(0);
    //     if (modeleselect.text() != "Libre") {
    //         $('.vich-image').parent().parent().children().show();
    //     } else {
    //         $('.vich-image').parent().parent().children().hide();
    //     }
    //     var idmodele = '#' + $("[id$=template]").eq(0).prop('id');
    //     $(idmodele).change(function () {
    //         var modeleselect = $("[id$=template] option:selected").text();
    //         if (modeleselect != "Libre") {
    //             $('.vich-image').parent().parent().children().show();
    //         } else {
    //             $('.vich-image').parent().parent().children().hide();
    //         }
    //     });
    // }

    // afficher/cacher la saisie de la couleur si transparent n'est pas sélectionné
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

    /*

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
    */

})(jQuery); // End of use strict
