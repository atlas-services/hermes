
var barres = document.querySelector('#navbar-toggler-button');
var croix = document.querySelector('#navbar-toggler-span');
var menu = document.querySelector('#navbarSupportedContent');
var items = document.querySelectorAll('.nav-link, :not(.dropdown)');

document.querySelector('.navbar-toggler').addEventListener('click', function(e) {

  // openCloseNavbarButton();

});

var i;
for (i = 0; i < items.length; i++) {
    items[i].addEventListener('click', function(event) {
        if(!event.target.classList.contains('dropdown-toggle')){
            croix.click();
        }
    });
}


function openCloseNavbarButton(){

    if(croix.classList.contains("d-none") == true){
      // on cache les 3 barres et on affiche la croix
      barres.classList.remove("navbar-toggler-icon");
      croix.classList.remove("d-none");
      }else{
        // on affiche les 3 barres et on cache la croix
        barres.classList.add("navbar-toggler-icon");
        croix.classList.add("d-none");
      }

}
