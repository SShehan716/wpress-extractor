<?php


if (!function_exists('triply_ba_get_default_single_id')) {
    function triply_ba_get_default_single_id() {
        $post_id = get_posts("post_type=".BABE_Post_types::$booking_obj_post_type."&numberposts=1&fields=ids");
        if(!empty($post_id) && isset($post_id[0])){
            return $post_id[0];
        }else{
            return false;
        }

    }
}

if (!function_exists('triply_ba_get_default_tax_id')) {
    function triply_ba_get_default_tax_id() {
        global $triply;
        $location_taxonomy =  BABE_Post_types::$attr_tax_pref . $triply->locations;

        $taxonomies = get_terms( array(
            'taxonomy'   => $location_taxonomy,
            'hide_empty' => false,
            'number' => 1

        ) );

        if ( ! empty( $taxonomies ) && ! is_wp_error( $taxonomies ) &&  isset($taxonomies[0])){
            return $taxonomies[0]->term_id;
        }else{
            return false;
        }

    }
}

if (!function_exists('triply_get_all_posts_html')) {
    function triply_get_all_posts_html($post_type, $user_info) {

        $output = '';

        $post_type_obj = get_post_type_object($post_type);

        //$output = print_r($post_type_obj->labels, 1);
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => 10,
            'paged'          => get_query_var('paged'),
            'post_status'    => 'any',
            'orderby'        => 'post_date',
            'order'          => 'DESC',
        );

        if (!(in_array('manager', $user_info->roles) || in_array('administrator', $user_info->roles))) {
            $args['author'] = $user_info->ID;
        }

        $args = apply_filters('babe_myaccount_all_posts_get_post_args', $args, $post_type, $user_info);

        $the_query     = new WP_Query($args);
        $max_num_pages = $the_query->max_num_pages;
        $found_posts   = $the_query->found_posts;
        if ($found_posts > 0) { ?>
            <div class="my_account_inner_page_block my_account_all_posts">
                <div class="all-post-top-meta">
                    <h2><?php echo esc_html($post_type_obj->labels->all_items); ?></h2>
                    <span class="my_account_all_posts_total"><?php echo esc_html__('Total: ', 'triply') . esc_html($found_posts); ?></span>
                </div>
                <div class="my_account_all_posts_inner dashboard-content-wrapper">
                    <table class="my_account_all_posts_table">
                        <thead>
                        <tr>
                            <?php
                            if( $post_type !== 'faq' ){
                                echo '<th>'.esc_html__('Image', 'triply').'</th>';
                            }
                            ?>
                            <th><?php esc_html_e('Title', 'triply'); ?></th>
                            <th class="head-published"><?php esc_html_e('Published', 'triply'); ?></th>
                            <th class="head-action"><?php esc_html_e('Action', 'triply'); ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        while ($the_query->have_posts()) : $the_query->the_post();
                            $post_id  = get_the_ID();
                            $edit_url = BABE_Settings::get_my_account_page_url(array('inner_page' => 'edit-post', 'edit_post_id' => $post_id));
                            ?>
                            <tr>
                                <?php
                                if( $post_type !== 'faq' ){ ?>
                                    <td class="item-thumbnail">
                                        <?php
                                        if (has_post_thumbnail($post_id)) { ?>
                                            <a href="<?php echo get_the_permalink($post_id); ?>">
                                                <?php echo get_the_post_thumbnail($post_id, 'post-thumbnail'); ?>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td class="item-title">
                                    <h5>
                                        <a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a>
                                    </h5>
                                </td>
                                <td class="item-published">
                                    <span><?php echo get_the_date('', $post_id); ?></span>
                                </td>
                                <td class="item-action">
                                    <a class="edit-btn" href="<?php echo esc_url($edit_url); ?>" title="<?php esc_html_e('Edit', 'triply') ?>"><i class="triply-icon-edit"></i></a>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                        /* Restore original Post Data */
                        wp_reset_postdata();
                        ?>
                        </tbody>

                    </table>
                </div>
                <?php printf("%s", BABE_Functions::pager($max_num_pages)); ?>
            </div>
            <?php
        }else{
            echo '<div class="my_account_no_posts dashboard-content-wrapper"><p>'.esc_html__('No posts are published, start posting your first post.','triply').'</p></div>';
        }
    }
}


if (!function_exists('triply_ba_item_block')) {
    function triply_ba_item_block($content, $post, $babe_post) {
        ob_start();

        include get_theme_file_path('template-parts/booking/block/item-block-1.php');

        return ob_get_clean();
    }
}

if (!function_exists('triply_babe_shortcode_all_items_html')) {
    function triply_babe_shortcode_all_items_html($output, $args, $post_args) {
        $classes = $args['classes'] ? $args['classes'] : '';
        $posts = BABE_Post_types::get_posts($post_args);
        $posts_pages = BABE_Post_types::$get_posts_pages;

        $pagination = '';

        if ( $args['pagination'] ){
            $pagination = BABE_Functions::pager($posts_pages);
        }
        $output  = '
				<div class="babe_shortcode_block sc_all_items ' . $classes . '">
					<div class="babe_shortcode_block_bg_inner">
						<div class="babe_shortcode_block_inner">
							' . BABE_shortcodes::get_posts_tile_view($post_args) . '
						</div>
						'. $pagination .'
					</div>
				</div>
			';
        return $output;
    }
}

if (!function_exists('triply_ba_get_gallery_popup_json')) {
    /**
     * @param $gallery_items
     * @return false|string
     */
    function triply_ba_get_gallery_popup_json($gallery_items) {
        $data = [];
        foreach ($gallery_items as $item) {
            $item_data = wp_get_attachment_image_src($item['image_id'], 'full');
            $data[]    = [
                'src' => $item_data[0],
                'w'   => $item_data[1],
                'h'   => $item_data[2],
            ];
        }

        return wp_json_encode($data);
    }
}

/*Tab Content Google Map*/
if (!function_exists('triply_ulisting_gallery_google_map')) {
    function triply_ulisting_gallery_google_map($latitude, $longitude, $height = 300, $class = 'map-data-location') {
        $icon_url = '';
        if (BABE_Settings::$settings['google_map_marker'] == 1) {
            $icon_url = get_template_directory_uri() . '/assets/images/booking/mapmarker.svg';
        } else {
            $icon_url = plugins_url(BABE_Settings::$markers_urls[BABE_Settings::$settings['google_map_marker']], BABE_PLUGIN);
        }
        ?>
        <div class="<?php echo esc_attr($class); ?>" style="height: <?php echo esc_attr($height); ?>px;"
             data-color='<?php echo esc_attr(triply_google_map_style()); ?>'
             data-lat="<?php echo esc_attr($latitude); ?>"
             data-lng="<?php echo esc_attr($longitude); ?>"
             data-zoom="<?php echo esc_attr(BABE_Settings::$settings['google_map_zoom']); ?>"
             data-icon="<?php echo esc_url($icon_url); ?>"></div>
        <?php
    }
}

/*Google Map Style*/
if (!function_exists('triply_google_map_style')) {
    function triply_google_map_style() {
        $triply_google_style     = BABE_Settings::$settings['triply_booking_google_map_style'];
        $triply_google_map_style = '[]';
        $triply_list_map_style   = triply_list_style_google_map();

        if (isset($triply_google_style)) {
            foreach ($triply_list_map_style as $key => $value) {
                if ($triply_google_style == $key) {
                    $triply_google_map_style = $value;
                    break;
                }
            }
        }

        return $triply_google_map_style;
    }
}

if (!function_exists('triply_list_style_google_map')) {
    function triply_list_style_google_map() {
        return $triply_style_map_arr = [
            'standard' => '[]',

            'light_grey_and_blue' => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#dde6e8"},{"visibility":"on"}]}]',

            'ultra_light' => '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]',

            'shades_of_grey' => '[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]',

            'blue_water' => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]',

            'farma_gray' => '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"},{"gamma":"1.00"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"color":"#ba5858"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"color":"#e57878"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"},{"lightness":"65"},{"saturation":"-100"},{"hue":"#ff0000"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"},{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway.controlled_access","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"},{"saturation":"-100"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"},{"visibility":"simplified"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"hue":"#ff0036"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"}]}]',

            'community' => '[{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#2c52a2"}]},{"featureType":"administrative.province","elementType":"labels.text.fill","stylers":[{"color":"#2c52a2"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#2c52a2"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#2c52a2"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"hue":"#ff0000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.stroke","stylers":[{"color":"#2c52a2"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"color":"#f5f5f5"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#c6ebbd"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#addbf1"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#2c52a2"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}]',

            'chilled' => '[{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"hue":149},{"saturation":-78},{"lightness":0}]},{"featureType":"road.highway","stylers":[{"hue":-31},{"saturation":-40},{"lightness":2.8}]},{"featureType":"poi","elementType":"label","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"hue":163},{"saturation":-26},{"lightness":-1.1}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"hue":3},{"saturation":-24.24},{"lightness":-38.57}]}]'
        ];
    }
}

/*Get Google Map Action*/
if (!function_exists('triply_action_google_map')) {
    function triply_action_google_map() {
        wp_enqueue_script('google-maps', "https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=" . BABE_Settings::$settings['google_api'] . "&callback=googleApiLoadw", array(), '', true);
    }

    add_action('triply_action_google_map', 'triply_action_google_map');
}

if (!function_exists('triply_ba_list_add_services')) {
    /**
     * Get services list with checkboxes.
     *
     * @param array $post - BABE post
     * @param array $selected_services_arr
     *
     * @return string
     */
    function triply_ba_list_add_services($post, $selected_services_arr = array()) {

        $output  = '';
        $results = array();

        if (!empty($post) && isset($post['services']) && !empty($post['services'])) {

            $services_arr = BABE_Post_types::get_post_services($post);

            if (!empty($services_arr)) {

                $selected_ids = array_flip($selected_services_arr);

                $rules_cat = BABE_Booking_Rules::get_rule_by_obj_id($post['ID']);

                $tax_am = isset($rules_cat['category_meta']['categories_add_taxes']) && $rules_cat['category_meta']['categories_add_taxes'] && isset($rules_cat['category_meta']['categories_tax']) ? floatval($rules_cat['category_meta']['categories_tax']) / 100 : 0;
                // $tax = 1 + $tax_am;

                $post_ages = BABE_Post_types::get_post_ages($post['ID']);

                $ages = !$rules_cat['rules']['ages'] || empty($post_ages) ? array(
                    0 => array(
                        'age_id'      => 0,
                        'name'        => esc_html__('Price', 'triply'),
                        'description' => '',
                        'menu_order'  => 1,
                        'slug'        => '',
                    )
                ) : $post_ages;

                $i                   = 0;
                $before_service_type = '';

                foreach ($services_arr as $service) {

                    $sr_block = '';

                    $general_price = floatval($service['prices'][0]);

                    $is_other_prices = false;

                    $other_prices = $service['prices'];

                    unset($other_prices[0]);

                    if (empty($other_prices) && !$general_price) {
                        continue;
                    }

                    if ($before_service_type != $service['service_type']) {
                        $before_service_type = $service['service_type'];
                    }

                    $is_percent = $before_service_type == 'booking' && $service['price_type'] == 'percent' ? true : false;

                    $checked = isset($selected_ids[$service['ID']]) ? ' checked="checked"' : '';

                    $sr_block
                        .= '<div class="list_service">
                    <div class="list_service_title"><div class="input-square">
                      <input id="booking_services_' . $service['ID'] . '" type="checkbox" name="booking_services[]" value="' . $service['ID'] . '" ' . $checked . '/>
                      <label for="booking_services_' . $service['ID'] . '">' . esc_html($service['post_title']) . '</label>
                    </div>
                    </div>
                    <div class="list_service_prices">
                    ';

                    if ($rules_cat['rules']['ages'] && is_array($other_prices)) {
                        foreach ($other_prices as $test_age => $test_price) {
                            $is_other_prices = $test_age && $test_price !== '' ? true : $is_other_prices;
                        }
                    }

                    if (!in_array($before_service_type, array('booking', 'day', 'night'))) {

                        foreach ($ages as $age) {

                            if (!$is_percent) {

                                $price_value = $is_other_prices && $age['age_id'] ? (isset($other_prices[$age['age_id']]) && $other_prices[$age['age_id']] !== '' ? floatval($other_prices[$age['age_id']]) : '') : $general_price;

                                if ($price_value !== '') {

                                    $price_value_html = $price_value == 0 ? esc_html__('included', 'triply') : BABE_Currency::get_currency_price($price_value + round($price_value * $tax_am, 2));

                                    $price_label = $is_other_prices ? '<label>' . apply_filters('translate_text', $age['name']) . ':</label>' : '';

                                    $sr_block .= '<span class="service_price_line">' . $price_label . $price_value_html . '</span>';

                                }

                            } else {
                                $sr_block .= $is_other_prices ? (isset($other_prices[$age['age_id']]) && $other_prices[$age['age_id']] !== '' ? '<span class="service_price_line"><label>' . apply_filters('translate_text', $age['name']) . ':</label>' . $other_prices[$age['age_id']] . ' %/' . esc_html__('booking ', 'triply') . '</span>' : '') : '<span class="service_price_line"><label>' . apply_filters('translate_text', $age['name']) . ':</label>' . $general_price . ' %/' . esc_html__('booking ', 'triply') . '</span>';
                            }

                        }

                    } else {
                        $sr_block .= '<span class="service_price_line">';

                        if (!$is_percent) {

                            $price_value_html = $general_price == 0 ? esc_html__('included', 'triply') : BABE_Currency::get_currency_price($general_price + round($general_price * $tax_am, 2));

                            $sr_block .= $price_value_html;

                        } else {

                            $sr_block .= $general_price . ' %/' . esc_html__('booking ', 'triply');

                        }

                        $sr_block .= '</span>';
                    }

                    $service_content = $service['post_content'] ? '<div class="view-list-details"><div class="view-details-inner"> ' . $service['post_content'] . '</div></div>' : '';

                    $sr_block .= '</div>' . $service_content . '
                     </div>';

                    $results[] = apply_filters('babe_list_add_services_block_html', $sr_block, $service, $ages);
                }

                if (!empty($results)) {

                    $output
                        .= '
              <div id="list_services">
                ' . implode('', $results) . '
              </div>
              ';
                }

            }
        } //// end if !empty($post['services'])

        return $output;
    }
}

if (!function_exists('triply_ba_tour_term_icons')) {
    //////////////////////////////////////////////////
    /**
     * Gets tour term icons.
     *
     * @param int $post_id
     * @param array $taxonomies - array of taxonomy slugs
     *
     * @return string
     */
    function triply_ba_tour_term_icons($post_id) {

        global $triply;

        $output = '';

        $features = BABE_Post_types::$attr_tax_pref . $triply->features;

        $terms = BABE_Post_types::get_post_terms($post_id, $features);


        if (!empty($terms) && (isset($terms['terms']))) {

            $results = array();

            foreach ($terms['terms'] as $term) {

                $term_output = '';

                if ($term['image_id'] || (isset($term['fa_class']) && $term['fa_class'])) {
                    if ($term['image_id']) {

                        // Image.
                        $src_arr = wp_get_attachment_image_src($term['image_id'], 'full');

                        $width = ($term['image_width']) ? $term['image_width'] : 50;
                        $height = ($term['image_height']) ? $term['image_height'] : 50;

                        $term_output = '
                                <div class="triply_preview_term_icon">
                                    <img src="' . $src_arr[0] . '" width="'.$width.'" height="'.$height.'">
                                    <span>' . $term['name'] . '</span>
                                </div>
                            ';


                    } else if($term['fa_class']){
                        // Fontawesome.
                        $term_output = '
                                <div class="triply_preview_term_icon" title="' . $term['name'] . '">
                                    <i class="' . $term['fa_class'] . '"></i>
                                    <span>' . $term['name'] . '</span>
                                </div>
                            ';
                    }
                }

                if ($term_output) {
                    $results[] = $term_output;
                }
            }

            $output .= implode('', $results);
        }

        return $output;
    }
}


if (!function_exists('triply_babe_search_result_html')) {
    function triply_babe_search_result_html($output, $posts, $posts_pages) {

        $columns = isset(BABE_Settings::$settings['triply_booking_search_columns']) ? BABE_Settings::$settings['triply_booking_search_columns'] : 3;

        $output = '<div data-elementor-columns="' . $columns . '" data-elementor-columns-tablet="2" data-elementor-columns-mobile="1">
                        <div class="babe_shortcode_block_inner">
                            ' . $output . '
                        </div>
                    </div>
                ';
        return $output;
    }
}
