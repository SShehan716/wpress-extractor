<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package triply
 */

do_action( 'triply_loop_before' );

$column = 3;

if (is_active_sidebar('sidebar-blog')){
    $column = 2;
}


echo '<div class="row" data-elementor-columns="'.$column.'" data-elementor-columns-tablet="2" data-elementor-columns-mobile="1">';


    while ( have_posts() ) : the_post();
        $post = get_post( get_the_ID(), ARRAY_A);
        $prices = BABE_Post_types::get_post_price_from( $post['ID'] );
        $post = array_merge($post, $prices);
        include get_theme_file_path('template-parts/booking/block/item-block-1.php');

    endwhile;


echo '</div>';


/**
 * Functions hooked in to triply_loop_after action
 *
 * @see triply_paging_nav - 10
 */
do_action( 'triply_loop_after' );
