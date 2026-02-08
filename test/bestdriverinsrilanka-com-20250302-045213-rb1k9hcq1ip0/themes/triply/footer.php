
		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'triply_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
		/**
		 * Functions hooked in to triply_footer action
		 *
		 * @see triply_footer_default - 20
         * @see triply_handheld_footer_bar - 25 - woo
		 *
		 */
		do_action( 'triply_footer' );

		?>

	</footer><!-- #colophon -->

	<?php

		/**
		 * Functions hooked in to triply_after_footer action
		 * @see triply_sticky_single_add_to_cart 	- 999 - woo
		 */
		do_action( 'triply_after_footer' );
	?>

</div><!-- #page -->

<?php

/**
 * Functions hooked in to wp_footer action
 * @see triply_form_login 	- 1
 * @see triply_mobile_nav - 1
 * @see render_html_back_to_top - 1
 *
 */

wp_footer();
?>

</body>
</html>
