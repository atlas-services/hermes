import $ from 'jquery';
const jQuery = $;
(function($) {

    // When the user scrolls the page, execute stickyfy
    window.onscroll = function() {stickyfy()};
    window.onload = function() {footerify()};
// Get the header
    var header = document.getElementById("mainNav");
    if(header != null) {
        var sticky = header.offsetTop;
    }

    function footerify() {

        var offsetTop = $('footer').offset().top;
        var innerHeight = window.innerHeight;
        var outerHeight = window.outerHeight;

        var diff1 = outerHeight - innerHeight;
        var diff2 = innerHeight - offsetTop;
        var diff = diff1 - diff2 ;

        if (diff > 0) {
            $("footer").css("position", 'relative');
        }else{
            $("footer").css("position", 'absolute');
            $("footer").css("bottom", '0');
            $("footer").css("left", '50%');
            $("footer").css("transform", 'translate(-50%, 0%)');
        }
    }

// Get the offset position of the navbar

//     if(null != header){
    
        // Add the sticky class to the header when you reach its scroll position. Remove "sticky-top" when you leave the scroll position
        function stickyfy() {
            if(header != null){
                if (window.pageYOffset > sticky) {
                    header.classList.add("sticky-top");
                    var bgcolor = $('#mainNav').data('navBgcolorShrink');
                    $(".sticky .container ul").css("background-color", bgcolor);
                    $(".footer").css("position", 'relative');
                } else {
                    header.classList.remove("sticky-top");
                    $(".footer").css("position", 'absolute');
                }
            }
        }

    // }

})(jQuery); // End of use strict
