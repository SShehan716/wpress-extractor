<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to triply_page action
	 *
	 * @see triply_page_header          - 10
	 * @see triply_page_content         - 20
	 *
	 */
	do_action( 'triply_page' );
	?>
</article><!-- #post-## -->
