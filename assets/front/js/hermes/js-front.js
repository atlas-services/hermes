
const libre_link = document.getElementsByClassName("link");
const content_link_color = document.getElementById("content_link_color");
const content_link_hover_color = document.getElementById("content_link_hover_color");


if(libre_link != null){
    window.addEventListener("load", (event) => {
            colorLink();
    });
}

function colorLink(){
    let link_color =  content_link_color.value;
    let link_hover_color = content_link_hover_color.value;

    for (let i = 0; i < libre_link.length; i++) {

        libre_link[i].style.color = link_color ;

        libre_link[i].onmouseover = function()
        {
            libre_link[i].style.color = link_hover_color ;
        };

        libre_link[i].onmouseout = function()
        {
            libre_link[i].style.color = link_color ;
        };

      }
}

// JS sans Jquery
const modalImage = document.getElementById('modalImage');
if(modalImage != null){
    document.addEventListener('DOMContentLoaded', function() {
        const modalPrev = document.getElementById('modalPrev');
        const modalNext = document.getElementById('modalNext');
        const images = Array.from(document.querySelectorAll('#carousel-fade img[data-img-src]'));
        let currentIndex = 0;
        // Ouvre la modale avec l'image cliquée
        images.forEach((img, index) => {
            img.addEventListener('click', function() {
                currentIndex = index;
                modalImage.setAttribute('src', this.getAttribute('data-img-src'));
            });
        });
        // Navigation Previous
        modalPrev.addEventListener('click', function() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            modalImage.setAttribute('src', images[currentIndex].getAttribute('data-img-src'));
        });
        // Navigation Next
        modalNext.addEventListener('click', function() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            modalImage.setAttribute('src', images[currentIndex].getAttribute('data-img-src'));
        });
    });
}
