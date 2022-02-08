import $ from 'jquery';
const jQuery = $;
(function ($) {
    "use strict"; // Start of use strict

    $(".copy").on('click', function (event) {
        var key = this.dataset.copy ;
        var type = this.dataset.copytype ;
        docopy(event, key, type);
    });

    function docopy(e, key, type) {

        e.preventDefault(); // Cancel the native event
        e.stopPropagation();// Don't bubble/capture the event
        var target = 'tocopy'+ key;

        try {
        // document.execCommand("Copy");
            const copy = require('clipboard-copy');
            var text_alert = "";
            var idCopy = 'copy'+ key;
            if (type === 'text') {
                var copyText = document.getElementById(target).textContent;
                copy(copyText); // innerText
                text_alert = '<div id="alert" class="mt-3 alert alert-success ">\n' +
                    '            <a href="#" class="hidden close" data-bs-dismiss="alert" aria-label="close">&times;</a>\n' +
                    '            Le texte du template a bien été <strong>copié</strong>!.\n' +
                    '        </div>';
            }
            if (type === 'html') {
                var copyHTML = document.getElementById(target).innerHTML;
                copy(copyHTML); // innerHtml
                text_alert = '<div id="alert" class="mt-3 alert alert-success ">\n' +
                    '            <a href="#" class="hidden close" data-bs-dismiss="alert" aria-label="close">&times;</a>\n' +
                    '            Le code html du template a bien été <strong>copié</strong>!.\n' +
                    '        </div>';
            }
            document.getElementById(idCopy).insertAdjacentHTML('afterend',text_alert);
        } catch (err) {
            // Une erreur est survenue lors de la tentative de copie
            alert(err);
        }
    }
})(jQuery); // End of use strict
