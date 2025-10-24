<?php
############  SETUP  ####################
require_once 'autoload.php';

use \NetworkPosts\Components\NetsPostsMultisite;
use NetworkPosts\Components\NetsPostsTemplateRenderer;
use NetworkPosts\Components\Resizer;
use NetworkPosts\Components\NetsPostsThumbnailManager;
use NetworkPosts\Components\Settings\NetsPostsBlogSettingsPage;
use NetworkPosts\Components\Settings\NetsPostsNetworkSettings;
use NetworkPosts\Components\Settings\NetsPostsNetworkSettingsPage;

define( 'DEFAULT_THUMBNAIL_WIDTH', 300 );
define( 'BASE_JS_PATH', plugins_url( '/network-posts-extended/js' ) );
define( 'POST_VIEWS_PATH', plugin_dir_path( __FILE__ ) . 'views/post' );
define( 'NETSPOSTS_VIEW_PATH', plugin_dir_path( __FILE__ ) . 'views' );

if( !defined( 'NETSPOSTS_TEST' ) ) {

	add_action('admin_init', 'netsposts_init_thumbnails_resizer');
	add_action( 'admin_init', array( NetsPostsBlogSettingsPage::class, 'register_settings' ) );
	add_action( 'wpmu_new_blog', array( NetsPostsMultisite::class, 'activate_new_blog_plugin' ) );

	NetsPostsTemplateRenderer::init( NETSPOSTS_VIEW_PATH );
	add_action( 'init', array( NetsPostsMultisite::class, 'multisite_deactivate' ) );
	add_action( 'init', array(NetsPostsThumbnailManager::class, 'initialize') );
	add_action( 'wp_enqueue_scripts', 'netsposts_add_js' );
	add_action( "plugins_loaded", "netsposts_load_translations" );

	add_action( 'admin_menu', array( NetsPostsBlogSettingsPage::class, 'add_toolpage' ) );
	add_action( 'admin_enqueue_scripts', 'netsposts_init_settings_page' );
	add_action( 'network_admin_menu', 'netsposts_add_network_settings' );
	add_action( 'update_option', 'netsposts_save_for_blog' );

	add_shortcode( 'netsposts', 'netsposts_shortcode' );

	// AJAX handlers for Load More
	add_action( 'wp_ajax_netsposts_load_more', 'netsposts_ajax_load_more' );
	add_action( 'wp_ajax_nopriv_netsposts_load_more', 'netsposts_ajax_load_more' );
}

$plugin = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$plugin", array(
    NetsPostsBlogSettingsPage::class,
    'plugin_settings_link'
) );


function netsposts_add_network_settings() {
    if ( is_super_admin() ) {
        Resizer\NetsPostsResizerSettingsPage::add_settings_page();
        NetsPostsNetworkSettingsPage::add_settings_page();
    }
}

function netsposts_add_js(){
	wp_enqueue_script( 'netsposts-js', plugins_url( 'dist/netsposts-public.js', __FILE__ ), array( 'jquery' ), '1.0.2', true );

	// Enqueue Masonry handler for shortcode usage
	wp_enqueue_script( 'network-posts-elementor-handler', plugins_url( 'dist/network-posts-elementor-handler.js', __FILE__ ), array( 'jquery' ), '1.0.2', true );

	// Localize script for AJAX
	wp_localize_script( 'netsposts-js', 'netspostsAjax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'netsposts_load_more' )
	) );
}

function netsposts_load_translations() {
    register_uninstall_hook( __FILE__, 'net_shared_posts_uninstall' );
    if( get_option( 'load_plugin_styles', 1 ) ) {
        add_action( 'wp_enqueue_scripts', 'netposts_add_stylesheet' );
    }
    load_plugin_textdomain( 'netsposts', false, basename( dirname( __FILE__ ) ) . '/language' );
}

function netsposts_init_thumbnails_resizer() {
    global $wpdb;
    $is_resizing_allowed = Resizer\NetsPostsThumbnailBlogSettings::is_allowed_for_blog( get_current_blog_id() );
    $is_global_resizing  = Resizer\NetsPostsThumbnailBlogSettings::is_global( get_current_blog_id() );
    Resizer\NetsPostsImageResizerFacade::getInstance( $is_resizing_allowed, $is_global_resizing );
}

function netposts_add_stylesheet() {
    wp_register_style( 'netsposts_css', plugins_url( '/css/net_posts_extended.css', __FILE__ ), array(), '1.0.0' );
    wp_enqueue_style( 'netsposts_css' );

	wp_register_style( 'netsposts_star_css', plugins_url( '/css/fontawesome-stars.css', __FILE__ ) );
	wp_enqueue_style( 'netsposts_star_css' );
}

function netsposts_init_settings_page() {
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'netsposts_page' ) {
        wp_register_style( 'netsposts_admin_css', plugins_url( '/css/settings.css', __FILE__ ) );
        wp_enqueue_style( 'netsposts_admin_css' );
        Resizer\NetsPostsImageResizerFacade::getInstance()->register_scripts();
    }
}


function net_shared_posts_uninstall() {
    remove_shortcode( 'netsposts' );
}

function netsposts_save_for_blog() {
	$is_options_page = isset( $_REQUEST['option_page'] ) &&
	                   $_REQUEST['option_page'] === 'netsposts_page';
    if ( $is_options_page ) {
        $blog_id = get_current_blog_id();
        if( !isset( $_POST['strip_blog_excerpt_tags'] ) ){
        	NetsPostsNetworkSettings::allow_excerpt_tags_for_blog( $blog_id );
        }
        if ( isset( $_POST['blog_resizer_options'] ) ) {
            if ( isset( $_POST['allowed'] ) ) {
                Resizer\NetsPostsThumbnailBlogSettings::allow_for_blog( $blog_id );
            } else {
                Resizer\NetsPostsThumbnailBlogSettings::restrict_for_blog( $blog_id );
            }
            if ( isset( $_POST['global'] ) ) {
                Resizer\NetsPostsThumbnailBlogSettings::make_global( $blog_id );
            } else {
                Resizer\NetsPostsThumbnailBlogSettings::delete_from_global( $blog_id );
            }
        }
    }
}

function netsposts_url( $relative_url ){
	return plugins_url( $relative_url, __FILE__ );
}

add_action( 'wp_delete_site', 'netsposts_delete_blog_global_settings' );

function netsposts_delete_blog_global_settings( $blog ) {
	Resizer\NetsPostsThumbnailBlogSettings::restrict_for_blog( $blog->blog_id );
	Resizer\NetsPostsThumbnailBlogSettings::delete_from_global( $blog->blog_id );
}

/**
 * AJAX handler for Load More functionality
 */
function netsposts_ajax_load_more() {
	// Verify nonce
	check_ajax_referer( 'netsposts_load_more', 'nonce' );

	// Get parameters from AJAX request
	$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';
	$settings = isset( $_POST['settings'] ) ? json_decode( stripslashes( $_POST['settings'] ), true ) : array();

	if ( empty( $settings ) ) {
		wp_send_json_error( array( 'message' => 'Invalid settings' ) );
	}

	// Build shortcode attributes from settings
	$shortcode_atts = array();

	// Pagination
	$shortcode_atts['page'] = $page;

	// Posts per page
	if ( isset( $settings['posts_per_page'] ) ) {
		$shortcode_atts['list'] = $settings['posts_per_page'];
	}

	// Post type
	$shortcode_atts['post_type'] = 'post';

	// Blog filters
	if ( isset( $settings['include_blog'] ) && ! empty( $settings['include_blog'] ) ) {
		$shortcode_atts['include_blog'] = is_array( $settings['include_blog'] ) ? join( ',', $settings['include_blog'] ) : $settings['include_blog'];
	}

	if ( isset( $settings['exclude_blog'] ) && ! empty( $settings['exclude_blog'] ) ) {
		$shortcode_atts['exclude_blog'] = is_array( $settings['exclude_blog'] ) ? join( ',', $settings['exclude_blog'] ) : $settings['exclude_blog'];
	}

	// Other settings
	$shortcode_atts['include_link_title'] = true;

	if ( isset( $settings['show_image'] ) && $settings['show_image'] !== '' ) {
		$shortcode_atts['thumbnail'] = 'true';
		if ( isset( $settings['thumbnail_size_size'] ) ) {
			$shortcode_atts['size'] = $settings['thumbnail_size_size'];
		}
	}

	if ( isset( $settings['title_tag'] ) && $settings['title_tag'] !== '' ) {
		$shortcode_atts['wrap_title_start'] = $settings['title_tag'];
		$shortcode_atts['wrap_title_end'] = $settings['title_tag'];
	}

	if ( isset( $settings['show_excerpt'] ) && $settings['show_excerpt'] === '' ) {
		$shortcode_atts['show_excerpt'] = 'false';
	}

	if ( isset( $settings['excerpt_length'] ) && isset( $settings['show_excerpt'] ) ) {
		$shortcode_atts['excerpt_length'] = $settings['excerpt_length'];
	}

	if ( isset( $settings['read_more_text'] ) && $settings['read_more_text'] !== '' ) {
		$shortcode_atts['read_more_text'] = $settings['read_more_text'];
	}

	// Masonry
	if ( isset( $settings['masonry'] ) && $settings['masonry'] === 'yes' ) {
		$shortcode_atts['netsposts_items_class'] = 'elementor-grid elementor-posts-masonry';
	} else {
		$shortcode_atts['netsposts_items_class'] = 'elementor-grid';
	}

	// Columns
	if ( isset( $settings['columns'] ) ) {
		$shortcode_atts['netsposts_items_class'] .= ' elementor-grid-' . $settings['columns'];
	}
	if ( isset( $settings['columns_tablet'] ) ) {
		$shortcode_atts['netsposts_items_class'] .= ' elementor-grid-tablet-' . $settings['columns_tablet'];
	}
	if ( isset( $settings['columns_mobile'] ) ) {
		$shortcode_atts['netsposts_items_class'] .= ' elementor-grid-mobile-' . $settings['columns_mobile'];
	}

	// Disable pagination in the shortcode output
	$shortcode_atts['paginate'] = 'false';

	// Build shortcode string
	$shortcode_atts_string = '';
	foreach ( $shortcode_atts as $key => $value ) {
		$shortcode_atts_string .= ' ' . $key . "='" . $value . "'";
	}

	// Execute shortcode
	$html = do_shortcode( "[netsposts {$shortcode_atts_string}]" );

	// Check if there are posts in the output
	$has_more = strpos( $html, 'netsposts-content' ) !== false;

	// Send response
	wp_send_json_success( array(
		'html'      => $html,
		'has_more'  => $has_more,
		'next_page' => $page + 1,
	) );
}
