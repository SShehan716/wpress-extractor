<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Triply_Elementor')) :

    /**
     * The Triply Elementor Integration class
     */
    class Triply_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/init', array($this, 'add_category'));
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);
            add_filter('wp_enqueue_scripts', [$this, 'add_animations_scroll_style']);

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/register', [$this, 'add_icons']);

            // Add Breakpoints
            add_action('wp_enqueue_scripts', 'triply_elementor_breakpoints', 9999);

            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);

            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('triply-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts["Thea Amelia"]     = 'system';
            return $fonts;
        }

        public function add_js() {
            global $triply_version;
            wp_enqueue_script('triply-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], $triply_version);
        }

        public function add_style_editor() {
            global $triply_version;
            wp_enqueue_style('triply-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], $triply_version);
        }

        public function add_scripts() {
            global $triply_version;
            wp_enqueue_style('triply-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', $triply_version);
            wp_style_add_data('triply-elementor', 'rtl', 'replace');

            // Add Scripts
            wp_register_script('tweenmax', get_theme_file_uri('/assets/js/vendor/TweenMax.min.js'), array('jquery'), '1.11.1');
            wp_register_script('parallaxmouse', get_theme_file_uri('/assets/js/vendor/jquery-parallax.js'), array('jquery'), $triply_version);
            wp_register_script('triply-elementor-item-related', get_theme_file_uri('/assets/js/booking/item-related.js'), array('jquery', 'elementor-frontend'), $triply_version, true);
            wp_register_script('triply-elementor-ba-all-items', get_theme_file_uri('/assets/js/booking/ba-all-items.js'), array('jquery', 'elementor-frontend'), $triply_version, true);
        }


        public function register_auto_scripts_frontend() {
            global $triply_version;
            wp_register_script('triply-elementor-login', get_theme_file_uri('/assets/js/elementor/login.js'), array('jquery','elementor-frontend'), $triply_version, true);
            wp_register_script('triply-elementor-posts-grid', get_theme_file_uri('/assets/js/elementor/posts-grid.js'), array('jquery','elementor-frontend'), $triply_version, true);
            wp_register_script('triply-elementor-testimonial', get_theme_file_uri('/assets/js/elementor/testimonial.js'), array('jquery','elementor-frontend'), $triply_version, true);
           
        }

        public function add_category() {
            Elementor\Plugin::instance()->elements_manager->add_category(
                'triply-addons',
                array(
                    'title' => esc_html__('Triply Addons', 'triply'),
                    'icon'  => 'fa fa-plug',
                ),
                1);
        }
        public function add_animations_scroll_style() {
            global $freshio_version;
            $animations =[
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];
            foreach ($animations as $animation => $name) {
                wp_deregister_style('e-animation-' . $animation);
                wp_register_style('e-animation-' . $animation, get_theme_file_uri('/assets/css/animations/' . $animation . '.css'), [], $freshio_version);
            }
        }
        public function add_animations_scroll($animations) {
            $animations['Triply Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }


		public function add_icons( $manager ) {
            $new_icons = json_decode( '{"triply-icon-activity":"activity","triply-icon-angle-double-left":"angle-double-left","triply-icon-angle-double-right":"angle-double-right","triply-icon-angle-down":"angle-down","triply-icon-angle-up":"angle-up","triply-icon-beaches":"beaches","triply-icon-best-price":"best-price","triply-icon-best-tours":"best-tours","triply-icon-best-travel":"best-travel","triply-icon-calendar-days":"calendar-days","triply-icon-cancellation":"cancellation","triply-icon-citytours":"citytours","triply-icon-clock-time":"clock-time","triply-icon-cruise":"cruise","triply-icon-destination":"destination","triply-icon-envelope-open":"envelope-open","triply-icon-fast-booking":"fast-booking","triply-icon-happy":"happy","triply-icon-hiking":"hiking","triply-icon-historical":"historical","triply-icon-login":"login","triply-icon-map-marker-alt":"map-marker-alt","triply-icon-map-marker-rounded":"map-marker-rounded","triply-icon-map-marker":"map-marker","triply-icon-menu":"menu","triply-icon-museum":"museum","triply-icon-passionate-travel":"passionate-travel","triply-icon-pencil":"pencil","triply-icon-phone-1":"phone-1","triply-icon-phone-3":"phone-3","triply-icon-phone-rotary":"phone-rotary","triply-icon-places":"places","triply-icon-price-1":"price-1","triply-icon-quote-2":"quote-2","triply-icon-safety":"safety","triply-icon-search":"search","triply-icon-star-bold":"star-bold","triply-icon-tag-left":"tag-left","triply-icon-tick-circle":"tick-circle","triply-icon-ticket-alt":"ticket-alt","triply-icon-tours":"tours","triply-icon-types":"types","triply-icon-user-2":"user-2","triply-icon-utensils-alt":"utensils-alt","triply-icon-wine-glass-alt":"wine-glass-alt","triply-icon-angle-left":"angle-left","triply-icon-angle-right":"angle-right","triply-icon-arrow-circle-down":"arrow-circle-down","triply-icon-arrow-circle-left":"arrow-circle-left","triply-icon-arrow-circle-right":"arrow-circle-right","triply-icon-arrow-circle-up":"arrow-circle-up","triply-icon-arrow-left":"arrow-left","triply-icon-arrow-right":"arrow-right","triply-icon-arrows-h":"arrows-h","triply-icon-bars":"bars","triply-icon-calendar-alt":"calendar-alt","triply-icon-calendar":"calendar","triply-icon-camera-alt":"camera-alt","triply-icon-caret-down":"caret-down","triply-icon-caret-left":"caret-left","triply-icon-caret-right":"caret-right","triply-icon-caret-up":"caret-up","triply-icon-cart-empty":"cart-empty","triply-icon-check-circle":"check-circle","triply-icon-check-square":"check-square","triply-icon-check":"check","triply-icon-chevron-circle-left":"chevron-circle-left","triply-icon-chevron-circle-right":"chevron-circle-right","triply-icon-chevron-down":"chevron-down","triply-icon-chevron-left":"chevron-left","triply-icon-chevron-right":"chevron-right","triply-icon-chevron-up":"chevron-up","triply-icon-circle":"circle","triply-icon-clock":"clock","triply-icon-cloud-download-alt":"cloud-download-alt","triply-icon-comment":"comment","triply-icon-comments-alt":"comments-alt","triply-icon-comments":"comments","triply-icon-contact":"contact","triply-icon-credit-card":"credit-card","triply-icon-dot-circle":"dot-circle","triply-icon-edit":"edit","triply-icon-envelope":"envelope","triply-icon-expand-alt":"expand-alt","triply-icon-external-link-alt":"external-link-alt","triply-icon-eye":"eye","triply-icon-fan":"fan","triply-icon-file-alt":"file-alt","triply-icon-file-archive":"file-archive","triply-icon-filter":"filter","triply-icon-folder-open":"folder-open","triply-icon-folder":"folder","triply-icon-free_ship":"free_ship","triply-icon-frown":"frown","triply-icon-gift":"gift","triply-icon-grip-horizontal":"grip-horizontal","triply-icon-heart-fill":"heart-fill","triply-icon-heart":"heart","triply-icon-history":"history","triply-icon-home":"home","triply-icon-info-circle":"info-circle","triply-icon-instagram":"instagram","triply-icon-level-up-alt":"level-up-alt","triply-icon-location-circle":"location-circle","triply-icon-long-arrow-alt-down":"long-arrow-alt-down","triply-icon-long-arrow-alt-left":"long-arrow-alt-left","triply-icon-long-arrow-alt-right":"long-arrow-alt-right","triply-icon-long-arrow-alt-up":"long-arrow-alt-up","triply-icon-long-arrow-left":"long-arrow-left","triply-icon-long-arrow-right":"long-arrow-right","triply-icon-map-marker-check":"map-marker-check","triply-icon-meh":"meh","triply-icon-minus-circle":"minus-circle","triply-icon-mobile-android-alt":"mobile-android-alt","triply-icon-money-bill":"money-bill","triply-icon-pencil-alt":"pencil-alt","triply-icon-play-2":"play-2","triply-icon-plus-circle":"plus-circle","triply-icon-plus":"plus","triply-icon-quote":"quote","triply-icon-random":"random","triply-icon-reply-all":"reply-all","triply-icon-reply":"reply","triply-icon-search-plus":"search-plus","triply-icon-shield-check":"shield-check","triply-icon-shopping-basket":"shopping-basket","triply-icon-shopping-cart":"shopping-cart","triply-icon-sign-in-alt":"sign-in-alt","triply-icon-sign-out-alt":"sign-out-alt","triply-icon-smile":"smile","triply-icon-spinner":"spinner","triply-icon-square":"square","triply-icon-star":"star","triply-icon-sync":"sync","triply-icon-tachometer-alt":"tachometer-alt","triply-icon-tags":"tags","triply-icon-th-large":"th-large","triply-icon-th-list":"th-list","triply-icon-thumbtack":"thumbtack","triply-icon-times-circle":"times-circle","triply-icon-times":"times","triply-icon-trophy-alt":"trophy-alt","triply-icon-truck":"truck","triply-icon-unlock":"unlock","triply-icon-user-headset":"user-headset","triply-icon-user-shield":"user-shield","triply-icon-user":"user","triply-icon-users":"users","triply-icon-video":"video","triply-icon-adobe":"adobe","triply-icon-amazon":"amazon","triply-icon-android":"android","triply-icon-angular":"angular","triply-icon-apper":"apper","triply-icon-apple":"apple","triply-icon-atlassian":"atlassian","triply-icon-behance":"behance","triply-icon-bitbucket":"bitbucket","triply-icon-bitcoin":"bitcoin","triply-icon-bity":"bity","triply-icon-bluetooth":"bluetooth","triply-icon-btc":"btc","triply-icon-centos":"centos","triply-icon-chrome":"chrome","triply-icon-codepen":"codepen","triply-icon-cpanel":"cpanel","triply-icon-discord":"discord","triply-icon-dochub":"dochub","triply-icon-docker":"docker","triply-icon-dribbble":"dribbble","triply-icon-dropbox":"dropbox","triply-icon-drupal":"drupal","triply-icon-ebay":"ebay","triply-icon-facebook":"facebook","triply-icon-figma":"figma","triply-icon-firefox":"firefox","triply-icon-google-plus":"google-plus","triply-icon-google":"google","triply-icon-grunt":"grunt","triply-icon-gulp":"gulp","triply-icon-html5":"html5","triply-icon-jenkins":"jenkins","triply-icon-joomla":"joomla","triply-icon-link-brand":"link-brand","triply-icon-linkedin":"linkedin","triply-icon-mailchimp":"mailchimp","triply-icon-opencart":"opencart","triply-icon-paypal":"paypal","triply-icon-pinterest-p":"pinterest-p","triply-icon-reddit":"reddit","triply-icon-skype":"skype","triply-icon-slack":"slack","triply-icon-snapchat":"snapchat","triply-icon-spotify":"spotify","triply-icon-trello":"trello","triply-icon-twitter":"twitter","triply-icon-vimeo":"vimeo","triply-icon-whatsapp":"whatsapp","triply-icon-wordpress":"wordpress","triply-icon-yoast":"yoast","triply-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native( $tabs ) {
            global $triply_version;
            $tabs['opal-custom'] = [
                'name'          => 'triply-icon',
                'label'         => esc_html__( 'Triply Icon', 'triply' ),
                'prefix'        => 'triply-icon-',
                'displayPrefix' => 'triply-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => $triply_version,
                'fetchJson'     => get_theme_file_uri( '/inc/elementor/icons.json' ),
                'native'        => true,
            ];

            return $tabs;
        }
}
endif;

return new Triply_Elementor();
