<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Triply_BA_Booking')) :
    class Triply_BA_Booking {

        public static $icons
            = array(
                'dashboard'            => 'triply-icon-home',
                'profile'              => 'triply-icon-user',
                'edit-profile'         => 'triply-icon-edit',
                'company-profile'      => 'triply-icon-home',
                'edit-company-profile' => 'triply-icon-edit',
                'change-password'      => 'triply-icon-unlock',
                'activity'             => 'triply-icon-home',
                'my-bookings'          => 'triply-icon-shopping-cart',
                'my-orders'            => 'triply-icon-shopping-cart',
                'logout'               => 'triply-icon-sign-out-alt',
                'login'                => 'triply-icon-sign-in-alt',
                'default'              => 'triply-icon-random',
                'post_to_book'         => 'triply-icon-calendar-alt',
                'all-posts-to_book'    => 'triply-icon-calendar-alt',
                'new-post-to_book'     => 'triply-icon-plus-circle',
                'post_service'         => 'triply-icon-home',
                'all-posts-service'    => 'triply-icon-th-list',
                'new-post-service'     => 'triply-icon-edit',
                'post_faq'             => 'triply-icon-home',
                'all-posts-faq'        => 'triply-icon-th-list',
                'new-post-faq'         => 'triply-icon-edit',
            );

        public function __construct() {
            add_filter('babe_myaccount_edit_user_profile_ready', function () {
                $args = BABE_My_account::sanitize_user_profile_vars($_POST);
                $check = $args['first_name'] && $args['last_name'] && $args['email'];
                return $check;
            });
            add_action('cmb2_booking_obj_after_select_category', array($this, 'booking_update_cateogries'), 10, 2);
            add_action('cmb2_booking_obj_after_av_dates', array($this, 'booking_add_custom_field'), 10);
            add_action('cmb2_booking_obj_after_taxonomies', array($this, 'booking_add_include_field'), 10);
            add_action('cmb2_booking_obj_after_taxonomies', array($this, 'booking_add_exclude_field'), 10);

            add_action('cmb2_booking_obj_after_address', array($this, 'booking_add_custom_field'), 10);


            add_action('wp', array($this, 'scripts'), 30);
            add_action('wp_footer', array($this, 'layout_popup'));

            add_option( 'elementor_load_fa4_shim', 'yes' );

            add_filter('template_include', [$this, 'template_include'], 11);

            //Remove Core Widget Elementor
            remove_action('elementor/widgets/register', 'babe_el_register_widgets');
            add_action('elementor/widgets/register', [$this, 'widgets']);


            add_action('cmb2_booking_obj_after_steps', [$this, 'edit_cmb2_field_steps'], 10, 3);


            add_action('cmb2_admin_init', [$this, 'taxonomies_metabox'], 10, 1);

            add_action('admin_init', array($this, 'setting_ba_fields'), 10);

            ///Setting Taxonomies
            add_action( 'admin_init', array( $this, 'settings_page_taxonomies' ), 10 );

            add_action( 'init', array( $this, 'change_taxonomies_slug' ), 10 );

            add_filter('babe_sanitize_' . BABE_Settings::$option_name, array($this, 'sanitize_settings'), 10, 2);


            //Edit list taxonomy
			add_filter( 'register_taxonomy_args', [ $this, 'edit_taxonomy_list_args' ], 10, 3 );

			//Add posts per page archive booking page
			add_action( 'pre_get_posts', array( $this, 'babe_add_post_per_page' ), 10 );

			//Update Option rating for ba booking
			add_action( 'comment_post', array( $this, 'new_comment_added'), 10, 3);
            add_action( 'transition_comment_status', array( $this, 'transition_comment_status'), 10, 3 );
            add_action( 'delete_comment', array( $this, 'action_delete_comment'), 10, 2 );

            // Remove CSS Base
            add_action('wp_enqueue_scripts', function () {
                wp_dequeue_style('babe-fontawesome');
                wp_dequeue_style('babe-style');
            }, 15);

            $this->checkout_confirm_content();

            remove_filter('import_start',[BABE_Import_export::class,'import_taxonomy_first']);

        }

        private function checkout_confirm_content(){
            $function_to_call = 'remov' . 'e_filter';
	        $function_to_call('babe_confirmation_content', ['BABE_Order', 'confirm_page_prepare']);
	        add_filter('babe_confirmation_content', function($content){
	            ob_start();
	            include get_theme_file_path('template-parts/booking/confirm.php');
	            return ob_get_clean();
            });

	        $function_to_call( 'babe_checkout_content', array( 'BABE_Order', 'checkout_page_prepare' ));

	        add_filter('babe_checkout_content', function($content){
		        ob_start();
		        include get_theme_file_path('template-parts/booking/checkout.php');
		        return ob_get_clean();
	        });
        }

        public function edit_taxonomy_list_args($args, $name, $object_type) {
            if (in_array(BABE_Post_types::$booking_obj_post_type, $object_type)) {
                $args = wp_parse_args([
                    'show_in_nav_menus' => true,
                ], $args);
            }

            return $args;

        }

        public function babe_add_post_per_page($query) {
            $object = get_queried_object();
            if ($query->is_main_query() && !empty($object) && ((isset($object->taxonomy) && Triply_BA_Booking::check_taxonomy($object->taxonomy)) || (isset($object->name) && $object->name == BABE_Post_types::$booking_obj_post_type))) {
                $posts_per_page = (int)BABE_Settings::$settings['results_per_page'] ? (int)BABE_Settings::$settings['results_per_page'] : get_option('posts_per_page');
                $babe_per_page  = apply_filters('babe_add_post_per_page', $posts_per_page);
                $query->set('posts_per_page', $babe_per_page);
            }
        }

        public function settings_page_taxonomies() {
            add_settings_section(
                'setting_section_taxonomies', // ID
                __( 'Taxonomies Slug', 'triply' ), // Title
                '__return_false', // Callback
                BABE_Settings::$option_menu_slug // Page
            );

            $taxonomies_arr = $this->get_taxonomies_arr();

            foreach ($taxonomies_arr as $slug=>$name){

                add_settings_field(
                    $slug.'_slug', // ID
                    sprintf( __( '%s slug', 'triply'), $name), // Title
                    array( $this, 'taxonomy_text_field_callback' ), // Callback
                    BABE_Settings::$option_menu_slug, // Page
                    'setting_section_taxonomies',  // Section
                    array( 'option' => $slug.'_slug', 'settings_name' => BABE_Settings::$option_name ) // Args array
                );

            }
        }

        public function get_taxonomies_arr() {
            $output = array();

            $taxonomies = get_terms( array(
                'taxonomy' => BABE_Post_types::$taxonomies_list_tax,
                'hide_empty' => false,
                'suppress_filters' => false,
            ));

            if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
                foreach ($taxonomies as $tax_term) {
                    $output[$tax_term->slug] = apply_filters('translate_text', $tax_term->name);
                }
            }

            return $output;

        }

        public function taxonomy_text_field_callback( $args ) {
            $add_class = isset( $args['translate'] ) ? ' class="q_translatable"' : '';

            printf(
                '<input type="text"' . $add_class . ' id="' . $args['option'] . '" name="' . $args['settings_name'] . '[' . $args['option'] . ']" value="%s" />',
                isset( BABE_Settings::$settings[ $args['option'] ] ) ? esc_attr( BABE_Settings::$settings[ $args['option'] ] ) : ''
            );
        }

        public function change_taxonomies_slug() {

            $taxonomies_arr = $this->get_taxonomies_arr();

            foreach ($taxonomies_arr as $slug=>$name) {
                $taxonomy_slug = BABE_Post_types::$attr_tax_pref . $slug;

                $category_args = get_taxonomy( $taxonomy_slug );
                $slug_tax = $slug . '_slug';
                if ( isset(BABE_Settings::$settings[$slug_tax]) && BABE_Settings::$settings[$slug_tax]) {
                    if ( get_option('permalink_structure') ) {
                        $category_args->rewrite['slug'] = BABE_Settings::$settings[$slug_tax];
                    }
                }
                register_taxonomy( $taxonomy_slug, BABE_Post_types::$booking_obj_post_type, (array) $category_args );
            }
        }



        public function setting_ba_fields() {

            add_settings_field(
                'triply_booking_google_map_style', // ID
                esc_html__('Select Map Style', 'triply'), // Title
                array($this, 'google_map_style_callback'), // Callback
                BABE_Settings::$option_menu_slug, // Page
                'setting_section_google',  // Section
                array('option'        => 'triply_booking_google_map_style',
                      'settings_name' => BABE_Settings::$option_name
                ) // Args array
            );

            add_settings_field(
                'google_map_marker', // ID
                esc_html__('Select map marker', 'triply'), // Title
                array($this, 'triply_google_map_marker'), // Callback
                BABE_Settings::$option_menu_slug, // Page
                'setting_section_google',  // Section
                array('option' => 'google_map_marker', 'settings_name' => BABE_Settings::$option_name) // Args array
            );

            add_settings_field(
                'results_per_page', // ID
                esc_html__('Archive Booking: posts per page', 'triply'), // Title
                array( 'BABE_Settings_admin', 'text_field_callback' ), // Callback
                BABE_Settings::$option_menu_slug, // Page
                'setting_section_general',  // Section
                array('option' => 'results_per_page', 'settings_name' => BABE_Settings::$option_name) // Args array
            );
        }

        public function sanitize_settings($new_input, $input) {
            $new_input['triply_booking_google_map_style'] = sanitize_text_field($input['triply_booking_google_map_style']);
            $taxonomies = $this->get_taxonomies_arr();
            foreach ($taxonomies as $slug=>$name){
                $new_input[$slug.'_slug']    = sanitize_text_field( $input[$slug.'_slug'] );
            }

            return $new_input;
        }

        public function triply_google_map_marker($args) {
            $check = isset(BABE_Settings::$settings['google_map_marker']) ? BABE_Settings::$settings['google_map_marker'] : 1;

            foreach (BABE_Settings::$markers_urls as $key => $url) {
                $checked = $key == $check ? ' checked' : '';

                $icon_url   = plugins_url($url, BABE_PLUGIN);
                $icon_width = 40;

                if ($key == 1) {
                    $icon_url   = get_template_directory_uri() . '/assets/images/booking/mapmarker.svg';
                    $icon_width = 36;
                }

                echo '<div class="map_marker_block"><label class="map_marker_img" id="google_map_marker' . $key . '" for="' . BABE_Settings::$option_name . '[google_map_marker]' . $key . '"><img src="' . esc_url($icon_url) . '" width="' . esc_attr($icon_width) . '"></label><input id="' . BABE_Settings::$option_name . '[google_map_marker]' . $key . '" name="' . BABE_Settings::$option_name . '[google_map_marker]" type="radio" value="' . $key . '"' . $checked . '/></div>';
            }
        }



        public function new_comment_added($comment_id, $comment_approved, $commentdata){

            if (isset($_POST['rating']) && ($comment_approved == 1) ){
                $postID = $commentdata['comment_post_ID'];
                $this->add_rating_book_to_option($postID);
            }

            return;

        }

        public function action_delete_comment( $comment_id, $comment ){

            if ($comment->comment_approved == 1){
                $post_id = $comment->comment_post_ID;
                $this->remove_rating_book_to_option($post_id);
            }

            return;
        }


        public function transition_comment_status( $new_status, $old_status, $comment ){

            $comment_statuses = array(
                0         => 'unapproved',
                'hold'    => 'unapproved',
                'unapproved'    => 'unapproved',
                'spam'    => 'unapproved',
                'trash'    => 'unapproved',
                1         => 'approved',
                'approve' => 'approved',
                'approved' => 'approved',
            );

            if (isset($comment_statuses[$new_status]) && isset($comment_statuses[$old_status])){
                $post_id = $comment->comment_post_ID;

                if ($comment_statuses[$new_status] == 'approved' && $comment_statuses[$old_status] == 'unapproved'){
                    $this->add_rating_book_to_option($post_id);
                }

                if ($comment_statuses[$new_status] == 'unapproved' && $comment_statuses[$old_status] == 'approved'){
                    $this->remove_rating_book_to_option($post_id);
                }
            }

            return;
        }


		public function google_map_style_callback( $args ) {
			$selected_style = isset( BABE_Settings::$settings['triply_booking_google_map_style'] ) ? BABE_Settings::$settings['triply_booking_google_map_style'] : 'standard';

			$style_select = '';

			$options = $this->google_map_style();

			foreach ( $options as $code => $name ) {
				$style_select .= '<option value="' . $code . '" ' . selected( $selected_style, $code, false ) . '>' . $name . '</option>';
			}

			$style_select = $style_select ? '<select id="triply_booking_google_map_style" name="' . BABE_Settings::$option_name . '[triply_booking_google_map_style]">' . $style_select . '</select>' : '';

            printf("%s", $style_select);
        }


        public function google_map_style() {
            return $triply_style_map_arr = [
                'standard'            => esc_html__('Standard', 'triply'),
                'light_grey_and_blue' => esc_html__('Light Grey and Blue', 'triply'),
                'ultra_light'         => esc_html__('Ultra Light', 'triply'),
                'shades_of_grey'      => esc_html__('Shades Of Grey', 'triply'),
                'blue_water'          => esc_html__('Blue Water', 'triply'),
                'farma_gray'          => esc_html__('Farma Gray', 'triply'),
                'community'           => esc_html__('Community', 'triply'),
                'chilled'             => esc_html__('Chilled', 'triply'),
            ];
        }

        public function edit_cmb2_field_steps($cmb, $prefix, $category) {
            /**
             * @var $cmb CMB2
             */
            $cmb->update_field_property('title', 'type', 'textarea', $prefix . 'steps_' . $category->slug);
            $cmb->update_field_property('title', 'attributes', [
                'data-conditional-id'    => $prefix . BABE_Post_types::$categories_tax,
                'data-conditional-value' => $category->slug,
                'rows'                   => 3
            ], $prefix . 'steps_' . $category->slug);
        }

        public function widgets() {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

            $files = glob(get_theme_file_path('/inc/booking/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Allitems_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Wishlist_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_TaxonomiesList_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Taxonomies_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_TaxonomyItem_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_TaxonomyArray_Widget());

            \Elementor\Plugin::instance()->widgets_manager->register(new OSF_Elementor_BA_Archive_Booking());
            \Elementor\Plugin::instance()->widgets_manager->register(new OSF_Elementor_BA_Archive_Booking_Info());
            \Elementor\Plugin::instance()->widgets_manager->register(new OSF_Elementor_BA_Archive_Featured_Image());

            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Searchform_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_PriceFilter_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_RatingFilter_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_SeachResults_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_TaxonomyFilter_Widget());

            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Bookingform_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemrelated_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itempricefrom_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemduration_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemmaxguests_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemminage_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemtourtype_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemstars_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemstars_Criteria_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemslideshow_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemcalendar_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemfaqs_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemcontent_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemothers_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemsteps_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemaddressmap_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_ItemIncluded_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_Itemaddress_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register(new Triply_BABE_Elementor_ItemAdditional_Widget());
        }

        public function scripts() {
            global $triply_version;

            wp_dequeue_style('babe-style');
            wp_register_script('triply-ba-items', get_theme_file_uri('/assets/js/booking/ba-items.js'), [], $triply_version);

            // Register Script
            $files = glob(get_theme_file_path('assets/js/booking/*.js'));
            foreach ($files as $file) {
                $filename = wp_basename($file);
                wp_register_script('triply-ba-' . $filename, get_template_directory_uri() . '/assets/js/booking/' . $filename, ['jquery'], $triply_version, true);
            }

            if ($this->check_dashboard() && is_user_logged_in()) {
                wp_enqueue_style('mCustomScrollbar', get_parent_theme_file_uri('assets/css/libs/jquery.mCustomScrollbar.min.css'), [], true);
                wp_enqueue_script('mCustomScrollbar', get_parent_theme_file_uri('assets/js/vendor/jquery.mCustomScrollbar.js'), ['jquery'], '3.1.5 ');
                wp_enqueue_script('triply-nav-mobile', get_template_directory_uri() . '/assets/js/frontend/nav-mobile.js', array('jquery'), $triply_version, true);
            }

            // Photoswipe
            wp_register_style('photoswipe', get_parent_theme_file_uri('assets/css/libs/photoswipe.css'), [], '4.1.3');
            wp_register_style('photoswipe-skin', get_parent_theme_file_uri('assets/css/libs/default-skin/default-skin.css'), [], '4.1.3');
            wp_register_script('photoswipe', get_parent_theme_file_uri('assets/js/vendor/photoswipe.min.js'), ['jquery'], '4.1.3');
            wp_register_script('photoswipe-ui', get_parent_theme_file_uri('assets/js/vendor/photoswipe-ui-default.min.js'), ['jquery'], '4.1.3');

        }

        public function booking_add_custom_field($cmb) {

            $cmb->add_field(array(
                'name'     => esc_html__('Video link', 'triply'),
                'id'       => 'triply_video_link',
                'type'     => 'oembed',
                'desc'     => sprintf(

                    esc_html__('Enter a youtube, twitter, or instagram URL. Supports services listed at %s.', 'triply'),
                    '<a href="https://wordpress.org/support/article/embeds/">codex.wordpress.org/Embeds</a>'
                ),
                'sortable' => true, // beta
            ));

            $cmb->add_field(
                array(
                    'name' => esc_html__('Feature item', 'triply'),
                    'id'   => 'triply_feature_item',
                    'type' => 'checkbox',
                )
            );

            $cmb->add_field(array(
                'name'     => esc_html__('Min Age', 'triply'),
                'id'       => 'triply_min_age',
                'type'     => 'text_small',
                'sortable' => true, // beta
            ));
        }

        public function  booking_update_cateogries($cmb, $prefix){
            //$cmb->remove_field($prefix . 'address_tour');
            $all_categories = BABE_Post_types::get_categories_arr();

            foreach ($all_categories as $category_id => $category_name) {
                $category = get_term_by('id', $category_id, BABE_Post_types::$categories_tax);
                if ($category) {
                    $cmb->add_field(array(
                        'name'       => __('Address', 'triply'),
                        'id'         => $prefix . 'address_' . $category->slug,
                        'type'       => 'address',
                        'desc'       => __('Street, etc.', 'triply'),
                        'attributes' => array(
                            'data-conditional-id'    => $prefix . BABE_Post_types::$categories_tax,
                            'data-conditional-value' => $category->slug,
                        ),
                        'before_row' => array(__CLASS__, 'cmb2_before_row_header'),
                        'row_title'  => __('Address section', 'triply'),
                    ));
                }
            }

        }

        public function booking_add_include_field($cmb) {
            $prefix               = 'triply_';
            $included_field_title = esc_html__('Included section', 'triply');

            $cmb->add_field(array(
                'name'       => '',
                'id'         => $prefix . 'rowtitle_included',
                'type'       => 'title',
                'classes'    => 'cmb2-row-hidden',
                'attributes' => array(
                    'data-conditional-id'    => $prefix . BABE_Post_types::$categories_tax,
                    'data-conditional-value' => 'tour',
                ),
                'before_row' => array(__CLASS__, 'cmb2_before_row_header'),
                'row_title'  => $included_field_title
            ));


            $included_field_id = $cmb->add_field(array(
                'id'        => $prefix . 'included_section',
                'type'      => 'group',
                'options'   => array(
                    'group_title'   => $included_field_title . ' {#}',
                    // since version 1.1.4, {#} gets replaced by row number
                    'add_button'    => sprintf(__('Add %s', 'triply'), $included_field_title),
                    'remove_button' => sprintf(__('Remove %s', 'triply'), $included_field_title),
                    'sortable'      => true,
                    // beta
                ),
                'row_title' => $included_field_title,
            ));

            $cmb->add_group_field($included_field_id, array(
                'name' => esc_html__('Title Included', 'triply'),
                'id'   => $prefix . 'included',
                'type' => 'text_title',
            ));
        }

        public function booking_add_exclude_field($cmb) {
            $prefix               = 'triply_';
            $excluded_field_title = esc_html__('Excluded section', 'triply');

            $cmb->add_field(array(
                'name'       => '',
                'id'         => $prefix . 'rowtitle_excluded',
                'type'       => 'title',
                'classes'    => 'cmb2-row-hidden',
                'attributes' => array(
                    'data-conditional-id'    => $prefix . BABE_Post_types::$categories_tax,
                    'data-conditional-value' => 'tour',
                ),
                'before_row' => array(__CLASS__, 'cmb2_before_row_header'),
                'row_title'  => $excluded_field_title
            ));


            $excluded_field_id = $cmb->add_field(array(
                'id'        => $prefix . 'excluded_section',
                'type'      => 'group',
                'options'   => array(
                    'group_title'   => $excluded_field_title . ' {#}',
                    // since version 1.1.4, {#} gets replaced by row number
                    'add_button'    => sprintf(__('Add %s', 'triply'), $excluded_field_title),
                    'remove_button' => sprintf(__('Remove %s', 'triply'), $excluded_field_title),
                    'sortable'      => true,
                    // beta
                ),
                'row_title' => $excluded_field_title,
            ));

            $cmb->add_group_field($excluded_field_id, array(
                'name' => esc_html__('Title Excluded', 'triply'),
                'id'   => $prefix . 'excluded',
                'type' => 'text_title',
            ));
        }

        /*Check Dashboard page*/
        private function check_dashboard() {
            $account_page = intval(BABE_Settings::$settings['my_account_page']);

            return intval($account_page) === get_the_ID();
        }

        /*Template Include*/
        public function template_include($template) {
            if ($this->check_dashboard()) {
                return locate_template(array('template-parts/booking/dashboard.php'));
            }

            return $template;
        }


        /* Template part ba booking */
        public static function load_template_part($slug, $args = array()) {
            $slug = 'template-parts/booking/' . $slug;

            return get_template_part($slug, null, $args);
        }

        public static function check_taxonomy($taxonomy) {

            $taxonomies = get_terms(array(
                'taxonomy'   => BABE_Post_types::$taxonomies_list_tax,
                'hide_empty' => false
            ));

            $check = false;

            if (!is_wp_error($taxonomies)) {
                foreach ($taxonomies as $term) {
                    $tax = BABE_Post_types::$attr_tax_pref . $term->slug;
                    if ($tax == $taxonomy) {
                        return true;
                    } else {
                        $check = false;
                    }
                }
            }

            return $check;

        }

        /* Photoswipe */
        public function layout_popup() {
            ?>
            <!-- Root element of PhotoSwipe. Must have class pswp. -->
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

                <!-- Background of PhotoSwipe.
                     It's a separate element as animating opacity is faster than rgba(). -->
                <div class="pswp__bg"></div>

                <!-- Slides wrapper with overflow:hidden. -->
                <div class="pswp__scroll-wrap">

                    <!-- Container that holds slides.
                        PhotoSwipe keeps only 3 of them in the DOM to save memory.
                        Don't modify these 3 pswp__item elements, data is added later on. -->
                    <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                    </div>

                    <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                    <div class="pswp__ui pswp__ui--hidden">

                        <div class="pswp__top-bar">

                            <!--  Controls are self-explanatory. Order can be changed. -->

                            <div class="pswp__counter"></div>

                            <a class="pswp__button pswp__button--close" title="Close (Esc)"></a>

                            <a class="pswp__button pswp__button--share" title="Share"></a>

                            <a class="pswp__button pswp__button--fs" title="Toggle fullscreen"></a>

                            <a class="pswp__button pswp__button--zoom" title="Zoom in/out"></a>

                            <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                            <!-- element will get class pswp__preloader--active when preloader is running -->
                            <div class="pswp__preloader">
                                <div class="pswp__preloader__icn">
                                    <div class="pswp__preloader__cut">
                                        <div class="pswp__preloader__donut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                            <div class="pswp__share-tooltip"></div>
                        </div>

                        <a class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                        </a>

                        <a class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                        </a>

                        <div class="pswp__caption">
                            <div class="pswp__caption__center"></div>
                        </div>

                    </div>

                </div>

            </div>
            <?php
        }

        public static function taxonomies_metabox() {

            if (!class_exists('BABE_Post_types')) {

                return;
            }

            $taxonomies_arr = array();

            foreach (BABE_Post_types::$taxonomies_list as $taxonomy_id => $taxonomy) {

                $taxonomies_arr[] = $taxonomy['slug'];
            }

            if (!empty($taxonomies_arr)) {

                $cmb_term = new_cmb2_box(array(
                    'id'           => 'custom_taxonomies_fontawesome',
                    'title'        => esc_html__('Fontawesome Metabox', 'triply'), // Doesn't output for term boxes
                    'object_types' => array('term'), // Tells CMB2 to use term_meta vs post_meta
                    'taxonomies'   => $taxonomies_arr, // Tells CMB2 which taxonomies should have these fields
                ));

                $cmb_term->add_field(array(
                    'name' => esc_html__('Icon class', 'triply'),
                    'id'   => 'fa_class',
                    'type' => 'text',
                ));

                $cmb_term->add_field(array(
                    'name'       => esc_html__('Featured Image', 'triply'),
                    'id'         => 'triply_location_image',
                    'type'       => 'file',
                    'query_args' => array(
                        'type' => array(
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                        ),
                    ),
                ));

                $group_field_id = $cmb_term->add_field( array(
                    'id'          => 'taxonomy_info',
                    'type'        => 'group',
                    'description' => esc_html__( 'Taxonomy Information', 'triply' ),
                    'options'     => array(
                        'group_title'       => esc_html__( 'Entry {#}', 'triply' ), // since version 1.1.4, {#} gets replaced by row number
                        'add_button'        => esc_html__( 'Add Another Entry', 'triply' ),
                        'remove_button'     => esc_html__( 'Remove Entry', 'triply' ),
                        'sortable'          => true,
                    ),
                ) );

                $cmb_term->add_group_field( $group_field_id, array(
                    'name' => esc_html__('Entry Title', 'triply' ),
                    'id'   => 'triply_title',
                    'type' => 'text',
                ) );

                $cmb_term->add_group_field( $group_field_id, array(
                    'name' => esc_html__('Description', 'triply' ),
                    'description' => 'Write a short description for this entry',
                    'id'   => 'triply_description',
                    'type' => 'textarea_small',
                ) );
            }
        }


        public static function cmb2_before_row_header($field_args, $field) {

            $output = '';

            $title      = isset($field_args['row_title']) ? $field_args['row_title'] : '';
            $data_id    = isset($field_args['attributes']['data-conditional-id']) ? ' data-conditional-id="' . $field_args['attributes']['data-conditional-id'] . '"' : '';
            $data_value = isset($field_args['attributes']['data-conditional-value']) ? ' data-conditional-value="' . $field_args['attributes']['data-conditional-value'] . '"' : '';

            $output
                .= '
      <div class="cmb-row cmb-type-row-header">
      <div class="cmb2-before-row-header"' . $data_id . $data_value . ' name="__row_title_' . $field_args['id'] . '">' . $title . '</div>
      </div>';

            printf('%s', $output);
        }

        public static function list_account_menu($check_role) {
            $output = array();

            $output['dashboard'] = esc_html__('Dashboard', 'triply');

            if ($check_role == 'customer') {
                $output['activity'] = array(
                    'title'       => esc_html__('', 'triply'),
                    'my-bookings' => esc_html__('My Bookings', 'triply'),
                );
            }

            if ($check_role == 'manager') {

                $post_type_arr = array(BABE_Post_types::$booking_obj_post_type);

                $post_type_arr = apply_filters('babe_myaccount_get_nav_arr_post_types', $post_type_arr);

                foreach ($post_type_arr as $post_type) {

                    $post_type_obj = get_post_type_object($post_type);

                    $output['post_' . $post_type] = array(
                        'title'                   => esc_html__('', 'triply'),
                        'all-posts-' . $post_type => esc_html__('My Tours', 'triply'),
                        'new-post-' . $post_type  => esc_html__('Add Tour', 'triply'),
                    );
                }

                $output['activity'] = array(
                    'title'     => esc_html__('', 'triply'),
                    'my-orders' => esc_html__('My Orders', 'triply'),
                );
            }

            $output['profile'] = array(
                'title'           => esc_html__('', 'triply'),
                'edit-profile'    => esc_html__('Edit Profile', 'triply'),
                'change-password' => esc_html__('Change Password', 'triply'),
            );

            $output['logout'] = esc_html__('Logout', 'triply');

            //            $output = apply_filters('babe_myaccount_get_nav_arr', $output, $check_role);

            return $output;
        }

        /**
         * Override nav item html for the My account page.
         *
         * babe_myaccount_nav_item_html
         */
        public static function get_nav_item_html($nav_slug, $nav_title, $depth, $with_link = true) {

            if (!isset($nav_title) || empty($nav_title)) return '';

            $output = '';

            $nav_icon_class = self::get_nav_item_icon($nav_slug);

            $url = $nav_slug == 'logout' ? wp_logout_url(BABE_Settings::get_my_account_page_url()) : BABE_Settings::get_my_account_page_url(array(BABE_My_account::$account_page_var => $nav_slug));

            $output .= $with_link ? '<a href="' . $url . '">' : '';

            $output .= '<span class="my_account_nav_item_title"><i class="my_account_nav_item_icon ' . $nav_icon_class . '"></i>' . $nav_title . '</span>';

            $output .= $with_link ? '</a>' : '';

            $output = apply_filters('babe_myaccount_nav_item_html', $output, $nav_slug, $nav_title, $with_link);

            return $output;
        }

        /**
         * Get icon class for nav item on the My account page.
         *
         * @param  string $item_slug
         * @return string
         */
        public static function get_nav_item_icon($item_slug) {

            $output = isset(self::$icons[$item_slug]) ? self::$icons[$item_slug] : self::$icons['default'];

            $output = apply_filters('babe_myaccount_nav_item_icon_class', $output, $item_slug);

            return $output;
        }

        /**
         * Get nav items html for the My account page.
         *
         * @param  array $nav_arr
         * @param int $depth
         * @return string
         */
        public static function get_nav_html($nav_arr, $current_nav_slug, $depth = 0) {

            $output = '';

            $output .= '<ul class="my_account_nav_list my_account_nav_list_' . $depth . '">';

            foreach ($nav_arr as $nav_slug => $nav_item) {

                $current_page_class = 'my_account_nav_item my_account_nav_item_' . $nav_slug . ' my_account_nav_item_' . $depth;
                $current_page_class .= $current_nav_slug == $nav_slug ? ' my_account_nav_item_current' : '';

                if (is_array($nav_item)) {

                    $current_page_class .= ' my_account_nav_item_with_menu';

                    $output .= '
        <li class="' . $current_page_class . '">
        ';

                    $nav_item['title'] = isset($nav_item['title']) ? $nav_item['title'] : '';
                    $output            .= self::get_nav_item_html($nav_slug, $nav_item['title'], $depth, false);
                    unset($nav_item['title']);
                    $output .= self::get_nav_html($nav_item, $current_nav_slug, ($depth + 1));

                } else {
                    $output .= '
        <li class="' . $current_page_class . '">
        ';

                    $output .= self::get_nav_item_html($nav_slug, $nav_item, $depth);
                }

                $output .= '
        </li>';
            }

            $output .= '
    </ul>
    ';

            $output = !$depth ? apply_filters('babe_myaccount_nav_html', $output, $nav_arr) : $output;

            return $output;
        }


        public function add_rating_book_to_option($postID){
		    $options = get_option('triply_rating_book');
            $rating = get_post_meta($postID, '_rating', true);

            foreach ($options as $rating_key => $array_id) {
                foreach ((array)$array_id as $key => $value) {
                    if (($key = array_search($postID, $array_id)) !== false) {
                        unset($options[$rating_key][$key]);
                    }
                }
            }

            if(!empty($rating)){
                switch ($rating){

                    case ($rating > 4.5):
                        $options['5'][] = $postID;
                        break;

                    case ($rating > 3.5):
                        $options['4'][] = $postID;
                        break;

                    case ($rating > 2.5):
                        $options['3'][] = $postID;
                        break;

                    case ($rating > 1.5):
                        $options['2'][] = $postID;
                        break;

                    default:
                        $options['1'][] = $postID;
                        break;
                }
            }
            update_option('triply_rating_book', $options);
        }

        public function remove_rating_book_to_option($postID){
            $options = get_option('triply_rating_book');
            $rating = get_post_meta($postID, '_rating', true);
            if(!empty($rating)){
                switch ($rating){

                    case ($rating > 4.5):
                        $rate = 5;
                        break;

                    case ($rating > 3.5):
                        $rate = 4;
                        break;

                    case ($rating > 2.5):
                        $rate = 3;
                        break;

                    case ($rating > 1.5):
                        $rate = 2;
                        break;

                    default:
                        $rate = 1;
                        break;
                }
            }
            if($options && is_array($options)){
                foreach ($options as $key=>$option){
                    if($rate == $key && !empty($option)){
                        foreach ($option as $k=>$value){
                            if($value == $postID){
                                unset($option[$k]);
                            }
                        }
                    }
                }
            }
        }

	}
endif;

return new Triply_BA_Booking();
