<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to triply_single_post_top action
         * @see triply_post_header          - 5
         * @see triply_post_thumbnail       - 10
         */
        do_action('triply_single_post_top'); ?>

        <div class="entry-content-wrapper">
            <?php
            /**
             * Functions hooked in to triply_single_post action
             *
             * @see triply_post_content         - 30
             */
            do_action('triply_single_post');
            ?>
        </div>
    </div>
    <?php
    /**
     * Functions hooked in to triply_single_post_bottom action
     *
     * @see triply_post_taxonomy       - 5
     * @see triply_post_nav            - 10
     * @see triply_display_comments    - 20
     */
    do_action('triply_single_post_bottom');
    ?>

</article><!-- #post-## -->
