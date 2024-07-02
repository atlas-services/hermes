import $ from 'jquery';
const jQuery = $;
let modalId = $('#image-gallery');

$(document)
    .ready(function () {

        loadGallery(true, 'a.thumbnail');

        //Cette fonction cache les boutons de défilement des images si necessaire.
        function disableButtons(counter_max, counter_current) {
            [...document.getElementsByClassName('show-next-image')].forEach(el => {
                el.classList.remove("d-none");
            });
            [...document.getElementsByClassName('show-previous-image')].forEach(el => {
                el.classList.remove("d-none");
            });
            if (counter_max === counter_current) {

                [...document.getElementsByClassName('show-next-image')].forEach(el => {
                    el.classList.add("d-none");
                })

            } else if (counter_current === 1) {
                
                [...document.getElementsByClassName('show-previous-image')].forEach(el => {
                    el.classList.add("d-none");
                })

            }
        }

        /**
         *
         * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
         * @param setClickAttr  Sets the attribute for the click handler.
         */

        function loadGallery(setIDs, setClickAttr) {
            let current_image,
                selector,
                counter = 0;

            $('#show-next-image, #show-previous-image')
                .click(function () {
                    if ($(this)
                        .attr('id') === 'show-previous-image') {
                        current_image--;
                    } else {
                        current_image++;
                    }

                    selector = $('[data-image-id="' + current_image + '"]');
                    updateGallery(selector);
                });

            function updateGallery(selector) {
                let $sel = selector;
                current_image = $sel.data('image-id');
                $('.image-gallery-title')
                    .text($sel.data('title'));
                $('.image-gallery-image')
                    .attr('src', $sel.data('image'));
                $('.post-name-gallery-image')
                    .html($sel.data('name'));
                $('.post-content-gallery-image')
                    .html($sel.data('content'));
                disableButtons(counter, $sel.data('image-id'));
            }

            if (setIDs == true) {
                $('[data-image-id]')
                    .each(function () {
                        counter++;
                        $(this)
                            .attr('data-image-id', counter);
                    });
            }
            $(setClickAttr)
                .on('click', function () {
                    updateGallery($(this));
                });
        }
    });

// build key actions
$(document)
    .keydown(function (e) {
        switch (e.which) {
            case 37: // left
                if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
                    $('#show-previous-image')
                        .click();
                }
                break;

            case 39: // right
                if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
                    $('#show-next-image')
                        .click();
                }
                break;

            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
