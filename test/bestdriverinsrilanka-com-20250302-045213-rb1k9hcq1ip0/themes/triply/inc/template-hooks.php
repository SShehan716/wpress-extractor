<?php
/**
 * =================================================
 * Hook triply_page
 * =================================================
 */
add_action('triply_page', 'triply_page_header', 10);
add_action('triply_page', 'triply_page_content', 20);

/**
 * =================================================
 * Hook triply_single_post_top
 * =================================================
 */
add_action('triply_single_post_top', 'triply_post_header', 5);
add_action('triply_single_post_top', 'triply_post_thumbnail', 10);

/**
 * =================================================
 * Hook triply_single_post
 * =================================================
 */
add_action('triply_single_post', 'triply_post_content', 30);

/**
 * =================================================
 * Hook triply_single_post_bottom
 * =================================================
 */
add_action('triply_single_post_bottom', 'triply_post_taxonomy', 5);
add_action('triply_single_post_bottom', 'triply_post_nav', 10);
add_action('triply_single_post_bottom', 'triply_display_comments', 20);

/**
 * =================================================
 * Hook triply_loop_post_thumbnail
 * =================================================
 */
add_action('triply_loop_post_thumbnail', 'triply_post_thumbnail', 10);

/**
 * =================================================
 * Hook triply_loop_post
 * =================================================
 */
add_action('triply_loop_post', 'triply_post_header', 10);
add_action('triply_loop_post', 'triply_post_content', 30);

/**
 * =================================================
 * Hook triply_footer
 * =================================================
 */
add_action('triply_footer', 'triply_footer_default', 20);

/**
 * =================================================
 * Hook triply_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'triply_form_login', 1);
add_action('wp_footer', 'triply_mobile_nav', 1);
add_action('wp_footer', 'render_html_back_to_top', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'triply_pingback_header', 1);

/**
 * =================================================
 * Hook triply_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook triply_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook triply_post_header_before
 * =================================================
 */

/**
 * =================================================
 * Hook triply_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook triply_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook triply_sidebar
 * =================================================
 */
add_action('triply_sidebar', 'triply_get_sidebar', 10);

/**
 * =================================================
 * Hook triply_loop_after
 * =================================================
 */
add_action('triply_loop_after', 'triply_paging_nav', 10);

/**
 * =================================================
 * Hook triply_page_after
 * =================================================
 */
add_action('triply_page_after', 'triply_display_comments', 10);
