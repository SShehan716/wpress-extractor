(function ($) {
    'use strict';


    function handleWindow() {
        var body = document.querySelector('body');

        if (window.innerWidth > body.clientWidth + 5) {
            body.classList.add('has-scrollbar');
            body.setAttribute('style', '--scroll-bar: ' + (window.innerWidth - body.clientWidth) + 'px');
        } else {
            body.classList.remove('has-scrollbar');
        }
    }

    function minHeight() {
        var $body = $('body'),
            bodyHeight = $(window).outerHeight(),
            headerHeight = $('header.header-1').outerHeight(),
            footerHeight = $('footer.site-footer').outerHeight();
        if($body.find('header.header-1').length){

            $('.site-content').css({
                'min-height': bodyHeight - headerHeight - footerHeight - 94
            });
        }
    }

    function setPositionLvN($item) {
        var sub = $item.children('.sub-menu'),
            offset = $item.offset(),
            width = $item.outerWidth(),
            screen_width = $(window).width(),
            sub_width = sub.outerWidth();
        var align_delta = offset.left + width + sub_width - screen_width;
        if (align_delta > 0) {
            if ($item.parents('.menu-item-has-children').length) {
                sub.css({ left: 'auto', right: '100%' });
            }else {
                sub.css({ left: 'auto', right: '0' });
            }
        } else {
            sub.css({ left: '', right: '' });
        }
    }

    function initSubmenuHover() {
        $('.site-header .main-navigation .menu-item-has-children').hover(function (event) {
            var $item = $(event.currentTarget);
            setPositionLvN($item);
        });
    }

    function account_side() {
        var $account_side = $('body .header-group-action .site-header-account a');
        var $account_active = $('body .header-group-action .site-header-account .account-dropdown');
        $(document).mouseup(function (e) {
            if ($account_side.has(e.target).length == 0 && !$account_active.is(e.target) && $account_active.has(e.target).length == 0) {
                $account_active.removeClass('active');
            }
        });
        $account_side.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $account_active.toggleClass('active');
        });
    }
    $(document).ready(function () {
        account_side();
    });

    function backtotop() {
        $(window).scroll(function(){
            if ($(this).scrollTop() > 50) {
                $('.scrollup').addClass('activate');
            } else {
                $('.scrollup').removeClass('activate');
            }
        });
        $('.scrollup').on('click', function () {
            $("html, body").animate({scrollTop: 0}, 600);
            return false;
        });
    }

    initSubmenuHover();
    minHeight();
    handleWindow();
    backtotop();

})(jQuery);
