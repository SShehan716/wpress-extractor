<?php
/**
 * BA Single Tour Other
 *
 * Override BABE_html::block_related($babe_post)
 * @version 1.0.0
 */
$babe_post  = BABE_Post_types::get_post( get_the_ID() );
$image_srcs = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
$item_url   = BABE_Functions::get_page_url_with_args( get_the_ID(), $_GET );

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

$price_old  = $prices['discount_price_from'] < $prices['price_from'] ? '<span class="item_info_price_old">' . BABE_Currency::get_currency_price( $prices['price_from'] ) . '</span>' : '';
?>
<div class="babe_items babe_items_5 column-item">
    <div class="babe_all_items_item_inner">
        <div class="item_img">
            <?php echo has_post_thumbnail( get_the_ID() ) ? '<a class="item-thumb" href="' . $item_url . '"><img src="' . $image_srcs[0] . '" alt="' . esc_attr( $babe_post['post_title'] ) . '"></a>' : ''; ?>
        </div>
        <div class="item_text">
            <div class="item-meta">
                <div class="item_title">
                    <a href="<?php echo esc_url( $item_url ); ?>"><?php echo apply_filters( 'translate_text', $babe_post['post_title'] ); ?></a>
                </div>
                <?php echo BABE_Rating::post_stars_rendering( get_the_ID() ); ?>
                <div class="item_info_price">
                    <span class="item_info_price_new"><?php echo BABE_Currency::get_currency_price( $prices['discount_price_from'] ); ?></span>
                    <?php printf( '%s', $price_old ); ?>
                </div>
            </div>
        </div>
    </div>
</div>





