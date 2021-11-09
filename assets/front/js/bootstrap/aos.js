import $ from 'jquery';
const jQuery = $;
import '../../css/bootstrap/aos.css';
import AOS from 'aos';

/**
 * Template Name: Medicio - v2.0.0
 * Template URL: https://bootstrapmade.com/medicio-free-bootstrap-theme/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
!(function($) {
    "use strict";

    // Init AOS
    function aos_init() {
        AOS.init({
            duration: 600,
            once: true
        });
    }
    $(window).on('load', function() {
        aos_init();
    });

})(jQuery);

export default AOS