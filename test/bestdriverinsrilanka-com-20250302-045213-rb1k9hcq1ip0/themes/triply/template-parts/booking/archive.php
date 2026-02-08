<?php

global  $wp_query;
$settings = $this->get_controls_settings();
if ($settings['style'] == 1) {
    if (!empty($settings['column'])) {
        $this->add_render_attribute('wrapper', 'data-elementor-columns', $settings['column']);
    } else {
        $this->add_render_attribute('wrapper', 'data-elementor-columns', 1);
    }

    if (!empty($settings['column_tablet'])) {
        $this->add_render_attribute('wrapper', 'data-elementor-columns-tablet', $settings['column_tablet']);
    } else {
        $this->add_render_attribute('wrapper', 'data-elementor-columns-tablet', 1);
    }

    if (!empty($settings['column_mobile'])) {
        $this->add_render_attribute('wrapper', 'data-elementor-columns-mobile', $settings['column_mobile']);
    } else {
        $this->add_render_attribute('wrapper', 'data-elementor-columns-mobile', 1);
    }
} else {
    $this->add_render_attribute('wrapper', 'data-elementor-columns', 1);
    $this->add_render_attribute('wrapper', 'data-elementor-columns-tablet', 1);
    $this->add_render_attribute('wrapper', 'data-elementor-columns-mobile', 1);
}



echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
    echo '<div class="babe_shortcode_block sc_all_items"><div class="babe_shortcode_block_bg_inner"><div class="babe_shortcode_block_inner">';
        while ( have_posts() ) : the_post();
            $post = get_post( get_the_ID(), ARRAY_A);
            $prices = BABE_Post_types::get_post_price_from( $post['ID'] );
            $post = array_merge($post, $prices);
            include get_theme_file_path('template-parts/booking/block/item-block-' . $settings['style'] . '.php');
        endwhile;
    echo '</div></div></div>';

echo '</div>';

$pl_args = array(
    'base'      => add_query_arg('paged', '%#%'),
    'format'    => '?paged=%#%',
    'total'     => $wp_query->max_num_pages,
    'current'   => max(1, get_query_var('paged')),
    //How many numbers to either side of current page, but not including current page.
    'end_size'  => 1,
    //Whether to include the previous and next links in the list or not.
    'mid_size'  => 2,
    'prev_text' => '<i class="triply-icon triply-icon-angle-double-left"></i>' . esc_html__('Previous', 'triply'), // text for previous page
    'next_text' => esc_html__('Next', 'triply') . '<i class="triply-icon triply-icon-angle-double-right"></i>', // text for next page
);

if(!empty(paginate_links($pl_args))){
?>
    <div class="pagination">
        <nav class="elementor-pagination" role="navigation"
             aria-label="<?php esc_attr_e('Pagination', 'triply'); ?>">
            <?php echo paginate_links($pl_args); ?>
        </nav>
    </div>
<?php
}
