<div class="column-item post-style-2 hentry">
    <div class="post-inner">
        <?php if (has_post_thumbnail() && '' !== get_the_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('triply-tour-detail-gallery-3'); ?>
                </a>
            </div><!-- .post-thumbnail -->

        <?php endif; ?>
        <div class="entry-content">
            <div class="entry-content-wrapper">
                <div class="entry-header">
                    <div class="entry-meta">
                        <?php
                        triply_categories_link();
                        triply_post_meta();
                        ?>
                    </div>
                    <?php
                    the_title(sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                    ?>
                </div>
                <div class="entry-bottom">
                    <p><?php echo  wp_trim_words(wp_kses_post(get_the_excerpt()), 18); ?></p>
                </div>
                <div class="more-link-wrap"><a class="more-link" href="<?php echo get_the_permalink(); ?>"><span><?php echo esc_html__( 'Read More', 'triply' ); ?></span><i class="triply-icon-long-arrow-right"></i></a></div>
            </div>
        </div>
    </div>
</div>
