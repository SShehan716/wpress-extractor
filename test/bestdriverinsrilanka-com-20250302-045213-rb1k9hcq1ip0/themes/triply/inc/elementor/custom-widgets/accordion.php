<?php

//Accordion
use Elementor\Controls_Manager;

add_action( 'elementor/element/accordion/section_title_style/before_section_end', function ($element, $args ) {

    $element->add_control(
        'style_theme',
        [
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__( 'Style Theme', 'triply' ),
            'prefix_class'	=> 'style-theme-'
        ]
    );

    $element->add_responsive_control(
        'title_margin',
        [
            'label' => esc_html__( 'Padding', 'triply' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );


},10,2);

add_action( 'elementor/element/accordion/section_toggle_style_title/before_section_end', function ( $element, $args ) {

    $element->update_control(
        'title_color',
        [
            'global' => [
                'default' => '',
            ],
        ]
    );

    $element->update_control(
        'tab_active_color',
        [
            'global' => [
                'default' => '',
            ],
        ]
    );

},10,2);