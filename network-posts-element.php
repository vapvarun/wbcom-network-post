<?php
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Icons_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElementorPro\Modules\Posts\Traits\Button_Widget_Trait;
use ElementorPro\Plugin;
use ElementorPro\Modules\Posts\Widgets\Posts_Base;
use ElementorPro\Core\Utils as ProUtils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Network_Posts_Widget extends Widget_Base {
	
	
	/**
	 * Construct.
	 *
	 * @param  array  $data Data.
	 * @param  string $args Args.
	 * @return void
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$script_path = plugin_dir_path( __FILE__ ) . 'dist/network-posts-elementor-handler.js';
		$style_path  = plugin_dir_path( __FILE__ ) . 'css/widget-posts.min.css';

		wp_register_script(
			'network-posts-elementor-handler',
			plugins_url( 'dist/network-posts-elementor-handler.js', __FILE__ ),
			array( 'jquery', 'elementor-frontend' ),
			file_exists( $script_path ) ? filemtime( $script_path ) : '1.0.2',
			true
		);
		wp_register_style(
			'network-posts-widget',
			plugins_url( 'css/widget-posts.min.css', __FILE__ ),
			array(),
			file_exists( $style_path ) ? filemtime( $style_path ) : '1.0.0'
		);
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget script dependencies.
	 */
	public function get_script_depends(): array {
		return array( 'network-posts-elementor-handler' );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'network-posts-widget' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return array( 'network', 'posts', 'multisite', 'global posts', 'blog posts', 'network posts' );
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url(): string {
		return 'https://agaveplugins.com/tutorials/plugins/multisite/network-posts-extended/';
	}

	/**
	 * Check if this widget has dynamic content.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return bool Whether the widget has dynamic content.
	 */
	protected function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Whether the widget has a custom inner wrapper.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return bool Whether the widget has inner wrapper.
	 */
	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}


	/**
	 * Get widget name.
	 *
	 * Retrieve network posts widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'network_posts_widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve network posts widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__('Network Posts', 'network-posts-extended' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve network posts widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-post-list';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the network posts widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return [ 'network-posts-widgets' ];
	}

	/**
	 * Register network posts widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls(): void {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'network-posts-extended' ),
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
			]
		);
		
		
		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'network-posts-extended' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
			]
		);
		
		$this->add_control(
			'masonry',
			[
				'label' => esc_html__( 'Masonry', 'network-posts-extended' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'network-posts-extended' ),
				'label_on' => esc_html__( 'On', 'network-posts-extended' ),
				'condition' => [
					'columns!' => '1',
					
				],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Image', 'network-posts-extended' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'network-posts-extended' ),
				'label_off' => esc_html__( 'Hide', 'network-posts-extended' ),
				'default' => 'yes',
			]
		);
		
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size',
				'default' => 'medium',
				'exclude' => [ 'custom' ],				
				'prefix_class' => 'elementor-posts--thumbnail-size-',
				'condition' => [
					'show_image' => 'yes',					
				],
			]
		);
		
		$this->add_responsive_control(
			'item_ratio',
			[
				'label' => esc_html__( 'Image Ratio', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.66,
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .netsposts-items .elementor-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',					
				],	
			'condition' => [
					'show_image' => 'yes',	
					'masonry' => '',
				],				
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',				
			]
		);
		
		$this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'network-posts-extended' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'network-posts-extended' ),
				'label_off' => esc_html__( 'Hide', 'network-posts-extended' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'network-posts-extended' ),
				'type' => Controls_Manager::NUMBER,
				/** This filter is documented in wp-includes/formatting.php */
				'default' => apply_filters( 'excerpt_length', 25 ),
				'condition' => [
					'show_excerpt' => 'yes',					
				],
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label' => esc_html__( 'Read More', 'network-posts-extended' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'network-posts-extended' ),
				'label_off' => esc_html__( 'Hide', 'network-posts-extended' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'network-posts-extended' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Read More »', 'network-posts-extended' ),
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'open_new_tab',
			[
				'label' => esc_html__( 'Open in new window', 'network-posts-extended' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'network-posts-extended' ),
				'label_off' => esc_html__( 'No', 'network-posts-extended' ),
				'default' => 'no',
				'render_type' => 'none',
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();
		/* Query */
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'network-posts-extended' ),
			]
		);		
		
		$this->start_controls_tabs(
			'query_tabs'
		);
		
		$blogs 		= get_network_posts_blogs();
		$blogs_info	= [];
		foreach( $blogs as $blog) {
			$current_blog_details 	= get_blog_details( array( 'blog_id' => $blog ) );
			$blogs_info[$blog] 		= $current_blog_details->blogname;
		}
		
		$this->start_controls_tab(
			'query_include_tabs',
			[
				'label' => esc_html__( 'Include ', 'textdomain' ),
			]
		);
		$this->add_control(
			'include_blog',
			[
				'label' => esc_html__( 'Include Blog', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $blogs_info,				
				'label_block' => true,				
			]
		);
		

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Exclude', 'textdomain' ),
			]
		);

		
		$this->add_control(
			'exclude_blog',
			[
				'label' => esc_html__( 'Exclude Blog', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $blogs_info,				
				'label_block' => true,				
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		
		
		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'network-posts-extended' ),
					'title' => esc_html__( 'Title', 'network-posts-extended' ),					
					'rand' => esc_html__( 'Random', 'network-posts-extended' ),					
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => esc_html__( 'ASC', 'network-posts-extended' ),
					'desc' => esc_html__( 'DESC', 'network-posts-extended' ),
				],
			]
		);
		$this->end_controls_section();
		/* Query Section End */
		
		/* Pagination */
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination', 'network-posts-extended' ),
			]
		);
		
		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Pagination', 'network-posts-extended' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [	'' => esc_html__( 'None', 'network-posts-extended' ),
								'numbers' => esc_html__( 'Numbers', 'network-posts-extended' ),								
								'numbers_and_prev_next' => esc_html__( 'Numbers', 'network-posts-extended' ) . ' + ' . esc_html__( 'Previous/Next', 'network-posts-extended' ),
								'load_more_on_click' => esc_html__( 'Load More', 'network-posts-extended' ),
								
							],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'pagination_prev_label',
			[
				'label' => esc_html__( 'Previous Label', 'network-posts-extended' ),
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( '&laquo; Previous', 'network-posts-extended' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_next_label',
			[
				'label' => esc_html__( 'Next Label', 'network-posts-extended' ),
				'default' => esc_html__( 'Next &raquo;', 'network-posts-extended' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
		

	$this->add_control(
		'load_more_button_text',
		[
			'label' => esc_html__( 'Load More Text', 'network-posts-extended' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Load More', 'network-posts-extended' ),
			'condition' => [
				'pagination_type' => 'load_more_on_click',
			],
			'dynamic' => [
				'active' => true,
			],
		]
	);

	$this->add_control(
		'load_more_loading_text',
		[
			'label' => esc_html__( 'Loading Text', 'network-posts-extended' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Loading...', 'network-posts-extended' ),
			'condition' => [
				'pagination_type' => 'load_more_on_click',
			],
			'dynamic' => [
				'active' => true,
			],
		]
	);

	$this->add_control(
		'load_more_no_more_text',
		[
			'label' => esc_html__( 'No More Posts Text', 'network-posts-extended' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'No More Posts', 'network-posts-extended' ),
			'condition' => [
				'pagination_type' => 'load_more_on_click',
			],
			'dynamic' => [
				'active' => true,
			],
		]
	);
		
		$this->add_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'network-posts-extended' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'pagination_type!' => [
						'load_more_on_click',
						'load_more_infinite_scroll',
						'',
					],
				],
			]
		);
		
		$this->end_controls_section();
		/* Pagination Section End */
		
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Layout', 'network-posts-extended' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'network-posts-extended' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'network-posts-extended' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-posts--align-',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_design_box',
			[
				'label' => esc_html__( 'Box', 'network-posts-extended' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_border_width',
			[
				'label' => esc_html__( 'Border Width', 'network-posts-extended' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .netsposts-content' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .netsposts-content' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'network-posts-extended' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .netsposts-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Content Padding', 'network-posts-extended' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'after',
			]
		);

		$this->start_controls_tabs( 'bg_effects_tabs' );

		$this->start_controls_tab( 'classic_style_normal',
			[
				'label' => esc_html__( 'Normal', 'network-posts-extended' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .netsposts-content',
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-content' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'classic_style_hover',
			[
				'label' => esc_html__( 'Hover', 'network-posts-extended' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .netsposts-content:hover',
			]
		);

		$this->add_control(
			'box_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-content:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-content:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
		/* Image Layout Section */
		$this->start_controls_section(
			'section_design_image',
			[
				'label' => esc_html__( 'Image', 'network-posts-extended' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'img_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'network-posts-extended' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [					
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 20,
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'network-posts-extended' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .elementor-post__thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'network-posts-extended' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .netsposts-content:hover .elementor-post__thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
		/* Content Layout  */
		$this->start_controls_section(
			'section_design_content',
			[
				'label' => esc_html__( 'Content', 'network-posts-extended' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => esc_html__( 'Title', 'network-posts-extended' ),
				'type' => Controls_Manager::HEADING,				
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a',				
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elementor-post__title',
			]
		);
		
		$this->add_control(
			'heading_meta_style',
			[
				'label' => esc_html__( 'Meta', 'network-posts-extended' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-source' => 'color: {{VALUE}};',
				],				
			]
		);
		
		/*
		$this->add_control(
			'meta_separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .netsposts-source span:before' => 'color: {{VALUE}};',
				],				
			]
		);
		*/

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .netsposts-source',				
			]
		);

		$this->add_control(
			'heading_excerpt_style',
			[
				'label' => esc_html__( 'Excerpt', 'network-posts-extended' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__excerpt p, {{WRAPPER}} .elementor-post__excerpt' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-post__excerpt p, {{WRAPPER}} .elementor-post__excerpt',
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		

		$this->add_control(
			'heading_readmore_style',
			[
				'label' => esc_html__( 'Read More', 'network-posts-extended' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .netsposts-read-more-link' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_read_more'  => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				// The 'a' selector is added for specificity, for when this control's selector is used in globals CSS.
				'selector' => '{{WRAPPER}} a.netsposts-read-more-link',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => [
					'show_read_more'  => 'yes',
				],
			]
		);
		

		$this->end_controls_section();
		
		// Pagination style controls for prev/next and numbers pagination.
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'network-posts-extended' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type!' => [						
						'',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .elementor-pagination',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_control(
			'pagination_color_heading',
			[
				'label' => esc_html__( 'Colors', 'network-posts-extended' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			[
				'label' => esc_html__( 'Normal', 'network-posts-extended' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			[
				'label' => esc_html__( 'Hover', 'network-posts-extended' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			[
				'label' => esc_html__( 'Active', 'network-posts-extended' ),
			]
		);

		$this->add_control(
			'pagination_active_color',
			[
				'label' => esc_html__( 'Color', 'network-posts-extended' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => esc_html__( 'Space Between', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing_top',
			[
				'label' => esc_html__( 'Spacing', 'network-posts-extended' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render network posts widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render(): void {	
		$settings = $this->get_settings_for_display();
		
		$shortcode_atts['post_type'] = 'post';		
		$shortcode_atts['list'] 	 = $settings['posts_per_page'];
		
		/* Include Blog */
		if ( isset($settings['include_blog']) && !empty($settings['include_blog'])) {
			$shortcode_atts['include_blog'] = join(',',$settings['include_blog']);
		}
		
		/* Include Blog */
		if ( isset($settings['exclude_blog']) && !empty($settings['exclude_blog'])) {
			$shortcode_atts['exclude_blog'] = join(',',$settings['exclude_blog']);
		}
		
		$shortcode_atts['include_link_title'] = true;
		
		/* Show Image */
		if ( isset($settings['show_image']) && $settings['show_image'] != '') {
			$shortcode_atts['thumbnail'] = 'true';
			$shortcode_atts['size'] 	 = $settings['thumbnail_size_size'];
		}
		
		if ( isset($settings['title_tag']) && $settings['title_tag'] != '' ) {
			$shortcode_atts['wrap_title_start'] = $settings['title_tag'];
			$shortcode_atts['wrap_title_end'] = $settings['title_tag'];
		}
		/* Excerpt Length*/
		if ( isset($settings['show_excerpt']) && $settings['show_excerpt'] == '' ) {
			$shortcode_atts['show_excerpt'] = 'false';
		}
		if ( isset($settings['excerpt_length']) && isset($settings['show_excerpt'])  ) {
			$shortcode_atts['excerpt_length'] = $settings['excerpt_length'];
		}
		
		/* read_more_text*/

		if ( isset($settings['read_more_text']) && isset($settings['show_read_more']) && $settings['show_read_more'] != ''   ) {
			$shortcode_atts['read_more_text'] = $settings['read_more_text'];
		} else {
			$shortcode_atts['exclude_read_more_link'] = true;
		}
		
		/* open_new_tab */
		if ( isset($settings['open_new_tab'])) {
			$shortcode_atts['link_open_new_window'] = 'true';
		}
		
		/* Order By Settings*/
		$shortcode_atts['random'] = false;
		if ( isset($settings['orderby']) &&  $settings['orderby'] == 'date') {
			$shortcode_atts['order_post_by'] = 'date_order '. $settings['order'];
		}
		if ( isset($settings['orderby']) &&  $settings['orderby'] == 'title') {
			$shortcode_atts['order_post_by'] = 'alphabetical_order '. $settings['order'];
		}
		if ( isset($settings['orderby']) &&  $settings['orderby'] == 'rand') {
			$shortcode_atts['random'] = true;
		}
		
		/* Pagination Settings */
		if ( isset($settings['pagination_type']) && $settings['pagination_type'] != '' ) {
			if ( $settings['pagination_type'] == 'load_more_on_click' ) {
				// Don't set paginate for Load More - JavaScript adds the button and handles AJAX
			} else {
				$shortcode_atts['paginate'] 	= true;
				$shortcode_atts['prev_next'] 	= false;
				if ( $settings['pagination_type'] == 'numbers_and_prev_next' ) {
					$shortcode_atts['prev_next'] 	= true;
					$shortcode_atts['prev'] 		= $settings['pagination_prev_label'];
					$shortcode_atts['next'] 		= $settings['pagination_next_label'];
				}
			}
		}
		
		$netsposts_items_class    = [];
		$netsposts_items_class[] = 'elementor-posts-container';
		$netsposts_items_class[] = 'elementor-posts';
		$netsposts_items_class[] = 'elementor-posts--skin-classic';
		$netsposts_items_class[] = 'elementor-grid';
		
		
		if ( isset($settings['masonry']) && $settings['masonry']== 'yes' ) {
			$netsposts_items_class[] = 'elementor-posts-masonry';
		} else {
			$netsposts_items_class[] = 'elementor-has-item-ratio';
		}
		
		$shortcode_atts['netsposts_items_class'] = join(' ', $netsposts_items_class);
		$shortcode_atts_string = '';
		foreach( $shortcode_atts as $atts_key=>$atts_value ){
			$shortcode_atts_string .= ' ' . $atts_key. "='". $atts_value ."'";
		}

		echo do_shortcode( "[netsposts {$shortcode_atts_string}]");

	}

}

/**
 * Register Network Posts Widget.
 *
 * Register the widget with Elementor.
 *
 * @since 1.0.0
 */
\Elementor\Plugin::instance()->widgets_manager->register( new Network_Posts_Widget() );