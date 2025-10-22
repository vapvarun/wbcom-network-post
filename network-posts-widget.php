<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * BP Business Hours Widget
 *
 * @since 1.0.0
 */
class Netwrok_Posts_Widget extends WP_Widget {


	public function __construct() {
		$widget_ops = array(
			'description'                 => esc_html__( 'Network Posts WPMU widget', 'network-posts-extended' ),
			'classname'                   => 'network_posts_wpmu network_posts_wpmu_widget widget',
			'customize_selective_refresh' => true,
		);

		parent::__construct( false, _x( 'Network Posts', 'widget name', 'network-posts-extended' ), $widget_ops );
	}



	/**
	 * Extends our front-end output method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Array of arguments for the widget.
	 * @param array $instance Widget instance data.
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		$title 			= strip_tags( $instance['title'] );
		/*
		$posts_per_page = strip_tags( $instance['posts_per_page'] );
		$show_image 	= strip_tags( $instance['show_image'] );
		$image_size 	= strip_tags( $instance['image_size'] );
		$title_tag 		= strip_tags( $instance['title_tag'] );
		$show_excerpt 	= strip_tags( $instance['show_excerpt'] );
		$excerpt_length = strip_tags( $instance['excerpt_length'] );
		$show_read_more = strip_tags( $instance['show_read_more'] );
		$read_more_text = strip_tags( $instance['read_more_text'] );
		$open_new_tab 	= strip_tags( $instance['open_new_tab'] );
		$orderby 		= strip_tags( $instance['orderby'] );
		$order 			= strip_tags( $instance['order'] );	
		*/

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $before_title . esc_html( $title ) . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$shortcode_atts['post_type'] = 'post';		
		$shortcode_atts['list'] 	 = $instance['posts_per_page'];
		
		/* Include Blog */
		if ( isset($instance['include_blog']) && $instance['include_blog'] != '' ) {
			$shortcode_atts['include_blog'] = $instance['include_blog'];
		}		
		
		
		$shortcode_atts['include_link_title'] = true;
		
		/* Show Image */
		if ( isset($instance['show_image']) && $instance['show_image'] != '') {
			$shortcode_atts['thumbnail'] = 'true';
			$shortcode_atts['size'] 	 = $instance['image_size'];
		}
		
		if ( isset($instance['title_tag']) && $instance['title_tag'] != '' ) {
			$shortcode_atts['wrap_title_start'] = $instance['title_tag'];
			$shortcode_atts['wrap_title_end'] = $instance['title_tag'];
		}
		/* Excerpt Length*/
		if ( isset($instance['show_excerpt']) && $instance['show_excerpt'] == '') {
			$shortcode_atts['show_excerpt'] = 'false';
		}
		if ( isset($instance['excerpt_length']) && isset($instance['show_excerpt'])  ) {
			$shortcode_atts['excerpt_length'] = $instance['excerpt_length'];
		}
		
		/* read_more_text*/
		if ( isset($instance['read_more_text']) && isset($instance['show_read_more']) && $instance['show_read_more'] != ''   ) {
			$shortcode_atts['read_more_text'] = $instance['read_more_text'];
		} else {
			$shortcode_atts['exclude_read_more_link'] = true;
		}
		
		/* open_new_tab */
		if ( isset($instance['open_new_tab'])) {
			$shortcode_atts['link_open_new_window'] = 'true';
		}
		
		/* Order By instance*/
		$shortcode_atts['random'] = false;
		if ( isset($instance['orderby']) &&  $instance['orderby'] == 'date') {
			$shortcode_atts['order_post_by'] = 'date_order '. $instance['order'];
		}
		if ( isset($instance['orderby']) &&  $instance['orderby'] == 'title') {
			$shortcode_atts['order_post_by'] = 'alphabetical_order '. $instance['order'];
		}
		if ( isset($instance['orderby']) &&  $instance['orderby'] == 'rand') {
			$shortcode_atts['random'] = true;
		}		
		
		
		$shortcode_atts['netsposts_items_class'] = 'elementor-posts-container elementor-posts elementor-posts--skin-classic elementor-grid elementor-has-item-ratio';
		$shortcode_atts_string = '';
		foreach( $shortcode_atts as $atts_key=>$atts_value ){
			$shortcode_atts_string .= ' ' . $atts_key. "='". $atts_value ."'";
		}
		
		echo do_shortcode( "[netsposts {$shortcode_atts_string}]");

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Extends our update method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New instance data.
	 * @param array $old_instance Original instance data.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] 			= strip_tags( $new_instance['title'] );		
		$instance['posts_per_page'] = strip_tags( $new_instance['posts_per_page'] );
		$instance['show_image'] 	= (isset($new_instance['show_image'])) ? strip_tags( $new_instance['show_image'] ) : '';
		$instance['image_size'] 	= strip_tags( $new_instance['image_size'] );
		$instance['title_tag'] 		= strip_tags( $new_instance['title_tag'] );
		$instance['show_excerpt'] 	= (isset($new_instance['show_excerpt'])) ? strip_tags( $new_instance['show_excerpt'] ) : '';
		$instance['excerpt_length'] = strip_tags( $new_instance['excerpt_length'] );
		$instance['show_read_more'] = (isset($new_instance['show_read_more'])) ? strip_tags( $new_instance['show_read_more'] ) : '';
		$instance['read_more_text'] = strip_tags( $new_instance['read_more_text'] );
		$instance['open_new_tab'] 	= (isset($new_instance['open_new_tab'])) ? strip_tags( $new_instance['open_new_tab'] ) : '';
		$instance['orderby'] 		= strip_tags( $new_instance['orderby'] );
		$instance['order'] 			= strip_tags( $new_instance['order'] );		
		$instance['include_blog'] 	= strip_tags( $new_instance['include_blog'] );		
		
		return $instance;
	}
	public function get_image_sizes() {
		$wp_image_sizes = $this->get_all_image_sizes();
		$image_sizes = [];

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}

		$image_sizes['full'] = esc_html_x( 'Full', 'Image Size Control', 'elementor' );		

		return $image_sizes;
	}
	
	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = [
				'width' => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop' => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		/** This filter is documented in wp-admin/includes/media.php */
		return apply_filters( 'image_size_names_choose', $image_sizes );
	}

	/**
	 * Extends our form method.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current instance.
	 * @return mixed
	 */
	public function form( $instance ) {

		$defaults = array(
			'title' 			=> esc_html__( 'Network Posts', 'network-posts-extended' ),
			'posts_per_page' 	=> '5',
			'show_image' 		=> 'yes',
			'image_size' 		=> 'medium',
			'title_tag' 		=> 'h4',
			'show_excerpt' 		=> 'yes',
			'excerpt_length' 	=> '25',
			'show_read_more' 	=> 'yes',
			'read_more_text' 	=> esc_html__( 'Read More »', 'elementor-pro' ),
			'open_new_tab' 		=> '',
			'orderby' 			=> 'date',
			'order' 			=> 'desc',
			'include_blog' 		=> '',			
		);

		$instance 		= wp_parse_args( (array) $instance, $defaults );		
		
		$title 			= strip_tags( $instance['title'] );
		$posts_per_page = strip_tags( $instance['posts_per_page'] );
		$show_image 	= strip_tags( $instance['show_image'] );
		$image_size 	= strip_tags( $instance['image_size'] );
		$title_tag 		= strip_tags( $instance['title_tag'] );
		$show_excerpt 	= strip_tags( $instance['show_excerpt'] );
		$excerpt_length = strip_tags( $instance['excerpt_length'] );
		$show_read_more = strip_tags( $instance['show_read_more'] );
		$read_more_text = strip_tags( $instance['read_more_text'] );
		$open_new_tab 	= strip_tags( $instance['open_new_tab'] );
		$orderby 		= strip_tags( $instance['orderby'] );
		$order 			= strip_tags( $instance['order'] );		
		$include_blog 	= strip_tags( $instance['include_blog'] );
		
		
		
		$_image_size = $this->get_image_sizes();
		$_title_tags = [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				];
				
		$blogs 		= get_network_posts_blogs();
		$blogs_info	= [];
		foreach( $blogs as $blog) {
			$current_blog_details 	= get_blog_details( array( 'blog_id' => $blog ) );
			$blogs_info[$blog] 		= $current_blog_details->blogname;
		}
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Posts Per Page:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" style="width: 100%" min="1"/></label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show Image:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="yes" <?php checked($show_image,'yes');?>/></label>
		</p>	
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php esc_html_e( 'Image Size:', 'network-posts-extended' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>">
				<?php foreach($_image_size as $key=>$val):?>
				<option value="<?php echo esc_attr($key);?>" <?php selected($key, $image_size)?>><?php echo esc_html($val);?></option>
				<?php endforeach;?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_tag' ) ); ?>"><?php esc_html_e( 'Title HTML Tag:', 'network-posts-extended' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'title_tag' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title_tag' ) ); ?>">
				<?php foreach($_title_tags as $key=>$val):?>
				<option value="<?php echo esc_attr($key);?>" <?php selected($key, $title_tag)?>><?php echo esc_html($val);?></option>
				<?php endforeach;?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Excerpt:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" type="checkbox" value="yes" <?php checked($show_excerpt,'yes');?>/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" style="width: 100%" min="1"/></label>
		</p>
		
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_read_more' ) ); ?>"><?php esc_html_e( 'Read More:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_read_more' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_read_more' ) ); ?>" type="checkbox" value="yes" <?php checked($show_read_more,'yes');?>/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>"><?php esc_html_e( 'Read More Text:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $read_more_text ); ?>" style="width: 100%"/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'open_new_tab' ) ); ?>"><?php esc_html_e( 'Open in new window:', 'network-posts-extended' ); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'open_new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'open_new_tab' ) ); ?>" type="checkbox" value="yes" <?php checked($open_new_tab,'yes');?>/></label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'network-posts-extended' ); ?> </label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<option value="date" <?php selected($orderby, 'date');?>><?php esc_html_e('Date');?></option>
				<option value="title" <?php selected($orderby, 'title');?>><?php esc_html_e('Title');?></option>
				<option value="rand" <?php selected($orderby, 'rand');?>><?php esc_html_e('Random');?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'network-posts-extended' ); ?> </label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
				<option value="asc" <?php selected($order, 'asc');?>><?php esc_html_e('ASC');?></option>
				<option value="desc" <?php selected($order, 'desc');?>><?php esc_html_e('DESC');?></option>				
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'include_blog' ) ); ?>"><?php esc_html_e( 'Include Blog:', 'network-posts-extended' ); ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include_blog' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include_blog' ) ); ?>" type="text" value="<?php echo esc_attr( $include_blog ); ?>" style="width: 100%" /></label>
			</label>			
			<?php esc_html_e('you can include blogs that are listed by blog_id and separated by commas.');?>
		</p>
		
		
		<?php
	}
}
add_action(
	'widgets_init',
	function() {
			register_widget( 'Netwrok_Posts_Widget' );
	}
);
