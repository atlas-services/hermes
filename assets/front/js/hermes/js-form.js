
const form_subject = document.getElementById("contact_subject");
const form_firstname = document.getElementById("contact_firstname");
const form_lastname = document.getElementById("contact_lastname");
const form_email = document.getElementById("contact_email");
const form_telephone = document.getElementById("contact_telephone");
const form_content = document.getElementById("contact_content");
const form_button = document.getElementById("sendMessageButton");

form_subject.addEventListener("input", (event) => {
    if( 'Newsletter' == form_subject.value){
        form_content.removeAttribute('required');
        form_content.hidden = true ;
        form_telephone.hidden = true ;
        form_button.innerHTML = "S'inscrire";
    }
    if( 'Contact' == form_subject.value || 'Livredor' == form_subject.value){
        if( 'Livredor' == form_subject.value){
            form_email.removeAttribute('required');
            form_telephone.hidden = true ;
        }else{
            form_telephone.hidden = false ;
        }
        form_content.setAttribute('required', 'required');
        form_content.hidden = false ;
        form_button.innerHTML = "Envoyer";
    }

});
