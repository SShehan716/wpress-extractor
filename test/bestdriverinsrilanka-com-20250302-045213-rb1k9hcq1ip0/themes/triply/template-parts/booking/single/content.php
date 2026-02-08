<?php
/**
 * BA Single Content
 *
 * @version 1.0.0
 */

if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
    $post_id = triply_ba_get_default_single_id();
} else {
    $post_id = get_the_ID();
}
$main_post = get_post( $post_id );

if ( is_single() && $main_post->post_type == BABE_Post_types::$booking_obj_post_type) {
    echo '<div class="triply-single-content">';
        echo do_shortcode(wpautop($main_post->post_content));
    echo '</div>';
}
