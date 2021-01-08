import select from 'select2';
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

                // clear Form new address
                var $addressNew = $('.js-address-new');
                var $addressButtonNew = $('.js-address-button-new');

                $addressButtonNew.addClass('d-none');
                $addressNew.addClass('d-none');

                // add Form new address
                if('CLICK_AND_COLLECT' != $deliveryMethodSelect.val()){
                    $addressButtonNew.removeClass('d-none');
                    $addressNew.addClass('d-none');

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
                                    .removeClass('d-none');
                                $("#delivery-button").addClass('d-none');
                                $addressTarget.addClass('d-none');
                            }
                        });
                    });
                }

                // handle select2

                // $("#delivery-button").removeClass('d-none');
                $( ".select2").select2({
                    theme: "bootstrap",
                });
                $('.select2-selection').css('margin-top', '12px');
                $('.select2-selection').css('border-color', '#a4c52e');
                $(".select2-results__option").css("background-color", "#123456");

            }
        });
    });


});
