let chevron_down_div = document.querySelector('#chevron_down_div');
let chevron_up_div = document.querySelector('#chevron_up_div');

function changeChevron(lastKnownScrollPosition) {
    "use strict"; // Start of use strict
    if(chevron_down_div != null && chevron_up_div != null){
        if (lastKnownScrollPosition !== 0) {
            chevron_down_div.style.display = "none";
            chevron_up_div.style.display = "block";
            // chevron_accueil_down_div.style.display = "none";
        }else{
            chevron_up_div.style.display = "none";
            chevron_down_div.style.display = "block";
            // chevron_accueil_down_div.style.display = "block";
        }
    }
}

window.addEventListener('scroll', function(e) {

    let lastKnownScrollPosition = window.scrollY;
     changeChevron(lastKnownScrollPosition);

});
