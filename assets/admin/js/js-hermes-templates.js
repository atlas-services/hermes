let anchor = '#templates';
let hermes_template = document.querySelector(anchor);
if(hermes_template != null ){
    hermes_template.addEventListener('click', function(e) {
        document.querySelector(anchor).scrollIntoView({
            behavior: 'smooth'
        });
    });
}
