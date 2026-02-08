<?php
/**
 * Add widget all-items to Elementor
 *
 * @since   1.3.13
 */

class Triply_BABE_Elementor_Itemslideshow_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-item-slideshow';
    }

    public function is_reload_preview_required() {
        return true;
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Item slideshow', 'triply');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-slides';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['slideshow'];
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
        return ['photoswipe', 'photoswipe-skin', 'magnific-popup'];
    }

    public function get_script_depends() {
        return ['triply-ba-slideshow.js', 'slick', 'photoswipe', 'photoswipe-ui', 'magnific-popup'];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'triply'),
                'tab'   => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slideshow_style',
            [
                'label'   => esc_html__('Choose Style', 'triply'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__('Style 1', 'triply'),
                    'style-2' => esc_html__('Style 2', 'triply'),
                    'style-3' => esc_html__('Style 3', 'triply'),
                ],
            ]
        );

        $this->add_control(
            'enable_video',
            [
                'label'   => esc_html__('Enable Video', 'triply'),
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );



        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'slider_image',
                // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'slideshow_style' => 'style-1',
                ]
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label'     => esc_html__('Height Image', 'triply'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1000,
                        'min'  => 600,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slideshow-style-1 .slick-slide img' => 'max-height: {{SIZE}}px;',
                ],
                'condition' => [
                    'slideshow_style' => 'style-1',
                ]
            ]
        );

        $this->end_controls_section();

        $this->add_control_style_wrapper();

        $this->start_controls_section(
            'section_style_tab_title',
            [
                'label' => esc_html__('Button Popup', 'triply'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'enable_video',
                            'operator' => '==',
                            'value'    => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'tab_position',
            [
                'label'       => esc_html__('Position', 'triply'),
                'type'        => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'top'    => [
                        'title' => esc_html__('Top', 'triply'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'triply'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'   => 'top',
            ]
        );

        $this->add_responsive_control(
            'margin_tab_wrapper',
            [
                'label'      => esc_html__('Margin', 'triply'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .booking_tab_gallery_inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_title_align',
            [
                'label'     => esc_html__('Alignment', 'triply'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start'   => [
                        'title' => esc_html__('Left', 'triply'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'triply'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__('Right', 'triply'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .tablist_gallery' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_title_typography',
                'selector' => '{{WRAPPER}} .tablist_gallery li a',
            ]
        );

        $this->start_controls_tabs('tabs_button_color');

        $this->start_controls_tab(
            'tab_title_color_normal',
            [
                'label' => esc_html__('Normal', 'triply'),
            ]
        );

        $this->add_control(
            'title_color_normal',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tablist_gallery li:not(.active) a:not(:hover)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_normal',
            [
                'label'     => esc_html__('Background Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tablist_gallery li:not(.active) a:not(:hover)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_color_active',
            [
                'label' => esc_html__('Active', 'triply'),
            ]
        );

        $this->add_control(
            'title_color_active',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tablist_gallery li.active a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tablist_gallery li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_active',
            [
                'label'     => esc_html__('Background Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tablist_gallery li.active a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .tablist_gallery li a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_title_padding',
            [
                'label'      => esc_html__('Padding', 'triply'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .tablist_gallery li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_title_spacing',
            [
                'label'      => esc_html__('Spacing', 'triply'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '.tablist_gallery'         => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .tablist_gallery li' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function add_control_style_wrapper($condition = array()) {



        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__('Wrapper', 'triply'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => esc_html__('Padding', 'triply'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-widget-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label'      => esc_html__('Margin', 'triply'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-widget-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_bg_color',
            [
                'label'     => esc_html__('Background Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => 'wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .elementor-widget-inner',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'wrapper_border_hover',
            [
                'label'     => esc_html__('Border Hover Color', 'triply'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-inner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_radius',
            [
                'label'      => esc_html__('Border Radius', 'triply'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-widget-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-widget-inner',
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
//        global $post;
//        $babe_post = BABE_Post_types::get_post($post->ID);
//        $gallery_items = isset($babe_post['images']) ? $babe_post['images'] : array();

        $class_overlay = '';
//        if($settings['enable_overlay'] == 'yes') $class_overlay = 'has_overlay';
        ?>
        <div class="elementor-widget-inner <?php echo esc_attr($class_overlay); ?>">
            <?php
            if( $settings['tab_position'] == 'top' ){
                $this->get_tab_title_html($settings);
            }
            include get_theme_file_path('template-parts/booking/single/slideshow/' . $settings['slideshow_style'].'.php');

            if( $settings['tab_position'] == 'bottom' ){
                $this->get_tab_title_html($settings);
            }
            ?>
        </div>
        <?php
    }

    protected function get_tab_title_html($settings) {
        if( $settings['enable_video'] !== 'yes' ){
            return;
        }
        ?>
            <div class="booking_tab_gallery col-full">
                <div class="booking_tab_gallery_inner">
                    <ul class="tablist_gallery">
                        <li>
                            <a class="tab-gallery js-gallery-popup" data-action="gallery" href="#">
                                <i class="triply-icon-camera-alt"></i>
                                <span><?php esc_html_e('Gallery', 'triply') ?></span>
                            </a>
                        </li>
                        <?php
                        if( $settings['enable_video'] == 'yes' ){
                            if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
                                $post_id = triply_ba_get_default_single_id();
                            } else {
                                $post_id = get_the_ID();
                            }
                            $babe_post = get_post( $post_id );

                            if ( is_single() && $babe_post->post_type == BABE_Post_types::$booking_obj_post_type) {
                                $babe_post = BABE_Post_types::get_post($babe_post->ID);

                                $videolink  = isset($babe_post['triply_video_link']) ? $babe_post['triply_video_link'] : false;

                                if($videolink){
                                    ?>
                                    <li class="js-tab-popup">
                                        <a class="tab-video" data-action="video" href="<?php echo esc_url($videolink); ?>" data-effect="mfp-zoom-in">
                                            <i class="triply-icon-video"></i>
                                            <span><?php esc_html_e('Video', 'triply') ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }

                            }

                        }
                        ?>
                    </ul>
                </div>
            </div>
        <?php
    }

}
