<?php
/**
 * Cinzal WooCommerce Settings Class
 *
 * @package  aro
 * @since    2.4.3
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Cinzal_WooCommerce_Settings')) :

    /**
     * The Cinzal WooCommerce Settings Class
     */
    class Cinzal_WooCommerce_Settings {
        protected static $sizechart = array();

        public function __construct() {
            if (cinzal_is_elementor_activated()) {
                add_action('add_meta_boxes', [$this, 'create_product_meta_box']);

                add_filter('woocommerce_product_data_tabs', array($this, 'settings_product_tabs'));
                add_filter('woocommerce_product_data_panels', array($this, 'settings_options_product_tab_content'));
                add_action('woocommerce_process_product_meta', array($this, 'save_settings_option_fields'));

                add_filter('woocommerce_product_tabs', [$this, 'add_technical_specs_product_tab'], 20, 1);
            }
        }


        function add_technical_specs_product_tab($tabs) {
            global $product;
            if ($product->get_meta('_specifications') !== '') {

                $tabs['additional_information'] = array(
                    'title'    => esc_html__('Specifications', 'triply'),
                    'priority' => 20,
                    'callback' => [$this, 'display_technical_specs_product_tab_content']

                );
            }

            return $tabs;
        }


        public function display_technical_specs_product_tab_content() {
            global $product;
            ?>
            <div class="wrapper-technical_specs">
                <h2 class="woocommerce-additional-title"><?php echo esc_html__('Specifications', 'triply'); ?></h2>
                <?php echo cinzal_parse_text_editor($product->get_meta('_specifications')) ?>
            </div>

            <?php
        }

        public function content_meta_box_specifications($post) {
            $product = wc_get_product($post->ID);
            $content = $product->get_meta('_specifications');
            ?>
            <div class="product_specifications">

                <?php wp_editor(wp_specialchars_decode($content, ENT_QUOTES), '_specifications', ['textarea_rows' => 10]); ?>
            </div>

            <?php
        }

        public function create_product_meta_box() {
            add_meta_box(
                'custom_product_meta_box',
                esc_html__('Specifications', 'triply'),
                [$this, 'content_meta_box_specifications'],
                'product',
                'normal',
                'default'
            );
        }

        public function settings_product_tabs($tabs) {

            $tabs['sizechart'] = array(
                'label'    => esc_html__('Cinzal settings', 'triply'),
                'target'   => 'cinzal_options',
                'class'    => array(),
                'priority' => 80,
            );

            return $tabs;

        }

        public function settings_options_product_tab_content() {

            global $post;

            ?>
            <div id='cinzal_options' class='panel woocommerce_options_panel'>
                <div class='options_group'>
                    
                    <?php
                    woocommerce_wp_text_input(array(
                        'id'    => '_video_select',
                        'label' => esc_html__('Url Video', 'triply'),
                    ));

                    woocommerce_wp_textarea_input(array(
                        'id'    => '_extra_description',
                        'rows'  => 5,
                        'label' => esc_html__('Single extra description', 'triply'),
                    ));

                    ?>
                </div>

            </div>
            <?php
        }

        public function save_settings_option_fields($post_id) {
            if (isset($_POST['_ask_a_question'])) {
                update_post_meta($post_id, '_ask_a_question', esc_attr($_POST['_ask_a_question']));
            }
            if (isset($_POST['_video_select'])) {
                update_post_meta($post_id, '_video_select', esc_attr($_POST['_video_select']));
            }
            if (isset($_POST['_extra_description'])) {
                update_post_meta($post_id, '_extra_description', wp_kses_post($_POST['_extra_description']));
            }
            if (isset($_POST['_specifications'])) {
                update_post_meta($post_id, '_specifications', $_POST['_specifications']);
            }
        }


    }

    return new Cinzal_WooCommerce_Settings();

endif;
