(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/babe-search-form.default', ($scope) => {

            $('.add_input_field .add_ids_title').on('click', function (event) {
                event.preventDefault();
                $('.add_ids_list').removeClass('active');
                if ($(this).parent().find('.add_ids_list').hasClass('triply-active')) {
                    $(this).parent().find('.add_ids_list').removeClass('triply-active');
                    search_add_list_close(this);
                } else {
                    $('.add_ids_list').removeClass('triply-active');
                    search_add_list_close('.add_ids_title');
                    $(this).parent().find('.add_ids_list').addClass('triply-active');
                    search_add_list_open(this);
                }
            });


            $('.add_input_field .add_ids_list .term_item').on('click', function (event) {
                var parent = $(this).closest('.add_ids_title');
                search_add_input_toggle(parent);
            });

            $(document).on('click', function (event) {
                var par = $(event.target).closest('.add_input_field');
                if (par.length) {
                    $(par).siblings().each(function (ind, elm) {
                        search_add_input_close(this);
                    });
                } else {
                    $(document).find('.add_input_field .add_ids_list.triply-active').parents().eq(1).each(function (ind, elm) {
                        search_add_input_close(this);
                    });
                }
            });

            function search_add_input_toggle(item) {
                $(item).parent().find('.add_ids_list').toggleClass('triply-active');
                $(item).parent().find('.add_ids_title .js-triply-icon').toggleClass('triply-icon-chevron-down');
                $(item).parent().find('.add_ids_title .js-triply-icon').toggleClass('triply-icon-chevron-up');
            }

            function search_add_input_close(item) {
                $(item).find('.add_ids_list').removeClass('triply-active');
                $(item).find('.add_ids_title .js-triply-icon').addClass('triply-icon-chevron-down');
                $(item).find('.add_ids_title .js-triply-icon').removeClass('triply-icon-chevron-up');
            }

            function search_add_list_open(item) {
                $(item).find('.js-triply-icon').removeClass('triply-icon-chevron-down');
                $(item).find('.js-triply-icon').addClass('triply-icon-chevron-up');
            }

            function search_add_list_close(item) {
                $(item).find('.js-triply-icon').addClass('triply-icon-chevron-down');
                $(item).find('.js-triply-icon').removeClass('triply-icon-chevron-up');
            }

        });
    });

})(jQuery);
