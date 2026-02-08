<?php
/**
 * BA Single Price From
 *
 * Override BABE_html::block_price_from($babe_post)
 * @version 1.0.0
 */

if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
	$post_id = triply_ba_get_default_single_id();
} else {
	$post_id = get_the_ID();
}
$babe_post = get_post( $post_id );

if ( is_single() && $babe_post->post_type == BABE_Post_types::$booking_obj_post_type) {

    $babe_post = BABE_Post_types::get_post($babe_post->ID);

    $output = '';

    if (
        ! isset( $babe_post['discount_price_from'] )
        || ! isset( $babe_post['price_from'] )
        || ! isset( $babe_post['discount_date_to'] )
        || ! isset( $babe_post['discount'] )
    ) {
        $prices = BABE_Post_types::get_post_price_from( $babe_post['ID'] );
    } else {
        $prices = $babe_post;
    }

    if ( ! empty( $prices ) ) { ?>
        <div class="item_info_price">
            <label><?php esc_html_e( 'From ', 'triply' ); ?></label>
            <span class="item_info_price_new"><?php printf("%s",BABE_Currency::get_currency_price( $prices['discount_price_from'] )); ?></span>
            <?php if($prices['discount_price_from'] < $prices['price_from']): ?>
                <span class="item_info_price_old"><?php printf("%s",BABE_Currency::get_currency_price( $prices['price_from'] )); ?></span>
            <?php endif; ?>
        </div>
    <?php
    }

}


