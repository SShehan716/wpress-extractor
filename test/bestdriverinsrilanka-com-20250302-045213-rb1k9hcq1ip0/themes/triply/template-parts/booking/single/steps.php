<?php
if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
	$post_id = triply_ba_get_default_single_id();
} else {
	$post_id = get_the_ID();
}
$babe_post = BABE_Post_types::get_post( $post_id );
$settings = $this->get_controls_settings();
if ( ! empty( $babe_post ) && isset( $babe_post['steps'] ) && ! empty( $babe_post['steps'] ) ) {
    $this->title_render();
	foreach ( $babe_post['steps'] as $step ) {
		if ( isset( $step['attraction'] ) && isset( $step['title'] ) ) {
			?>
                <div class="block_step">
                    <div class="block_step_title collapse-title">
                        <h4 class="step_title"> <?php $this->render_icon($settings['icon_step']); ?><?php echo apply_filters( 'translate_text', $step['title'] ); ?> </h4>
                        <span class="step_icon">
                            <span class="step_icon_closed"><?php $this->render_icon($settings['icon_open']); ?></span>
                            <span class="step_icon_opened"><?php $this->render_icon($settings['icon_close']); ?></span>
                        </span>
                    </div>
                    <div class="block_step_content collapse-body">
                        <div class="content">
                            <?php echo wpautop( $step['attraction'] ); ?>
                        </div>
                    </div>
                </div>
            <?php
		}
	}
}

