<?php

if (!function_exists('triply_display_comments')) {
    /**
     * Triply display comments
     *
     * @since  1.0.0
     */
    function triply_display_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || 0 !== intval(get_comments_number())) :
            comments_template();
        endif;
    }
}

if (!function_exists('triply_comment')) {
    /**
     * Triply comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int $depth the comment depth.
     *
     * @since 1.0.0
     */
    function triply_comment($comment, $args, $depth) {
        if ('div' === $args['style']) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr($tag) . ' '; ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-body">
        <div class="comment-meta commentmetadata">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 128); ?>
                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
            </div>
            <?php if ('0' === $comment->comment_approved) : ?>
                <em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'triply'); ?></em>
                <br/>
            <?php endif; ?>

            <a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
               class="comment-date">
                <?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
            </a>
        </div>
        <?php if ('div' !== $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
    <?php endif; ?>
        <div class="comment-text">
            <?php comment_text(); ?>
        </div>
        <div class="reply">
            <?php
            comment_reply_link(
                array_merge(
                    $args, array(
                        'add_below' => $add_below,
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                    )
                )
            );
            ?>
            <?php edit_comment_link(esc_html__('Edit', 'triply'), '  ', ''); ?>
        </div>
        </div>
        <?php if ('div' !== $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
}

if (!function_exists('triply_credit')) {
    /**
     * Display the theme credit
     *
     * @return void
     * @since  1.0.0
     */
    function triply_credit() {
        ?>
        <div class="site-info">
            <?php echo apply_filters('triply_copyright_text', $content = esc_html__('Coppyright', 'triply') . ' &copy; ' . date('Y') . ' ' . '<a class="site-url" href="' . site_url() . '">' . get_bloginfo('name') . '</a>' . esc_html__('. All Rights Reserved.', 'triply')); ?>
        </div><!-- .site-info -->
        <?php
    }
}

if (!function_exists('triply_site_branding')) {
    /**
     * Site branding wrapper and display
     *
     * @return void
     * @since  1.0.0
     */
    function triply_site_branding() {
        ?>
        <div class="site-branding">
            <?php echo triply_site_title_or_logo(); ?>
        </div>
        <?php
    }
}

if (!function_exists('triply_site_title_or_logo')) {
    /**
     * Display the site title or logo
     *
     * @param bool $echo Echo the string or return it.
     *
     * @return string
     * @since 2.1.0
     */
    function triply_site_title_or_logo() {
        ob_start();
        the_custom_logo(); ?>
        <div class="site-branding-text">
            <?php if (is_front_page()) : ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                          rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php else : ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                         rel="home"><?php bloginfo('name'); ?></a></p>
            <?php endif; ?>

            <?php
            $description = get_bloginfo('description', 'display');

            if ($description || is_customize_preview()) :
                ?>
                <p class="site-description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div><!-- .site-branding-text -->
        <?php
        $html = ob_get_clean();

        return $html;
    }
}

if (!function_exists('triply_primary_navigation')) {
    /**
     * Display Primary Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function triply_primary_navigation() {
        ?>
        <nav class="main-navigation" role="navigation"
             aria-label="<?php esc_attr_e('Primary Navigation', 'triply'); ?>">
            <?php
            $args = apply_filters('triply_nav_menu_args', [
                'fallback_cb'     => '__return_empty_string',
                'theme_location'  => 'primary',
                'container_class' => 'primary-navigation',
            ]);
            wp_nav_menu($args);
            ?>
        </nav>
        <?php
    }
}

if (!function_exists('triply_mobile_navigation')) {
    /**
     * Display Handheld Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function triply_mobile_navigation() {
        ?>
        <nav class="mobile-navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'triply'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'handheld',
                    'container_class' => 'handheld-navigation',
                )
            );
            ?>
        </nav>
        <?php
    }
}

if (!function_exists('triply_vertical_navigation')) {
    /**
     * Display Vertical Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function triply_vertical_navigation() {

        if (isset(get_nav_menu_locations()['vertical'])) {
            $string = get_term(get_nav_menu_locations()['vertical'], 'nav_menu')->name;
            ?>
            <nav class="vertical-navigation" aria-label="<?php esc_attr_e('Vertical Navigation', 'triply'); ?>">
                <div class="vertical-navigation-header">
                    <i class="triply-icon-caret-vertiacl-menu"></i>
                    <span class="vertical-navigation-title"><?php echo esc_html($string); ?></span>
                </div>
                <?php

                $args = apply_filters('triply_nav_menu_args', [
                    'fallback_cb'     => '__return_empty_string',
                    'theme_location'  => 'vertical',
                    'container_class' => 'vertical-menu',
                ]);

                wp_nav_menu($args);
                ?>
            </nav>
            <?php
        }
    }
}

if (!function_exists('triply_homepage_header')) {
    /**
     * Display the page header without the featured image
     *
     * @since 1.0.0
     */
    function triply_homepage_header() {
        edit_post_link(esc_html__('Edit this section', 'triply'), '', '', '', 'button triply-hero__button-edit');
        ?>
        <header class="entry-header">
            <?php
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('triply_page_header')) {
    /**
     * Display the page header
     *
     * @since 1.0.0
     */
    function triply_page_header() {

        if (is_front_page() || !is_page_template('default')) {
            return;
        }

        ?>
        <header class="entry-header">
            <?php
            if (has_post_thumbnail()) {
                triply_post_thumbnail('full');
            }
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('triply_page_content')) {
    /**
     * Display the post content
     *
     * @since 1.0.0
     */
    function triply_page_content() {
        ?>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'triply'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('triply_post_header')) {
    /**
     * Display the post header with a link to the single post
     *
     * @since 1.0.0
     */
    function triply_post_header() {
        ?>
        <header class="entry-header">
            <?php

            /**
             * Functions hooked in to triply_post_header_before action.
             */
            do_action('triply_post_header_before');
            ?>
            <div class="entry-meta">
                <?php
                triply_categories_link();
                triply_post_meta();
                ?>
            </div>

            <?php
            if (is_single()) {
                the_title('<h1 class="entry-title">', '</h1>');
            } else {
                the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
            }
            do_action('triply_post_header_after');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('triply_post_content')) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 1.0.0
     */
    function triply_post_content() {
        ?>
        <div class="entry-content">
            <?php

            /**
             * Functions hooked in to triply_post_content_before action.
             *
             */
            do_action('triply_post_content_before');


            if (is_search()) {
                the_excerpt();
            } else {
                the_content(
                    sprintf(
                    /* translators: %s: post title */
                        esc_html__('Read More', 'triply')
                    )
                );
            }

            /**
             * Functions hooked in to triply_post_content_after action.
             *
             */
            do_action('triply_post_content_after');

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'triply'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('triply_post_meta')) {
    /**
     * Display the post meta
     *
     * @since 1.0.0
     */
    function triply_post_meta() {
        if ('post' !== get_post_type()) {
            return;
        }

        // Posted on.
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date('c')),
            esc_html(get_the_modified_date())
        );

        $posted_on = '<span class="posted-on">' . sprintf('<a href="%1$s" rel="bookmark">%2$s</a>', esc_url(get_permalink()), $time_string) . '</span>';

        // Author.
        $author = sprintf(
            '<span class="post-author"><span>%1$s<a href="%2$s">%3$s</a></span></span>',
            esc_html__('By ', 'triply'),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_html(get_the_author())
        );


        echo wp_kses(
            sprintf('%1$s %2$s', $posted_on, $author), array(
                'span' => array(
                    'class' => array(),
                ),
                'a'    => array(
                    'href'  => array(),
                    'title' => array(),
                    'rel'   => array(),
                ),
                'time' => array(
                    'datetime' => array(),
                    'class'    => array(),
                ),
            )
        );
    }
}

if (!function_exists('triply_get_allowed_html')) {
    function triply_get_allowed_html() {
        return apply_filters(
            'triply_allowed_html',
            array(
                'br'     => array(),
                'i'      => array(),
                'b'      => array(),
                'u'      => array(),
                'em'     => array(),
                'del'    => array(),
                'a'      => array(
                    'href'  => true,
                    'class' => true,
                    'title' => true,
                    'rel'   => true,
                ),
                'strong' => array(),
                'span'   => array(
                    'style' => true,
                    'class' => true,
                ),
            )
        );
    }
}

if (!function_exists('triply_edit_post_link')) {
    /**
     * Display the edit link
     *
     * @since 2.5.0
     */
    function triply_edit_post_link() {
        edit_post_link(
            sprintf(
                wp_kses(__('Edit <span class="screen-reader-text">%s</span>', 'triply'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<div class="edit-link">',
            '</div>'
        );
    }
}

if (!function_exists('triply_categories_link')) {
    /**
     * Prints HTML with meta information for the current cateogries
     */
    function triply_categories_link() {

        // Get Categories for posts.
        $categories_list = get_the_category_list(' ');

        if ('post' === get_post_type() && $categories_list) {
            // Make sure there's more than one category before displaying.
            echo '<span class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'triply') . '</span>' . $categories_list . '</span>';
        }
    }
}

if (!function_exists('triply_post_taxonomy')) {
    /**
     * Display the post taxonomies
     *
     * @since 2.4.0
     */
    function triply_post_taxonomy() {
        /* translators: used between list items, there is a space after the comma */

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('');
        ?>
        <aside class="entry-taxonomy">
            <?php if ($tags_list) : ?>
                <div class="tags-links">
                    <strong><?php echo esc_html(_n('Tag:', 'Tags:', count(get_the_tags()), 'triply')); ?></strong>
                    <?php printf('%s', $tags_list); ?>
                </div>
            <?php endif; ?>
        </aside>
        <?php
    }
}

if (!function_exists('triply_paging_nav')) {
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function triply_paging_nav() {
        global $wp_query;

        $args = array(
            'type'      => 'list',
            'next_text' => '<span>' . esc_html__('NEXT', 'triply') . '</span><i class="triply-icon triply-icon-angle-double-right"></i>',
            'prev_text' => '<i class="triply-icon triply-icon-angle-double-left"></i><span>' . esc_html__('PREV', 'triply') . '</span>',
        );

        the_posts_pagination($args);
    }
}

if (!function_exists('triply_post_nav')) {
    /**
     * Display navigation to next/previous post when applicable.
     */
    function triply_post_nav() {
        $args = array(
            'next_text' => '<span class="nav-content"><span class="reader-text">' . esc_html__('NEXT POST', 'triply') . ' </span>%title' . '</span> ',
            'prev_text' => '<span class="nav-content"><span class="reader-text">' . esc_html__('PREV POST', 'triply') . ' </span>%title' . '</span> ',
        );

        the_post_navigation($args);
    }
}

if (!function_exists('triply_posted_on')) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     *
     * @deprecated 2.4.0
     */
    function triply_posted_on() {
        _deprecated_function('triply_posted_on', '2.4.0');
    }
}

if (!function_exists('triply_homepage_content')) {
    /**
     * Display homepage content
     * Hooked into the `homepage` action in the homepage template
     *
     * @return  void
     * @since  1.0.0
     */
    function triply_homepage_content() {
        while (have_posts()) {
            the_post();

            get_template_part('content', 'homepage');

        } // end of the loop.
    }
}

if (!function_exists('triply_get_sidebar')) {
    /**
     * Display triply sidebar
     *
     * @uses get_sidebar()
     * @since 1.0.0
     */
    function triply_get_sidebar() {
        get_sidebar();
    }
}

if (!function_exists('triply_post_thumbnail')) {
    /**
     * Display post thumbnail
     *
     * @param string $size the post thumbnail size.
     *
     * @uses has_post_thumbnail()
     * @uses the_post_thumbnail
     * @var $size . thumbnail|medium|large|full|$custom
     * @since 1.5.0
     */
    function triply_post_thumbnail($size = 'post-thumbnail') {
        echo '<div class="post-thumbnail">';
        if (has_post_thumbnail()) {
            the_post_thumbnail($size ? $size : 'post-thumbnail');
        }
        echo '</div>';
    }
}

if (!function_exists('triply_primary_navigation_wrapper')) {
    /**
     * The primary navigation wrapper
     */
    function triply_primary_navigation_wrapper() {
        echo '<div class="triply-primary-navigation"><div class="col-full">';
    }
}

if (!function_exists('triply_primary_navigation_wrapper_close')) {
    /**
     * The primary navigation wrapper close
     */
    function triply_primary_navigation_wrapper_close() {
        echo '</div></div>';
    }
}

if (!function_exists('triply_header_container')) {
    /**
     * The header container
     */
    function triply_header_container() {
        echo '<div class="col-full">';
    }
}

if (!function_exists('triply_header_container_close')) {
    /**
     * The header container close
     */
    function triply_header_container_close() {
        echo '</div>';
    }
}

if (!function_exists('triply_header_custom_link')) {
    function triply_header_custom_link() {
        echo triply_get_theme_option('custom-link', '');
    }

}

if (!function_exists('triply_header_contact_info')) {
    function triply_header_contact_info() {
        echo triply_get_theme_option('contact-info', '');
    }

}



if (!function_exists('triply_template_account_dropdown')) {
    function triply_template_account_dropdown() {
        if (!triply_get_theme_option('show_header_account', true)) {
            return;
        }
        ?>
        <div class="account-wrap" id="triply-dashboard" style="display: none;">
            <div class="account-inner <?php if (is_user_logged_in()): echo "dashboard"; endif; ?>">
                <?php if (is_user_logged_in()) {
                    triply_account_dropdown();
                }
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('triply_form_login')) {
    function triply_form_login() {
        if (!is_user_logged_in()) {
            if ( triply_is_ba_booking_activated() ):

                $account_page = intval(BABE_Settings::$settings['my_account_page']);
                $account_link = get_the_permalink($account_page);

                ?>
                <div class="account-wrap mfp-hide" id="triply-login-form">
                    <div class="my_account_page_content_wrapper">
                        <?php echo BABE_My_account::get_login_form(); ?>
                        <?php if ( get_option( 'users_can_register' ) ) : ?>
                            <div class="login_registration">
                                <h3><?php esc_html_e('Do not have an account?', 'triply'); ?></h3>
                                <div class="registration_link">
                                    <a class="btn-register js-btn-register-popup" href="#triply-register-form"><?php esc_html_e('Register', 'triply'); ?></a>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="account-wrap mfp-hide" id="triply-register-form">
                    <div class="my_account_page_content_wrapper">

                        <?php if ( get_option( 'users_can_register' ) ) : ?>

                            <div id="signup_form" class="triply-form-popup login_reg_content">
                                <h3 class="triply-login-title"><?php esc_html_e('Sign Up', 'triply'); ?></h3>
                                <form name="registration_form" id="registration_form" action="<?php echo esc_url(BABE_Settings::get_my_account_page_url(array('action' => 'registration'))); ?>" method="post">

                                    <div class="new-username">
                                        <label for="new_username"><?php esc_html_e('Username *', 'triply'); ?></label>
                                        <input type="text" name="new_username" id="new_username" class="input" value="" size="20" required="required">
                                        <div class="new-username-check-msg"><?php esc_html_e('This username already exists*', 'triply'); ?></div>
                                    </div>

                                    <div class="new-first-name">
                                        <label for="new_first_name"><?php esc_html_e('First name', 'triply'); ?></label>
                                        <input type="text" name="new_first_name" id="new_first_name" class="input" value="" size="20" required="required">
                                    </div>
                                    <div class="new-last-name">
                                        <label for="new_last_name"><?php esc_html_e('Last name', 'triply'); ?></label>
                                        <input type="text" name="new_last_name" id="new_last_name" class="input" value="" size="20" required="required">
                                    </div>

                                    <div class="new-email">
                                        <label for="new_email"><?php esc_html_e('Your email *', 'triply'); ?></label>
                                        <input type="text" name="new_email" id="new_email" class="input" value="" size="20" required="required">
                                        <div class="new-email-check-msg"><?php esc_html_e('This email already exists', 'triply'); ?></div>
                                    </div>
                                    <div class="new-email">
                                        <label for="new_email_confirm"><?php esc_html_e('Confirm email *', 'triply'); ?></label>
                                        <input type="text" name="new_email_confirm" id="new_email_confirm" class="input" value="" size="20" required="required">
                                    </div>

                                    <div class="new-submit">
                                        <input type="submit" name="new-submit" id="new-submit" class="button button-primary" value="<?php esc_attr_e('Sign up', 'triply'); ?>">
                                        <div class="form-spinner"><i class="fas fa-spinner fa-spin"></i></div>
                                    </div>
                                </form>
                                <div class="login_registration">
                                    <h3><?php esc_html_e('Already have an account?', 'triply'); ?></h3>
                                    <div class="registration_link">
                                        <a class="btn-login js-btn-login-popup" href="#triply-login-form"><?php esc_html_e('Login', 'triply'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="account-wrap mfp-hide" id="triply-login-form">
                    <div class="login-form-head">
                        <span class="login-form-title"><?php esc_attr_e('Sign in', 'triply') ?></span>
                        <?php if (get_option('users_can_register')) : ?>
                            <span class="pull-right">
                        <a class="register-link" href="<?php echo esc_url(wp_registration_url()); ?>"
                           title="<?php esc_attr_e('Register', 'triply'); ?>"><?php esc_attr_e('Create an Account', 'triply'); ?></a>
                    </span>
                        <?php endif; ?>
                    </div>
                    <form class="custom-triply-login-form-ajax" data-toggle="validator">
                        <p>
                            <label><?php esc_attr_e('Username or email', 'triply'); ?>
                                <span class="required">*</span></label>
                            <input name="username" type="text" required placeholder="<?php esc_attr_e('Username', 'triply') ?>">
                        </p>
                        <p>
                            <label><?php esc_attr_e('Password', 'triply'); ?> <span class="required">*</span></label>
                            <input name="password" type="password" required placeholder="<?php esc_attr_e('Password', 'triply') ?>">
                        </p>
                        <button type="submit" data-button-action
                                class="btn btn-primary btn-block w-100 mt-1"><?php esc_html_e('Login', 'triply') ?></button>
                        <input type="hidden" name="action" value="triply_login">
                        <?php wp_nonce_field('ajax-triply-login-nonce', 'security-login'); ?>
                    </form>
                    <div class="login-form-bottom">
                        <a href="<?php echo wp_lostpassword_url(get_permalink()); ?>" class="lostpass-link"
                           title="<?php esc_attr_e('Lost your password?', 'triply'); ?>"><?php esc_attr_e('Lost your password?', 'triply'); ?></a>
                    </div>
                </div>
            <?php
            endif;
        }else{ ?>
            <div class="account-wrap" id="triply-dashboard" style="display: none;">
                <div class="account-inner dashboard">
                    <?php triply_account_dropdown(); ?>
                </div>
            </div>
        <?php
        }
    }
}


if (!function_exists('triply_account_dropdown')) {
    function triply_account_dropdown() { ?>
        <?php if (has_nav_menu('my-account')) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e('Dashboard', 'triply'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'my-account',
                    'menu_class'     => 'account-links-menu',
                    'depth'          => 1,
                ));
                ?>
            </nav><!-- .social-navigation -->
        <?php else: ?>
            <?php
            $check_role               = false;
            if (triply_is_ba_booking_activated()) {
                $user_info  = wp_get_current_user();
                $check_role = BABE_My_account::validate_role($user_info);
            }

            if ($check_role):
                $nav_arr = Triply_BA_Booking::list_account_menu($check_role);
                $current_nav_slug_arr = BABE_My_account::get_current_nav_slug($nav_arr);
                $current_nav_slug     = key($current_nav_slug_arr);
                $depth                = 0;

                ?>
                <ul class="account-dashboard">
                    <?php
                    foreach ($nav_arr as $nav_slug => $nav_item) {

                        $current_page_class = 'my_account_nav_item my_account_nav_item_' . $nav_slug . ' my_account_nav_item_' . $depth;
                        $current_page_class .= $current_nav_slug == $nav_slug ? ' my_account_nav_item_current' : ''; ?>
                        <li class="<?php echo esc_attr($current_page_class); ?>">
                            <?php
                            if (is_array($nav_item)) {
                                $nav_item['title'] = isset($nav_item['title']) ? $nav_item['title'] : '';
                                ?>

                                <?php
                                printf("%s", Triply_BA_Booking::get_nav_html($nav_item, $current_nav_slug, ($depth + 1)));
                            } else {
                                printf("%s", Triply_BA_Booking::get_nav_item_html($nav_slug, $nav_item, $depth));
                            } ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            <?php else: ?>
                <ul class="account-dashboard">
                    <li>
                        <a class="nav-link" href="<?php echo esc_url(get_dashboard_url(get_current_user_id())); ?>" title="<?php esc_attr_e('Dashboard', 'triply'); ?>"><?php esc_html_e('Dashboard', 'triply'); ?></a>
                    </li>
                    <li>
                        <a class="nav-link" title="<?php esc_attr_e('Log out', 'triply'); ?>" class="tips" href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><?php esc_html_e('Log Out', 'triply'); ?></a>
                    </li>
                </ul>
            <?php endif; ?>
        <?php endif;

    }
}




if (!function_exists('triply_mobile_nav')) {
    function triply_mobile_nav() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <div class="triply-mobile-nav">
                <a href="#" class="mobile-nav-close"><i class="triply-icon-times"></i></a>
                <?php
                triply_language_switcher_mobile();
                triply_mobile_navigation();
                ?>
            </div>
            <div class="triply-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('triply_canvas_nav')) {
    function triply_canvas_nav() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <div class="triply-canvas-nav">
                <?php
                triply_language_switcher_mobile();
                triply_mobile_navigation();
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('triply_mobile_nav_button')) {
    function triply_mobile_nav_button() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <a href="#" class="menu-mobile-nav-button">
				<span
                        class="toggle-text screen-reader-text"><?php echo esc_attr(apply_filters('triply_menu_toggle_text', esc_html__('Menu', 'triply'))); ?></span>
                <i class="triply-icon-bars"></i>
            </a>
            <?php
        }
    }
}


if (!function_exists('triply_language_switcher')) {
    function triply_language_switcher() {
        $languages = apply_filters('wpml_active_languages', []);
        if (!triply_is_wpml_activated() || count($languages) <= 0) {
            return;
        }
        ?>
        <div class="triply-language-switcher">
            <ul class="menu">
                <li class="item">
					<span>
						<img width="18" height="12"
                             src="<?php echo esc_url($languages[ICL_LANGUAGE_CODE]['country_flag_url']) ?>"
                             alt="<?php esc_attr($languages[ICL_LANGUAGE_CODE]['default_locale']) ?>">
						<?php
                        echo esc_html($languages[ICL_LANGUAGE_CODE]['translated_name']);
                        ?>
					</span>
                    <ul class="sub-item">
                        <?php
                        foreach ($languages as $key => $language) {
                            if (ICL_LANGUAGE_CODE === $key) {
                                continue;
                            }
                            ?>
                            <li>
                                <a href="<?php echo esc_url($language['url']) ?>">
                                    <img width="18" height="12"
                                         src="<?php echo esc_url($language['country_flag_url']) ?>"
                                         alt="<?php esc_attr($language['default_locale']) ?>">
                                    <?php echo esc_html($language['translated_name']); ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
        <?php
    }
}

if (!function_exists('triply_language_switcher_mobile')) {
    function triply_language_switcher_mobile() {
        $languages = apply_filters('wpml_active_languages', []);
        if (!triply_is_wpml_activated() || count($languages) <= 0) {
            return;
        }
        ?>
        <div class="triply-language-switcher-mobile">
            <span>
                <img width="18" height="12"
                     src="<?php echo esc_url($languages[ICL_LANGUAGE_CODE]['country_flag_url']) ?>"
                     alt="<?php esc_attr($languages[ICL_LANGUAGE_CODE]['default_locale']) ?>">
            </span>
            <?php
            foreach ($languages as $key => $language) {
                if (ICL_LANGUAGE_CODE === $key) {
                    continue;
                }
                ?>
                <a href="<?php echo esc_url($language['url']) ?>">
                    <img width="18" height="12" src="<?php echo esc_url($language['country_flag_url']) ?>"
                         alt="<?php esc_attr($language['default_locale']) ?>">
                </a>
                <?php
            }
            ?>
        </div>
        <?php
    }
}

if (!function_exists('triply_footer_default')) {
    function triply_footer_default() {
        get_template_part('template-parts/copyright');
    }
}


if (!function_exists('triply_pingback_header')) {
    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     */
    function triply_pingback_header() {
        if (is_singular() && pings_open()) {
            echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
        }
    }
}

if (!function_exists('modify_read_more_link')) {
    function modify_read_more_link($html) {
        return preg_replace('/<a(.*)>(.*)<\/a>/iU', sprintf('<span class="more-link-wrap"><a$1><span class="faux-button">$2</span><i class="triply-icon-long-arrow-right"></i> <span class="screen-reader-text">"%1$s"</span></a></span>', get_the_title(get_the_ID())), $html);
    }
}

add_filter('the_content_more_link', 'modify_read_more_link');

function darken_color($rgb, $darker = 1.1) {

    $hash = (strpos($rgb, '#') !== false) ? '#' : '';
    $rgb  = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
    if (strlen($rgb) != 6) {
        return $hash . '000000';
    }
    $darker = ($darker > 1) ? $darker : 1;

    list($R16, $G16, $B16) = str_split($rgb, 2);

    $R = sprintf("%02X", floor(hexdec($R16) / $darker));
    $G = sprintf("%02X", floor(hexdec($G16) / $darker));
    $B = sprintf("%02X", floor(hexdec($B16) / $darker));

    return $hash . $R . $G . $B;
}


if (!function_exists('triply_update_comment_fields')) {
    function triply_update_comment_fields($fields) {

        $commenter = wp_get_current_commenter();
        $req       = get_option('require_name_email');
        $aria_req  = $req ? "aria-required='true'" : '';

        $fields['author']
            = '<p class="comment-form-author">
			<input id="author" name="author" type="text" placeholder="' . esc_attr__("Your Name *", "triply") . '" value="' . esc_attr($commenter['comment_author']) .
              '" size="30" ' . $aria_req . ' />
		</p>';

        $fields['email']
            = '<p class="comment-form-email">
			<input id="email" name="email" type="email" placeholder="' . esc_attr__("Email Address *", "triply") . '" value="' . esc_attr($commenter['comment_author_email']) .
              '" size="30" ' . $aria_req . ' />
		</p>';

        $fields['url']
            = '<p class="comment-form-url">
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__("Your Website", "triply") . '" value="' . esc_attr($commenter['comment_author_url']) .
              '" size="30" />
			</p>';

        return $fields;
    }
}

add_filter('comment_form_default_fields', 'triply_update_comment_fields');


if (!function_exists('render_html_back_to_top')) {
    function render_html_back_to_top() {
            echo <<<HTML
        <a href="#" class="scrollup"><i class="triply-icon-angle-up"></i></a>
HTML;

    }
}