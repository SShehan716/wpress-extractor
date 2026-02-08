<?php
/**
 * Add widget all-items to Elementor
 *
 * @since   1.3.13
 */

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;


class Triply_BABE_Elementor_Itemfaqs_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'babe-item-faqs';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Item Questions & Answers', 'triply');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-info-circle-o';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['faq'];
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

        $this->title_controls();

    }

    protected function title_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Title Setting', 'triply'),
            ]
        );

        $this->add_control(
            'enable_title',
            [
                'label'   => esc_html__('Enable title', 'triply'),
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'triply'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title', 'triply'),
                'default'     => __('Questions & Answers', 'triply'),
                'condition'   => [
                    'enable_title' => 'yes',
                ]
            ]
        );


        $this->add_control(
            'size',
            [
                'label'     => __('Size', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => [
                    'default' => __('Default', 'triply'),
                    'small'   => __('Small', 'triply'),
                    'medium'  => __('Medium', 'triply'),
                    'large'   => __('Large', 'triply'),
                    'xl'      => __('XL', 'triply'),
                    'xxl'     => __('XXL', 'triply'),
                ],
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label'     => __('HTML Tag', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default'   => 'h2',
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => __('Alignment', 'triply'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => __('Left', 'triply'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'triply'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'triply'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'triply'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'view',
            [
                'label'     => __('View', 'triply'),
                'type'      => Controls_Manager::HIDDEN,
                'default'   => 'traditional',
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label'     => __('Title', 'triply'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Text Color', 'triply'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => 'typography',
                'global'    => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector'  => '{{WRAPPER}} .elementor-heading-title',
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'     => __('Margin bottom', 'triply'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .elementor-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'text_shadow',
                'selector'  => '{{WRAPPER}} .elementor-heading-title',
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label'     => __('Blend Mode', 'triply'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''            => __('Normal', 'triply'),
                    'multiply'    => 'Multiply',
                    'screen'      => 'Screen',
                    'overlay'     => 'Overlay',
                    'darken'      => 'Darken',
                    'lighten'     => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation'  => 'Saturation',
                    'color'       => 'Color',
                    'difference'  => 'Difference',
                    'exclusion'   => 'Exclusion',
                    'hue'         => 'Hue',
                    'luminosity'  => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
                'condition' => [
                    'enable_title' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        include get_theme_file_path('template-parts/booking/single/faqs.php');
    }


    protected function title_render() {
        $settings = $this->get_settings_for_display();

        if ('' === $settings['title'] || $settings['enable_title'] === 'no') {
            return;
        }

        $this->add_render_attribute('title', 'class', 'elementor-heading-title');

        if (!empty($settings['size'])) {
            $this->add_render_attribute('title', 'class', 'elementor-size-' . $settings['size']);
        }

        $this->add_inline_editing_attributes('title');

        $title = $settings['title'];


        $title_html = sprintf('<%1$s %2$s>%3$s</%1$s>', \Elementor\Utils::validate_html_tag($settings['header_size']), $this->get_render_attribute_string('title'), $title);

        printf('%s', $title_html);
    }

}
/////////////////////
