<div class="column-item post-style-1 hentry">
    <div class="post-inner">
        <?php if (has_post_thumbnail() && '' !== get_the_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('triply-post-grid'); ?>
                </a>
            </div><!-- .post-thumbnail -->

        <?php endif; ?>
        <div class="entry-content">
            <div class="entry-content-wrapper">
                <div class="entry-header">
                    <?php
                    triply_categories_link();
                    the_title(sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                    ?>
                </div>
                <div class="entry-bottom">
                    <p><?php echo wp_trim_words(wp_kses_post(get_the_excerpt()), 18); ?></p>
                </div>
                <div class="entry-meta">
                    <?php
                    triply_post_meta();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
