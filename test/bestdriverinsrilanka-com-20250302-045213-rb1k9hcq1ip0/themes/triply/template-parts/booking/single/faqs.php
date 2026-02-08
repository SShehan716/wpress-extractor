<?php
if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
	$post_id = triply_ba_get_default_single_id();
} else {
	$post_id = get_the_ID();
}
$babe_post = BABE_Post_types::get_post( $post_id );
if ( ! empty( $babe_post ) && isset( $babe_post['faq'] ) && ! empty( $babe_post['faq'] ) ) {
	$faqs_arr = BABE_Post_types::get_post_faq( $babe_post );
	if ( ! empty( $faqs_arr ) ) {
        $this->title_render();
		foreach ( $faqs_arr as $faq ) {
			?>
            <div class="block_faq accordion-block">
                <div class="block_faq_title accordion-title">
					<?php printf( '<h4 class="faq_title">%s</h4>', $faq['post_title'] ); ?>
                    <span class="step_icon">
                        <span class="step_icon_closed"><i class="triply-icon-chevron-down"></i></span>
                        <span class="step_icon_opened"><i class="triply-icon-chevron-up"></i></span>
                    </span>
                </div>
                <div class="block_faq_content accordion-body">
                    <div class="content">
						<?php echo wptexturize( $faq['post_content'] ); ?>
                    </div>

                </div>
            </div>
			<?php
		}
	}
}
