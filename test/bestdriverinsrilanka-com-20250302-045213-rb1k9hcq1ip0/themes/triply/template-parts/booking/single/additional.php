<?php
if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
    $post_id = triply_ba_get_default_single_id();
} else {
    $post_id = get_the_ID();
}
$babe_post = BABE_Post_types::get_post( $post_id );


if ( ! empty( $babe_post ) && isset( $babe_post['custom_section'] ) && ! empty( $babe_post['custom_section'] ) ) {
    echo '<div class="additional-wrapper">';
        foreach ( $babe_post['custom_section'] as $info ) {
            if ( isset( $info['title'] ) && isset( $info['content'] ) ) {
                ?>
                <div class="block_info">
                    <div class="block_info_title collapse-title">
                        <?php if($info['fa_class']): ?>
                            <span class="<?php echo esc_attr($info['fa_class']); ?>"></span>
                        <?php else: ?>
                            <span class="triply-icon-info-circle"></span>
                        <?php endif; ?>
                        <h4 class="info_title"> <?php echo apply_filters( 'translate_text', $info['title'] ); ?> </h4>
                        <span class="info_icon">
                            <span class="info_icon_closed"><i class="triply-icon-chevron-down"></i></span>
                            <span class="info_icon_opened"><i class="triply-icon-chevron-up"></i></span>
                        </span>
                    </div>
                    <div class="block_info_content collapse-body">
                        <div class="content">
                            <?php echo wpautop( $info['content'] ); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    echo '</div>';
}

