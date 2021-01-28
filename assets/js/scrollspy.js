import $ from 'jquery';
const jQuery = $;
(function($) {
    "use strict"; // Start of use strict

    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
        target: '#mainNav',
        offset: 56
    });

})(jQuery); // End of use strict
