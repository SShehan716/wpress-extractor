<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to triply_loop_post_thumbnail action.
	 *
	 * @see triply_post_thumbnail       - 10
	 */
	do_action( 'triply_loop_post_thumbnail' );
	?>
    <div class="entry-content-wrapper">
        <?php
        /**
         * Functions hooked in to triply_loop_post action
         *
         * @see triply_post_header          - 10
         * @see triply_post_content         - 30
         */
        do_action('triply_loop_post');
        ?>
    </div>

</article><!-- #post-## -->

