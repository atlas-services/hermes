// Preloader (if the #preloader div exists)
$(window).on('load', function() {
    if ($('.stripe-button').length) {
        $('.stripe-button-el').addClass('btn btn-outline-dark align-middle fa fa-credit-card');
        $('.stripe-button-el span').removeAttr('style');
        $('.stripe-button-el').removeClass('stripe-button-el');
    }
});

