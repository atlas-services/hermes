import $ from 'jquery';
const jQuery = $;
(function($) {

    // When the user scrolls the page, execute stickyfy
    window.onscroll = function() {stickyfy()};

// Get the header
    var header = document.getElementById("mainNav");

// Get the offset position of the navbar

//     if(null != header){

        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function stickyfy() {
            // var sectionContentPaddingTop = 20 ;
            var sectionContentPaddingTop = 0 ;
            var navBarHeight =  $('.navbar').height() ;
            var navBarSectionContentPaddingTop = navBarHeight + sectionContentPaddingTop;
            if (navBarSectionContentPaddingTop > sticky) {
                $(".footer").css("margin-top", 0 + 'px');
                $(".section_content").last().css("padding-bottom", sectionContentPaddingTop + 'px');
            }
            if (window.pageYOffset > sticky) {
                // if ($("#mainNav").offset().top > 0) {
                header.classList.add("sticky");
                var bgcolor = $('#mainNav').data('navBgcolorShrink');
                $(".sticky .container ul").css("background-color", bgcolor);
                var stickyHeight =  $('.sticky').height() ;
                var stickySectionContentPaddingTop = stickyHeight + sectionContentPaddingTop + 50;
                $(".section_content").eq(0).css("padding-top", stickySectionContentPaddingTop + 'px');
            } else {
                header.classList.remove("sticky");
                $(".section_content").eq(0).css("padding-top", sectionContentPaddingTop + "px");
            }
        }

    // }

})(jQuery); // End of use strict
