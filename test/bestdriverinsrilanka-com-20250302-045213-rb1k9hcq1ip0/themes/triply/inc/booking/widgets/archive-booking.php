<?php

use Elementor\Controls_Manager;

/**
 * Add widget all-items to Elementor
 *
 * @since   1.3.13
 */
class OSF_Elementor_BA_Archive_Booking extends \Elementor\Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-archive-items';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('BA Archive', 'triply');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-archive-posts';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['Booking', 'Archive'];
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['book-everything-elements'];
    }

    public function get_style_depends() {
        return ['magnific-popup'];
    }

    public function get_script_depends() {
        return ['magnific-popup', 'triply-ba-items'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Settings', 'triply'),
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => esc_html__('Style', 'triply'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => esc_html__('Grid', 'triply'),
                    4 => esc_html__('List', 'triply'),
                ],
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'           => esc_html__('Columns', 'triply'),
                'type'            => \Elementor\Controls_Manager::SELECT,
                'desktop_default' => 2,
                'tablet_default'  => 2,
                'mobile_default'  => 1,
                'options'         => [1 => 1, 2 => 2, 3 => 3, 4 => 4],
                'condition'       => [
                    'style' => '1'
                ]
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {
        if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
            global $wp_query;
            $wp_query = new WP_Query(array(
                'post_type' => BABE_Post_types::$booking_obj_post_type
            ));

            include get_theme_file_path('template-parts/booking/archive.php');

        }else{
            $object = get_queried_object();
            if(!empty( $object)) {
                include get_theme_file_path('template-parts/booking/archive.php');
            }
        }

    }

}
