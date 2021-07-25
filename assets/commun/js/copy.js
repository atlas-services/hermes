import $ from 'jquery';
const jQuery = $;
(function ($) {
    "use strict"; // Start of use strict

    $("#copy").on('click', function (event) {
            docopy(event);
        });

    function docopy(e) {

        e.preventDefault(); // Cancel the native event
        e.stopPropagation();// Don't bubble/capture the event

        var range = document.createRange();
        var target = '#tocopy';
        var fromElement = document.querySelector(target);
        try {
        // document.execCommand("Copy");
            const copy = require('clipboard-copy');
            var copyHTML = document.getElementById(fromElement.getAttribute('id')).textContent;

            if (typeof copyHTML === 'string') {
                copy(copyHTML); // innerHtml
                var text_alert = '<div id="alert" class="mt-3 alert alert-success ">\n' +
                    '            <a href="#" class="hidden close" data-bs-dismiss="alert" aria-label="close">&times;</a>\n' +
                    '            Le code html du template a bien été <strong>copié</strong>!.\n' +
                    '        </div>';
                document.getElementById('copy').insertAdjacentHTML('afterend',text_alert);
            }
        } catch (err) {
            // Une erreur est survenue lors de la tentative de copie
            alert(err);
        }
    }
})(jQuery); // End of use strict
