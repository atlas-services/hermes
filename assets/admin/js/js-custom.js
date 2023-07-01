import $ from 'jquery';
const jQuery = $;

window.addEventListener('load', (event) => {
    var uri = window.location.pathname;
    var urls = ['/nouveau-menu/nouveau-contenu', '/nouvelle-section/nouveau-contenu'];
    urls.forEach(element => hiddeNew(element, uri, true));

});

function hiddeNew(element, uri, hide) {
    if(uri.includes(element)){
        hiddeBaseListe(true);
        hiddeBaseLibre(false);
        if(document.getElementById('menu_sections_0_templateWidth') != null){
            document.getElementById('menu_sections_0_template2Width').parentElement.parentElement.hidden = true;
            document.getElementById('menu_sections_0_posts_0_url').parentElement.parentElement.hidden = true;
        }
        if(document.getElementById('section_template_templateWidth') != null){
            document.getElementById('section_template_template2Width').parentElement.parentElement.hidden = true;
            document.getElementById('section_template_posts_0_url').parentElement.parentElement.hidden = true;
        }
        if(document.getElementById('id_collapseOptions') != null){
            document.getElementById('id_collapseOptions').hidden = true;
        }
        // show name  
        if(document.getElementById('section_template_posts_0_name') != null){
            document.getElementById('section_template_posts_0_name').parentElement.parentElement.hidden = false;
        }
    }
}


function hiddeBaseListe(hide) {
    // switch hide/show "menu_sections"
    if(document.getElementById('menu_sections_0_templateWidth') != null){
        document.getElementById('menu_sections_0_templateWidth').parentElement.parentElement.hidden = hide;
        document.getElementById('menu_sections_0_templateNbCol').parentElement.parentElement.hidden = hide;
     }
    // switch hide "menu_sections"
    if(document.getElementById('menu_sections_0_templateWidth') != null){
         document.getElementById('menu_sections_0_templateImageFilter').parentElement.parentElement.hidden = true;
    }

    // switch hide/show "section"
    if(document.getElementById('section_template_templateWidth') != null){
        document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = hide;
        document.getElementById('section_template_templateWidth').parentElement.parentElement.hidden = hide;
        document.getElementById('section_template_templateNbCol').parentElement.parentElement.hidden = hide;
     }
    
    // hide "section"
    //  if(document.getElementById('section_template_transparent') != null){
    //      document.getElementById('section_template_templateBgcolor').parentElement.parentElement.hidden = true;
    //     document.getElementById('section_template_template2Width').parentElement.parentElement.hidden = true;
    //     document.getElementById('section_template_templateImageFilter').parentElement.parentElement.hidden = true;
    // }
     
}

function hiddeBaseLibre(hide) {
    // switch hide/show "menu_section"
    if(document.getElementById('menu_sections_0_posts_0_content') != null){
        document.getElementById('menu_sections_0_posts_0_content').parentElement.parentElement.hidden = hide;
        document.getElementById('menu_sections_0_posts_0_imageFile_file').parentElement.parentElement.parentElement.parentElement.hidden = hide;
    }

    // switch hide/show "section"
    if(document.getElementById('section_template_posts_0_content') != null){
        document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = hide;
        document.getElementById('section_template_posts_0_imageFile_file').parentElement.parentElement.hidden = hide;
    }

}


// selectionner la page dans le menu "sections"
var menu_section_select_template = document.querySelector('#menu_sections_0_template');
var section_template_select_template = document.querySelector('#section_template_template');

var select_template = menu_section_select_template;
handleSelectTemplate(select_template);
var select_template = section_template_select_template;
handleSelectTemplate(select_template);


function handleSelectTemplate(select_template) {
    if(null != select_template){
        select_template.addEventListener('click', function() {
            var selected = select_template.options[select_template.selectedIndex].value;
            var options = select_template.getElementsByTagName('option');
            var selected_template_type = select_template.options[select_template.selectedIndex].innerHTML;
            hiddeBaseListe(true);
            hiddeBaseLibre(false);
            // hide menu_sections
            if(document.getElementById('menu_saveAndAddPost') != null){
                document.getElementById('menu_saveAndAddPost').hidden = true;
            } 
            if(document.getElementById('id_collapseOptions') != null){
                document.getElementById('id_collapseOptions').hidden = true;
            }   
            if('Contact' == selected_template_type){
                hiddeBaseListe(true);
                hiddeBaseLibre(true);             
            }  
            if('Libre' == selected_template_type){
                hiddeBaseListe(true);
                hiddeBaseLibre(false);
            }
            if('Folio classique' == selected_template_type){
                hiddeBaseListe(false);
                hiddeBaseLibre(true);
                // show menu_sections
                if(document.getElementById('id_collapseOptions') != null){
                    document.getElementById('id_collapseOptions').hidden = false;
                } 
                if(document.getElementById('section_template_uploaded') != null){
                    document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = false;
                } 
                if(document.getElementById('menu_section0_template_uploaded') != null){
                    document.getElementById('menu_section0_template_uploaded').parentElement.parentElement.hidden = false;
                } 
                if(document.getElementById('menu_saveAndAddPost') != null){
                    document.getElementById('menu_saveAndAddPost').hidden = false;
                }
         
            }
        });
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


