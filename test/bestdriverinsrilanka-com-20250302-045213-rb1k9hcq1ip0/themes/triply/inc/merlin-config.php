<?php

class Triply_Merlin_Config {

	private $config = [];
	private $wizard;

	public function __construct() {
		$this->init();
		add_action( 'merlin_import_files', [ $this, 'setup_ba_tour' ], 1);
		add_action( 'merlin_import_files', [ $this, 'import_files' ], 10);
		add_action( 'merlin_after_all_import', [ $this, 'after_import_setup' ], 10, 1 );
		add_filter( 'merlin_generate_child_functions_php', [ $this, 'render_child_functions_php' ] );


		add_action( 'admin_post_custom_setup_data', [ $this, 'custom_setup_data' ] );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );

		add_action('import_start', function () {
            add_filter('wxr_importer.pre_process.post_meta', [$this, 'fiximport_elementor'], 10, 1);
        });
	}

	public function fiximport_elementor($post_meta) {
        if ('_elementor_data' === $post_meta['key']) {
            $post_meta['value'] = wp_slash($post_meta['value']);
        }

        return $post_meta;
    }

	public function admin_scripts() {
		global $triply_version;
		wp_enqueue_script( 'triply-admin-script', get_template_directory_uri() . '/assets/js/admin/admin.js', array( 'jquery' ), $triply_version, true );
	}

	private function init() {
		$this->wizard = new Merlin(
			$config = array(
				// Location / directory where Merlin WP is placed in your theme.
				'merlin_url'         => 'merlin',
				// The wp-admin page slug where Merlin WP loads.
				'parent_slug'        => 'themes.php',
				// The wp-admin parent page slug for the admin menu item.
				'capability'         => 'manage_options',
				// The capability required for this menu to be displayed to the user.
				'dev_mode'           => true,
				// Enable development mode for testing.
				'license_step'       => false,
				// EDD license activation step.
				'license_required'   => false,
				// Require the license activation step.
				'license_help_url'   => '',
				// URL for the 'license-tooltip'.
				'edd_remote_api_url' => '',
				'directory'          => 'inc/merlin',
				// EDD_Theme_Updater_Admin remote_api_url.
				'edd_item_name'      => '',
				// EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'     => '',
				// EDD_Theme_Updater_Admin item_slug.
			),
			$strings = array(
				'admin-menu'          => esc_html__( 'Theme Setup', 'triply' ),

				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'       => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'triply' ),
				'return-to-dashboard' => esc_html__( 'Return to the dashboard', 'triply' ),
				'ignore'              => esc_html__( 'Disable this wizard', 'triply' ),

				'btn-skip'                 => esc_html__( 'Skip', 'triply' ),
				'btn-next'                 => esc_html__( 'Next', 'triply' ),
				'btn-start'                => esc_html__( 'Start', 'triply' ),
				'btn-no'                   => esc_html__( 'Cancel', 'triply' ),
				'btn-plugins-install'      => esc_html__( 'Install', 'triply' ),
				'btn-child-install'        => esc_html__( 'Install', 'triply' ),
				'btn-content-install'      => esc_html__( 'Install', 'triply' ),
				'btn-import'               => esc_html__( 'Import', 'triply' ),
				'btn-license-activate'     => esc_html__( 'Activate', 'triply' ),
				'btn-license-skip'         => esc_html__( 'Later', 'triply' ),

				/* translators: Theme Name */
				'license-header%s'         => esc_html__( 'Activate %s', 'triply' ),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__( '%s is Activated', 'triply' ),
				/* translators: Theme Name */
				'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'triply' ),
				'license-label'            => esc_html__( 'License key', 'triply' ),
				'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'triply' ),
				'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'triply' ),
				'license-tooltip'          => esc_html__( 'Need help?', 'triply' ),

				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__( 'Welcome to %s', 'triply' ),
				'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'triply' ),
				'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'triply' ),
				'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'triply' ),

				'child-header'         => esc_html__( 'Install Child Theme', 'triply' ),
				'child-header-success' => esc_html__( 'You\'re good to go!', 'triply' ),
				'child'                => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'triply' ),
				'child-success%s'      => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'triply' ),
				'child-action-link'    => esc_html__( 'Learn about child themes', 'triply' ),
				'child-json-success%s' => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'triply' ),
				'child-json-already%s' => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'triply' ),

				'plugins-header'         => esc_html__( 'Install Plugins', 'triply' ),
				'plugins-header-success' => esc_html__( 'You\'re up to speed!', 'triply' ),
				'plugins'                => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'triply' ),
				'plugins-success%s'      => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'triply' ),
				'plugins-action-link'    => esc_html__( 'Advanced', 'triply' ),

				'import-header'      => esc_html__( 'Import Content', 'triply' ),
				'import'             => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'triply' ),
				'import-action-link' => esc_html__( 'Advanced', 'triply' ),

				'ready-header'      => esc_html__( 'All done. Have fun!', 'triply' ),

				/* translators: Theme Author */
				'ready%s'           => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'triply' ),
				'ready-action-link' => esc_html__( 'Extras', 'triply' ),
				'ready-big-button'  => esc_html__( 'View your website', 'triply' ),
				'ready-link-1'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'triply' ) ),
				'ready-link-2'      => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://themebeans.com/contact/', esc_html__( 'Get Theme Support', 'triply' ) ),
				'ready-link-3'      => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'triply' ) ),
			)
		);

		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	public function setup_ba_tour(){
	    $check_oneclick  = get_option( 'triply_check_oneclick', [] );
	    if(triply_is_ba_booking_activated() && !isset($check_oneclick['before_setup_ba'])){
            BABE_Install::setup_ages();
			BABE_Install::setup_tax_features();
			BABE_Install::setup_posts_places();
			BABE_Install::setup_rules();
			BABE_Install::setup_categories();
            $this->register_locations();
            $this->register_types();
            $this->register_amenities();
            $this->register_language();
            $check_oneclick['before_setup_ba'] = true;

		}
		update_option('triply_check_oneclick', $check_oneclick);
	}

	public function import_files(){
            return array(
            array(
                'import_file_name'           => 'home 1',
                'home'                       => 'home-1',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-1.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_1.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-1',
            ),

            array(
                'import_file_name'           => 'home 2',
                'home'                       => 'home-2',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-2.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_2.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-2',
            ),

            array(
                'import_file_name'           => 'home 3',
                'home'                       => 'home-3',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-3.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_3.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-3',
            ),

            array(
                'import_file_name'           => 'home 4',
                'home'                       => 'home-4',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-4.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_4.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-4',
            ),

            array(
                'import_file_name'           => 'home 5',
                'home'                       => 'home-5',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-5.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_5.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-5',
            ),

            array(
                'import_file_name'           => 'home 6',
                'home'                       => 'home-6',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-6.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_6.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-6',
            ),

            array(
                'import_file_name'           => 'home 7',
                'home'                       => 'home-7',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-7.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_7.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-7',
            ),

            array(
                'import_file_name'           => 'home 8',
                'home'                       => 'home-8',
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-8.xml'),
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_8.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/triply/home-8',
            ),
            );           
        }

	public function after_import_setup( $selected_import ) {
		$selected_import = ( $this->import_files() )[ $selected_import ];
		$check_oneclick  = get_option( 'triply_check_oneclick', [] );

		$this->set_demo_menus();

		wp_delete_post( 1, true );

		// setup Home page
		$home = get_page_by_path( $selected_import['home'] );

		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
		}

		// Setup Options
		$options = $this->get_all_options();
		// Elementor
		if ( ! isset( $check_oneclick['elementor-options'] ) ) {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            update_post_meta( $active_kit_id, '_elementor_page_settings', $options['elementor'] );
            update_option('elementor_experiment-e_font_icon_svg', 'inactive');
            $check_oneclick['elementor-options'] = true;
        }

		$this->setup_header_footer( $selected_import['home'] );

		if ( ! isset( $check_oneclick['logo'] ) ) {
			set_theme_mod('custom_logo', 371);
			$check_oneclick['logo'] = true;
		}

		if ( ! isset( $check_oneclick['booking']) && triply_is_ba_booking_activated() ) {
		    $this->update_location_data();
		    $this->update_types_data();
		    $this->update_features_data();
		    $this->update_booking_tour();
		    $ba_settings = wp_parse_args($options['babe'], get_option('babe_settings',[]));
		    update_option('babe_settings', $ba_settings);
		    $check_oneclick['booking'] = true;
		}

		update_option( 'triply_check_oneclick', $check_oneclick );
	}

	public function render_child_functions_php() {
		$output
			= "<?php
/**
 * Theme functions and definitions.
 */
";

		return $output;
	}

	public function widgets_init() {
		require_once get_parent_theme_file_path( '/inc/merlin/includes/recent-post.php' );
		register_widget( 'Triply_WP_Widget_Recent_Posts' );
		require_once get_parent_theme_file_path( '/inc/merlin/includes/categories.php' );
		register_widget( 'Triply_WP_Widget_Categories' );
	}

	private function setup_header_footer( $id ) {
		$this->reset_header_footer();
		$options = ( $this->get_all_header_footer() )[ $id ];

		foreach ( $options['header'] as $header_options ) {
			$header = get_page_by_path( $header_options['slug'], OBJECT, 'elementor_library' );
			if ( $header ) {
				update_post_meta( $header->ID, '_elementor_conditions', $header_options['conditions'] );
			}
		}

		foreach ( $options['footer'] as $footer_options ) {
			$footer = get_page_by_path( $footer_options['slug'], OBJECT, 'elementor_library' );
			if ( $footer ) {
				update_post_meta( $footer->ID, '_elementor_conditions', $footer_options['conditions'] );
			}
		}

		$cache = new ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
		$cache->regenerate();
	}

	 //// register tax Locations
    public function register_locations() {

	    global $triply;

        $new_tax_slug = BABE_Post_types::$attr_tax_pref . $triply->locations;
        $name = esc_html__('Booking Locations', 'triply');

        if(!taxonomy_exists( $new_tax_slug )){
            //// Locations insert term
            $inserted_term = wp_insert_term($name,   // the term
                BABE_Post_types::$taxonomies_list_tax, // the taxonomy
                array(
                    'description' => $name,
                    'slug'        => $triply->locations,
                )
            );

            if (!is_wp_error($inserted_term)){
                BABE_Post_types::init_taxonomies_list();
                update_term_meta($inserted_term['term_id'], 'gmap_active', 0);
                update_term_meta($inserted_term['term_id'], 'select_mode', 'multi_checkbox');
                update_term_meta($inserted_term['term_id'], 'frontend_style', 'col_3');
            }

            $labels = array(
                'name'              => $name,
                'singular_name'     => $name,
                'search_items'      => sprintf(__( 'Search %s', 'triply' ), $name),
                'all_items'         => sprintf(__( 'All %s', 'triply' ), $name),
                'parent_item'       => sprintf(__( 'Parent %s', 'triply' ), $name),
                'parent_item_colon' => sprintf(__( 'Parent %s:', 'triply' ), $name),
                'edit_item'         => sprintf(__( 'Edit %s', 'triply' ), $name),
                'update_itm'        => sprintf(__( 'Update %s', 'triply' ), $name),
                'add_new_item'      => sprintf(__( 'Add New %s', 'triply' ), $name),
                'new_item_name'     => sprintf(__( 'New %s', 'triply' ), $name),
                'menu_name'         => sprintf(__( '%s', 'triply' ), $name),
            );

            register_taxonomy( $new_tax_slug, BABE_Post_types::$booking_obj_post_type, array(
                'labels'            => $labels,
                'hierarchical'      => true,
                'query_var'         => $new_tax_slug,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
            ) );
        }
    }

    //// register tax Types
    public function register_types() {

	    global $triply;
        $name = esc_html__('Booking Types', 'triply');
        $new_tax_slug = BABE_Post_types::$attr_tax_pref .$triply->types;

        //// Types insert term
        if(!taxonomy_exists( $new_tax_slug )){
            //// Locations insert term
            $inserted_term = wp_insert_term($name,   // the term
                BABE_Post_types::$taxonomies_list_tax, // the taxonomy
                array(
                    'description' => $name,
                    'slug'        => $triply->types,
                )
            );

            if (!is_wp_error($inserted_term)){
                BABE_Post_types::init_taxonomies_list();
                update_term_meta($inserted_term['term_id'], 'gmap_active', 0);
                update_term_meta($inserted_term['term_id'], 'select_mode', 'multi_checkbox');
                update_term_meta($inserted_term['term_id'], 'frontend_style', 'col_3');
            }

            $labels = array(
                'name'              => $name,
                'singular_name'     => $name,
                'search_items'      => sprintf(__( 'Search %s', 'triply' ), $name),
                'all_items'         => sprintf(__( 'All %s', 'triply' ), $name),
                'parent_item'       => sprintf(__( 'Parent %s', 'triply' ), $name),
                'parent_item_colon' => sprintf(__( 'Parent %s:', 'triply' ), $name),
                'edit_item'         => sprintf(__( 'Edit %s', 'triply' ), $name),
                'update_itm'        => sprintf(__( 'Update %s', 'triply' ), $name),
                'add_new_item'      => sprintf(__( 'Add New %s', 'triply' ), $name),
                'new_item_name'     => sprintf(__( 'New %s', 'triply' ), $name),
                'menu_name'         => sprintf(__( '%s', 'triply' ), $name),
            );

            register_taxonomy( $new_tax_slug, BABE_Post_types::$booking_obj_post_type, array(
                'labels'            => $labels,
                'hierarchical'      => true,
                'query_var'         => $new_tax_slug,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
            ) );
        }

    }


    //// register tax Types
    public function register_amenities() {

        global $triply;
	    $name = esc_html__('Amenities', 'triply');
        $new_tax_slug = BABE_Post_types::$attr_tax_pref . $triply->amenities;

        //// Types insert term
        if(!taxonomy_exists( $new_tax_slug )){
            //// Locations insert term
            $inserted_term = wp_insert_term($name,   // the term
                BABE_Post_types::$taxonomies_list_tax, // the taxonomy
                array(
                    'description' => $name,
                    'slug'        => $triply->amenities,
                )
            );

            if (!is_wp_error($inserted_term)){
                BABE_Post_types::init_taxonomies_list();
                update_term_meta($inserted_term['term_id'], 'gmap_active', 0);
                update_term_meta($inserted_term['term_id'], 'select_mode', 'multi_checkbox');
                update_term_meta($inserted_term['term_id'], 'frontend_style', 'col_3');
            }

            $labels = array(
                'name'              => $name,
                'singular_name'     => $name,
                'search_items'      => sprintf(__( 'Search %s', 'triply' ), $name),
                'all_items'         => sprintf(__( 'All %s', 'triply' ), $name),
                'parent_item'       => sprintf(__( 'Parent %s', 'triply' ), $name),
                'parent_item_colon' => sprintf(__( 'Parent %s:', 'triply' ), $name),
                'edit_item'         => sprintf(__( 'Edit %s', 'triply' ), $name),
                'update_itm'        => sprintf(__( 'Update %s', 'triply' ), $name),
                'add_new_item'      => sprintf(__( 'Add New %s', 'triply' ), $name),
                'new_item_name'     => sprintf(__( 'New %s', 'triply' ), $name),
                'menu_name'         => sprintf(__( '%s', 'triply' ), $name),
            );

            register_taxonomy( $new_tax_slug, BABE_Post_types::$booking_obj_post_type, array(
                'labels'            => $labels,
                'hierarchical'      => true,
                'query_var'         => $new_tax_slug,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
            ) );
        }

    }


     //// register tax Types
    public function register_language() {

	    global $triply;
        $name = esc_html__('Language', 'triply');
        $new_tax_slug = BABE_Post_types::$attr_tax_pref . $triply->language;

        //// Types insert term
        if(!taxonomy_exists( $new_tax_slug )){
            //// Locations insert term
            $inserted_term = wp_insert_term($name,   // the term
                BABE_Post_types::$taxonomies_list_tax, // the taxonomy
                array(
                    'description' => $name,
                    'slug'        => $triply->language,
                )
            );

            if (!is_wp_error($inserted_term)){
                BABE_Post_types::init_taxonomies_list();
                update_term_meta($inserted_term['term_id'], 'gmap_active', 0);
                update_term_meta($inserted_term['term_id'], 'select_mode', 'multi_checkbox');
                update_term_meta($inserted_term['term_id'], 'frontend_style', 'col_3');
            }

            $labels = array(
                'name'              => $name,
                'singular_name'     => $name,
                'search_items'      => sprintf(__( 'Search %s', 'triply' ), $name),
                'all_items'         => sprintf(__( 'All %s', 'triply' ), $name),
                'parent_item'       => sprintf(__( 'Parent %s', 'triply' ), $name),
                'parent_item_colon' => sprintf(__( 'Parent %s:', 'triply' ), $name),
                'edit_item'         => sprintf(__( 'Edit %s', 'triply' ), $name),
                'update_itm'        => sprintf(__( 'Update %s', 'triply' ), $name),
                'add_new_item'      => sprintf(__( 'Add New %s', 'triply' ), $name),
                'new_item_name'     => sprintf(__( 'New %s', 'triply' ), $name),
                'menu_name'         => sprintf(__( '%s', 'triply' ), $name),
            );

            register_taxonomy( $new_tax_slug, BABE_Post_types::$booking_obj_post_type, array(
                'labels'            => $labels,
                'hierarchical'      => true,
                'query_var'         => $new_tax_slug,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
            ) );
        }

    }

    public function update_location_data(){
	    global $triply;

	    $location_taxonomy =  BABE_Post_types::$attr_tax_pref . $triply->locations;

        $taxonomies = get_terms( array(
            'taxonomy'   => $location_taxonomy,
            'hide_empty' => false

        ) );

        $data_info   = array(
            'Country'           => '',
            'Visa Requirements' => 'Visa in not needed for EU citizens. Everyone else need a visa.',
            'Languages spoken'  => 'English',
            'Currency used'     => 'USD',
            'Area (km2)'        => '300,000 km2'
        );

        $post_thumbnail = 286;


        if ( ! is_wp_error( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $data_info['Country'] = $taxonomy->name;
                $data                 = [];
                foreach ( $data_info as $title => $value ) {
                    $data[] = [
                        'triply_title'       => $title,
                        'triply_description' => $value
                    ];
                }

                update_term_meta( $taxonomy->term_id, 'taxonomy_info', $data );
                update_term_meta($taxonomy->term_id, 'triply_location_image', wp_get_attachment_url( $post_thumbnail ));

            }
        }
    }

    public function update_types_data(){

	    global $triply;

	    $types_taxonomy =  BABE_Post_types::$attr_tax_pref . $triply->types;

        $taxonomies = get_terms( array(
            'taxonomy'   => $types_taxonomy,
            'hide_empty' => false

        ) );

        $data_info   = array('triply-icon-ticket-alt', 'triply-icon-citytours', 'triply-icon-cruise', 'triply-icon-historical', 'triply-icon-hiking', 'triply-icon-museum');
        if ( ! is_wp_error( $taxonomies ) ) {
            foreach ( $taxonomies as $key=>$taxonomy ) {
                foreach ( $data_info as $k => $value ) {
                    if($key == $k){
                        update_term_meta( $taxonomy->term_id, 'fa_class', $value );
                    }
                }
            }
        }
	}

	public function update_features_data(){

	    global $triply;

	    $features_taxonomy =  BABE_Post_types::$attr_tax_pref . $triply->features;

        $taxonomies = get_terms( array(
            'taxonomy'   => $features_taxonomy,
            'hide_empty' => false

        ) );


        $data_info   = array('triply-icon-beaches', 'triply-icon-camera-alt', 'triply-icon-utensils-alt', 'triply-icon-wine-glass-alt');
        if ( ! is_wp_error( $taxonomies ) ) {
            foreach ( $taxonomies as $key=>$taxonomy ) {
                foreach ( $data_info as $k => $value ) {
                    if($key == $k){
                        update_term_meta( $taxonomy->term_id, 'fa_class', $value );
                    }
                }
            }
        }
	}


    public function update_booking_tour() {
        $params = array(
            'posts_per_page' => - 1,
            'post_type'      => BABE_Post_types::$booking_obj_post_type,
        );
        $query  = new WP_Query( $params );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ):
                $query->the_post();
                $this->add_start_date(get_the_ID());
                $this->add_end_date(get_the_ID());
                $this->add_slider_image(get_the_ID());
                $this->add_steps_tour(get_the_ID());
                $this->add_rate_tour(get_the_ID());
            endwhile;
        }
    }

    private function add_steps_tour($post_id) {
        $slug = 'tour';

        $data_steps = array(
            [
                'title'      => 'Day 1',
                'attraction' => 'Eum eu sumo albucius perfecto, commodo torquatos consequuntur pro ut, id posse splendide ius. Cu nisl putent omittantur usu, mutat atomorum ex pro, ius nibh nonumy id. Nam at eius dissentias disputando, molestie mnesarchum complectitur per te. In commune pericula mediocritatem per. Cu audiam dolorum appareat per, id habeo suavitate argumentum vel. Te his eros ludus tibique.'
            ],
            [
                'title'      => 'Day 2',
                'attraction' => 'Aenean eu leo quam pellentesque ornare. Sem lacinia quam venenatis vestibulum. Donec ullamcorper nulla non metus auctor fringilla. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nullam quis risus eget urna mollis ornare vel eu leo.'
            ],
            [
                'title'      => 'Day 3',
                'attraction' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.'
            ],
            [
                'title'      => 'Day 4',
                'attraction' => 'Lorem ipsum dolor sit amet, utinam munere antiopam vel ad. Qui eros iusto te. Nec ad feugiat honestatis. Quo illum detraxit an. Ius eius quodsi molestiae at, nostrum definitiones his cu. Discere referrentur mea id, an pri novum possim deterruisset.'
            ]
        );


        update_post_meta( $post_id, 'steps_' . $slug, $data_steps );
    }

    public function add_rate_tour($post_id) {

	    $category_slug  = 'tour';

        $price_arr = array( 0 => rand( 100, 200 ) );
        $rules     = BABE_Booking_Rules::get_rule_by_cat_slug( $category_slug );
        if ( $rules && isset($rules['ages'] ) ) {
            $ages = BABE_Post_types::get_ages_arr();
            $i    = 1;
            foreach ( $ages as $age_arr ) {
                $price_arr[ $age_arr['age_id'] ] = $i <= 2 ? floatval( $price_arr[0] - $i * 10 ) : floatval( 0 );
                $i ++;
            }

            unset( $price_arr[0] );

        }

        $days_arr  = BABE_Calendar_functions::get_week_days_arr();
        $rate_days_arr = array();
        foreach ( $days_arr as $day_num => $day_title ) {
            $rate_days_arr[ $day_num ] = $day_num;
        }

        //// create and save rates
         $rate_arr = array(
             'post_id' => $post_id,
             'cat_slug' => $category_slug,
             'apply_days' => $rate_days_arr,
             'start_days' => $rate_days_arr,
             '_price_general' => $price_arr,
             '_price_from' => '',
             '_prices_conditional' => array(),
             '_rate_min_booking' => '',
             '_rate_max_booking' => '',
             '_rate_title' => esc_html__('Default Price', 'triply'),
             '_rate_date_from' => '',
             '_rate_date_to' => '',
          );

        BABE_Prices::save_rate( $rate_arr );

        BABE_CMB2_admin::update_booking_obj_post($post_id, [], (object)array());
    }

    private function add_start_date($post_id) {
        $date_from_obj = new DateTime( '-3 days' );
        update_post_meta( $post_id, 'start_date', BABE_Calendar_functions::date_from_sql( $date_from_obj->format( 'Y-m-d' ) ) );
    }

    private function add_end_date($post_id) {
        $date_to_obj = new DateTime( '+1 year' );
        update_post_meta( $post_id, 'end_date', BABE_Calendar_functions::date_from_sql( $date_to_obj->format( 'Y-m-d' ) ) );
    }

    private function add_slider_image($post_id) {
        $post_thumbnail = 286;
        $images_slider = [];

        for ( $i = 1; $i <= 5; $i ++ ) {
            $images_slider[] = array(
                'image_id'    => $post_thumbnail,
                'image'       => wp_get_attachment_url( $post_thumbnail ),
                'description' => get_the_title($post_id) . ' - Image ' . $i,
            );
        }
        update_post_meta( $post_id, 'images', $images_slider );
    }


	private function get_all_header_footer() {
		return [
			'home-1' => [
				'header' => [
					[
						'slug'       => 'headerbuilder',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-1',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-2' => [
				'header' => [
					[
						'slug'       => 'headerbuilder',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-1',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-3' => [
				'header' => [
					[
						'slug'       => 'headerbuilder',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-2',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-4' => [
				'header' => [
					[
						'slug'       => 'headerbuilder',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-2',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-5' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-3',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-2',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-6' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-3',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-7' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-4',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'conditions' => [ 'include/general' ],
					]
				]
			],
			'home-8' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-5',
						'conditions' => [ 'include/general' ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'conditions' => [ 'include/general' ],
					]
				]
			],
		];
	}

	private function reset_header_footer() {
		$footer_args = array(
			'post_type'      => 'elementor_library',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => '_elementor_template_type',
					'compare' => 'IN',
					'value'   => [ 'footer', 'header' ]
				),
			)
		);
		$footer      = new WP_Query( $footer_args );
		while ( $footer->have_posts() ) : $footer->the_post();
			update_post_meta( get_the_ID(), '_elementor_conditions', [] );
		endwhile;
		wp_reset_postdata();
	}

	public function set_demo_menus() {
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'primary'  => $main_menu->term_id,
				'handheld' => $main_menu->term_id,
			)
		);
	}

	 /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
            add_options_page(
            'Custom Setup Theme',
            'Custom Setup Theme',
            'manage_options',
            'custom-setup-settings',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option('triply_options_setup');

        $header_data = $this->get_data_elementor_template('header');
        $footer_data = $this->get_data_elementor_template('footer');
        $tour_data = $this->get_data_elementor_template('single-post');

        $profile = $this->get_all_header_footer();

        $homepage = [];
        foreach ($profile as $key=>$value){
            $homepage[$key] = ucfirst( str_replace('-', ' ', $key) );
        }
        ?>
        <div class="wrap">
        <h1><?php esc_html_e('Custom Setup Themes', 'triply') ?></h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <table class="form-table">
                <tr>
                    <th>
                        <label><?php esc_html_e('Setup Themes', 'triply') ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <ul>
                                <li>
                                    <label><?php esc_html_e('Setup Theme', 'triply') ?>:
                                        <select name="setup-theme">
                                            <option value="profile" selected><?php esc_html_e('Select Profile', 'triply') ?></option>
                                             <option value="custom_theme"><?php esc_html_e('Custom Header and Footer', 'triply') ?></option>
                                        </select>
                                    </label>
                                </li>
                                <li class="profile setup-theme">
                                    <label><?php esc_html_e('Profile', 'triply') ?>:
                                        <select name="opal-data-home">
                                            <option value="" selected> <?php esc_html_e('Select home page profile', 'triply') ?></option>
                                            <?php foreach ($homepage as $id => $home) { ?>
                                                <option value="<?php echo esc_attr($id); ?>">
                                                    <?php echo esc_attr($home); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                </li>
                                <li class="custom_theme setup-theme">
                                    <label><?php esc_html_e('Header', 'triply') ?>:
                                        <select name="header">
                                            <option value="" selected><?php esc_html_e('Select header', 'triply') ?></option>
                                            <?php foreach ($header_data as $id => $header) { ?>
                                                <option value="<?php echo esc_attr($id); ?>">
                                                    <?php echo esc_attr($header); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                </li>
                                <li class="custom_theme setup-theme">
                                    <label><?php esc_html_e('Footer', 'triply') ?>:
                                        <select name="footer">
                                            <option value="" selected ><?php esc_html_e('Select Footer', 'triply') ?></option>
                                            <?php foreach ($footer_data as $id => $footer) { ?>
                                                <option value="<?php echo esc_attr($id); ?>">
                                                    <?php echo esc_attr($footer); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                </li>
                                <li class="setup-template">
                                    <label><?php esc_html_e('Tour detail template', 'triply') ?>:
                                        <select name="tour-template">
                                            <option value="" selected ><?php esc_html_e('Select Tour Template', 'triply') ?></option>
                                            <?php foreach ($tour_data as $id => $tour) { ?>
                                                <option value="<?php echo esc_attr($id); ?>">
                                                    <?php echo esc_attr($tour); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                </li>
                                <li>
                                    <input type="checkbox" id="update_elementor_content" name="opal-setup-data-elementor" value="1">
                                    <label><?php esc_html_e('Update Elementor Content', 'triply') ?></label>
                                </li>
                                <li>
                                    <input type="checkbox" id="update_elementor_options" name="opal-setup-data-elementor-options" value="1">
                                    <label><?php esc_html_e('Update Elementor Options', 'triply') ?></label>
                                </li>
                                 <li>
                                    <input type="checkbox" id="update_data_booking" name="opal-setup-data-booking" value="1">
                                    <label><?php esc_html_e('Update Data Booking', 'triply') ?></label>
                                </li>
                            </ul>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="custom_setup_data">
            <?php submit_button(esc_html('Setup Now!')); ?>
        </form>
        <?php  if (isset($_GET['saved'])) { ?>
            <div class="updated">
                <p><?php esc_html_e('Success! Have been setup for your website', 'triply'); ?></p>
            </div>
        <?php }
    }

    private function get_data_elementor_template($type){
        $args = array(
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_elementor_template_type',
                    'compare' => '=',
                    'value'   => $type
                ),
            )
        );
        $data = new WP_Query($args);
        $select_data = [];
        while ($data->have_posts()): $data->the_post();
            $select_data[get_the_ID()] = get_the_title();
        endwhile;
        wp_reset_postdata();

        return $select_data;
    }

    private function reset_elementor_conditions($type) {
		$args = array(
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => '_elementor_template_type',
					'compare' => '=',
					'value'   => $type
				),
			)
		);
		$query = new WP_Query($args);
		while ($query->have_posts()) : $query->the_post();
			update_post_meta(get_the_ID(), '_elementor_conditions', []);
		endwhile;
		wp_reset_postdata();
	}

    public function custom_setup_data(){
        if(isset($_POST)){
            if(isset($_POST['setup-theme'])){
                if( $_POST['setup-theme'] == 'profile'){
                    if (isset($_POST['opal-data-home']) && !empty($_POST['opal-data-home'])) {
                        $home = (isset($_POST['opal-data-home']) && $_POST['opal-data-home']) ? $_POST['opal-data-home'] : 'home-1';
                        $this->reset_elementor_conditions('header');
                        $this->reset_elementor_conditions('footer');
                        $this->setup_header_footer($home);
                    }
                }else{

                     if(isset($_POST['header']) && !empty($_POST['header'])){
                        $header = $_POST['header'];
                        $this->reset_elementor_conditions('header');
                        update_post_meta($header, '_elementor_conditions', ['include/general']);

                    }

                    if(isset($_POST['footer']) && !empty($_POST['footer'])){
                        $footer= $_POST['footer'];
                        $this->reset_elementor_conditions('footer');
                        update_post_meta($footer, '_elementor_conditions', ['include/general']);
                    }
                }
            }

            if(isset($_POST['tour-template']) && !empty($_POST['tour-template'])){
                $tour_template= $_POST['tour-template'];
                $this->reset_elementor_conditions('single-post');
                update_post_meta($tour_template, '_elementor_conditions', ['include/to_book']);
            }

            if (isset($_POST['opal-setup-data-elementor-options'])) {
                $options = $this->get_all_options();
                // Elementor
                $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
                update_post_meta($active_kit_id, '_elementor_page_settings', $options['elementor']);
            }

            if (isset($_POST['opal-setup-data-booking'])) {
                $check_oneclick['booking'] = false;
                if ( !( $check_oneclick['booking']) && triply_is_ba_booking_activated() ) {
                    $this->update_location_data();
                    $this->update_types_data();
                    $this->update_features_data();
                    $this->update_booking_tour();
                    $ba_settings = wp_parse_args($options['babe'], get_option('babe_settings',[]));
                    update_option('babe_settings', $ba_settings);
                    $check_oneclick['booking'] = true;
                }
            }

            if (isset($_POST['opal-setup-data-elementor']) || isset($_POST['opal-setup-data-elementor-options'])) {

                $cache = new ElementorPro\Modules\ThemeBuilder\Classes\Conditions_Cache();
                $cache->regenerate();

                Elementor\Plugin::$instance->files_manager->clear_cache();
            }

            wp_redirect(admin_url('options-general.php?page=custom-setup-settings&saved=1'));
            exit;
        }
    }

    public function get_all_options(){
            $options = [];
            $options['elementor'] = json_decode('{"system_colors":[{"_id":"primary","title":"Primary","color":"#dc834e"},{"_id":"primary_hover","title":"Primary Hover","color":"#9E5D36"},{"_id":"secondary","title":"Secondary","color":"#202F59"},{"_id":"secondary_hover","title":"Secondary Hover","color":"#121D39"},{"_id":"text","title":"Text","color":"#666666"},{"_id":"accent","title":"Heading","color":"#000000"},{"_id":"lighter","title":"Lighter","color":"#999999"},{"_id":"border","title":"Border","color":"#e5e5e5"}],"custom_colors":[],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom"},{"_id":"secondary","title":"Secondary","typography_typography":"custom"},{"_id":"text","title":"Text","typography_typography":"custom"},{"_id":"accent","title":"Accent","typography_typography":"custom"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Triply","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"stretched_section_container":"body","default_page_template":"elementor_header_footer","button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_typography_text_transform":"capitalize","button_typography_line_height":{"unit":"px","size":20,"sizes":[]},"button_text_color":"#FFFFFF","button_border_radius":{"unit":"px","top":"5","right":"5","bottom":"5","left":"5","isLinked":true},"button_padding":{"unit":"px","top":"15","right":"30","bottom":"15","left":"30","isLinked":false},"__globals__":{"button_background_color":"globals/colors?id=primary","button_hover_background_color":"globals/colors?id=primary_hover"},"viewport_mobile":"","viewport_tablet":""}', true);
            $options['options'] = json_decode('[]', true);
            $options['babe'] = json_decode('{"date_format":"d/m/Y","booking_obj_post_slug":"to_book","booking_obj_post_name":"Booking Object","booking_obj_post_name_general":"Booking Objects","booking_obj_menu_name":"BA Book Everything","mpoints_active":0,"content_in_tabs":0,"reviews_in_tabs":0,"reviews_comment_template":"","view_only_uploaded_images":0,"results_per_page":12,"posts_per_taxonomy_page":12,"max_guests_select":12,"av_calendar_max_months":12,"results_view":"grid","google_api":"AIzaSyBidqZRRPexq_TJ3hJ8nPqh6EA5fs3ftZ4","google_map_start_lat":"-33.8688","google_map_start_lng":"151.2195","google_map_zoom":7,"google_map_active":1,"google_map_marker":1,"currency":"USD","currency_place":"left","price_thousand_separator":"","price_decimal_separator":".","price_decimals":2,"price_from_label":"From %s","order_availability_confirm":"auto","order_payment_processing_waiting":30,"unitegallery_remove":0,"av_calendar_remove":0,"google_map_remove":0,"services_to_booking_form":1,"message_av_confirmation":"Your order is waiting for the availability confirmation, you will be notified by email when it\'s ready. Thank you!","message_not_available":"Sorry, but your selected items are not available for selected dates/times. Please, search another dates/times or items and create new order.","message_payment_deferred":"Your order is completed and received, and a confirmation email was sent to you. You will pay the full amount later. Thank you!","message_payment_expected":"Your order is confirmed, but not completed. To complete your order, please, click the link below to make a payment.","message_payment_processing":"Your order has been confirmed and your payment is being processed. Thank you!","message_payment_received":"Your order is completed, your payment has been received, and a confirmation email was sent to you. Thank you!","email_admin_new_order_subject":"New order #%s","email_admin_new_order_title":"New order","email_admin_new_order_message":"You have new order. Please, find details below.","email_admin_new_order_av_confirm_subject":"Availability request","email_admin_new_order_av_confirm_title":"New Order is waiting for confirmation","email_admin_new_order_av_confirm_message":"Please, confirm or reject this Order.","email_new_order_av_confirm_subject":"Your order #%s","email_new_order_av_confirm_title":"New Order created","email_new_order_av_confirm_message":"Hello, %s\r\n\r\nThank you for booking! Your Order is waiting for availability confirmation. We will send you a confirmation letter as soon as possible.","email_new_order_subject":"Your order #%s","email_new_order_title":"Your order has been received","email_new_order_message":"Hello, %1$s\r\n\r\nThank you for booking! Your order has been received.","email_new_order_to_pay_subject":"Your order is waiting for payment","email_new_order_to_pay_title":"Your order is waiting for payment","email_new_order_to_pay_message":"Hello, %1$s\r\n\r\nYour order is confirmed, but not completed. To complete your order, click the link below to make a payment. Amount to pay is %2$s.","email_order_rejected_subject":"Selected items are not available","email_order_rejected_title":"Selected items are not available","email_order_rejected_message":"Hello, %s\r\n\r\nSorry, but your selected items are not available for selected dates/times. You could search another dates/times or items and create new Order.","email_new_customer_created_subject":"Your account details","email_new_customer_created_title":"Your account details","email_new_customer_created_message":"Hello, %s\r\n\r\nThank you for booking with us! You could use this login/password to manage your bookings:","email_password_reseted_subject":"Your password has been reset","email_password_reseted_title":"Your password has been reset.","email_password_reseted_message":"Hello, %s\r\n            \r\nYour password has been reset. You could use this new password to manage your account:","email_admin_order_canceled_subject":"Order # %1$s was canceled","email_admin_order_canceled_title":"Order has been canceled","email_admin_order_canceled_message":"The order has been canceled:","email_order_canceled_subject":"Your order was canceled","email_order_canceled_title":"Your order has been canceled","email_order_canceled_message":"Hello, %1$s\r\n\r\nYour order has been canceled:","email_logo":"","email_header_image":"","email_footer_message":"","email_footer_credit":"","email_color_font":"#000000","email_color_background":"#EAECED","email_color_title":"#ff4800","email_color_link":"#039be5","email_color_button":"#ff4800","email_color_button_yes":"#9acd32","email_color_button_no":"#F64020","payment_methods":["cash","paypal"],"use_extended_wp_import":"1","coupons_active":0,"coupons_expire_days":0,"paypal_email":"ducphamtien-facilitator@gmail.com","paypal_sandbox":1,"paypal_live_client_id":"","paypal_live_secret":"","paypal_test_client_id":"ARIGtnaC3DsPrxza4WTWXEcaNcJRhwGaHOVaN3S-vFV-lZuT_8ze_x_fCLioclMRviCwBaxuxiVK52xP","paypal_test_secret":"EJrYReDGzvxxr0U5RVP4BHbFcIGph-F5XfPsqY1Zz4lzoco4ZvHs89GWkY6-ZzOrTPrq6t-YyIqlshpE","stripe_live_public_key":"","stripe_live_secret_key":"","stripe_test_public_key":"","stripe_test_secret_key":"","stripe_country":"","stripe_sandbox":0,"braintree_live_public_key":"","braintree_live_private_key":"","braintree_live_merchant_id":"","braintree_sandbox_public_key":"","braintree_sandbox_private_key":"","braintree_sandbox_merchant_id":"","braintree_sandbox":0,"triply_booking_google_map_style":"light_grey_and_blue","locations_slug":"","types_slug":"","features_slug":"","amenities_slug":"","languages_slug":"","wishlist_active":"1","wishlist_page":"374","wishlist_icon":"triply-icon-heart","wishlist_added":"Tour added to wishlist!"}', true);
            
            return $options;
        }

}

return new Triply_Merlin_Config();
