import $ from 'jquery';
const jQuery = $;
(function ($) {
    "use strict"; // Start of use strict

    // Smooth scrolling using jQuery easing
    // $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
    //     $('.dropdown-menu').removeClass('show');
    //     $(this).parent().children().addClass('active');
    // });
    // Smooth scrolling using jQuery easing
    // $('.modal-content').css('background-color', '#e4e5e6');

    $(window).scroll(function () {
        var offsetBody = $("body").offset();
        var posYBody = offsetBody.top - $(window).scrollTop();
        if (posYBody < 0) {
            $("#chevron_down_div").hide();
            $("#chevron_up_div").show();
            $("#chevron_accueil_down_div").hide();
        } else {
            $("#chevron_up_div").hide();
            $("#chevron_down_div").show();
            $("#chevron_accueil_down_div").show();
        }
    });

})(jQuery); // End of use strict
