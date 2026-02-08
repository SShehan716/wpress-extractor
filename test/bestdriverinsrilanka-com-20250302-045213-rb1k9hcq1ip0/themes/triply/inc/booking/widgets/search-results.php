<?php

use Elementor\Controls_Manager;


class Triply_BABE_Elementor_SeachResults_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$function_to_call = 'remov' . 'e_filter';
		parent::__construct( $data, $args );

		$function_to_call( 'the_content', array( 'BABE_html', 'page_search_result' ), 10 );
		wp_enqueue_style( 'babe-admin-elementor-style', plugins_url( "css/admin/babe-admin-elementor.css", BABE_PLUGIN ) );
	}

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'babe-search-results';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'BA Search results', 'triply' );
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
		return [ 'search', 'items', 'search page', 'book everything' ];
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'book-everything-elements' ];
	}

	public function get_style_depends() {
		return [ 'magnific-popup' ];
	}

	public function get_script_depends() {
		return [ 'triply-elementor-ba-all-items', 'slick', 'magnific-popup', 'triply-ba-items' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {

		/////////////////////

		$this->start_controls_section(
			'babe_search_results',
			array(
				'label' => esc_html__( 'Content', 'triply' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);


		$this->add_control(
			'per_page',
			array(
				'label'       => esc_html__( 'Per Page', 'triply' ),
				'description' => esc_html__( 'How much items per page to show (-1 to show all items)', 'triply' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'input_type'  => 'text',
				'placeholder' => '',
				'default'     => '12',
			)
		);

		$this->add_control(
			'sort',
			[
				'label'   => esc_html__( 'Filter by', 'triply' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'title'  => esc_html__( 'Title', 'triply' ),
					'price'  => esc_html__( 'Price', 'triply' ),
					'rating' => esc_html__( 'Rating', 'triply' ),
				],
				'default' => 'title',
			]
		);

		$this->add_control(
			'sort_by',
			[
				'label'   => esc_html__( 'Sort by', 'triply' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'asc'  => esc_html__( 'ASC', 'triply' ),
					'desc' => esc_html__( 'DESC', 'triply' ),
				],
				'default' => 'asc',
			]
		);

		$this->add_control(
			'show_count',
			array(
				'label'     => esc_html__( 'Show count result', 'triply' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => 'Show',
				'label_off' => 'Hide'
			)
		);

		$this->add_control(
			'search_view',
			[
				'label'   => esc_html__( 'Layout', 'triply' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'triply' ),
					'list' => esc_html__( 'List', 'triply' ),
				],
			]
		);

		$this->add_responsive_control(
			'column',
			[
				'label'           => esc_html__( 'Columns', 'triply' ),
				'type'            => \Elementor\Controls_Manager::SELECT,
				'desktop_default' => 2,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'options'         => [ 1 => 1, 2 => 2, 3 => 3, 4 => 4 ],
				'condition'       => [
					'search_view' => 'grid'
				]
			]
		);


		$this->add_control(
			'style',
			[
				'label'     => esc_html__( 'Style', 'triply' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => [
					1 => esc_html__( 'Style 1', 'triply' ),
					2 => esc_html__( 'Style 2', 'triply' ),
					3 => esc_html__( 'Style 3', 'triply' ),
					5 => esc_html__( 'Style 4', 'triply' ),
				],
				'condition' => [
					'search_view' => 'grid'
				]
			]
		);


		$this->add_control(
			'classes',
			array(
				'label'       => esc_html__( 'Extra class name', 'triply' ),
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'triply' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'input_type'  => 'text',
				'placeholder' => '',
			)
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

		global $post;

		$settings = $this->get_controls_settings();

		$this->add_render_attribute( 'wrapper', 'class', 'ba-items-style-' . $settings['style'] );

		if ( $settings['search_view'] == 'list' ) {
			$this->add_render_attribute( 'wrapper', 'data-elementor-columns', 1 );
			$this->add_render_attribute( 'wrapper', 'data-elementor-columns-tablet', 1 );
			$this->add_render_attribute( 'wrapper', 'data-elementor-columns-mobile', 1 );
			$settings['style'] = 4;
		} else {

			if ( ! empty( $settings['column'] ) ) {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns', $settings['column'] );
			} else {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns', 1 );
			}

			if ( ! empty( $settings['column_tablet'] ) ) {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns-tablet', $settings['column_tablet'] );
			} else {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns-tablet', 1 );
			}

			if ( ! empty( $settings['column_mobile'] ) ) {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns-mobile', $settings['column_mobile'] );
			} else {
				$this->add_render_attribute( 'wrapper', 'data-elementor-columns-mobile', 1 );
			}

		}


		$this->add_render_attribute( 'block_inner', 'class', 'babe_shortcode_block_inner' );
		$this->add_render_attribute( 'block_inner', 'class', $settings['classes'] );

		if ( in_the_loop() && is_main_query() || \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$results = $this->get_search_result( $settings );

			if ( $results ) { ?>

                <div class="babe_search_results">
                    <div class="babe_search_results_filters">
						<?php if ( isset( $results['posts_count'] ) && ! empty( $results['posts_count'] ) && $settings['show_count'] ) { ?>
                            <div class="count-posts">
                                <strong class="count"><?php echo esc_html( $results['posts_count'] ); ?></strong>&nbsp;
								<?php echo 1 < $results['posts_count'] ? esc_html__( 'Tours', 'triply' ) : esc_html__( 'Tour', 'triply' ); ?>
                            </div>
						<?php } ?>

						<?php if ( isset( $results['sort_by_filter'] ) && ! empty( $results['sort_by_filter'] ) ) {

							printf( '<div class="filter-sort"><span>' . esc_html__( 'Sort by', 'triply' ) . '</span>%s</div>', $results['sort_by_filter'] );
						} ?>
                    </div>

                    <div <?php echo triply_elementor_get_render_attribute_string( 'wrapper', $this ); ?>>
                        <div class="babe_shortcode_block sc_all_items">
                            <div class="babe_shortcode_block_bg_inner">
                                <div <?php echo triply_elementor_get_render_attribute_string( 'block_inner', $this ); ?>>
									<?php if ( isset( $results['output'] ) && ! empty( $results['output'] ) ) {
										printf( '%s', $results['output'] );
									} ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php if ( isset( $results['page'] ) && ! empty( $results['page'] ) ) {
						printf( '%s', $results['page'] );
					} ?>


                    <div id="babe_search_result_refresh">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>

                </div>
				<?php

			} else {
				echo '<h2 class="empty-list">' . esc_html__( 'No available tours', 'triply' ) . '</h2>';
				echo '<p>' . esc_html__( 'It seems we can’t find what you’re looking for. ', 'triply' ) . '</p>';
			}
		}


	}



	////////////////////////////

	/**
	 * Get search result
	 *
	 * @param string $view
	 *
	 * @return mixed
	 */
	public function get_search_result( $settings ) {

		$output  = '';
		$results = [];

		$args = wp_parse_args( $_GET, array(
			'request_search_results' => '',
			'date_from'              => '', //// d/m/Y or m/d/Y format
			'date_to'                => '',
			'time_from'              => '00:00',
			'time_to'                => '00:00',
			'categories'             => [], //// term_taxonomy_ids from categories
			'terms'                  => [], //// term_taxonomy_ids from custom taxonomies in $taxonomies_list
			'keyword'                => '',
			'posts_per_page'         => $settings['per_page'],
			'sort'                   => $settings['sort'],
			'sort_by'                => $settings['sort_by'],
			'return_total_count'     => 1
		) );

		if ( isset( $_GET['search_results_sort_by'] ) ) {
			$args['search_results_sort_by'] = $_GET['search_results_sort_by'];
		} else {
			$args['search_results_sort_by'] = $args['sort'] . '_' . $args['sort_by'];
		}


		if ( isset( $_GET['guests'] ) ) {
			$guests         = array_map( 'absint', $_GET['guests'] );
			$args['guests'] = array_sum( $guests );
		}

		$args = $this->get_rating_book_option( $args );

		// sanitize args
		foreach ( $args as $arg_key => $arg_value ) {
			$args[ sanitize_title( $arg_key ) ] = is_array( $arg_value ) ? array_map( 'absint', $arg_value ) : sanitize_text_field( $arg_value );
		}


		$args = apply_filters( 'babe_search_result_args', $args );

		$args = BABE_Post_types::search_filter_to_get_posts_args( $args );

		$posts       = BABE_Post_types::get_posts( $args );
		$posts_pages = BABE_Post_types::$get_posts_pages;


		foreach ( $posts as $post ) {
			ob_start();
			include get_theme_file_path( 'template-parts/booking/block/item-block-' . $settings['style'] . '.php' );
			$output .= ob_get_clean();
		} /// end foreach $posts

		if ( $output ) {
			$results['output']         = $output;
			$results['sort_by_filter'] = BABE_html::input_select_field_with_order( 'sr_sort_by', '', BABE_Post_types::get_search_filter_sort_by_args(), $args['search_results_sort_by'] );
			$results['page']           = BABE_Functions::pager( $posts_pages );
			$results['posts_count']    = BABE_Post_types::$get_posts_count;
		}
		//echo '<pre>'.print_r( $results['sort_by_filter'], 1).'</pre>';

		return $results;
	}

	public function get_rating_book_option( $args = [] ) {
		$options = get_option( 'triply_rating_book' );
		if ( isset( $_GET['rating_value'] ) && isset( $options ) && ! empty( $options ) ) {
			foreach ( $options as $key => $option ) {
				if ( $_GET['rating_value'] == $key ) {
					if ( empty( $option ) ) {
						$args['post__in'] = array( 0 );
					} else {
						$args['post__in'] = $option;
					}

					return $args;
				}else {
                    $args['post__in'] = array( 0 );
                }
			}
		}

		return $args;

	}

}

