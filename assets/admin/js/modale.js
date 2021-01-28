import $ from 'jquery';
const jQuery = $;

(function ($) {
    "use strict"; // Start of use strict

    // hide modale (bug delete page)
jQuery(document).ready(function () {
    $( "#exampleModal").hide();
});

$('.select2').on("select2:select", function(e) {
    $( "#exampleModal").hide();
});


})(jQuery); // End of use strict
