
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
