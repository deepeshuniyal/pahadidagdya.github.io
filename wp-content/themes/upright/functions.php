<?php

/**
 * Upright only works in WordPress 4.5 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}


if ( ! function_exists( 'upright_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function upright_setup() {

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on upright, use a find and replace
		 * to change 'upright' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'upright', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for custom logo.
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 90,
			'width'       => 300,
			'flex-height' => true,
		) );

		/**
		 * Enable support for Post Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 435, 247, true );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/**
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
		) );

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary'   => __( 'Primary Menu', 'upright' ),
			'secondary' => __( 'Secondary Menu', 'upright' ),
			'footer'    => __( 'Footer Menu', 'upright' ),
		) );

		/**
		 * Adding styling to the editor
		 */
		add_editor_style( 'style-editor.css' );

		/**
		 * Remove Default CSS for gallery
		 */
		add_filter( 'use_default_gallery_style', '__return_false' );
	}

endif;

add_action( 'after_setup_theme', 'upright_setup' );


/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function upright_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'upright_content_width', 900 );
}

add_action( 'after_setup_theme', 'upright_content_width', 0 );


/**
 * Setup the WordPress core custom background feature.
 */
function upright_register_custom_background() {

	add_theme_support( 'custom-background', apply_filters( 'upright_custom_background_args', array(
		'default-color' => 'f4f4f4',
	) ) );

}

add_action( 'after_setup_theme', 'upright_register_custom_background' );


/**
 * Register widgetized area and update sidebar with default widgets
 */
function upright_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'upright' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Header Sidebar', 'upright' ),
		'id'            => 'sidebar-header',
		'before_widget' => '<div id="%1$s" class="widget %2$s group">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'upright_widgets_init' );


/**
 * Registers Widgets
 */
function upright_register_widgets() {

	require( get_template_directory() . '/inc/widgets/recent_comments.php' );
	require( get_template_directory() . '/inc/widgets/recent_posts.php' );
	require( get_template_directory() . '/inc/widgets/tabs.php' );

	register_widget( 'Upright_Widget_Recent_Comments' );
	register_widget( 'Upright_Widget_Recent_Posts' );
	register_widget( 'Upright_Widget_Tabs' );
}

add_action( 'widgets_init', 'upright_register_widgets' );


/**
 * Enqueue scripts and styles
 */
function upright_scripts() {
	/**
	 * Load FontAwesome
	 */
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3' );

	/**
	 * Load Theme Style
	 */
	wp_enqueue_style( 'upright-style', get_stylesheet_uri() );

	/**
	 * Conditionally Load scripts
	 */
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/vendor/html5shiv.min.js', array(), '20160722' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'selectivizr', get_template_directory_uri() . '/js/vendor/selectivizr.min.js', array(), '20160722' );
	wp_script_add_data( 'selectivizr', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/vendor/respond.min.js', array(), '20160722' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	/**
	 * Load Theme Scripts
	 */
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/js/vendor/jquery.fitvids.min.js', array( 'jquery' ), '1.1', true );
	wp_enqueue_script( 'jquery-flexslider', get_template_directory_uri() . '/js/vendor/jquery.flexslider.min.js', array( 'jquery' ), '2.6.1', true );
	wp_enqueue_script( 'upright-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '1.11', true );
	wp_enqueue_script( 'upright-script', get_template_directory_uri() . '/js/script.js', array(
		'jquery',
		'jquery-fitvids',
		'jquery-flexslider',
		'imagesloaded'
	), '1.11', true );

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'upright-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array(
			'jquery',
			'upright-script'
		), '1.11' );
	}

	/**
	 * Load Script for Threaded Comments
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$js_settings = array();

	// slider
	if ( ! upright_get_option( 'slider_auto' ) ) {
		$js_settings['featured']['slideshow'] = false;
	} else {
		$js_settings['featured']['slideshow'] = true;
	}
	if ( ! upright_get_option( 'slider_auto_timer' ) ) {
		$js_settings['featured']['slideshowSpeed'] = 5000;
	} else {
		$js_settings['featured']['slideshowSpeed'] = intval( upright_get_option( 'slider_auto_timer' ) . '000' );
	}

	// carousel
	if ( ! upright_get_option( 'carousel_auto' ) ) {
		$js_settings['carousel']['slideshow'] = false;
	} else {
		$js_settings['carousel']['slideshow'] = true;
	}
	if ( ! upright_get_option( 'carousel_auto_timer' ) ) {
		$js_settings['carousel']['slideshowSpeed'] = 5000;
	} else {
		$js_settings['featured']['slideshowSpeed'] = intval( upright_get_option( 'carousel_auto_timer' ) . '000' );
	}

	// Pass to the JavaScript
	wp_localize_script(
		'upright-script',
		'_uprightJS',
		$js_settings
	);

}

add_action( 'wp_enqueue_scripts', 'upright_scripts' );


/*
 * Manage font to load based on the customizer setting
 */
function upright_load_font() {

	$websafe_lib = array(
		'Arial__400,700',
		'Arial_Black__400,700',
		'Book_Antiqua__400,700',
		'Comic_Sans_MS__400,700',
		'Courier_New__400,700',
		'Geneva__400,700',
		'Georgia__400,700',
		'Helvetica__400,700',
		'Impact__400,700',
		'Lucida_Console__400,700',
		'Lucida_Grande__400,700',
		'Lucida_Sans_Unicode__400,700',
		'Monaco__400,700',
		'New_York__400,700',
		'Palatino_Lynotype__400,700',
		'Tahoma__400,700',
		'Times_New_Roman__400,700',
		'Trebuchet_MS__400,700',
		'Verdana__400,700',
	);

	$default_font = array(
		'body_font'    => 'Raleway__100,200,300,400,500,600,700,800,900',
		'heading_font' => 'Nixie_One__400',
		'menu_font'    => 'Raleway__100,200,300,400,500,600,700,800,900',
	);

	$added = array();

	foreach ( $default_font as $section => $default ) {

		if ( ! ( $font_to_load = get_theme_mod( $section ) ) ) {
			$font_to_load = $default;
		}

		if ( ! in_array( $font_to_load, $websafe_lib ) && ! in_array( $font_to_load, $added ) ) {
			$added[]      = $font_to_load;
			$font_to_load = str_replace( '__', ':', $font_to_load );
			$font_id      = substr( $font_to_load, 0, strpos( $font_to_load, ':' ) );
			$font_to_load = str_replace( '_', '+', $font_to_load );

			wp_enqueue_style( 'google-font-' . sanitize_html_class( $font_id ), esc_url( 'https://fonts.googleapis.com/css?family=' . $font_to_load )  );
		}
	}

}

add_action( 'wp_enqueue_scripts', 'upright_load_font' );


/*
 * Custom fonts css based on the customizer setting
 */
function upright_fonts_css() {

	$echo = '';
	if ( $font_to_load = get_theme_mod( 'body_font' ) ) {
		$echo .= 'body, .no-heading-style, input[type=text], input[type=email], input[type=password], textarea {';
		$echo .= 'font-family : "' . esc_html( str_replace( '_', ' ', substr( $font_to_load, 0, strpos( $font_to_load, '__' ) ) ) ) . '"; ';
		$echo .= '}';
	}

	if ( $font_to_load = get_theme_mod( 'heading_font' ) ) {
		$echo .= 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {';
		$echo .= 'font-family : "' . esc_html( str_replace( '_', ' ', substr( $font_to_load, 0, strpos( $font_to_load, '__' ) ) ) ) . '"; ';
		$echo .= '}';
	}

	if ( $font_to_load = get_theme_mod( 'menu_font' ) ) {
		$echo .= '.secondary-navigation > div > ul > li > a, .section-title, #reply-title, .widget-title, button, html input[type="button"], input[type="reset"], input[type="submit"] {';
		$echo .= 'font-family : "' . esc_html( str_replace( '_', ' ', substr( $font_to_load, 0, strpos( $font_to_load, '__' ) ) ) ) . '"; ';
		$echo .= '}';
	}

	if ( $echo ) {
		$css = '
			/* Custom Fonts CSS */
			%1$s
		';

		wp_add_inline_style( 'upright-style', sprintf(
			$css,
			strip_tags( $echo )
		) );
	}
}

add_action( 'wp_enqueue_scripts', 'upright_fonts_css' );


/**
 * Get theme option set by customizer
 */
function upright_get_option( $optname, $default = false ) {

	return get_theme_mod( $optname, $default );

}


/**
 * Custom template tags for this theme.
 */
require( get_template_directory() . '/inc/template-tags.php' );

/**
 * Custom functions that act independently of the theme templates
 */
require( get_template_directory() . '/inc/extras.php' );

/**
 * Customizer additions
 */
require( get_template_directory() . '/inc/customizer-helper.php' );
require( get_template_directory() . '/inc/customizer.php' );
