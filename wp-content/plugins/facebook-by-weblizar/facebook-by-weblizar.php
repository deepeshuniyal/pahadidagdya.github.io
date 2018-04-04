<?php
/**
 * Plugin Name: Facebook Feed & LikeBox
 * Version: 2.6.3
 * Description: Display the Facebook Feed and Like box on your website. Its completely customizable, responsive and search engine optimization feeds contents.
 * Author: Weblizar
 * Author URI: http://www.weblizar.com
 * Plugin URI: http://www.weblizar.com/plugins/
 */

/*** Constant Values & Variables ***/
define("WEBLIZAR_FACEBOOK_PLUGIN_URL", plugin_dir_url(__FILE__));
define("WEBLIZAR_FACEBOOK_TEXT_DOMAIN", "wl_facebook");

/*** Get Ready Plugin Translation ***/
add_action('plugins_loaded', 'FacebookTranslation');
function FacebookTranslation() {
	load_plugin_textdomain( WEBLIZAR_FACEBOOK_TEXT_DOMAIN, FALSE, dirname( plugin_basename(__FILE__)).'/lang/' );
}

/*** Facebook By Weblizar Menu ***/
add_action('admin_menu','WeblizarFacebookMenu');
function WeblizarFacebookMenu() {
    $adminmenu = add_menu_page( 'Facebook Feed & LikeBox', 'Facebook Feed & LikeBox', 'administrator', 'facebooky-by-weblizar', 'facebooky_by_weblizar_page_function', 'dashicons-facebook-alt');
	//add hook to add styles and scripts for coming soon admin page
    add_action( 'admin_print_styles-' . $adminmenu, 'facebooky_by_weblizar_page_function_js_css' );
}

function facebooky_by_weblizar_page_function() 
{
	require_once("function/facebook-by-weblizar-data.php");
	require_once("function/facebook-by-weblizar-help.php");
}
function facebooky_by_weblizar_page_function_js_css()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap-min-js', WEBLIZAR_FACEBOOK_PLUGIN_URL.'js/bootstrap.min.js');
	wp_enqueue_script('weblizar-tab-js', WEBLIZAR_FACEBOOK_PLUGIN_URL .'js/option-js.js',array('jquery', 'media-upload', 'jquery-ui-sortable'));
	wp_enqueue_style('weblizar-option-style-css', WEBLIZAR_FACEBOOK_PLUGIN_URL .'css/weblizar-option-style.css');
	wp_enqueue_style('op-bootstrap-css', WEBLIZAR_FACEBOOK_PLUGIN_URL. 'css/bootstrap.min.css');
	wp_enqueue_style('weblizar-bootstrap-responsive-google', WEBLIZAR_FACEBOOK_PLUGIN_URL .'css/bootstrap-responsive.css');
	wp_enqueue_style('font-awesome-min-css', WEBLIZAR_FACEBOOK_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');
}
function weblizar_feed_code_script()
{ 
   global $post;
   if (isset($post->post_content) && is_singular(array( 'post','page') ) && has_shortcode( $post->post_content, 'facebook_feed' ) || is_active_widget(false, false,'weblizar_facebook_feed_widget')) 
	{  
        wp_enqueue_script('jquery');
        wp_enqueue_style('feed-font-awesome-min-css', WEBLIZAR_FACEBOOK_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');
		wp_enqueue_style('feed-facebook-feed-shortcode-css', WEBLIZAR_FACEBOOK_PLUGIN_URL.'css/facebook-feed-shortcode.css');
		wp_enqueue_style('feed-facebook-custom-box-slider-css', WEBLIZAR_FACEBOOK_PLUGIN_URL.'css/custom-box-slider.css');
		wp_enqueue_style('feed-bootstrap-css', WEBLIZAR_FACEBOOK_PLUGIN_URL. 'css/bootstrap.css');	
	}
}	
add_action('wp_enqueue_scripts', 'weblizar_feed_code_script');


/*** Load Facebook Like Box widgets ***/
require_once("function/facebook-by-weblizar-widgets.php");
require_once("function/facebook-feed-widget.php");
 
/*** Load Facebook Like Box Shortcode ***/
require_once("function/facebook-by-weblizar-short-code.php");

/*** Load Facebook Page Feed Shortcode ***/
require_once("function/facebook-feed-shortcode.php");
?>