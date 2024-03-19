
var barres = document.querySelector('#navbar-toggler-button');
var croix = document.querySelector('#navbar-toggler-span');
var menu = document.querySelector('#navbarSupportedContent');
var items = document.querySelectorAll('.nav-link, li:not(.dropdown)');

document.querySelector('.navbar-toggler').addEventListener('click', function(event) {

    openCloseNavbarButton();

});
// document.querySelector('#btnOffcanvasDarkNavbarSmall').addEventListener('click', function(event) {

//     document.querySelector('#btnOffcanvasDarkNavbarSmall').remove("show");

// });

var i;
for (i = 0; i < items.length; i++) {
    items[i].addEventListener('click', function(event) {
        if(!event.target.classList.contains('dropdown-toggle')){
            croix.click();
        }
    });
}


function openCloseNavbarButton(){
    var menu_height = parseInt(menu.style.height);
    console.log(menu_height);
    if(menu_height > 0){
        barres.classList.remove("navbar-toggler-icon");
        croix.classList.remove("d-none");
    }else{
        barres.classList.add("navbar-toggler-icon");
        croix.classList.add("d-none");
    }

}
