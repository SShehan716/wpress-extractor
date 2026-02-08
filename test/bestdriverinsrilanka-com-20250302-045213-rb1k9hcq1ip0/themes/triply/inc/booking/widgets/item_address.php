<?php
/**
 * Add widget all-items to Elementor
 *
 * @since   1.3.13
 */
use Elementor\Controls_Manager;

class Triply_BABE_Elementor_Itemaddress_Widget extends \Elementor\Widget_Base {

    /**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'babe-item-address';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Item Text Address', 'triply' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

    /**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'address' ];
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'book-everything-elements' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__('Style', 'triply'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .triply-single-address' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_size',
            [
                'label'     => esc_html__('Font Size', 'triply'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .triply-single-address' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();

        echo '<div class="elementor-widget-inner">';
        get_template_part('template-parts/booking/single/address');
        echo '</div>';

	}

}
/////////////////////
