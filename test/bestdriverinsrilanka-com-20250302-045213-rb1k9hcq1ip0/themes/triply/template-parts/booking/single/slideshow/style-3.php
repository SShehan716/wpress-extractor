<?php
/*Slideshow Style 4*/

if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
    $post_id = triply_ba_get_default_single_id();
} else {
    $post_id = get_the_ID();
}
$booking_post = get_post($post_id);

if (is_single() && $booking_post->post_type == BABE_Post_types::$booking_obj_post_type) {
    $babe_post     = BABE_Post_types::get_post($booking_post->ID);
    $gallery_items = isset($babe_post['images']) ? $babe_post['images'] : array();

    $thumbnail_count = 4;
    ?>
    <div class="triply-single-slideshow slideshow-style-3" data-layout="style-3">
        <div class="slideshow-tab-container">
            <?php
            if (isset($gallery_items) && !empty($gallery_items)):
                ?>
                <div class="tab-content active gallery">
                    <div class="gallery-wrapper">
                        <div class="booking_single_gallery" id="booking-single-gallery-thumbnail-preview">
<!--                            <a href="#" class="gallery-view-full">-->
<!--                                <i class="triply-icon-expand-alt"></i>-->
<!--                            </a>-->
                            <div class="inner triply-carousel" data-popup-json="<?php echo esc_attr(triply_ba_get_gallery_popup_json($gallery_items)); ?>">
                                <?php
                                if (isset($gallery_items) && !empty($gallery_items)) {
                                    foreach ($gallery_items as $key => $gallery) { ?>
                                        <?php echo wp_get_attachment_image($gallery['image_id'], 'triply-tour-detail-gallery'); ?>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                        <div class="booking_single_thumbnails" id="booking-single-gallery-thumbnail">
                            <?php
                            if (isset($gallery_items) && !empty($gallery_items)) {
                                foreach ($gallery_items as $key => $gallery) {
                                    $image_thumbnail = wp_get_attachment_image_url($gallery['image_id'], 'thumbnail');
                                    $numberimages    = count($gallery_items) - $thumbnail_count;
                                    ?>
                                    <div data-slick-index="<?php echo esc_attr($key) ?>" data-thumbnail="<?php echo esc_url($image_thumbnail); ?>" class="thumbnail-inner<?php echo esc_attr(($key === 0 ? ' active' : '') . ($key === $thumbnail_count - 1 ? ' last' : '')); ?>">
                                        <img src="<?php echo esc_url($image_thumbnail); ?>" alt="<?php echo esc_attr__('Gallery Thumbnail ', 'triply') . esc_html($key); ?>">
                                        <?php if ($key === $thumbnail_count - 1 && $numberimages > 0): ?>
                                            <span class="count">+<?php echo esc_html($numberimages); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
    <?php
}
?>


