<?php
$babe_post  = BABE_Post_types::get_post( $post['ID'] );
$image_srcs = wp_get_attachment_image_src( get_post_thumbnail_id( $post['ID'] ), 'triply-item' );
$item_url   = BABE_Functions::get_page_url_with_args( $post['ID'], $_GET );
$price_old  = $post['discount_price_from'] < $post['price_from'] ? '<span class="item_info_price_old">' . BABE_Currency::get_currency_price( $post['price_from'] ) . '</span>' : '';
$discount   = $post['discount'] ? '<span class="item-label price_discount">' . $post['discount'] . '% ' . esc_html__( 'OFF', 'triply' ) . '</span>' : '';
$address    = isset( $babe_post['address'] ) ? $babe_post['address'] : false;
$videolink  = isset( $babe_post['triply_video_link'] ) ? $babe_post['triply_video_link'] : false;
$gallerys   = isset( $babe_post['images'] ) ? $babe_post['images'] : false;
$wishlist   = Triply_BA_Booking_Wishlist::add_to_wishlist( $post['ID'] );
$times_arr  = BABE_Post_types::get_post_av_times( $babe_post );
$guests     = isset( $babe_post['guests'] ) ? $babe_post['guests'] : false;
$feature    = isset( $babe_post['triply_feature_item'] ) ? $babe_post['triply_feature_item'] : false;

?>
<div class="babe_items babe_items_2 column-item">
    <div class="babe_all_items_item_inner">
        <div class="item_img">
			<?php if ( $discount !== '' ) {
				printf( '%s', $discount );
			} else {
				if ( $feature ) {
					echo '<span class="item-label">' . esc_html__( 'Featured', 'triply' ) . '</span>';
				}
			}
			?>
			<?php echo has_post_thumbnail( $post['ID'] ) ? '<a class="item-thumb" href="' . $item_url . '"><img src="' . $image_srcs[0] . '" alt="' . esc_attr( $post['post_title'] ) . '"></a>' : ''; ?>
			<?php if ( $wishlist && ! empty( $wishlist ) ): ?>
                <a class="triply_add_to_wishlist <?php echo esc_attr( $wishlist['class'] ); ?>" href="<?php echo esc_url( $wishlist['link'] ); ?>" title="<?php echo esc_attr( $wishlist['text'] ); ?>" rel="nofollow" data-book-title="<?php echo esc_attr( get_the_title( $post['ID'] ) ); ?>" data-book-id="<?php echo esc_attr( $post['ID'] ); ?>">
                    <span class="wishlist <?php echo esc_attr( $wishlist['icon_class'] ); ?>"></span>
                </a>
			<?php endif; ?>
        </div>
        <div class="item_text">
            <div class="item-meta">
				<?php echo BABE_Rating::post_stars_rendering( $post['ID'] ); ?>
            </div>
            <div class="item-bottom">
                <div class="item_title">
                    <a href="<?php echo esc_url( $item_url ); ?>"><?php echo apply_filters( 'translate_text', $post['post_title'] ); ?></a>
                </div>
                <div class="item_info_price">
                    <label><?php echo sprintf( BABE_Settings::get_option('price_from_label') , '' ); ?></label>
                    <span class="item_info_price_new"><?php echo BABE_Currency::get_currency_price( $post['discount_price_from'] ); ?></span>
					<?php printf( '%s', $price_old ); ?>
                </div>
            </div>
        </div>
    </div>
</div>


