<?php

/*
Plugin Name: Image Carousel
Plugin URI: http://www.ghozylab.com/plugins/
Description: Touch enabled Wordpress plugin that lets you create a beautiful responsive image carousel
Author: GhozyLab, Inc.
Text Domain: image-carousel
Domain Path: /languages
Version: 1.0.0.21
Author URI: http://www.ghozylab.com/plugins/
*/

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}


/*-------------------------------------------------------------------------------*/
/*   All DEFINES
/*-------------------------------------------------------------------------------*/
$icp_plugin_url = substr( plugin_dir_url( __FILE__ ), 0, -1 );
$icp_plugin_dir = substr( plugin_dir_path( __FILE__ ), 0, -1 );

define( 'ICP_VERSION', '1.0.0.21' );
define( 'ICP_URL', $icp_plugin_url );
define( 'ICP_DIR', $icp_plugin_dir );
define( 'ICP_ITEM_NAME', 'Image Carousel' );
define( 'ICP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
@define( BFITHUMB_UPLOAD_DIR, 'image_carousel_thumbs' );

$icp_upload_info = wp_upload_dir();
$icp_upload_dir = $icp_upload_info['basedir'];
define( 'FULL_BFITHUMB_UPLOAD_DIR', $icp_upload_dir. "/" . BFITHUMB_UPLOAD_DIR. "/" );
define( 'ICP_PLUGIN_SLUG', 'image-carousel/image-carousel.php' );

/*-------------------------------------------------------------------------------*/
/*   Plugin Init
/*-------------------------------------------------------------------------------*/
add_action( 'init', 'icp_general_init' );

function icp_general_init() {
	
	// Make sure jQuery loaded on frontend
	if( !is_admin() ) {
		
		wp_enqueue_script( 'jquery' );
		
		}
	
	// Global
	load_plugin_textdomain( 'image-carousel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	include_once( ICP_PLUGIN_DIR . 'inc/functions/icp-functions.php' );
	
	// Backend
	if ( is_admin() ) {
		
		add_action( 'admin_menu', 'icp_menu_page' );
		add_filter( 'plugin_action_links', 'icp_settings_link', 10, 2 );
		add_action( 'admin_enqueue_scripts', 'icp_admin_enqueue_scripts' );
		
		include_once( ICP_PLUGIN_DIR . 'inc/icp-metabox.php' );
		include_once( ICP_PLUGIN_DIR . 'inc/settings/icp-global-settings.php' );
		include_once( ICP_PLUGIN_DIR . 'inc/functions/ajax/icp-admin-ajax.php' );
		
	}
	
	// Frontend
	if ( ! is_admin() ) {
		
		require_once( ICP_PLUGIN_DIR . 'inc/class/BFI_Thumb.php' );
		add_action( 'wp_enqueue_scripts', 'icp_frontend_enqueue_scripts' );
		add_filter( 'the_content', 'icp_post_page_hook' );	
		
	}
	
}


/*-------------------------------------------------------------------------------*/
/*  Plugin Settings Link @since 1.0.0.13
/*-------------------------------------------------------------------------------*/
function icp_settings_link( $link, $file ) {
	
	static $this_plugin;
	
	if ( !$this_plugin )
		$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=icp-carousel-settings' ) . '"><span class="dashicons dashicons-admin-generic"></span>&nbsp;' . __( 'Settings', 'image-carousel' ) . '</a>';
		array_unshift( $link, $settings_link );
	}
	
	return $link;
}


/*-------------------------------------------------------------------------------*/
/*   Redirect on Activation
/*-------------------------------------------------------------------------------*/
function icp_plugin_activate() {

  add_option( 'activated_icp_plugin', 'icp-activate' );

}
register_activation_hook( __FILE__, 'icp_plugin_activate' );

function icp_load_plugin() {

    if ( is_admin() && get_option( 'activated_icp_plugin' ) == 'icp-activate' ) {
		
		 delete_option( 'activated_icp_plugin' );
		 
		if ( !is_network_admin() ) {
			
			wp_redirect("admin.php?page=icp-carousel-settings");
			
			}
			
    }
	
}
add_action( 'admin_init', 'icp_load_plugin' );