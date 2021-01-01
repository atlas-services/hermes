(function ($) {
    "use strict"; // Start of use strict

    // ne pas afficher la liste des adresses de livraison si on est CLICK_AND_COLLECT ou RELAIS
    $('.select-delivery-address').parent().parent().hide();
    $( ".select-delivery-method" )
        .change(function () {
            var deliverymode = "";
            $( ".select-delivery-method option:selected" ).each(function() {
                deliverymode = $( this ).text() ;
                if (deliverymode == "HOME" || deliverymode == "EXPRESS" ) {
                    $('.select-delivery-address').parent().parent().show();
                }else{
                    $('.select-delivery-address').parent().parent().hide();
                }
            });
        });

})(jQuery); // End of use strict
