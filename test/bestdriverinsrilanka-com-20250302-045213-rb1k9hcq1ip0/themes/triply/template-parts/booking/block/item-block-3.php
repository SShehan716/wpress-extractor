<?php
$babe_post      = BABE_Post_types::get_post( $post['ID'] );
$image_srcs     = wp_get_attachment_image_src( get_post_thumbnail_id( $post['ID'] ), 'triply-item' );
$item_url       = BABE_Functions::get_page_url_with_args( $post['ID'], $_GET );
$price_old      = $post['discount_price_from'] < $post['price_from'] ? '<span class="item_info_price_old">' . BABE_Currency::get_currency_price( $post['price_from'] ) . '</span>' : '';
$discount       = $post['discount'] ? '<span class="item-label price_discount">' . $post['discount'] . '% ' . esc_html__( 'OFF', 'triply' ) . '</span>' : '';
$address        = isset( $babe_post['address'] ) ? $babe_post['address'] : false;
$videolink      = isset( $babe_post['triply_video_link'] ) ? $babe_post['triply_video_link'] : false;
$gallerys       = isset( $babe_post['images'] ) ? $babe_post['images'] : false;
$wishlist       = Triply_BA_Booking_Wishlist::add_to_wishlist( $post['ID'] );
$times_arr      = BABE_Post_types::get_post_av_times( $babe_post );
$guests         = isset( $babe_post['guests'] ) ? $babe_post['guests'] : false;
$excerpt_length = apply_filters( 'babe_shortcodes_all_item_excerpt_length', 30 );
$feature        = isset( $babe_post['triply_feature_item'] ) ? $babe_post['triply_feature_item'] : false;

?>
<div class="babe_items babe_items_1 babe_items_3 column-item">
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
                <div class="item-meta-left">
					<?php
					if ( ! empty( $times_arr ) ) {
						echo '<span class="item-days item-meta-value"><i class="triply-icon-calendar"></i><span>' . BABE_Post_types::get_post_duration( $babe_post ) . '</span></span>';
					}
					if ( $guests ) {
						echo '<span class="item-user item-meta-value"><i class="triply-icon-user"></i><span>' . $guests . '</span></span>';
					}
					?>
                </div>
                <div class="item-meta-right">
					<?php if ( $videolink || $gallerys ): ?>
                        <div class="item-meta-media">
							<?php if ( $gallerys ) {
								echo '<a href="#" data-images="' . esc_attr( json_encode( $gallerys ) ) . '" class="item-gallery item-meta-value"><i class="triply-icon-camera-alt"></i><span>' . count( $gallerys ) . '</span></a>';
							}
							if ( !empty( $videolink ) ) {
								echo '<a href="' . esc_url( $videolink ) . '" class="item-video item-meta-value"><i class="triply-icon-video"></i></a>';
							} ?>
                        </div>
					<?php
					endif;
					echo BABE_Rating::post_stars_rendering( $post['ID'] ); ?>
                </div>
            </div>
            <div class="item_title">
                <a href="<?php echo esc_url( $item_url ); ?>"><?php echo apply_filters( 'translate_text', $post['post_title'] ); ?></a>
            </div>
			<?php
			if ( ! empty( $address ) ) {
				?>
                <div class="item-location">
                    <i class="triply-icon-map-marker-alt"></i><span><?php echo esc_html( $address['address'] ); ?></span>
                </div>
				<?php
			}

			?>
            <div class="item_description">
				<?php echo BABE_Post_types::get_post_excerpt( $post, $excerpt_length ); ?>
            </div>
			<?php
			echo '<div class="item-icons">' . triply_ba_tour_term_icons( $post['ID'] ) . '</div>';
			?>
            <div class="item-bottom">
                <div class="item_info_price">
                    <label><?php echo sprintf( BABE_Settings::get_option('price_from_label') , '' ); ?></label>
                    <span class="item_info_price_new"><?php echo BABE_Currency::get_currency_price( $post['discount_price_from'] ); ?></span>
					<?php printf( '%s', $price_old ); ?>
                </div>
                <a class="read-more-item" href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html__( 'Explore', 'triply' ); ?>
                    <i class="triply-icon-long-arrow-right"></i></a>
            </div>

        </div>
    </div>
</div>


