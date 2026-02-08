(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/triply-login.default', ($scope) => {
            $('.js-btn-login-popup').magnificPopup({
                type:'inline',
                midClick: true,
                // Delay in milliseconds before popup is removed
                removalDelay: 300,

                // Class that is added to popup wrapper and background
                // make it unique to apply your CSS animations just to this exact popup
                mainClass: 'triply-mfp-zoom-in'
            });

            $('.js-btn-register-popup').magnificPopup({
                type:'inline',
                midClick: true,
                // Delay in milliseconds before popup is removed
                removalDelay: 300,

                // Class that is added to popup wrapper and background
                // make it unique to apply your CSS animations just to this exact popup
                // mainClass: 'triply-mfp-zoom-in'
            });

            $('.site-header-account a.group-button.login').mouseenter(function () {
                $(this).parent().find('.account-dropdown').append($('.account-wrap'));
            });
        });
    });

})(jQuery);


