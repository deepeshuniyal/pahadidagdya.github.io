<?php

/*
Plugin Name:Carousels Ultimate
Plugin URI: http://themepoints.com
Description: Carousels ultimate allows you to use shortcode to display carousel, slider, post slider in post/page or widgets.
Version: 1.2
Author: themepoints
Author URI: http://themepoints.com
TextDomain: carosuelfree
License: GPLv2
*/


if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );
	
define('THEMEPOINTS_CAROUSEL_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
add_filter('widget_text', 'do_shortcode');	


/* carousels ultimate stylesheet */
function tp_ultimate_carousel_script()
	{
	wp_enqueue_script('jquery');
	wp_enqueue_script('tp-carousel-js', plugins_url( '/js/owl.carousel.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_enqueue_style('tp-carousel-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/owl.carousel.css');	
	wp_enqueue_style('tp-carousel-theme-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/owl.theme.css');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('tp-carousel-wp-color-picker', plugins_url(), array( 'wp-color-picker' ), false, true );
	}
add_action('init', 'tp_ultimate_carousel_script');



function tp_ultimate_carousel_images($atts, $content = null) {
	return ('<div class="item">
				<img src="'.$content.'" alt=""/>
			</div>	
			');
}
add_shortcode ("carouselsimages", "tp_ultimate_carousel_images");


/* carousels ultimate Shortcode */
function tp_ultimate_carousel_shortcodes($atts, $content = null) {
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	
	$ownlrndn = rand(1,1000);
	return ('
			<div id="demo">
				<div class="container">
					<div class="row">
						<div class="span12">
							<div id="owl-demo-'.$ownlrndn.'" class="owl-carousel">
								'.do_shortcode($content).'
							</div>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
			  jQuery("#owl-demo-'.$ownlrndn.'").owlCarousel({
				autoPlay: true,
				items : 4,
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [979,3]
			  });

			});
			</script>
			<style type="text/css">
				.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
				  backface-visibility: hidden;
				  transform: translate3d(0px, 0px, 0px);
				}
				#owl-demo-'.$ownlrndn.' .item {
				  margin: 3px;
				}
				#owl-demo-'.$ownlrndn.' .item img {
				  border: 1px solid #ddd;
				  border-radius: 0;
				  box-shadow: none;
				  display: block;
				  height: 200px;
				}
			</style>
		');
}
add_shortcode ("ultimatecarousels", "tp_ultimate_carousel_shortcodes");



/*
* Creating a function to create our CPT
*/

function tp_carousel_main_custom_post_register() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Carousels Free', 'Post Type General Name', 'carosuelfree' ),
		'singular_name'       => _x( 'Carousel Free', 'Post Type Singular Name', 'carosuelfree' ),
		'menu_name'           => __( 'Carousels Free', 'carosuelfree' ),
		'parent_item_colon'   => __( 'Parent Carousel', 'carosuelfree' ),
		'all_items'           => __( 'All Carousels', 'carosuelfree' ),
		'view_item'           => __( 'View Carousel', 'carosuelfree' ),
		'add_new_item'        => __( 'Add New Carousel', 'carosuelfree' ),
		'add_new'             => __( 'Add New', 'carosuelfree' ),
		'edit_item'           => __( 'Edit Carousel', 'carosuelfree' ),
		'update_item'         => __( 'Update Carousel', 'carosuelfree' ),
		'search_items'        => __( 'Search Carousel', 'carosuelfree' ),
		'not_found'           => __( 'Not Found', 'carosuelfree' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'carosuelfree' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Carousels', 'carosuelfree' ),
		'description'         => __( 'Carousel reviews', 'carosuelfree' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'thumbnail'),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'tpmfcarousel', $args );

}
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'tp_carousel_main_custom_post_register', 0 );

/*----------------------------------------------------------------------
	Register Carousel Free Categories
----------------------------------------------------------------------*/

function tp_carousel_main_custom_post_post_build_taxonomies()
{
	register_taxonomy( 'tpmfcarouselcat', 'tpmfcarousel', array(
		'hierarchical' => true,
		'label' => 'Categories',
		'query_var' => true,
		'rewrite' => true
	));
}	
	
add_action( 'init', 'tp_carousel_main_custom_post_post_build_taxonomies', 0);	

/*----------------------------------------------------------------------
	Add Meta Box 
----------------------------------------------------------------------*/
function tp_carousel_main_custom_post_wordpress_meta_box() {
	add_meta_box(
		'custom_meta_box', // $id
		'Carousel Details', // $title
		'tp_carousel_main_custom_post_inner_custom_box', // $callback
		'tpmfcarousel', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'tp_carousel_main_custom_post_wordpress_meta_box');

/*----------------------------------------------------------------------
	Content Of Testimonials Options Meta Box 
----------------------------------------------------------------------*/

function tp_carousel_main_custom_post_inner_custom_box( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'tp_carousel_main_custom_inner_custom_noncename' );
	
	?>

	
	<!-- Company Website -->
						
	<p><label for="company_website_input"><strong><?php _e('Website Url', 'carosuelfree');?></strong></label></p>
	
	<input type="text" name="company_website_input" id="company_website_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'any_web_links', true); ?>" />
						
	<p><span class="description"><?php _e('Example: (www.example.com)', 'carosuelfree');?></span></p>
	
	<hr class="horizontalRuler"/>
	
	<!-- Company Link Target -->
	
	<p><label for="company_link_target_list"><strong><?php _e('Link Target', 'carosuelfree');?></strong></label></p>
		
	<select id="company_link_target_list" name="company_link_target_list">
		<option value="_blank" <?php if(get_post_meta($post->ID, 'any_web_links_target', true)=='_blank') { echo 'selected'; } ?> >_blank</option>
		<option value="_self" <?php if(get_post_meta($post->ID, 'any_web_links_target', true)=='_self') { echo 'selected'; } ?> >_self</option>
	</select>
	<p><span class="description"><?php _e('Example: ( Open your target link to same page or a new page.)', 'carosuelfree');?></span></p>
	
	<?php
}

/*=============================================================================================================
	Save testimonial Options Meta Box Function
===============================================================================================================*/

function tp_carousel_main_custom_inner_save_meta_box($post_id) 
{

	/*----------------------------------------------------------------------
		company website
	----------------------------------------------------------------------*/
	if(isset($_POST['company_website_input'])) {
		update_post_meta($post_id, 'any_web_links', $_POST['company_website_input']);
	}
	
	/*----------------------------------------------------------------------
		company link target
	----------------------------------------------------------------------*/
	if(isset($_POST['company_link_target_list'])) {
		update_post_meta($post_id, 'any_web_links_target', $_POST['company_link_target_list']);
	}
			
}

/*----------------------------------------------------------------------
	Save testimonial Options Meta Box Action
----------------------------------------------------------------------*/
add_action('save_post', 'tp_carousel_main_custom_inner_save_meta_box');








/*----------------------------------------------------------------------
	Carousel custom post shortcode
----------------------------------------------------------------------*/

function tp_carousel_main_custom_items_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '-1',
		'items' => '4',
		'number' => '6',
		'order_by' => 'date',
		'order' => 'DESC',
		'items_tablet' => '2',
		'items_desktop' => '3',
		'itemsdesktop_small' => '3',
		'img_height' => '190',		
		'display_caption' => 'none',		
	), $atts) );
	$townlrndn = rand(1,1000);
	global $post;

	
		$args =	array ( 'post_type' => 'tpmfcarousel',
						'posts_per_page' => $number,
						'orderby' => $order_by,
						'order' => $order );
		
		if($category > -1) {
			$args['tax_query'] = array(array('taxonomy' => 'tpmfcarouselcat','field' => 'id','terms' => $category ));
		}
		
		$carousel_q = new WP_Query( $args );

			
	$tpc = '
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			  jQuery("#owl-demo-'.$townlrndn.'").owlCarousel({
				autoPlay: true,
				items : '.$items.',
				itemsTablet : [768,'.$items_tablet.'],
				itemsTabletSmall : false,
				itemsMobile : [479,1],
				itemsDesktop : [1199,'.$items_desktop.'],
				itemsDesktopSmall : [979,'.$itemsdesktop_small.']
			  });

			});
		</script>		
		<style type="text/css">
			.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
			  backface-visibility: hidden;
			  transform: translate3d(0px, 0px, 0px);
			}
			#owl-demo-'.$townlrndn.' .item-'.$townlrndn.' {
				margin: 3px;
				position: relative;
			}
			#owl-demo-'.$townlrndn.' .item-'.$townlrndn.' img {
				border: 1px solid #ddd;
				border-radius: 0;
				box-shadow: none;
				display: block;
				height: '.$img_height.'px;
				width:100%;
			}

			#owl-demo-'.$townlrndn.' .item-'.$townlrndn.' .title_caption-'.$townlrndn.' {
				background: #000 none repeat scroll 0 0;
				bottom: 1px;
				color: #fff;
				font-size: 14px;
				font-weight: 400;
				height: 26px;
				left: 1px;
				line-height: 26px;
				opacity: 0.8;
				overflow: hidden;
				position: absolute;
				width: 100%;
			}
		</style>			
	';
	$tpc.='
	<div id="owl-demo-'.$townlrndn.'" class="owl-carousel">';
	while($carousel_q->have_posts()) : $carousel_q->the_post();
		$imgurls = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );	
		$company_website_url = get_post_meta( $post->ID, 'any_web_links', true );
		$company_url_target = get_post_meta( $post->ID, 'any_web_links_target', true );
		
		$tpc .= '
		<div class="item-'.$townlrndn.'">
			<a target="'.$company_url_target.'" href="'.$company_website_url.'"><img src="'.$imgurls.'" alt="'.get_the_title().'"/></a>
			<div style="display:'.$display_caption.'" class="title_caption-'.$townlrndn.'">&nbsp;&nbsp;'.get_the_title().'</div>
		</div>'; 		

 		
	endwhile;
	$tpc.= '</div>';
	
	
	wp_reset_query();
	return $tpc;
}
add_shortcode('tpmfcarousel', 'tp_carousel_main_custom_items_shortcode');	


/*----------------------------------------------------------------------
	Carousel post query shortcode
----------------------------------------------------------------------*/

function tp_carousel_main_custom_post_query_shortcode($atts){
	extract( shortcode_atts( array(
		'items' => '3',
		'img_height' => '190',		
	), $atts) );
	$townwlrndn = rand(1,1000);
	global $post;
    $carousel_qpost = new WP_Query(
        array('posts_per_page' => 7, 'post_type' => 'post')
        );	

			
	$tpclist = '
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			  jQuery("#owl-demo-'.$townwlrndn.'").owlCarousel({
				autoPlay: true,
				items : '.$items.',
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [979,3],
			  });

			});
		</script>		
		<style type="text/css">
			.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
			  backface-visibility: hidden;
			  transform: translate3d(0px, 0px, 0px);
			}
			#owl-demo-'.$townwlrndn.' .item {
			  margin: 3px;
			}
			#owl-demo-'.$townwlrndn.' .item img {
			  border: 1px solid #ddd;
			  border-radius: 0;
			  box-shadow: none;
			  display: block;
			  height: '.$img_height.'px;
			}

			.carousel-post-display-title {
				background: #ddd none repeat scroll 0 0;
				color: #393939;
				margin-bottom: 10px;
			}
			.carousel-post-display-title a {
				color: #000;
			}
			.item > a img {
				width: 100%;
			}

		</style>			
	';
	$tpclist.='
	<div id="owl-demo-'.$townwlrndn.'" class="owl-carousel">';
	while($carousel_qpost->have_posts()) : $carousel_qpost->the_post();
		$imgurls = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );	
			
		setup_postdata( $post );
		$carouselexcerpt = get_the_excerpt();
		
		$tpclist .= '
		<div class="item">';
		
			if ( has_post_thumbnail() ) {
				$tpclist .= '<a href="'.get_the_permalink().'"><img src="'.esc_url($imgurls).'" alt="'.get_the_title().'"/></a>';
			}
			else {
				
			}		
		
			$tpclist .= '<div class="carousel-post-display-title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>
			<div class="carousel-post-display-description">'.wpautop(trim($carouselexcerpt)).'</div>
			
		</div>'; 		

 		
	endwhile;
	$tpclist.= '</div>';
	
	
	wp_reset_query();
	return $tpclist;
}
add_shortcode('tpmfcarouselpost', 'tp_carousel_main_custom_post_query_shortcode');	



function tp_carousel_main_custom_post_tc_css() {
    wp_enqueue_style('gavickpro-tc', plugins_url('/style.css', __FILE__));
}
 
add_action('admin_enqueue_scripts', 'tp_carousel_main_custom_post_tc_css');


add_action('admin_head', 'tp_carousel_main_custom_post_add_my_tc_button');
function tp_carousel_main_custom_post_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "tp_carousel_main_custom_add_tinymce_plugin");
        add_filter('mce_buttons', 'tp_carousel_main_custom_register_add_tinymce_plugin');
    }
}

function tp_carousel_main_custom_add_tinymce_plugin($plugin_array) {
    $plugin_array['carousel_tinymce_added_button'] = plugins_url( '/text-button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}


function tp_carousel_main_custom_register_add_tinymce_plugin($buttons) {
   array_push($buttons, "carousel_tinymce_added_button");
   return $buttons;
}













?>