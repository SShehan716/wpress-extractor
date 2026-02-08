<?php

// Button
use Elementor\Controls_Manager;

add_action('elementor/element/button/section_style/after_section_end', function ($element, $args) {

    $element->update_control(
        'background_color',
        [
            'global' => [
                'default' => '',
            ],
        ]
    );
}, 10, 2);

add_action('elementor/element/button/section_style/before_section_end', function ($element, $args) {
    $element->add_control(
        'button_icon_color',
        [
            'label'     => esc_html__('Icon Color', 'triply'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
            'condition' => [
                'selected_icon[value]!' => '',
            ],
        ]
    );
}, 10, 2);
