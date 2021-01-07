
$(document).ready(function() {
    var $deliveryMethodSelect = $('.js-delivery-form-method');
    var $addressTarget = $('.js-address-target');
    var $addressNew = $('.js-address-new');
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
                    .removeClass('d-none');

                // Form new address
                var $addressNew = $('.js-address-new');
                $addressNew.addClass('d-none');

                var $addressButtonNew = $('.js-address-button-new');
                $addressButtonNew.click( function(e) {
                    // e.preventDefault();
                    $.ajax({
                        url: $addressButtonNew.data('address-new-url'),
                        success: function (html) {
                            if (!html) {
                                $addressNew.find('select').remove();
                                $addressNew.addClass('d-none');
                                return;
                            }
                            // Replace the current field and show
                            $addressNew
                                .html(html)
                                .removeClass('d-none')
                        }
                    });
                });
            }
        });
    });


});
