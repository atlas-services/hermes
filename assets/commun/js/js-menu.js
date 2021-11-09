function slideUp(elem) {
    elem.style.transition = "all 5s";
}
function slideDown(elem) {
    elem.style.transition = "all 5s";
}

for (let step = 1; step < 6 ; step++) {

    var element = document.getElementById('dropdown'+step);
    if(element != null){
        element.addEventListener('show.bs.dropdown', function(e) {
            slideDown(document.getElementById('dropdown'+step).querySelector('.dropdown-menu'));
        });

        element.addEventListener('hide.bs.dropdown', function(e) {
            slideUp(document.getElementById('dropdown'+step).querySelector('.dropdown-menu'));
        });

        element.addEventListener('hidden.bs.dropdown', function(e) {
            slideUp(document.getElementById('dropdown'+step).querySelector('.dropdown-menu'));
        });
    }
}



