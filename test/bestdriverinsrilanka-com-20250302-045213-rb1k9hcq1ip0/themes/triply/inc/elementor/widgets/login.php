<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class OSF_Elementor_Login extends Elementor\Widget_Base {

    public function get_name() {
        return 'triply-login';
    }

    public function get_title() {
        return esc_html__('Triply Login', 'triply');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('triply-addons');
    }

    public function get_style_depends() {
        return ['magnific-popup'];
    }

    public function get_script_depends() {
        return ['magnific-popup', 'triply-elementor-login'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'login-layout',
            [
                'label' => esc_html__('Layout', 'triply'),
                'tab'   => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $this->add_control('layout_style',
            [
                'label'   => esc_html__('Style', 'triply'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('Style Icon', 'triply'),
                    '2' => esc_html__('Style Text 1', 'triply'),
                    '3' => esc_html__('Style Text 2', 'triply'),
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'login_title',
            [
                'label'     => esc_html__('Rigister Title', 'triply'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('Log In', 'triply'),
                'condition' => [
                    'layout_style!' => '1'
                ]
            ]
        );

        $this->add_control(
            'separator',
            [
                'label'     => esc_html__('Separator', 'triply'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('/', 'triply'),
                'condition' => [
                    'layout_style' => '2'
                ]
            ]
        );

        $this->add_control(
            'register_title',
            [
                'label'     => esc_html__('Rigister Title', 'triply'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('Register', 'triply'),
                'condition' => [
                    'layout_style!' => '1'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'login-icon-style',
            [
                'label'     => esc_html__('Icon', 'triply'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style' => '1'
                ]
            ]
        );

        $this->add_responsive_control(
            'size',
            [
                'label'     => esc_html__('Size', 'triply'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .login-action > div > a.login-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button-icon i:not(:hover)'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button-icon:not(:hover):before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button-icon i:hover'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button-icon:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'login-button-style',
            [
                'label'     => esc_html__('Login', 'triply'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style!' => '1'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'login_typography',
                'selector' => '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button',
            ]
        );

        $this->add_control(
            'login_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'login_border',
                'selector' => '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button',
            ]
        );


        $this->add_control(
            'login_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control('login_padding',
            [
                'label'      => esc_html__('Padding', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('login_margin',
            [
                'label'      => esc_html__('Margin', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.login-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'register-button-style',
            [
                'label'     => esc_html__('Register', 'triply'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style!' => '1'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'register_typography',
                'selector' => '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button',
            ]
        );

        $this->add_control(
            'register_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'register_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'register_border',
                'selector' => '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button',
            ]
        );


        $this->add_control(
            'register_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control('register_padding',
            [
                'label'      => esc_html__('Padding', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('register_margin',
            [
                'label'      => esc_html__('Margin', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action > div a.register-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        //Separator

        $this->start_controls_section(
            'separator-button-style',
            [
                'label'     => esc_html__('Separator', 'triply'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style' => '2'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'separator_typography',
                'selector' => '{{WRAPPER}} .elementor-login-wrapper .login-action .separator',
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action .separator' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control('separator_padding',
            [
                'label'      => esc_html__('Padding', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action .elementor-login-wrapper .login-action .separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('separator_margin',
            [
                'label'      => esc_html__('Margin', 'triply'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-login-wrapper .login-action .separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-login-wrapper');
        $this->add_render_attribute('wrapper', 'class', 'elementor-login-style-' . $settings['layout_style']);

        $account_link = wp_login_url();

        if (triply_is_ba_booking_activated()) {
            $account_page = intval(BABE_Settings::$settings['my_account_page']);
            $account_link = get_the_permalink($account_page);
        }
        ?>
        <div <?php echo triply_elementor_get_render_attribute_string('wrapper', $this); ?>>
            <div class="login-action">
                <div class="site-header-account">
                    <?php if (!is_user_logged_in() || \Elementor\Plugin::instance()->editor->is_edit_mode()) {
                        if ($settings['layout_style'] == '2') {
                            ?>
                            <a class="login-button group-button popup js-btn-register-popup" href="#triply-login-form"><?php echo esc_html($settings['login_title']); ?></a>
                            <?php echo '<span class="separator">' . esc_html($settings['separator']) . '</span>'; ?>
                            <a class="register-button group-button popup js-btn-register-popup" href="#triply-register-form"><?php echo esc_html($settings['register_title']); ?></a>
                            <?php
                        } elseif ($settings['layout_style'] == '3') {
                            ?>
                            <a class="login-button group-button popup js-btn-register-popup" href="#triply-login-form"><?php echo esc_html($settings['login_title']); ?></a>
                            <a class="register-button group-button popup js-btn-register-popup" href="#triply-register-form"><?php echo esc_html($settings['register_title']); ?> <i class="triply-icon-long-arrow-right"></i></a>
                            <?php
                        } else {
                            ?>
                            <a class="login-button-icon group-button popup js-btn-register-popup" href="#triply-login-form"><i class="triply-icon-login"></i></a>
                            <?php
                        }
                    } else {
                        ?>
                        <a class="group-button login" href="<?php echo esc_url($account_link); ?>"> <?php echo get_avatar(get_current_user_id(), 30); ?> </a>
                        <div class="account-dropdown"></div>
                        <?php
                    } ?>
                </div>
            </div>
        </div>
        <?php
    }

}

$widgets_manager->register(new OSF_Elementor_Login());
