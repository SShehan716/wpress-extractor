<?php

use Elementor\Controls_Manager;

class Triply_BABE_Elementor_Taxonomies_Widget extends \Elementor\Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-taxonomies';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Ba Taxonomies', 'triply');
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
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['book-everything-elements'];
    }

    public function get_script_depends() {
        return ['triply-ba-babe-taxonomies.js', 'slick'];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     */
    protected function register_controls() {

        // Get all terms of categories

        $this->start_controls_section(
            'babe_taxonomies',
            array(
                'label' => esc_html__('Content', 'triply'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'taxonomy_slug',
            array(
                'label'   => esc_html__('Ba Taxonomies', 'triply'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'multiple'    => true,
                'options' => $this->get_taxonomies_arr(),
                'label_block' => true,
            )
        );

        $this->add_control(
            'enable_all_taxonomy',
            [
                'label' => esc_html__('Get all taxonomy', 'triply'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->render_setting_taxonomy();


        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__('Order by', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dots',
                'options'   => [
                    'id'   => esc_html__('ID', 'triply'),
                    'count' => esc_html__('Count', 'triply'),
                    'name'   => esc_html__('Name', 'triply'),
                ],
                'default'   => 'name',
                'condition' => [
                    'enable_all_taxonomy!' => ''
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'     => esc_html__('Order', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'asc',
                'options'   => [
                    'asc'   => esc_html__('ASC', 'triply'),
                    'desc' => esc_html__('DESC', 'triply'),
                ],
                'condition' => [
                    'enable_all_taxonomy!' => ''
                ],
            ]
        );


        $this->add_control(
            'hide_empty',
            [
                'label' => esc_html__('Hide Empty', 'triply'),
                'type'  => Controls_Manager::SWITCHER,
                'condition' => [
                    'enable_all_taxonomy!' => ''
                ],
            ]
        );

        $this->add_control(
            'pad_counts',
            [
                'label' => esc_html__('Pad Count', 'triply'),
                'type'  => Controls_Manager::SWITCHER,
                'condition' => [
                    'enable_all_taxonomy!' => ''
                ],
            ]
        );

        $this->add_control(
            'per_page',
            array(
                'label'       => esc_html__('Per Page', 'triply'),
                'description' => esc_html__('How much items per page to show (-1 to show all items)', 'triply'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => '',
                'default'     => '6',
                'condition' => [
                    'enable_all_taxonomy!' => ''
                ],
            )
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
            ]
        );

        $this->add_control(
            'count_tour',
            array(
                'label'       => esc_html__('Enable count tour', 'triply'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
            )
        );

        $this->add_responsive_control(
            'item_height',
            [
                'label'      => esc_html__('Height Item', 'triply'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .column-item.location-item .thumbnail-location' => 'height: {{SIZE}}px;',
                ],
            ]
        );



        $this->end_controls_section();

        $this->add_control_carousel();

    }

    protected function add_control_carousel() {
        $this->start_controls_section(
            'section_carousel_options',
            [
                'label'     => esc_html__('Carousel Options', 'triply'),
                'type'      => Controls_Manager::SECTION
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable', 'triply'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );


        $this->add_control(
            'navigation',
            [
                'label'     => esc_html__('Navigation', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dots',
                'options'   => [
                    'both'   => esc_html__('Arrows and Dots', 'triply'),
                    'arrows' => esc_html__('Arrows', 'triply'),
                    'dots'   => esc_html__('Dots', 'triply'),
                    'none'   => esc_html__('None', 'triply'),
                ],
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'     => esc_html__('Pause on Hover', 'triply'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'     => esc_html__('Autoplay', 'triply'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'     => esc_html__('Autoplay Speed', 'triply'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'frontend_available' => true,
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
                ],
            ]
        );

        $this->add_control(
            'infinite',
            [
                'label'     => esc_html__('Infinite Loop', 'triply'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'visibility',
            [
                'label'     => esc_html__('Visibility', 'triply'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'no',
                'condition' => [
                    'enable_carousel' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'style_layout_carousel',
            [
                'label'        => esc_html__('Layout Carousel', 'triply'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    '1' => esc_html__('Layout 1', 'triply'),
                    '2' => esc_html__('Layout 2', 'triply'),
                ],
                'condition'    => [
                    'enable_carousel' => 'yes'
                ],
                'default'      => '1',
                'prefix_class' => 'layout-carousel-'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'carousel_arrows',
            [
                'label'      => esc_html__('Carousel Arrows', 'triply'),
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'none',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'dots',
                        ],
                    ],
                ],
            ]
        );

        //Style arrow
        $this->add_control(
            'style_arrow',
            [
                'label'        => esc_html__('Style Arrow', 'triply'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'style-1' => esc_html__('Style 1', 'triply'),
                    'style-2' => esc_html__('Style 2', 'triply')
                ],
                'default'      => 'style-1',
                'prefix_class' => 'arrow-'
            ]
        );

        //add icon next size
        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Size', 'triply'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        //add icon next color
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-arrow:before' => 'color: {{VALUE}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'next_heading',
            [
                'label' => esc_html__('Next button', 'triply'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'next_vertical',
            [
                'label'       => esc_html__('Next Vertical', 'triply'),
                'type'        => Controls_Manager::CHOOSE,
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
                ]
            ]
        );

        $this->add_responsive_control(
            'next_vertical_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .slick-next' => 'top: unset; bottom: unset; {{next_vertical.value}}: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_control(
            'next_horizontal',
            [
                'label'       => esc_html__('Next Horizontal', 'triply'),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'left'  => [
                        'title' => esc_html__('Left', 'triply'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'triply'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'defautl'     => 'right'
            ]
        );
        $this->add_responsive_control(
            'next_horizontal_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => -45,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .slick-next' => 'left: unset; right: unset;{{next_horizontal.value}}: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'prev_heading',
            [
                'label'     => esc_html__('Prev button', 'triply'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'prev_vertical',
            [
                'label'       => esc_html__('Prev Vertical', 'triply'),
                'type'        => Controls_Manager::CHOOSE,
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
                ]
            ]
        );

        $this->add_responsive_control(
            'prev_vertical_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .slick-prev' => 'top: unset; bottom: unset; {{prev_vertical.value}}: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_control(
            'prev_horizontal',
            [
                'label'       => esc_html__('Prev Horizontal', 'triply'),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'left'  => [
                        'title' => esc_html__('Left', 'triply'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'triply'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'defautl'     => 'left'
            ]
        );
        $this->add_responsive_control(
            'prev_horizontal_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => -45,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .slick-prev' => 'left: unset; right: unset; {{prev_horizontal.value}}: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function get_carousel_settings()
    {
        $settings = $this->get_settings_for_display();
        $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_breakpoints();
        $tablet = isset($settings['column_tablet']) ? $settings['column_tablet'] : 2;
        return array(
            'navigation' => $settings['navigation'],
            'autoplayHoverPause' => $settings['pause_on_hover'] === 'yes' ? true : false,
            'autoplay' => $settings['autoplay'] === 'yes' ? true : false,
            'autoplaySpeed' => $settings['autoplay_speed'],
            'items' => $settings['column'],
            'items_laptop' => isset($settings['column_laptop']) ? $settings['column_laptop'] : $settings['column'],
            'items_tablet_extra' => isset($settings['column_tablet_extra']) ? $settings['column_tablet_extra'] : $settings['column'],
            'items_tablet' => $tablet,
            'items_mobile_extra' => isset($settings['column_mobile_extra']) ? $settings['column_mobile_extra'] : $tablet,
            'items_mobile' => isset($settings['column_mobile']) ? $settings['column_mobile'] : 1,
            'loop' => $settings['infinite'] === 'yes' ? true : false,
            'breakpoint_laptop' => $breakpoints['laptop']->get_value(),
            'breakpoint_tablet_extra' => $breakpoints['tablet_extra']->get_value(),
            'breakpoint_tablet' => $breakpoints['tablet']->get_value(),
            'breakpoint_mobile_extra' => $breakpoints['mobile_extra']->get_value(),
            'breakpoint_mobile' => $breakpoints['mobile']->get_value(),
        );
    }


    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {

        $settings = $this->get_controls_settings();

        $this->add_render_attribute('wrapper', 'class', 'row');

        if ($settings['enable_carousel'] === 'yes') {
            if ($settings['visibility'] == 'yes') {
                $this->add_render_attribute('wrapper', 'class', 'triply-carousel-visibility');
            }
            $this->add_render_attribute('wrapper', 'class', 'triply-carousel-items');
            $carousel_settings = $this->get_carousel_settings();
            $this->add_render_attribute('wrapper', 'data-carousel', wp_json_encode($carousel_settings));
        } else {
            if (!empty($settings['column_widescreen'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-widescreen', $settings['column_widescreen']);
            }

            if (!empty($settings['column'])) {
                $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns', 5);
            }

            if (!empty($settings['column_laptop'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-laptop', $settings['column_laptop']);
            }

            if (!empty($settings['column_tablet_extra'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet-extra', $settings['column_tablet_extra']);
            }

            if (!empty($settings['column_tablet'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns-tablet', 2);
            }

            if (!empty($settings['column_mobile_extra'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile-extra', $settings['column_mobile_extra']);
            }

            if (!empty($settings['column_mobile'])) {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
            } else {
                $this->add_render_attribute('row', 'data-elementor-columns-mobile', 1);
            }
        }

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        if(!empty($settings['taxonomy_slug'])){

            $default_lang = BABE_Functions::get_default_language();
            $current_lang = BABE_Functions::get_current_language();
            if ( BABE_Functions::is_wpml_active() && $current_lang !== $default_lang ){
                do_action( 'wpml_switch_language', $default_lang );
                $taxonomy = get_term_by( 'slug', $settings['taxonomy_slug'], BABE_Post_types::$taxonomies_list_tax );
                do_action( 'wpml_switch_language', $current_lang );
            }else{
                $taxonomy = get_term_by('slug', $settings['taxonomy_slug'], BABE_Post_types::$taxonomies_list_tax);
            }


            if ( ! is_wp_error( $taxonomy ) && ! empty( $taxonomy ) ) {

                if(empty($settings[$settings['taxonomy_slug'].'_ids']) || $settings['enable_all_taxonomy']){
                    $taxonomies = get_terms(array(
                        'taxonomy'   => BABE_Post_types::$attr_tax_pref . $taxonomy->slug,
                        'hide_empty' => $settings['hide_empty'],
                        'number'     => $settings['per_page'],
                        'orderby'    => $settings['orderby'],
                        'order'      => $settings['order'],
                    ));

                    if (!is_wp_error($taxonomies) && !empty($taxonomies)) {
                        foreach ($taxonomies as $taxonomy) {
                            $this->render_taxonomy_item($taxonomy);
                        }
                    }
                }else{
                    $taxonomies = $settings[$settings['taxonomy_slug'].'_ids'];
                    foreach ($taxonomies as $taxonomy){

                        $default_lang = BABE_Functions::get_default_language();
                        $current_lang = BABE_Functions::get_current_language();
                        if ( BABE_Functions::is_wpml_active() && $current_lang !== $default_lang ){
                            do_action( 'wpml_switch_language', $default_lang );
                            $tax = get_term_by( 'slug', $taxonomy, BABE_Post_types::$attr_tax_pref . $settings['taxonomy_slug']);
                            do_action( 'wpml_switch_language', $current_lang );
                        }else{
                            $tax = get_term_by( 'slug', $taxonomy, BABE_Post_types::$attr_tax_pref . $settings['taxonomy_slug']);
                        }
                        if ( ! is_wp_error( $tax ) && ! empty( $tax ) ) {
                            $this->render_taxonomy_item($tax);
                        }
                    }
                }

            }
        }
        echo '</div>';

    }

    public function render_taxonomy_item($taxonomy){

        $settings = $this->get_controls_settings();
        $image_location = get_term_meta($taxonomy->term_id, 'triply_location_image', true);
        $settings = $this->get_controls_settings();

        if(empty($image_location)){
            $image_location = Elementor\Utils::get_placeholder_image_src();
        }
        ?>
        <div class="column-item location-item">
            <div class="item-inner">
                <a class="title-location" href="<?php echo esc_url( get_term_link($taxonomy->slug, $taxonomy->taxonomy) ); ?>">
                    <div class="thumbnail-location">
                        <img src="<?php echo esc_url($image_location); ?>" alt="<?php echo esc_attr( $taxonomy->name );?>">
                    </div>
                    <div class="content-location">
                        <span class="location-name"><?php echo esc_attr( $taxonomy->name );?></span>
                        <?php if($settings['count_tour']): ?>
                        <span class="location-count"><?php echo esc_attr( $taxonomy->count ) . '&nbsp;'; ?><?php echo 1 < $taxonomy->count ? esc_html__('Tours', 'triply') : esc_html__('Tour', 'triply');?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }



    public static function get_taxonomies_arr() {
        $output = array();

        $taxonomies = get_terms( array(
            'taxonomy' => BABE_Post_types::$taxonomies_list_tax,
            'hide_empty' => false,
            'suppress_filters' => false,
        ));

        if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ($taxonomies as $tax_term) {
                $output[$tax_term->slug] = apply_filters('translate_text', $tax_term->name);
            }
        }

        return $output;

    }



    private function get_taxonomy_name($taxonomy_slug){

        $default_lang = BABE_Functions::get_default_language();
        $current_lang = BABE_Functions::get_current_language();
        if ( BABE_Functions::is_wpml_active() && $current_lang !== $default_lang ){
            do_action( 'wpml_switch_language', $default_lang );
            $taxonomy = get_term_by( 'slug', $taxonomy_slug, BABE_Post_types::$taxonomies_list_tax );
            do_action( 'wpml_switch_language', $current_lang );
        }else{
            $taxonomy = get_term_by( 'slug', $taxonomy_slug, BABE_Post_types::$taxonomies_list_tax );
        }

        if ( ! is_wp_error( $taxonomy ) && ! empty( $taxonomy ) ) {
            return BABE_Post_types::$attr_tax_pref.$taxonomy->slug;
        }
        return false;
    }

    private function render_setting_taxonomy(){
        $taxonomies = get_terms( array(
            'taxonomy' => BABE_Post_types::$taxonomies_list_tax,
            'hide_empty' => false
        ));

        if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ($taxonomies as $tax_term) {
                $this->add_control(
                    $tax_term->slug.'_ids',
                    array(
                        'label'   => esc_html__('Ba ', 'triply'). $tax_term->name,
                        'type'    => \Elementor\Controls_Manager::SELECT2,
                        'multiple'    => true,
                        'options' => $this->get_taxonomy_arr($this->get_taxonomy_name($tax_term->slug)),
                        'label_block' => true,
                        'condition' => [
                            'taxonomy_slug' => $tax_term->slug,
                            '!enable_all_taxonomy' => ''
                        ],
                    )
                );
            }
        }
    }

    public static function get_taxonomy_arr($taxonomy_name) {
        $output = array();

        $taxonomies = get_terms( array(
            'taxonomy' => $taxonomy_name,
            'hide_empty' => false,
            'suppress_filters' => false,
        ));

        if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ($taxonomies as $tax_term) {
                $output[$tax_term->slug] = apply_filters('translate_text', $tax_term->name);
            }
        }

        return $output;
    }

}

