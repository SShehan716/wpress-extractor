<?php
$theme          = wp_get_theme( 'triply' );
$triply_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}
require get_theme_file_path( 'inc/class-tgm-plugin-activation.php' );
$triply = (object) array(
	'version' => $triply_version,
	/**
	 * Initialize all the things.
	 */
	'main'    => require 'inc/class-main.php'
);

require get_theme_file_path( 'inc/class-walker-menu.php' );
require get_theme_file_path( 'inc/functions.php' );
require get_theme_file_path( 'inc/template-hooks.php' );
require get_theme_file_path( 'inc/template-functions.php' );

require_once get_theme_file_path( 'inc/merlin/vendor/autoload.php' );
require_once get_theme_file_path( 'inc/merlin/class-merlin.php' );
require_once get_theme_file_path( 'inc/merlin-config.php' );

require_once get_theme_file_path( 'inc/class-customize.php' );

if ( triply_is_elementor_activated() ) {
	require get_theme_file_path( 'inc/elementor/functions-elementor.php' );
	$triply->elementor = require get_theme_file_path( 'inc/elementor/class-elementor.php' );

	if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
		require get_theme_file_path( 'inc/elementor/class-elementor-pro.php' );
	}
}

if ( triply_is_ba_booking_activated() ) {
	$triply->booking         = require get_theme_file_path( 'inc/booking/class-ba.php' );
	$triply->booking_reviews = require get_theme_file_path( 'inc/booking/ba-reviews.php' );
	$triply->locations       = 'locations';
	$triply->types           = 'types';
	$triply->features        = 'features';
    $triply->amenities       = 'amenities';
    $triply->language        = 'language';

	require get_theme_file_path( 'inc/booking/ba-template-functions.php' );
	require get_theme_file_path( 'inc/booking/ba-template-hooks.php' );

	$triply->booking_wishlist = require get_theme_file_path( 'inc/booking/ba-wishlist.php' );
}

if ( ! is_user_logged_in() ) {
    require get_theme_file_path('inc/modules/class-login.php');
}

