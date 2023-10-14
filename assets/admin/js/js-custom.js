const formulaires = ["Formulaire Contact", "Formulaire Newsletter", "Formulaire Livre d'or"] ;
const section_edit = "/section/edit";
var uri = window.location.pathname;

window.addEventListener('load', (event) => {
    var uri = window.location.pathname;
    var urls = ['/nouveau-menu/nouveau-contenu', '/nouvelle-section/nouveau-contenu', '/section/edit'];
    urls.forEach(element => hiddeNew(element, uri, true));

});


function hiddeNew(element, uri, hide) {
    if(uri.includes(element)){
        // showBase();
        // collapseListe(true);

        // selectionner la page dans le menu "sections"
        var menu_section_select_template = document.querySelector('#menu_sections_0_template');
        if(null != menu_section_select_template){
            handleSelectTemplate(menu_section_select_template, uri);   
        }

        var section_template_select_template = document.querySelector('#section_template_template'); 
        if(null != section_template_select_template){
            handleSelectTemplate(section_template_select_template, uri);             
        }



        // if(document.getElementById('section_template_posts_0_content') != null){
        //     document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = false;
        // }        
    }
}

// function showBase() {
//     // show shared inputs"
//     if(document.getElementById('section_template_position') != null){
//         document.getElementById('section_template_position').parentElement.parentElement.hidden = false;
//         document.getElementById('section_template_template').parentElement.parentElement.hidden = false;
//         document.getElementById('section_template_posts_0_name').parentElement.parentElement.hidden = false;
//         document.getElementById('section_template_templateWidth').parentElement.parentElement.hidden = false;
//     }   

//     // hide non shared inputs"
//     if(document.getElementById('section_template_uploaded') != null){        
//         document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = true;
//         document.getElementById('section_template_posts_0_url').parentElement.parentElement.hidden = true;
//         document.getElementById('section_template_posts_0_imageFile_file').hidden = true;        
//     } 
//     if(document.getElementById('section_template_saveAndAddPost') != null){
//         document.getElementById('section_template_saveAndAddPost').hidden = true;
//     } 
// }

function collapseListe(collapse) {
    // collapse and show/hide non List inputs"
    
    let collapseButton = document.getElementById('id_collapseOptions');
    let collapseOptions = document.getElementById('collapseOptions');

    if(collapseButton != null){  
        collapseButton.hidden = false;  
        if(collapseOptions != null){  
            collapseOptions.hidden = false; 
            collapseOptions.classList.remove('show'); 
        }
        collapseButton.classList.remove('collapsed') ;  
        collapseButton.setAttribute('data-bs-toggle',  'collapse') ;  
        collapseButton.setAttribute('aria-expanded',  'false') ;  
        if(collapse){ 
            collapseButton.setAttribute('data-bs-toggle',  'collapsed') ;  
            collapseButton.setAttribute('aria-expanded',  'true') ;  
            collapseButton.classList.add('collapsed') ;  
            collapseButton.hidden = true; 
            if(collapseOptions != null){  
                collapseOptions.hidden = true; 
            }
        } 
    } 
}

// selectionner la page dans le menu "sections"
var select_template = document.querySelector('#menu_sections_0_template');
if(null != select_template){
    select_template.addEventListener('change', function() {
        handleSelectTemplate(select_template, uri);  
    });
}

 var select_template = document.querySelector('#section_template_template'); 
 if(null != select_template){
    select_template.addEventListener('change', function() {
        handleSelectTemplate(select_template, uri);  
    });
 }
   


function handleSelectTemplate(select_template, uri) {
    if(null != select_template){
        var selected = select_template.options[select_template.selectedIndex].value;
        var options = select_template.getElementsByTagName('option');
        var selected_template_type = select_template.options[select_template.selectedIndex].innerHTML;
        //showBase();   
        // alert(selected_template_type);
        if('Template Libre' == selected_template_type || 'Template Newsletter' == selected_template_type){
            collapseListe(false);
            if(document.getElementById('section_template_posts_0_content') != null){
                document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = false;
                document.getElementById('section_template_posts_0_content').setAttribute('required', 'required');
            } 
            if(document.getElementById('section_template_uploaded') != null){        
                document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = true;

            } 
            if(document.getElementById('section_template_posts_0_url') != null){        
                document.getElementById('section_template_posts_0_url').parentElement.parentElement.hidden = true; 
            } 
            if(document.getElementById('section_template_templateNbCol') != null){
                document.getElementById('section_template_templateNbCol').parentElement.parentElement.hidden = true;
            } 
            if(document.getElementById('section_template_templateImageFilter') != null){
                document.getElementById('section_template_templateImageFilter').parentElement.parentElement.hidden = true;
            }
            if(document.getElementById('section_template_template2') != null){
                document.getElementById('section_template_template2').parentElement.parentElement.hidden = true;
            }  
            if(document.getElementById('section_template_template2Width') != null){
                document.getElementById('section_template_template2Width').parentElement.parentElement.hidden = true;
            } 
            
        }else{
            document.getElementById('section_template_posts_0_content').removeAttribute('required');  
        }

        if('Folio Classique' == selected_template_type){
            if(document.getElementById('section_template_posts_0_content') != null){
                document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;
            }  

            if(document.getElementById('section_template_uploaded') != null){
                document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = false;
            } 
            if(document.getElementById('menu_section0_template_uploaded') != null){
                document.getElementById('menu_section0_template_uploaded').parentElement.parentElement.hidden = false;
            } 

            if(document.getElementById('section_template_templateNbCol') != null){
                document.getElementById('section_template_templateNbCol').parentElement.parentElement.hidden = false;
            } 
            if(document.getElementById('section_template_templateImageFilter') != null){
                document.getElementById('section_template_templateImageFilter').parentElement.parentElement.hidden = false;
            }
            if(document.getElementById('section_template_template2') != null){
                document.getElementById('section_template_template2').parentElement.parentElement.hidden = false;
            }  
            if(document.getElementById('section_template_template2Width') != null){
                document.getElementById('section_template_template2Width').parentElement.parentElement.hidden = false;
            } 
            
            collapseListe(false);

            if(document.getElementById('menu_saveAndAddPost') != null){
                document.getElementById('menu_saveAndAddPost').hidden = false;
            }
        
        }

        if(selected_template_type.includes("Livre d'or") ){
            collapseListe(true);
            if(document.getElementById('section_template_posts_0_content') != null){
                document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;
            } 

            if(document.getElementById('menu_section0_template_posts_0_content') != null){
                document.getElementById('menu_section0_template_posts_0_content').parentElement.parentElement.hidden = true;
            }  

            if(document.getElementById('section_template_uploaded') != null){
                document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = true;
            } 
            if(document.getElementById('menu_section0_template_uploaded') != null){
                document.getElementById('menu_section0_template_uploaded').parentElement.parentElement.hidden = true;
            } 

            if(document.getElementById('section_template_url') != null){
                document.getElementById('section_template_url').parentElement.parentElement.hidden = true;
            } 

            if(document.getElementById('section_template_posts_0_imageFile_file') != null){
                document.getElementById('section_template_posts_0_imageFile_file').parentElement.parentElement.hidden = false;
            }

            if(document.getElementById('menu_section0_template_posts_0_imageFile_file') != null){
                document.getElementById('menu_section0_template_posts_0_imageFile_file').parentElement.parentElement.hidden = false;
            }

            if(document.getElementById('section_template_url') != null){
                document.getElementById('section_template_url').parentElement.parentElement.hidden = true;
            } 

            if(document.getElementById('menu_section0_template_url') != null){
                document.getElementById('menu_section0_template_url').parentElement.parentElement.hidden = true;
            } 
            
            if(document.getElementById('menu_saveAndAddPost') != null){
                document.getElementById('menu_saveAndAddPost').hidden = false;
            }
        }

        if(selected_template_type.includes('Formulaire')){
            collapseListe(true);
            if(document.getElementById('section_template_posts_0_content') != null){
                document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;             
            } 

            if(document.getElementById('section_template_uploaded') != null){
                document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = true;
            } 
            if(document.getElementById('menu_section0_template_uploaded') != null){
                document.getElementById('menu_section0_template_uploaded').parentElement.parentElement.hidden = true;
            } 
            
            if(document.getElementById('menu_saveAndAddPost') != null){
                document.getElementById('menu_saveAndAddPost').hidden = false;
            }
        }else{
            if(document.getElementById('section_template_posts_0_imageFile_file') != null){
                document.getElementById('section_template_posts_0_imageFile_file').hidden = true;  
                if(uri.includes(section_edit)){
                    document.getElementById('section_template_posts_0_imageFile_file').hidden = false;     
                }
            }  
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
        if(null != tbody){
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
        }
    });
}

var select_api_template = document.querySelector('#select_api_template');

if(null !=select_api_template){
    select_api_template.addEventListener('click', function() {
        var selected = select_api_template.options[select_api_template.selectedIndex].value;
        var selected_template = document.getElementById(selected);
        if(null !=selected){
            for (var k = 1; k <= select_api_template.length; ++k) {
                var template = document.getElementById(k);
                if(null != template){                    
                    if(selected_template == template){
                        template.classList.remove("d-none");
                        template.classList.add("d-block");
                    }else{
                        if(null == selected_template){
                            template.classList.remove("d-none");
                            template.classList.add("d-block");
                        }else{
                            template.classList.add("d-none");
                            template.classList.remove("d-block");
                        }
                    }
                    
                }
            }    
        }    
    });
}


