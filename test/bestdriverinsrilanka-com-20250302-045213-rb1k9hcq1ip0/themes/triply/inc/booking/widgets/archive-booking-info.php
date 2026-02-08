<?php

use Elementor\Controls_Manager;

/**
 * Add widget all-items to Elementor
 *
 * @since   1.3.13
 */
class OSF_Elementor_BA_Archive_Booking_Info extends \Elementor\Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-archive-info';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('BA Archive Information', 'triply');
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
        return ['Booking', 'Archive', 'Archive Info'];
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['book-everything-elements'];
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_style_info',
            [
                'label' => esc_html__('Style Typography', 'triply'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'title_style',
            [
                'label' => esc_html__('Title', 'triply'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'triply'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-info-item .item-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .taxonomy-info-item .item-label',
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => esc_html__('Min Width', 'triply'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-info-item .item-label' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_style',
            [
                'label' => esc_html__('Description', 'triply'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'triply'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-info-item .item-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'selector' => '{{WRAPPER}} .taxonomy-info-item .item-content',
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
            $taxonomy_ID = triply_ba_get_default_tax_id();
            $this->render_taxonomy_info($taxonomy_ID);


        }else{
            $object = get_queried_object();
            if(!empty( $object)) {
                $this->render_taxonomy_info($object->term_id);
            }
        }

    }

    private function render_taxonomy_info($tax_ID){
        $term_data = get_term_meta($tax_ID, 'taxonomy_info', true);
        if($term_data && !empty($term_data)){
            echo '<div class="elementor-widget-inner">';
                foreach ($term_data as $data): ?>
                    <div class="taxonomy-info-item">
                        <div class="item-label"><?php echo esc_attr($data['triply_title']);?></div>
                        <div class="item-content"><?php echo esc_attr($data['triply_description']);?></div>
                    </div>
            <?php
                endforeach;
            echo '</div>';
        }
    }

}
