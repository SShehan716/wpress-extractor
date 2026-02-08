<?php
if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
	$post_id = triply_ba_get_default_single_id();
} else {
	$post_id = get_the_ID();
}
$babe_post = BABE_Post_types::get_post( $post_id );

$total_rating = BABE_Rating::get_post_total_rating( $post_id );
$total_votes  = BABE_Rating::get_post_total_votes( $post_id );

if ( $total_rating ) {

	$rating_arr = BABE_Rating::get_post_rating( $post_id );

	$rating_criteria = BABE_Settings::get_rating_criteria();
	$stars_num       = BABE_Settings::get_rating_stars_num();
	$criteria_num    = sizeof( $rating_arr );

	//// get total rating stars
	?>
    <div class="post-total-rating-stars stars">
        <div class="rating-stars">
			<?php
			for ( $i = 1; $i <= $stars_num; $i ++ ) {
				echo BABE_Rating::star_rendering( $i, $total_rating );
			}
			?>
        </div>
        <div class="post-total-rating-value">
            <span class="rating-score">
                <?php echo round( $total_rating, 2 ); ?>
            </span>

			<?php
			if ( $total_votes > 1 ) {
				echo sprintf( '<span class="rating-count">%d ' . esc_html__( "reviews", 'triply' ) . '</span>', $total_votes );
			} else {
				echo '';
			}
			?>
        </div>
    </div>
	<?php

} else {
	esc_html_e( 'No reviews yet', 'triply' );
} /// end if $total_rating

