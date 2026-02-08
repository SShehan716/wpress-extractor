<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo triply_get_theme_option('site_mode') == 'dark' ? esc_attr('site-dark') : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    <link rel="profile" href="//gmpg.org/xfn/11">
    <?php
    /**
     * Functions hooked in to wp_head action
     *
     * @see triply_pingback_header - 1
     */
    wp_head();

    ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('triply_before_site'); ?>

<div id="page" class="hfeed site">
    <?php
    /**
     * Functions hooked in to triply_before_header action
     *
     */
    do_action('triply_before_header');
    ?>

    <header id="masthead" class="site-header header-1" role="banner" style="<?php triply_header_styles(); ?>">
        <div class="header-container">
            <div class="container header-main">
                <div class="header-left">
                    <?php
                    triply_site_branding();
                    triply_mobile_nav_button(); ?>
                </div>
                <div class="header-center">
                    <?php triply_primary_navigation(); ?>
                </div>
            </div>
        </div>
    </header>
    <!-- #masthead -->
    <?php


    do_action('triply_before_content');
    ?>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">

<?php
/**
 * Functions hooked in to triply_content_top action
 *
 * @see triply_shop_messages - 10 - woo
 */
do_action('triply_content_top');
