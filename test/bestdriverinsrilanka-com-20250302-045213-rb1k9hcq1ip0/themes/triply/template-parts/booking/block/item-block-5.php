<?php
$babe_post  = BABE_Post_types::get_post( $post['ID'] );
$image_srcs = wp_get_attachment_image_src( get_post_thumbnail_id( $post['ID'] ), 'medium' );
$item_url   = BABE_Functions::get_page_url_with_args( $post['ID'], $_GET );
$price_old  = $post['discount_price_from'] < $post['price_from'] ? '<span class="item_info_price_old">' . BABE_Currency::get_currency_price( $post['price_from'] ) . '</span>' : '';

?>
<div class="babe_items babe_items_5 column-item">
    <div class="babe_all_items_item_inner">
        <div class="item_img">
	        <?php echo has_post_thumbnail( $post['ID'] ) ? '<a class="item-thumb" href="' . $item_url . '"><img src="' . $image_srcs[0] . '" alt="' . esc_attr( $post['post_title'] ) . '"></a>' : ''; ?>
        </div>
        <div class="item_text">
            <div class="item-meta">
                <div class="item_title">
                    <a href="<?php echo esc_url( $item_url ); ?>"><?php echo apply_filters( 'translate_text', $post['post_title'] ); ?></a>
                </div>
				<?php echo BABE_Rating::post_stars_rendering( $post['ID'] ); ?>
                <div class="item_info_price">
                    <span class="item_info_price_new"><?php echo BABE_Currency::get_currency_price( $post['discount_price_from'] ); ?></span>
					<?php printf( '%s', $price_old ); ?>
                </div>
            </div>
        </div>
    </div>
</div>