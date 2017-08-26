<?php
	/*
	Plugin Name:Carousels Ultimate
	Plugin URI: http://themepoints.com/carouselpro/
	Description: Carousel Ultimate WordPress Plugin allows you to easily create Responsive carousel/slider/post slider/logo showcase/ team etc. You can easily display multiple responsive carousel, slider, team, logo showcase, post slider in a same page or widget’s.
	Version: 1.6
	Author: themepoints
	Author URI: https://themepoints.com
	TextDomain: carosuelfree
	License: GPLv2
	*/


	if ( ! defined( 'ABSPATH' ) )
		die( "Can't load this file directly" );

	define('THEMEPOINTS_CAROUSEL_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('themepoints_carousel_plugin_dir', plugin_dir_path( __FILE__ ) );

	add_filter('widget_text', 'do_shortcode');


	// added version 1.3
	require_once( plugin_dir_path( __FILE__ ) . 'inc/tp-carousel-settings.php');



	function tp_ultimate_carousel_load_textdomain(){
		load_plugin_textdomain('carosuelfree', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
	}
	add_action('plugins_loaded', 'tp_ultimate_carousel_load_textdomain');
	
	
	function tp_ultimate_carousel_version_link( $links ) {
	   $links[] = '<a style="color:red;font-weight:bold;" href="https://themepoints.com/product/carousel-shortcode-pro/" target="_blank">Upgrade Pro</a>';
	   return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tp_ultimate_carousel_version_link' );	
	
	
	

/* carousels ultimate stylesheet */
function tp_ultimate_carousel_script()
	{
	wp_enqueue_script('jquery');
	wp_enqueue_script('tp-carousel-js', plugins_url( '/js/owl.carousel.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_enqueue_script('tp-carousel-responsiveslides', plugins_url( '/js/responsiveslides.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_enqueue_script('tp-carousel-minicolors-js', plugins_url( '/js/jquery.minicolors.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_enqueue_style('tp-carousel-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/owl.carousel.css');	
	wp_enqueue_style('tp-carousel-theme-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/owl.theme.css');
	wp_enqueue_style('tp-carousel-responsiveslides-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/responsiveslides.css');
	wp_enqueue_style('tp-carousel-minicolors-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/jquery.minicolors.css');
	wp_enqueue_style('tp-carousel-themes-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/themes.css');
	wp_enqueue_style('tp-carousel-font-awesome-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/font-awesome.css');
	wp_enqueue_style('tp-carousel-style-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'css/main-style.css');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('tp-carousel-wp-color-picker', plugins_url(), array( 'wp-color-picker' ), false, true );
	}
add_action('init', 'tp_ultimate_carousel_script');



function tp_ultimate_carousel_shortcode_menu() {
	//shortcode
	add_submenu_page( 'edit.php?post_type=tpmfcarousel', __('Create Shortcode Page','carosuelfree'), __('Create Shortcode','carosuelfree'), 'administrator', 'carousel-create-shortcode', 'tp_ultimate_carousel_settings_shorcode' );
}
// Action hook to add the menu item
add_action('admin_menu', 'tp_ultimate_carousel_shortcode_menu');


function tp_ultimate_carousel_settings_shorcode() {
	include_once( "inc/tp-carousel-shortcode-settings.php" );
}


function tp_ultimate_carousel_register_settings() {
	//register Array of settings
	register_setting( 'csp-free-options-settings', 'csp_free_options' ); //shortcodes
}
// Action hook to register option settings
add_action( 'admin_init', 'tp_ultimate_carousel_register_settings' );


function tp_ultimate_add_google_fonts() {

wp_enqueue_style( 'example-google-fonts1', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300', false );
wp_enqueue_style( 'example-google-fonts2', 'https://fonts.googleapis.com/css?family=Tangerine', false ); 
}
add_action( 'wp_enqueue_scripts', 'tp_ultimate_add_google_fonts' );



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
		'Carousel Details - <a target="_blank" href="https://themepoints.com/product/carousel-shortcode-pro/">Available Pro</a>', // $title
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









/***********************************************
//  Version 1.4
************************************************/


function tp_carousel_get_excerpt($count){
	global $post;
  $permalink = get_permalink($post->ID);
  $excerpt = get_the_content();
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, $count);
  $excerpt = $excerpt.'... <a href="'.$permalink.'">Read More</a>';
  return $excerpt;
}


function tp_carousel_reg_shortcode($atts, $content = null) {
	global $post;
	extract(shortcode_atts(array(
		'id' => 1, 							// done
		'autoplay' => 'true', 				// done
		'slide_items' => 3, 				// done
		'autoplay_stop' => 'true', 			// done
		'post_styles' => 'default', 		// done
		'textlength' => 100, 				// done
		'showpaginationbtn' => 'true',		// done
		'shownavigationbtn' => 'true',		// done
		'align_pagination' => 'center', 	// done
		'pagination_style' => 'square',
		'image_height' => 200, 				// done
		'show_title' => 'block', 			// done
		'titlefont_size' => 15, 			// done
		'titletext_align' => 'left', 		// done
		'title_color' => '#000000', 		// done
		'titlehover_color' => '#dddddd', 	// done
		'show_readmore' => 'block', 		// done
		'post_type' => 'tpmfcarousel', 		// done
		'categories' => '', 				// done
	), $atts));

$townlrndnsss = rand(1,1000);
	
	$tpclist='';
	

				$cs_carousel_wp_query = new WP_Query();

				//post type
				if ($post_type == 'post') {
					// post - default WordPress post type
					$cs_carousel_wp_query->query( array('post_type' => 'post', 'category_name' => $categories,
					'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => 'date', 'order' => 'DESC') );
				} elseif ($post_type == 'tpmfcarousel') {
					// carousel ultimate - custom post type
					$cs_carousel_wp_query->query( array('post_type' => 'tpmfcarousel', 'tpmfcarouselcat' => $categories, 
					'post_status' => 'publish', 'posts_per_page' => -1, 'nopaging' => true, 'orderby' => 'date', 'order' => 'DESC') );
				}





				if($post_type=="post"){
					


						
						
						if($post_styles=="default"){
							
							$tpclist.='<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#owl-demo-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : '.$slide_items.',
									itemsDesktop : [1199,3],
									itemsDesktopSmall : [979,3],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });
								});
							</script>
							<style type="text/css">
								.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
								  backface-visibility: hidden;
								  transform: translate3d(0px, 0px, 0px);
								}
								#owl-demo-'.$townlrndnsss.' .item {
								  margin: 3px;
								}
								#owl-demo-'.$townlrndnsss.' .item img {
								  border: 1px solid #ddd;
								  border-radius: 0;
								  box-shadow: none;
								  display: block;
								  height: '.$image_height.'px;
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-title {
									background: #ddd none repeat scroll 0 0;
									color: #393939;
									margin-bottom: 10px;
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-title a {
								  border: medium none;
								  box-shadow: none;
								  color: '.$title_color.';
								  display: '.$show_title.';
								  outline: medium none;
								  overflow: hidden;
								  padding: 5px;
								  text-decoration: none;
								  font-size:'.$titlefont_size.'px;
								  text-align:'.$titletext_align.';
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-title a:hover {
								  box-shadow: none;
								  color: '.$titlehover_color.';
								  outline: medium none;
								  text-decoration: none;
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-description {
								  display: block;
								  overflow: hidden;
								  padding: 0 5px;
								}
								#owl-demo-'.$townlrndnsss.' .item > a img {
									width: 100%;
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-description a {
								  box-shadow: none;
								  color: #000;
								  display: block;
								  margin-top: 5px;
								  outline: medium none;
								  overflow: hidden;
								  text-decoration: none;
								  display:'.$show_readmore.';
								}
								#owl-demo-'.$townlrndnsss.' .carousel-post-display-description a:hover {
								  box-shadow: none;
								  color: #c3c3c3;
								  outline: medium none;
								  text-decoration: none;
								}
								#owl-demo-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
								  text-align: '.$align_pagination.';
								}
							</style>';
							$tpclist.='
								<div id="owl-demo-'.$townlrndnsss.'" class="owl-carousel">';
									
								// Creating a new side loop
								while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
								$imgurls_new = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
								$content = get_the_content();
								$tpclist .= '
								<div class="item">';
									if ( has_post_thumbnail() ) {
										$tpclist .= '<a href="'.get_the_permalink().'"><img src="'.esc_url($imgurls_new).'" alt="'.get_the_title().'"/></a>';
									}
									else {
										
									}
									$tpclist .= '<div class="carousel-post-display-title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>
									<div class="carousel-post-display-description">'.tp_carousel_get_excerpt($textlength).'</div>
								</div>'; 
									
								endwhile;
							$tpclist.= '</div>';
							wp_reset_postdata();
							return $tpclist; 
						}
						// Post Carousel theme 1
						elseif($post_styles=="style1"){
							
								$tpclist.='<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#owls-demo-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : '.$slide_items.',
									itemsDesktop : [1199,3],
									itemsDesktopSmall : [979,3],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });
								});
							</script>
							<style type="text/css">
							.cs_carousel_style4-'.$townlrndnsss.'{
								box-shadow: none;
								display: block;
								overflow: hidden;
								position: relative;
								text-align: center;
							}
							.cs_carousel_style4-'.$townlrndnsss.' img{
								width: 100%;
								height: '.$image_height.'px;
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-content-'.$townlrndnsss.'{
								width: 100%;
								height: 100%;
								background: transparent;
								padding-top: 25%;
								position: absolute;
								top: 0;
								left: 0;
								transition: all 0.3s ease 0s;
							}
							.cs_carousel_style4-'.$townlrndnsss.':hover .cs_carousel_style4-content-'.$townlrndnsss.'{
								background: rgba(0,0,0,0.5);
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-title-'.$townlrndnsss.' {
							  color: #fff;
							  font-size: 14px;
							  margin: 15px 0 0;
							  padding: 0;
							  text-transform: capitalize;
							  transform: scale(0);
							  transition: all 0.2s ease 0s;
							}
							.cs_carousel_style4-'.$townlrndnsss.':hover .cs_carousel_style4-title-'.$townlrndnsss.'{
								transform: scale(1);
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.'{
								list-style: none;
								padding: 0;
								margin: 0;
								opacity: 0;
								transition: all 0.2s ease 0s;
							}
							.cs_carousel_style4-'.$townlrndnsss.':hover .cs_carousel_style4-icons-'.$townlrndnsss.'{
								opacity: 1;
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li{
								display: inline-block;
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li:first-child a, .cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li:last-child a {
							  border: 1px solid #fff;
							  color: #fff;
							  display: block;
							  font-size: 14px;
							  height: 45px;
							  line-height: 45px;
							  position: relative;
							  width: 45px;
							}
							.cs_carousel_style4-title-'.$townlrndnsss.' {
								display: block;
								overflow: hidden;
							}
							.cs_carousel_style4-title-'.$townlrndnsss.' a {
								border: medium none;
								box-shadow: none;
								color: '.$title_color.';
								display: '.$show_title.';
								outline: medium none;
								overflow: hidden;
								text-decoration: none;
								text-align:'.$titletext_align.';
							}
							.cs_carousel_style4-title-'.$townlrndnsss.' a:hover {
								color: '.$titlehover_color.';
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li a{
								top: -150px;
							}
							.cs_carousel_style4-'.$townlrndnsss.':hover .cs_carousel_style4-icons-'.$townlrndnsss.' li a{
								top: 0;
							}
							.cs_carousel_style4-'.$townlrndnsss.':hover .cs_carousel_style4-icons-'.$townlrndnsss.' li a:hover{
								background: transparent none repeat scroll 0 0;
								border-color: #ffffff;
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li:first-child a{
								transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0s;
							}
							.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-icons-'.$townlrndnsss.' li:last-child a {
							  box-shadow: none;
							  outline: medium none;
							  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s;
							}
							@media only screen and (max-width:990px){
								.cs_carousel_style4-'.$townlrndnsss.'{ margin-bottom: 30px; }
							}
							@media only screen and (max-width:360px){
								.cs_carousel_style4-'.$townlrndnsss.' .cs_carousel_style4-content-'.$townlrndnsss.'{ padding-top: 20%; }
							}
							.owl-theme .owl-controls{
								margin-top: 20px;
							}
							#owls-demo-'.$townlrndnsss.' .owl-controls .owl-buttons div {
								background-color: #ddd;
								border: 1px solid #ddd;
								color: #000;
								float: left;
								margin-right: 1px;
								padding: 0 11px;
								text-align: center;
								vertical-align: middle;
								opacity:1;
								border-radius:0;
							}
							#owls-demo-'.$townlrndnsss.' .owl-controls .owl-buttons {
								margin-right: 0px;
								position: absolute;
								right: -1px;
								top: -34px;
							}
							#owls-demo-'.$townlrndnsss.' .owl-theme .owl-controls .owl-page {
								display: inline-block;
							}
							#owls-demo-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page span {
							  background: #ddd none repeat scroll 0 0;
							  border-radius: 0 !important;
							  display: block;
							  height: 12px;
							  margin: 5px 7px;
							  opacity: 0.5;
							  width: 12px;
							}
							#owls-demo-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
							  text-align: '.$align_pagination.';
							}		
							#owls-demo-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {
							  opacity: 1;
							}
							</style>';
							
							$tpclist.='<div id="owls-demo-'.$townlrndnsss.'" class="owl-carousel">';
							// Creating a new side loop
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$imgurls_new = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
							$content = get_the_content();
							$tpclist .= '
							<div class="item">';
								$tpclist.='
								<div class="cs_carousel_style4-'.$townlrndnsss.'">';
									if ( has_post_thumbnail() ) {
										$tpclist .= '<a href="'.get_the_permalink().'"><img src="'.esc_url($imgurls_new).'" alt="'.get_the_title().'"/></a>';
									}
									else {
										
									}
									$tpclist.='<div class="cs_carousel_style4-content-'.$townlrndnsss.'">
										<ul class="cs_carousel_style4-icons-'.$townlrndnsss.'">
											<li><a href="'.esc_url(get_the_permalink()).'"><i class="fa fa-eye"></i></a></li>
										</ul>
										
									</div>
								</div>
								<div class="cs_carousel_style4-title-'.$townlrndnsss.'"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>
								';
								$tpclist .= '</div>';
								
							endwhile;
								$tpclist.= '</div>';
							wp_reset_postdata();
							return $tpclist;
						}// End theme 1
						
						else{
							
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$tpclist.='<div class="post-grid-container">';
								$tpclist.='<div class="post-grid-container-single">';
								$tpclist.='<div class="post-grid-title">'.get_the_title().'</div>';

								$tpclist.='</div>';
							$tpclist.='</div>';
							endwhile;
							wp_reset_postdata();
							return $tpclist; 
							
						}
						
					}

				elseif($post_type=="tpmfcarousel"){
					
						if($post_styles=="style5"){
							$tpclist.='
							<style type="text/css">
							.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
							  backface-visibility: hidden;
							  transform: translate3d(0px, 0px, 0px);
							}
							#tpcarouselitems-'.$townlrndnsss.' .item-'.$townlrndnsss.' {
								margin: 3px;
								position: relative;
							}
							#tpcarouselitems-'.$townlrndnsss.' .item-'.$townlrndnsss.' img {
								border: 1px solid #ddd;
								border-radius: 0;
								box-shadow: none;
								display: block;
								width:100%;
								height: '.$image_height.'px;
							}

							#tpcarouselitems-'.$townlrndnsss.' .item-'.$townlrndnsss.' .title_caption-'.$townlrndnsss.' {
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
								
								
								
							.owl-theme .owl-controls{
								margin-top: 20px;
							}
							#tpcarouselitems-'.$townlrndnsss.' .owl-controls .owl-buttons div {
								background-color: #ddd;
								border: 1px solid #ddd;
								color: #000;
								float: left;
								margin-right: 1px;
								padding: 0 11px;
								text-align: center;
								vertical-align: middle;
								opacity:1;
								border-radius:0;
							}
							#tpcarouselitems-'.$townlrndnsss.' .owl-controls .owl-buttons {
								margin-right: 0px;
								position: absolute;
								right: 3px;
								top: -34px;
							}
							#tpcarouselitems-'.$townlrndnsss.' .owl-theme .owl-controls .owl-page {
								display: inline-block;
							}
							#tpcarouselitems-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page span {
							  background: #aeaeae none repeat scroll 0 0;
							  border-radius: 50% !important;
							  display: block;
							  height: 12px;
							  margin: 5px;
							  opacity: 0.5;
							  width: 12px;
							}
							#tpcarouselitems-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
							  text-align: '.$align_pagination.';
							}		
							#tpcarouselitems-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {
							  opacity: 1;
							}
							</style>';


							$tpclist.='
							<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#tpcarouselitems-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : '.$slide_items.',
									itemsDesktop : [1199,3],
									itemsDesktopSmall : [979,3],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });

								});
							</script>';

							
							
							$tpclist.='<div id="tpcarouselitems-'.$townlrndnsss.'" class="owl-carousel">';
							// Creating a new side loop
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$company_website_url = get_post_meta( $post->ID, 'any_web_links', true );
							$company_url_target = get_post_meta( $post->ID, 'any_web_links_target', true );
							$imgurls = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
							
							$tpclist.= '
							<div class="item-'.$townlrndnsss.'">
								<a target="'.$company_url_target.'" href="'.esc_url($company_website_url).'"><img src="'.$imgurls.'" alt="'.get_the_title().'"/></a>
							</div>'; 

									
							endwhile;
							wp_reset_postdata();
							$tpclist .='</div><div class="clearfix"></div>';
							return $tpclist; 
						}
					
						else if($post_styles=="style6"){
							$tpclist.='
							<style type="text/css">
							.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
							  backface-visibility: hidden;
							  transform: translate3d(0px, 0px, 0px);
							}
							#tpcarouselitems6-'.$townlrndnsss.' .item-'.$townlrndnsss.' {
								margin: 3px;
								position: relative;
							}
							#tpcarouselitems6-'.$townlrndnsss.' .item-'.$townlrndnsss.' img {
								border: 1px solid #ddd;
								border-radius: 0;
								box-shadow: none;
								display: block;
								height: '.$img_height.'px;
								width:100%;
								height: '.$image_height.'px;
							}

							#tpcarouselitems6-'.$townlrndnsss.' .item-'.$townlrndnsss.' .title_caption-'.$townlrndnsss.' {
								background: #000 none repeat scroll 0 0;
								bottom: 1px;
								color: '.$title_color.';
								font-size:'.$titlefont_size.'px;
								text-align:'.$titletext_align.';
								font-weight: 400;
								height: 26px;
								left: 1px;
								line-height: 26px;
								opacity: 0.8;
								overflow: hidden;
								position: absolute;
								width: 99%;
							}
								
							.owl-theme .owl-controls{
								margin-top: 20px;
							}
							#tpcarouselitems6-'.$townlrndnsss.' .owl-controls .owl-buttons div {
								background-color: #ddd;
								border: 1px solid #ddd;
								color: #000;
								float: left;
								margin-right: 1px;
								padding: 0 11px;
								text-align: center;
								vertical-align: middle;
								opacity:1;
								border-radius:0;
							}
							#tpcarouselitems6-'.$townlrndnsss.' .owl-controls .owl-buttons {
								margin-right: 0px;
								position: absolute;
								right: 3px;
								top: -34px;
							}
							#tpcarouselitems6-'.$townlrndnsss.' .owl-theme .owl-controls .owl-page {
								display: inline-block;
							}
							#tpcarouselitems6-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page span {
							  background: #aeaeae none repeat scroll 0 0;
							  border-radius: 50% !important;
							  display: block;
							  height: 12px;
							  margin: 5px;
							  opacity: 0.5;
							  width: 12px;
							}
							#tpcarouselitems6-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
							  text-align: '.$align_pagination.';
							}		
							#tpcarouselitems6-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {
							  opacity: 1;
							}
							</style>';


							$tpclist.='
							<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#tpcarouselitems6-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : '.$slide_items.',
									itemsDesktop : [1199,3],
									itemsDesktopSmall : [979,3],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });

								});
							</script>';

							
							
							$tpclist.='<div id="tpcarouselitems6-'.$townlrndnsss.'" class="owl-carousel">';
							// Creating a new side loop
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$company_website_url = get_post_meta( $post->ID, 'any_web_links', true );
							$company_url_target = get_post_meta( $post->ID, 'any_web_links_target', true );
							$imgurls = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
							
							$tpclist.= '
							<div class="item-'.$townlrndnsss.'">
								<a target="'.$company_url_target.'" href="'.esc_url($company_website_url).'"><img src="'.$imgurls.'" alt="'.get_the_title().'"/></a>
								<div class="title_caption-'.$townlrndnsss.'">&nbsp;&nbsp;'.get_the_title().'</div>
							</div>'; 

									
							endwhile;
							wp_reset_postdata();
							$tpclist .='</div><div class="clearfix"></div>';
							return $tpclist; 
						}
					
						else if($post_styles=="style7"){
							$tpclist.='
							<style type="text/css">
							.owl-carousel .owl-wrapper, .owl-carousel .csppro-item {
							  backface-visibility: hidden;
							  transform: translate3d(0px, 0px, 0px);
							}
							#tpcarouselitems7-'.$townlrndnsss.' .item-'.$townlrndnsss.' {
								margin: 3px;
								position: relative;
							}
							#tpcarouselitems7-'.$townlrndnsss.' .item-'.$townlrndnsss.' img {
								border: 1px solid #ddd;
								border-radius: 0;
								box-shadow: none;
								display: block;
								height: '.$img_height.'px;
								width:100%;
								height: '.$image_height.'px;
							}

							#tpcarouselitems7-'.$townlrndnsss.' .item-'.$townlrndnsss.' .title_caption-'.$townlrndnsss.' {
								background: #000 none repeat scroll 0 0;
								bottom: 1px;
								color: '.$title_color.';
								font-size:'.$titlefont_size.'px;
								text-align:'.$titletext_align.';
								font-weight: 400;
								height: 26px;
								left: 1px;
								line-height: 26px;
								opacity: 0.8;
								overflow: hidden;
								position: absolute;
								width: 99.8%;
							}
								
							.owl-theme .owl-controls{
								margin-top: 20px;
							}
							#tpcarouselitems7-'.$townlrndnsss.' .owl-controls .owl-buttons div {
								background-color: #ddd;
								border: 1px solid #ddd;
								color: #000;
								float: left;
								margin-right: 1px;
								padding: 0 11px;
								text-align: center;
								vertical-align: middle;
								opacity:1;
								border-radius:0;
							}
							#tpcarouselitems7-'.$townlrndnsss.' .owl-controls .owl-buttons {
								margin-right: 0px;
								position: absolute;
								right: 3px;
								top: -34px;
							}
							#tpcarouselitems7-'.$townlrndnsss.' .owl-theme .owl-controls .owl-page {
								display: inline-block;
							}
							#tpcarouselitems6-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page span {
							  background: #aeaeae none repeat scroll 0 0;
							  border-radius: 50% !important;
							  display: block;
							  height: 12px;
							  margin: 5px;
							  opacity: 0.5;
							  width: 12px;
							}
							#tpcarouselitems7-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
							  text-align: '.$align_pagination.';
							}		
							#tpcarouselitems7-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {
							  opacity: 1;
							}
							</style>';


							$tpclist.='
							<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#tpcarouselitems7-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : 1,
									itemsDesktop : [1199,1],
									itemsDesktopSmall : [979,1],
									itemsTablet : [768,1],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });

								});
							</script>';

							
							
							$tpclist.='<div id="tpcarouselitems7-'.$townlrndnsss.'" class="owl-carousel">';
							// Creating a new side loop
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$company_website_url = get_post_meta( $post->ID, 'any_web_links', true );
							$company_url_target = get_post_meta( $post->ID, 'any_web_links_target', true );
							$imgurls = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
							
							$tpclist.= '
							<div class="item-'.$townlrndnsss.'">
								<a target="'.$company_url_target.'" href="'.esc_url($company_website_url).'"><img src="'.$imgurls.'" alt="'.get_the_title().'"/></a>
								<div class="title_caption-'.$townlrndnsss.'">&nbsp;&nbsp;'.get_the_title().'</div>
							</div>'; 

									
							endwhile;
							wp_reset_postdata();
							$tpclist .='</div><div class="clearfix"></div>';
							return $tpclist; 
						}
						
						// Post Carousel theme 1
						elseif($post_styles=="style8"){
							
								$tpclist.='<script type="text/javascript">
								jQuery(document).ready(function($) {
								  jQuery("#tpcarouselitems8-'.$townlrndnsss.'").owlCarousel({
									autoPlay: '.$autoplay.',
									items : '.$slide_items.',
									itemsTablet : [768,2],
									itemsDesktop : [1199,3],
									itemsDesktopSmall : [979,3],
									pagination:'.$showpaginationbtn.',
									navigation:'.$shownavigationbtn.',
									navigationText:["<",">"],
									stopOnHover: '.$autoplay_stop.',
								  });
								});
							</script>
							<style type="text/css">
							.cs_carousel_style8-'.$townlrndnsss.'{
								box-shadow: none;
								display: block;
								overflow: hidden;
								position: relative;
								text-align: center;
							}
							.cs_carousel_style8-'.$townlrndnsss.' img{
								width: 100%;
								height: '.$image_height.'px;
							}
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-content-'.$townlrndnsss.'{
								width: 100%;
								height: 100%;
								background: transparent;
								padding-top: 25%;
								position: absolute;
								top: 0;
								left: 0;
								transition: all 0.3s ease 0s;
							}
							.cs_carousel_style8-'.$townlrndnsss.':hover .cs_carousel_style8-content-'.$townlrndnsss.'{
								background: rgba(0,0,0,0.5);
							}

							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.'{
								list-style: none;
								padding: 0;
								margin: 0;
								opacity: 0;
								transition: all 0.2s ease 0s;
							}
							.cs_carousel_style8-'.$townlrndnsss.':hover .cs_carousel_style8-icons-'.$townlrndnsss.'{
								opacity: 1;
							}
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li{
								display: inline-block;
							}
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li:first-child a, .cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li:last-child a {
							  border: 1px solid #fff;
							  color: #fff;
							  display: block;
							  font-size: 14px;
							  height: 45px;
							  line-height: 45px;
							  position: relative;
							  width: 45px;
							}

							
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li a{
								top: -150px;
							}
							.cs_carousel_style8-'.$townlrndnsss.':hover .cs_carousel_style8-icons-'.$townlrndnsss.' li a{
								top: 0;
							}
							.cs_carousel_style8-'.$townlrndnsss.':hover .cs_carousel_style8-icons-'.$townlrndnsss.' li a:hover{
								background: transparent none repeat scroll 0 0;
								border-color: #ffffff;
							}
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li:first-child a{
								transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0s;
							}
							.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-icons-'.$townlrndnsss.' li:last-child a {
							  box-shadow: none;
							  outline: medium none;
							  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s;
							}
							@media only screen and (max-width:990px){
								.cs_carousel_style8-'.$townlrndnsss.'{ margin-bottom: 30px; }
							}
							@media only screen and (max-width:360px){
								.cs_carousel_style8-'.$townlrndnsss.' .cs_carousel_style8-content-'.$townlrndnsss.'{ padding-top: 20%; }
							}
							
							
							.owl-theme .owl-controls{
								margin-top: 20px;
							}
							#tpcarouselitems8-'.$townlrndnsss.' .owl-controls .owl-buttons div {
								background-color: #ddd;
								border: 1px solid #ddd;
								color: #000;
								float: left;
								margin-right: 1px;
								padding: 0 11px;
								text-align: center;
								vertical-align: middle;
								opacity:1;
								border-radius:0;
							}
							#tpcarouselitems8-'.$townlrndnsss.' .owl-controls .owl-buttons {
								margin-right: 0px;
								position: absolute;
								right: -1px;
								top: -34px;
							}
							#tpcarouselitems8-'.$townlrndnsss.' .owl-theme .owl-controls .owl-page {
								display: inline-block;
							}
							#tpcarouselitems8-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page span {
							  background: #ddd none repeat scroll 0 0;
							  border-radius: 0 !important;
							  display: block;
							  height: 12px;
							  margin: 5px 7px;
							  opacity: 0.5;
							  width: 12px;
							}
							#tpcarouselitems8-'.$townlrndnsss.'.owl-theme.owl-carousel .owl-controls .owl-pagination {
							  text-align: '.$align_pagination.';
							}		
							#tpcarouselitems8-'.$townlrndnsss.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {
							  opacity: 1;
							}
							</style>';
							
							$tpclist.='<div id="tpcarouselitems8-'.$townlrndnsss.'" class="owl-carousel">';
							// Creating a new side loop
							while ( $cs_carousel_wp_query->have_posts() ) : $cs_carousel_wp_query->the_post();
							$imgurls_new = get_the_post_thumbnail_url( get_the_ID(), 'thumbs', false );
							$company_website_url = get_post_meta( $post->ID, 'any_web_links', true );
							$company_url_target = get_post_meta( $post->ID, 'any_web_links_target', true );
							
							$tpclist .= '
							<div class="item">';
								$tpclist.='
								<div class="cs_carousel_style8-'.$townlrndnsss.'">';
									if ( has_post_thumbnail() ) {
										$tpclist .= '<a href="'.get_the_permalink().'"><img src="'.esc_url($imgurls_new).'" alt="'.get_the_title().'"/></a>';
									}
									else {
										
									}
									$tpclist.='<div class="cs_carousel_style8-content-'.$townlrndnsss.'">
										<ul class="cs_carousel_style8-icons-'.$townlrndnsss.'">
											<li><a target="'.$company_url_target.'" href="'.esc_url($company_website_url).'"><i class="fa fa-eye"></i></a></li>
										</ul>
										
									</div>
								</div>';
								$tpclist .= '</div>';
								
							endwhile;
								$tpclist.= '</div>';
							wp_reset_postdata();
							return $tpclist;
						}// End theme 8
						else{
							
							echo 'Nothing Found';
						}

				}
}	

// Action hook to create znews shortcode
add_shortcode('cspscode', 'tp_carousel_reg_shortcode');





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








	function tp_carousel_main_free_redirect_options_page( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'options-general.php' ) ) );
		}
	}

	add_action( 'activated_plugin', 'tp_carousel_main_free_redirect_options_page' );	




	// admin menu
	function tp_carousel_main_free_plugins_options_framwrork() {
		add_options_page( 'Carousel Pro Features', '', 'manage_options', 'tps-carousel-features', 'tps_caro_main_frees_options_framework' );
	}
	add_action( 'admin_menu', 'tp_carousel_main_free_plugins_options_framwrork' );


	if ( is_admin() ) : // Load only if we are viewing an admin page

	function tp_caromain_options_framework_settings() {
		// Register settings and call sanitation functions
		register_setting( 'accordion_free_options', 'tp_accordion_free_options', 'tpls_accordion_free_options' );
	}
	add_action( 'admin_init', 'tp_caromain_options_framework_settings' );



	function tps_caro_main_frees_options_framework() {

		if ( ! isset( $_REQUEST['updated'] ) ) {
			$_REQUEST['updated'] = false;
		} ?>


		<div class="wrap about-wrap">
			<h1>Welcome to Carousel Ultimate - V1.6</h1>

			<div class="about-text">Thank you for using Carousel Ultimate plugin free version.</div>
			<strong>Submit a Review</strong>			
			<hr>

			<p>We spend plenty of time to develop a plugin like this and give you freely. If you like this plugin, please <a style="color:red;font-weight:bold" href="https://wordpress.org/plugins/carousel/#reviews" target="_blank">rate it 5 stars</a>. If you have any problems with the plugin, please <a href="https://themepoints.com/questions-answer/" target="_blank">let us know</a> before leaving a review.</p>

			<hr>

			<h3>We create a <a target="_blank" href="https://themepoints.com/product/carousel-shortcode-pro/">premium version</a> of this plugin with some amazing cool features?</h3>
			<br>

			<hr>
			<br>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="http://themepoints.com/carouselpro/">Live Preview</a>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="https://themepoints.com/product/carousel-shortcode-pro/">Unlimited License Only $13</a>
			<a target="_blank" class="button button-primary load-customize hide-if-no-customize" href="https://themepoints.com/product/carousel-shortcode-pro/">Pro Version Features</a>
			<br>
			<br>
			<hr>

<!-- 			<div class="feature-section two-col">
				<h2>Premium Version Amazing Features</h2>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> All Features of the free version.</li>
						<li><span class="dashicons dashicons-yes"></span> Fully responsive.</li>
						<li><span class="dashicons dashicons-yes"></span> 20 Slider Style & 60 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 10 List Style & 30 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 05 Grid Style & 15 Ready Skin.</li>
						<li><span class="dashicons dashicons-yes"></span> 100+ Ready Shortcode.</li>
						<li><span class="dashicons dashicons-yes"></span> Highly customized for User Experience.</li>
						<li><span class="dashicons dashicons-yes"></span> Widget Ready.</li>
						<li><span class="dashicons dashicons-yes"></span> License: Unlimited Domain</li>
						<li><span class="dashicons dashicons-yes"></span> Supports unlimited Testimonial per page.</li>
						<li><span class="dashicons dashicons-yes"></span> Touch & Swipe Enable per page.</li>
						<li><span class="dashicons dashicons-yes"></span> Display Testimonial by Category.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial order_by (Publish date, Order, Random).</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial order (DESC, ASC).</li>
						<li><span class="dashicons dashicons-yes"></span> Create Testimonial by group.</li>
						<li><span class="dashicons dashicons-yes"></span> Show all testimonials via a shortcode.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial Slider AutoPlay Option.</li>
						<li><span class="dashicons dashicons-yes"></span> Testimonial Support Multiple Column.</li>
						<li><span class="dashicons dashicons-yes"></span> Life Time Self hosted auto updated enable.</li>
						<li><span class="dashicons dashicons-yes"></span> 24/7 dedicated support forum.</li>
						<li><span class="dashicons dashicons-yes"></span> Cross-browser compatibility.</li>
						<li><span class="dashicons dashicons-yes"></span> Use via short-codes.</li>				
						<li><span class="dashicons dashicons-yes"></span> Well Documentation.</li>				
						<li><span class="dashicons dashicons-yes"></span> & Many More...</li>				
					</ul>
				</div>
				<div class="col">
					<ul>
						<li><span class="dashicons dashicons-yes"></span> Shortcode Parameters :</li>
						<li><span class="dashicons dashicons-yes"></span> themes</li>
						<li><span class="dashicons dashicons-yes"></span> navigation</li>
						<li><span class="dashicons dashicons-yes"></span> navigation_color</li>
						<li><span class="dashicons dashicons-yes"></span> navigation_bg_color</li>
						<li><span class="dashicons dashicons-yes"></span> pagination</li>
						<li><span class="dashicons dashicons-yes"></span> auto_play</li>
						<li><span class="dashicons dashicons-yes"></span> autoplay_speed</li>
						<li><span class="dashicons dashicons-yes"></span> text_align</li>
						<li><span class="dashicons dashicons-yes"></span> stars_color</li>
						<li><span class="dashicons dashicons-yes"></span> tcontent_color</li>
						<li><span class="dashicons dashicons-yes"></span> tcontent_size</li>
						<li><span class="dashicons dashicons-yes"></span> ttitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> ttitle_size</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_size</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> tbg_color</li>
						<li><span class="dashicons dashicons-yes"></span> tborder_radious</li>
						<li><span class="dashicons dashicons-yes"></span> columns_number</li>
						<li><span class="dashicons dashicons-yes"></span> columns_tablet</li>
						<li><span class="dashicons dashicons-yes"></span> tsubtitle_color</li>
						<li><span class="dashicons dashicons-yes"></span> contentbg_color</li>
						<li><span class="dashicons dashicons-yes"></span> tborder_color</li>
						<li><span class="dashicons dashicons-yes"></span> And Many More</li>
					</ul>
				</div>
			</div>

			<h2><a href="https://themepoints.com/product/carousel-shortcode-pro/" class="button button-primary button-hero" target="_blank">Unlimited License Only $13</a>
			</h2> -->
			<br>
			<br>
			<br>
			<br>

		</div>

		<?php
	}


	endif;  // EndIf is_admin()




	register_activation_hook( __FILE__, 'tps_super_caro_free_plugin_active_hook' );
	add_action( 'admin_init', 'tps_super_caro_free_main_active_redirect_hook' );

	function tps_super_caro_free_plugin_active_hook() {
		add_option( 'tps_super_main_plugin_active_free_redirect_hook', true );
	}

	function tps_super_caro_free_main_active_redirect_hook() {
		if ( get_option( 'tps_super_main_plugin_active_free_redirect_hook', false ) ) {
			delete_option( 'tps_super_main_plugin_active_free_redirect_hook' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( "options-general.php?page=tps-carousel-features" );
			}
		}
	}


?>