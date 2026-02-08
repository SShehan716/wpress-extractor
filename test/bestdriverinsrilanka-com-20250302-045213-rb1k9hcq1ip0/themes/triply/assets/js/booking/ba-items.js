(function ($) {
    'use strict';

    $(document).ready(function () {
        $('.item-video').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
        $('body').on('click', '.item-gallery:not(.added)', function (e) {
            $(this).addClass('added');
            e.preventDefault();
            let gallery = $(this),
                items = [],
                galleryImages = $(this).data('images');

            galleryImages.forEach(function (item, index) {
                items.push({
                    src: item.image,
                    title: item.description
                });
            });
            gallery.magnificPopup({
                mainClass: 'mfp-fade',
                items: items,
                gallery: {
                    enabled: true,
                    tPrev: $(this).data('prev-text'),
                    tNext: $(this).data('next-text')
                },
                type: 'image',
            }).magnificPopup('open');

        });
    });

})(jQuery);
