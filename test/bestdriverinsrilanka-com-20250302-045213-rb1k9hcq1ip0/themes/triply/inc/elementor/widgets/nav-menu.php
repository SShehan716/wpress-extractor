<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class OSF_Elementor_Nav_Menu extends Elementor\Widget_Base{

    public function get_name()
    {
        return 'triply-nav-menu';
    }

    public function get_title()
    {
        return esc_html__('Triply Nav Menu', 'triply');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    public function get_categories()
    {
        return ['opal-addons'];
    }

    protected function register_controls()
    {
        $this -> start_controls_section(
            'nav-menu_content',
            [
                'label' => esc_html__('Menu','triply'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this -> add_control(
             'menu-style-content',
            [
                'label' =>  esc_html__('layout','triply'),
                'type' => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => [
                    'primary' => esc_html__( 'Primary Menu', 'triply' ),
                    'handheld' => esc_html__( 'Handheld Menu', 'triply' ),
                ],
            ]
        );

        $this -> end_controls_section();

        $this -> start_controls_section(
            'nav-menu_style',
            [
                'label' => esc_html__('Primary Menu','triply'),
                'condition' => [
                    'menu-style-content' => 'primary',
                ],
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this -> add_responsive_control(
            'nav_menu_aligrment',
            [
                'label'       => esc_html__( 'Alignment', 'triply' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'triply' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'triply' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'triply' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .main-navigation' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_menu_typography',
                'selector' => '{{WRAPPER}} .main-navigation ul.menu li.menu-item a',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography Sub Menu', 'triply'),
                'name'     => 'nav_sub_menu_typography',
                'selector' => '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item a',
            ]
        );

        $this->start_controls_tabs( 'tabs_nav_menu_style' );

        $this->start_controls_tab(
            'tab_nav_menu_normal',
            [
                'label' =>  esc_html__( 'Normal', 'triply' ),
            ]
        );
        $this->add_control(
            'menu_title_color',
            [
                'label'     => esc_html__( 'Color Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu >li.menu-item >a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_sub_title_color',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sub_menu_color',
            [
                'label'     => esc_html__( 'Background Dropdown', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_menu_hover',
            [
                'label' =>  esc_html__( 'Hover', 'triply' ),
            ]
        );
        $this->add_control(
            'menu_title_color_hover',
            [
                'label'     => esc_html__( 'Color Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu >li.menu-item >a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_sub_title_color_hover',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_color_hover',
            [
                'label'     => esc_html__( 'Background Item', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_menu_action',
            [
                'label' =>  esc_html__( 'Active', 'triply' ),
            ]
        );
        $this->add_control(
            'menu_title_color_action',
            [
                'label'     => esc_html__( 'Color Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-item > a:not(:hover)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-parent > a:not(:hover)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-ancestor > a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_sub_title_color_action',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item.current-menu-item > a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_item_color_action',
            [
                'label'     => esc_html__( 'Background Item', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item.current-menu-item > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this -> start_controls_section(
            'icon-menu_style',
            [
                'label' => esc_html__('Handheld Menu','triply'),
                'condition' => [
                    'menu-style-content' => 'handheld',
                ],
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'canvas_menu_typography',
                'selector' => '{{WRAPPER}} .mobile-navigation ul li a',
            ]
        );

        $this->add_control(
            'color_menu_canvas',
            [
                'label'     => esc_html__( 'Color ', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mobile-navigation ul li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mobile-navigation .dropdown-toggle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .triply-canvas-nav .triply-social ul li a:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mobile-nav-close' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color-menu-canvas-active',
            [
                'label'     => esc_html__( 'Color Active ', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mobile-navigation ul.menu li.current-menu-ancestor > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mobile-navigation ul.menu li.current-menu-parent > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mobile-navigation ul.menu li.current-menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color-menu-canvas-border',
            [
                'label'     => esc_html__( 'Color Border ', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mobile-navigation ul li' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .triply-canvas-nav .spacey-social' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background-menu-canvas',
            [
                'label'     => esc_html__( 'Background ', 'triply' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .triply-canvas-nav' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-nav-menu-wrapper' );
        ?>
        <div <?php echo triply_elementor_get_render_attribute_string('wrapper', $this);?>>
            <?php
                if($settings['menu-style-content']=='primary'){
                    triply_primary_navigation();
                }
                elseif ($settings['menu-style-content']=='handheld'){
                    triply_canvas_nav();
                }
            ?>
        </div>
        <?php
    }

}
$widgets_manager->register(new OSF_Elementor_Nav_Menu());
