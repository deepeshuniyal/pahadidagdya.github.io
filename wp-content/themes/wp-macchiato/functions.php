<?php

require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/widget-post.php';


add_action('after_setup_theme', 'wp_macchiato_theme_setup');
if (!function_exists( 'wp_macchiato_theme_setup' ) ) {
	function wp_macchiato_theme_setup(){
		load_theme_textdomain('wp-macchiato', get_template_directory() . '/languages');		
		add_editor_style();
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-background', apply_filters( 'wp_macchiato_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
		) ) );
		/* Set image sizes*/	
		add_image_size( 'wp-macchiato-widget-post-thumb',  70, 70, true );
		add_image_size( 'wp-macchiato-post-thumb',  400, 200 , true );
		add_image_size( 'wp-macchiato-small-thumb',  130, 135 , true );
		add_image_size( 'wp-macchiato-medium-thumb',  560 );
		add_image_size( 'wp-macchiato-feature-image',  800, 350, true );
		add_image_size( 'wp-macchiato-large-image',  800, 689, true );
		// register navigation menus
		register_nav_menus(
			array(
			'primary-menu'=>__('Primary Menu', 'wp-macchiato'),
			'top-menu'=>__('Top Menu', 'wp-macchiato')
		));
	}
}

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

if (!function_exists( 'wp_macchiato_menu' ) ){
	function wp_macchiato_menu() {	
		require get_template_directory() . '/inc/wp-macchiato-menu.php';	
	}
}



if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}


/**
 * Enqueue scripts & styles
 */

function wp_macchiato_custom_scripts() {
	global $wp_scripts;
	wp_enqueue_script( 'wp_macchiato_responsive_js', get_template_directory_uri() . '/js/responsive.js', array( 'jquery' ) );	
	wp_enqueue_script( 'wp_macchiato_navigation_js', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ) );		
	wp_enqueue_script( 'wp_macchiato_ie', get_template_directory_uri() . "/js/html5shiv.js");
	$wp_scripts->add_data( 'wp_macchiato_ie', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'wp_macchiato_ie-responsive', get_template_directory_uri() . "/js/ie-responsive.js");
	$wp_scripts->add_data( 'wp_macchiato_ie-responsive', 'conditional', 'lt IE 9' );
	wp_enqueue_style( 'wp_macchiato_responsive', get_template_directory_uri() .'/css/responsive.css', array(), false ,'screen' );
	wp_enqueue_style( 'wp_macchiato_font_awesome', get_template_directory_uri() .'/assets/css/font-awesome.min.css' );
	wp_enqueue_style( 'wp_macchiato_style', get_stylesheet_uri() );
	wp_enqueue_style('wp_macchiato_googleFonts', '//fonts.googleapis.com/css?family=Lato|open+sans');
	wp_enqueue_script( 'wp_macchiato_navigation_js', get_template_directory_uri() . '/js/navigation.js' );

}

add_action('wp_enqueue_scripts', 'wp_macchiato_custom_scripts');


function wp_macchiato_enqueue_comment_reply() {
	wp_enqueue_script( 'comment-reply' );
 }

add_action( 'comment_form_before', 'wp_macchiato_enqueue_comment_reply' );


// Register widgetized area and update sidebar with default widgets
function wp_macchiato_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Default Sidebar', 'wp-macchiato' ),
		'id' => 'defaul-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
		'class' => 'clearfix'
	) );
	
	register_sidebar( array(
		'name' => __( 'Left Sidebar', 'wp-macchiato' ),
		'id' => 'left-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Right Sidebar', 'wp-macchiato' ),
		'id' => 'rigth-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Banner Widget', 'wp-macchiato' ),
		'description' => 'Enter your banner code into this text widget.',
		'id' => 'top-right-widget',
		'before_widget' => '<div id="top-widget">',
		'after_widget' => "</div>",
		'before_title' => '',
		'after_title' => '',
	) );
		
	register_sidebar( array(
		'name' => __( 'Footer One', 'wp-macchiato' ),
		'id' => 'footer-one',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Two', 'wp-macchiato' ),
		'id' => 'footer-two',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Three', 'wp-macchiato' ),
		'id' => 'footer-three',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );
	
		register_sidebar( array(
		'name' => __( 'Footer Four', 'wp-macchiato' ),
		'id' => 'footer-four',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<div class="widget-title"><h4>',
		'after_title' => '</h4><div class="arrow-right"></div></div>',
	) );	

	
}
add_action( 'widgets_init', 'wp_macchiato_widgets_init' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

?>
