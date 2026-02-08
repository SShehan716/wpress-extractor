<?php

// tabs
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

add_action('elementor/element/tabs/section_tabs_style/after_section_end', function ($element, $args) {
    $element->update_control(
        'background_color',
        [
            'selectors' => [
                '{{WRAPPER}} .elementor-tabs-wrapper' => 'background-color: {{VALUE}};',
            ],
        ]
    );
    $element->update_control(
        'heading_title',
        [
            'type' => Controls_Manager::HIDDEN,
        ]
    );
    $element->update_control(
        'tab_color',
        [
            'type'   => Controls_Manager::HIDDEN,
            'global' => [
                'default' => ''
            ]
        ]
    );
    $element->update_control(
        'tab_active_color',
        [
            'type'   => Controls_Manager::HIDDEN,
            'global' => [
                'default' => ''
            ]
        ]
    );
    $element->update_control(
        'content_color',
        [
            'global' => [
                'default' => ''
            ]
        ]
    );

}, 10, 2);

add_action('elementor/element/tabs/section_tabs_style/before_section_end', function ($element, $args) {
    $element->add_responsive_control(
        'content_padding',
        [
            'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            'label'     => esc_html__('Padding Content', 'triply'),
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->add_control(
        'heading_title_update',
        [
            'label'     => esc_html__('Title', 'triply'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]
    );

    $element->add_responsive_control(
        'align',
        [
            'label' => esc_html__( 'Alignment', 'triply' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left'    => [
                    'title' => esc_html__( 'Left', 'triply' ),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__( 'Center', 'triply' ),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => esc_html__( 'Right', 'triply' ),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'condition' => [
                'tabs_style_theme' => 'yes',
            ],
            'prefix_class' => 'tabs-style-triply-align%s-',
            'default' => '',
        ]
    );

    $element->add_control(
        'background_color_title',
        [
            'label'     => esc_html__('Background Color', 'triply'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title' => 'background-color: {{VALUE}};',
            ],
        ]
    );
    $element->add_control(
        'background_color_title_active',
        [
            'label'     => esc_html__('Background Color Active', 'triply'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title.elementor-active' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $element->add_control(
        'title_tab_color',
        [
            'label'     => esc_html__('Title Color', 'triply'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title a' => 'color: {{VALUE}};',
            ],
        ]
    );

    $element->add_control(
        'title_tab_active_color',
        [
            'label'     => esc_html__('Title Active Color', 'triply'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title.elementor-active a' => 'color: {{VALUE}};',
            ],
        ]
    );

    $element->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'tab_title_shadow',
            'selector' => '{{WRAPPER}} .elementor-tab-title',
            'condition' => [
                'tabs_style_theme' => 'yes',
            ],
        ]
    );

    $element->add_control(
        'title_tab_radius',
        [
            'label' => esc_html__('Border Radius', 'triply'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->add_responsive_control(
        'title_padding',
        [
            'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            'label'     => esc_html__('Padding Title', 'triply'),
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->add_responsive_control(
        'title_margin',
        [
            'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            'label'     => esc_html__('Margin Title', 'triply'),
            'selectors' => [
                '{{WRAPPER}} .elementor-tab-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'tabs_style_theme' => 'yes',
            ],
        ]
    );

}, 10, 2);

add_action('elementor/element/tabs/section_tabs/before_section_end', function ($element, $args) {
    $element->add_control(
        'tabs_style_theme',
        [
            'label'        => esc_html__('Theme Style', 'triply'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => '',
            'prefix_class' => 'tabs-style-triply-',
        ]
    );

}, 10, 2);
