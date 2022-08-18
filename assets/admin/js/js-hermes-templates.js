let anchor = '#templates';
let hermes_template = document.querySelector(anchor);

hermes_template.addEventListener('click', function(e) {
    if(hermes_template != null ){
        document.querySelector(anchor).scrollIntoView({
            behavior: 'smooth'
        });
    }
});
