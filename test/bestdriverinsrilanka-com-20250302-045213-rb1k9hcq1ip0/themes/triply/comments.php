<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<section id="comments" class="comments-area" aria-label="<?php esc_attr_e('Post Comments', 'triply'); ?>">
    <?php
    if (have_comments()) :
        ?>
        <div class="comment-list-wrap">
            <h2 class="comments-title">
                <span>
				<?php
                printf( // WPCS: XSS OK.
                /* translators: 1: number of comments, 2: post title */
                    esc_html(_nx('%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'triply')),
                    number_format_i18n(get_comments_number())
                );
                ?>
                </span>
            </h2>
            <?php
            if (get_option('page_comments')) {
                ?>
                <div class="comment-result">
                    <?php
                    $total    = get_comments_number();
                    $per_page = get_query_var('comments_per_page');
                    $current  = get_query_var('cpage');
                    if (1 === intval($total)) {
                        esc_html_e('Showing the single comment', 'triply');
                    } elseif ($total <= $per_page || -1 === $per_page) {
                        /* translators: %d: total results */
                        printf(_n('Showing all %d comment', 'Showing all %d comments', $total, 'triply'), $total);
                    } else {
                        $first = ($per_page * $current) - $per_page + 1;
                        $last  = min($total, $per_page * $current);
                        /* translators: 1: first result 2: last result 3: total results */
                        printf(_nx('Showing %1$d &ndash; %2$d of %3$d comment', 'Showing %1$d&ndash;%2$d of %3$d comments', $total, 'with first and last result', 'triply'), $first, $last, $total);
                    }
                    ?>
                </div>
                <?php
            }
            ?>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through. ?>
                <nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="<?php esc_attr_e('Comment Navigation Above', 'triply'); ?>">
                    <span class="screen-reader-text"><?php esc_html_e('Comment navigation', 'triply'); ?></span>
                    <div class="nav-previous"><?php previous_comments_link('<i class="triply-icon-arrow-left"></i><span>' . esc_html__('Older Comments', 'triply') . '</span>'); ?></div>
                    <div class="nav-next"><?php next_comments_link('<span>' . esc_html__('Newer Comments', 'triply') . '</span><i class="triply-icon-arrow-right"></i>'); ?></div>
                </nav><!-- #comment-nav-above -->
            <?php endif; // Check for comment navigation.
            ?>

            <ol class="comment-list">
                <?php
                wp_list_comments(
                    array(
                        'style'      => 'ol',
                        'short_ping' => true,
                        'callback'   => 'triply_comment',
                    )
                );
                ?>
            </ol><!-- .comment-list -->

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through. ?>
                <nav id="comment-nav-below" class="comment-navigation" role="navigation" aria-label="<?php esc_attr_e('Comment Navigation Below', 'triply'); ?>">
                    <span class="screen-reader-text"><?php esc_html_e('Comment navigation', 'triply'); ?></span>
                    <div class="nav-previous"><?php previous_comments_link('<i class="triply-icon-arrow-left"></i><span>' . esc_html__('Older Comments', 'triply') . '</span>'); ?></div>
                    <div class="nav-next"><?php next_comments_link('<span>' . esc_html__('Newer Comments', 'triply') . '</span><i class="triply-icon-arrow-right"></i>'); ?></div>
                </nav><!-- #comment-nav-below -->
            <?php endif; // Check for comment navigation.
            ?>
        </div>
    <?php

    endif;

    if (!comments_open() && 0 !== intval(get_comments_number()) && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'triply'); ?></p>
    <?php
    endif;
    $args = apply_filters(
        'triply_comment_form_args', array(
            'title_reply_before' => '<span id="reply-title" class="gamma comment-reply-title">',
            'title_reply_after'  => '</span>',
            'comment_field'      => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="' . esc_attr__('Comment', 'triply') . '"></textarea></p>',
        )
    );
    comment_form($args);

    ?>

</section><!-- #comments -->

