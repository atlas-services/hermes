import $ from 'jquery';
const jQuery = $;

(function ($) {
    "use strict"; // Start of use strict

    // select2
jQuery(document).ready(function () {
    $( ".select2").select2({
        theme: "bootstrap",
    }).maximizeSelect2Height();
    $('.select2-selection').css('margin-top', '12px');
    $('.select2-selection').css('border-color', '#a4c52e');
    $(".select2-results__option").css("background-color", "#123456");
});



})(jQuery); // End of use strict
