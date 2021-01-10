
$(document).ready(function() {
    var $addressMethodSelect = $('.js-address-form-method');
    var $addressTarget = $('.js-address-target');
    var $addressNew = $('.js-address-new');
   
    $addressMethodSelect.on('change', function(e) { 
        $.ajax({
            url: $addressMethodSelect.data('specific-address-method-url'),
            data: {
                addressMethod: $addressMethodSelect.val()
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
                $("#address-button").removeClass('d-none');

                // clear Form new address
                var $addressNew = $('.js-address-new');
                var $addressButtonNew = $('.js-address-button-new');

                $addressButtonNew.addClass('d-none');
                $addressNew.html('').removeClass('d-none');
                // add Form new address
              //  if('CLICK_AND_COLLECT' != $addressMethodSelect.val()){
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
                                $("#address-button").addClass('d-none');
                            }
                        });
                    });
               // }

            }
        });
    });


});