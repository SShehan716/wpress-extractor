<?php

use Elementor\Controls_Manager;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Triply_BABE_Elementor_TaxonomiesList_Widget extends \Elementor\Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-taxonomies-list';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Ba Taxonomies List', 'triply');

    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-bullet-list';
    }


    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['book-everything-elements'];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     */
    protected function register_controls() {

        // Get all terms of categories
        $this->start_controls_section(
            'babe_taxonomies_list',
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

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Content', 'triply' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'Typography',
                'selector' => '{{WRAPPER}} .location-list .title-tour',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'triply' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .location-list .title-tour' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__( 'Count Color', 'triply' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .location-list .count-count' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'count_tour!' => '',
                ]
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'triply' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .location-list .location-content:hover .title-tour' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .location-list .location-content:hover .count-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'location_list_padding',
            [
                'size_units' => ['px', 'em', '%'],
                'label' => esc_html__('Spacing', 'triply'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .location-list' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
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

        $settings = $this->get_controls_settings();

        $this->add_render_attribute('row', 'class', 'row');

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

        echo '<div ' . $this->get_render_attribute_string('row') . '>';

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
    ?>
            <div class="location-list column-item">
                <a class="location-content" href="<?php echo esc_url( get_term_link($taxonomy->slug, $taxonomy->taxonomy) ); ?>">
                    <span class="title-tour"> <?php echo esc_attr( $taxonomy->name );?> </span>
                    <?php if($settings['count_tour']): ?>
                        <span class="count-count">(<?php echo esc_attr( $taxonomy->count ); ?>)</span>
                    <?php endif; ?>
                </a>
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

        $taxonomy = get_term_by('slug', $taxonomy_slug, BABE_Post_types::$taxonomies_list_tax);
        if ( ! is_wp_error( $taxonomy ) && ! empty( $taxonomy ) ) {
            return BABE_Post_types::$attr_tax_pref.$taxonomy->slug;
        }
        return false;
    }

    private function render_setting_taxonomy(){
        $taxonomies = get_terms( array(
            'taxonomy' => BABE_Post_types::$taxonomies_list_tax,
            'hide_empty' => false,
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
            'hide_empty' => false
        ));

        if ( ! is_wp_error( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ($taxonomies as $tax_term) {
                $output[$tax_term->slug] = apply_filters('translate_text', $tax_term->name);
            }
        }

        return $output;
    }

}

