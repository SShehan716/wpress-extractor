(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.menu-mobile-nav-button').on('click', function (e) {
            e.preventDefault();
            $('html').toggleClass('mobile-nav-active');
        });

        $('.triply-overlay, .mobile-nav-close').on('click', function (e) {
            e.preventDefault();
            $('html').toggleClass('mobile-nav-active');
        });

        var $menu_mobile = $('.handheld-navigation');

        if ($menu_mobile.length > 0) {
            $menu_mobile.find('.menu-item-has-children > a, .page_item_has_children > a').each((index, element) => {
                var $dropdown = $('<button class="dropdown-toggle"></button>');
                $dropdown.insertAfter(element);

            });
            $(document).on('click', '.handheld-navigation .dropdown-toggle', function (e) {
                e.preventDefault();
                $(e.target).toggleClass('toggled-on');
                $(e.target).siblings('ul').stop().slideToggle(400);
            });
        }
    });
})(jQuery);