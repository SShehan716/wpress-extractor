<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('sb_instagram_feed_init')) {
    return;
}

use Elementor\Controls_Manager;


class OSF_Elementor_Intagram extends Elementor\Widget_Base {

    public function get_name() {
        return 'triply-instagram';
    }

    public function get_title() {
        return esc_html__('Instagram', 'triply');
    }


    public function get_categories() {
        return array('triply-addons');
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'triply'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'num',
            [
                'label'   => esc_html__('Number of Photos', 'triply'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => '1',
                'max'     => '33'
            ]
        );

        $this->add_control(
            'cols',
            [
                'label'   => esc_html__('Number of Columns', 'triply'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 3,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10],
            ]
        );

        $this->add_control(
            'imagepadding',
            [
                'label'      => esc_html__('Padding around Images', 'triply'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit' => 'px',
                    'size' => 5,
                ]
            ]
        );

        $this->add_control(
            'showheader',
            [
                'label'   => esc_html__('Show Feed Header', 'triply'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'showbutton',
            [
                'label'   => esc_html__('Show the Load More button', 'triply'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'showfollow',
            [
                'label'   => esc_html__('Show the Follow button', 'triply'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = '';
        $args .= empty($settings['num']) ? ' num=10' : ' num=' . $settings['num'];
        $args .= empty($settings['cols']) ? ' cols=4' : ' cols=' . $settings['cols'];
        $args .= empty($settings['imagepadding']['size']) ? ' imagepadding=5' : ' imagepadding=' . $settings['imagepadding']['size'];
        $args .= empty($settings['imagepadding']['unit']) ? ' imagepaddingunit=px' : ' imagepaddingunit=' . $settings['imagepadding']['unit'];
        $args .= $settings['showheader'] == 'yes' ? ' showheader=true' : ' showheader=false';
        $args .= $settings['showbutton'] == 'yes' ? ' showbutton=true' : ' showbutton=false';
        $args .= $settings['showfollow'] == 'yes' ? ' showfollow=true' : ' showfollow=false';

        echo do_shortcode('[instagram-feed ' . $args . ']');

    }

}

$widgets_manager->register(new OSF_Elementor_Intagram());
