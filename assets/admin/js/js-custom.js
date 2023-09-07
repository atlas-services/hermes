const formulaires = ["Contact", "Newsletter", "Livre d'or"] ;

window.addEventListener('load', (event) => {
    var uri = window.location.pathname;
    var urls = ['/nouveau-menu/nouveau-contenu', '/nouvelle-section/nouveau-contenu'];
    urls.forEach(element => hiddeNew(element, uri, true));

});

function hiddeNew(element, uri, hide) {
    if(uri.includes(element)){
        showBase();
        collapseListe(true);
        if(document.getElementById('section_template_posts_0_content') != null){
            document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = false;
        }        
    }
}

function showBase() {
    // show shared inputs"
    if(document.getElementById('section_template_position') != null){
        document.getElementById('section_template_position').parentElement.parentElement.hidden = false;
        document.getElementById('section_template_template').parentElement.parentElement.hidden = false;
        document.getElementById('section_template_posts_0_name').parentElement.parentElement.hidden = false;
        document.getElementById('section_template_templateWidth').parentElement.parentElement.hidden = false;
    }   

    // hide non shared inputs"
    if(document.getElementById('section_template_uploaded') != null){        
        document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = true;
        document.getElementById('section_template_posts_0_url').parentElement.parentElement.hidden = true;
        document.getElementById('section_template_posts_0_imageFile_file').hidden = true;        
    } 
    if(document.getElementById('section_template_saveAndAddPost') != null){
        document.getElementById('section_template_saveAndAddPost').hidden = true;
    } 
}

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
var menu_section_select_template = document.querySelector('#menu_sections_0_template');
handleSelectTemplate(menu_section_select_template);  

var section_template_select_template = document.querySelector('#section_template_template'); 
handleSelectTemplate(section_template_select_template);   


function handleSelectTemplate(select_template) {
    if(null != select_template){
        select_template.addEventListener('change', function() {
            var selected = select_template.options[select_template.selectedIndex].value;
            var options = select_template.getElementsByTagName('option');
            var selected_template_type = select_template.options[select_template.selectedIndex].innerHTML;
            showBase();   

            if(formulaires.includes(selected_template_type)){
                collapseListe(true);
                if(document.getElementById('section_template_posts_0_content') != null){
                    document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;
                }     
            }  
            if('Template libre' == selected_template_type || 'Template Newsletter' == selected_template_type){
                collapseListe(false);
                if(document.getElementById('section_template_posts_0_content') != null){
                    document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = false;
                } 
            }
            if('Folio classique' == selected_template_type){
                if(document.getElementById('section_template_posts_0_content') != null){
                    document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;
                }  

                if(document.getElementById('section_template_uploaded') != null){
                    document.getElementById('section_template_uploaded').parentElement.parentElement.hidden = false;
                } 
                if(document.getElementById('menu_section0_template_uploaded') != null){
                    document.getElementById('menu_section0_template_uploaded').parentElement.parentElement.hidden = false;
                } 
                
                collapseListe(false);

                if(document.getElementById('menu_saveAndAddPost') != null){
                    document.getElementById('menu_saveAndAddPost').hidden = false;
                }
         
            }
            if(selected_template_type.includes('Formulaire')){
                collapseListe(true);
                if(document.getElementById('section_template_posts_0_content') != null){
                    document.getElementById('section_template_posts_0_content').parentElement.parentElement.hidden = true;
                } 

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

if(null !=select){
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


