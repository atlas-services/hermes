
$(document).ready(function() {
    var $deliveryMethodSelect = $('.js-delivery-form-method');
    var $addressTarget = $('.js-address-target');
    $deliveryMethodSelect.on('change', function(e) {
        $.ajax({
            url: $deliveryMethodSelect.data('specific-delivery-method-url'),
            data: {
                deliveryMethod: $deliveryMethodSelect.val()
            },
            success: function (html) {
                if (!html) {
                    $addressTarget.find('select').remove();
                    $addressTarget.addClass('d-none');
                    return;
                }
                // Replace the current field and show
                $addressTarget
                    .html(html)
                    .removeClass('d-none')
            }
        });
    });
});


// (function ($) {
//     "use strict"; // Start of use strict
//
//     // ne pas afficher la liste des adresses de livraison si on est CLICK_AND_COLLECT ou RELAIS
//     $('.select-delivery-address').parent().parent().hide();
//     $( ".select-delivery-method" )
//         .change(function () {
//             var deliveryMethod = "";
//             $( ".select-delivery-method option:selected" ).each(function() {
//                 deliveryMethod = $( this ).text() ;
//                 if (deliveryMethod == "HOME" || deliveryMethod == "EXPRESS" ) {
//                     $('.select-delivery-address').parent().parent().show();
//                 }else{
//                     $('.select-delivery-address').parent().parent().hide();
//                 }
//             });
//         });
//
// })(jQuery); // End of use strict
