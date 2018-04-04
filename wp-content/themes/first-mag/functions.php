<?php
/**
 * Settig Theme-options
 */
include_once( trailingslashit( get_template_directory() ) . 'lib/plugin-activation.php' );
include_once( trailingslashit( get_template_directory() ) . 'lib/theme-config.php' );
include_once( trailingslashit( get_template_directory() ) . 'lib/include-kirki.php' );
require_once( trailingslashit( get_template_directory() ) . 'lib/customize-pro/class-customize.php' );

add_action( 'after_setup_theme', 'first_mag_setup' );

if ( !function_exists( 'first_mag_setup' ) ) :

	function first_mag_setup() {

		// Theme lang
		load_theme_textdomain( 'first-mag', get_template_directory() . '/languages' );

		// Add Title Tag Support
		add_theme_support( 'title-tag' );

		// Register Menus
		register_nav_menus(
			array(
				'main_menu' => __( 'Main Menu', 'first-mag' ),
			)
		);

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 300, true );
		add_image_size( 'first-mag-home', 394, 221, true );
		add_image_size( 'first-mag-home-small', 131, 98, true );
		add_image_size( 'first-mag-slider', 818, 430, true );
		add_image_size( 'first-mag-single', 1170, 400, true );


		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'woocommerce' );
	}

endif;

/**
 * Enqueue Styles (normal style.css and bootstrap.css)
 */
function first_mag_theme_stylesheets() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.css', array(), '1', 'all' );
	wp_enqueue_style( 'first-mag-stylesheet', get_stylesheet_uri(), array(), '1', 'all' );
	// load Font Awesome css
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	// load Flexslider css
	wp_enqueue_style( 'flexslider', get_template_directory_uri() . '/css/flexslider.css', 'style' );
}

add_action( 'wp_enqueue_scripts', 'first_mag_theme_stylesheets' );

/**
 * Register Bootstrap JS with jquery
 */
function first_mag_theme_js() {
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'first-mag-theme-js', get_template_directory_uri() . '/js/customscript.js', array( 'jquery' ) );
	wp_enqueue_script( 'flexslider-js', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'first_mag_theme_js' );


/**
 * Register Custom Navigation Walker include custom menu widget to use walkerclass
 */
require_once('lib/wp_bootstrap_navwalker.php');

/**
 * Register the Sidebar(s)
 */
add_action( 'widgets_init', 'first_mag_widgets_init' );

function first_mag_widgets_init() {
	register_sidebar(
	array(
		'name'			 => __( 'Front Page: Content Section', 'first-mag' ),
		'id'			 => 'first-mag-front-page',
		'description'	 => __( 'Content Section', 'first-mag' ),
		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title"><div class="title-text">',
		'after_title'	 => '</div><div class="widget-line"></div></h3>',
	) );

	register_sidebar(
	array(
		'name'			 => __( 'Right Sidebar', 'first-mag' ),
		'id'			 => 'first-mag-right-sidebar',
		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title"><div class="title-text">',
		'after_title'	 => '</div><div class="widget-line"></div></h3>',
	) );

	register_sidebar(
	array(
		'name'			 => __( 'Left Sidebar', 'first-mag' ),
		'id'			 => 'first-mag-left-sidebar',
		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title"><div class="title-text">',
		'after_title'	 => '</div><div class="widget-line"></div></h3>',
	) );

	register_sidebar(
	array(
		'name'			 => __( 'Header Section', 'first-mag' ),
		'id'			 => 'first-mag-header-top-section',
		'description'	 => __( 'Shows widgets in header section just above the main navigation menu. Suitable for search field or Ad (text widget).', 'first-mag' ),
		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );

	register_sidebar(
	array(
		'name'			 => __( 'Top Ad Section', 'first-mag' ),
		'id'			 => 'first-mag-top-ad-section',
		'description'	 => __( 'Shows widgets just below the main navigation menu. Fullwidth section. Suitable for any Ad (text widget).', 'first-mag' ),
		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );

	register_widget( 'first_mag_featured_posts_widget' );
	register_widget( 'first_mag_featured_posts_widget_second' );
	register_widget( 'first_mag_fullwidth_posts_widget' );
}

/**
 * Register Widgets
 */
require_once( trailingslashit( get_template_directory() ) . 'lib/widgets.php' );
/**
 * Register hook and action to set Main content area col-md- width based on sidebar declarations
 */
add_action( 'first_mag_main_content_width_hook', 'first_mag_main_content_width_columns' );

function first_mag_main_content_width_columns() {

	$columns = '12';

	if ( get_theme_mod( 'rigth-sidebar-check', 1 ) != 0 ) {
		$columns = $columns - absint( get_theme_mod( 'right-sidebar-size', 3 ) );
	}

	if ( get_theme_mod( 'left-sidebar-check', 0 ) != 0 ) {
		$columns = $columns - absint( get_theme_mod( 'left-sidebar-size', 3 ) );
	}

	echo $columns;
}

function first_mag_main_content_width() {
	do_action( 'first_mag_main_content_width_hook' );
}

/**
 * Set Content Width
 */
function first_mag_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'first_mag_content_width', 800 );
}
add_action( 'after_setup_theme', 'first_mag_content_width', 0 );

if ( !function_exists( 'first_mag_breadcrumb' ) ) :
	/**
	 * Breadcrumbs
	 */
	function first_mag_breadcrumb() {
		global $post, $wp_query;
		$home		 = esc_html__( 'Home', 'first-mag' );
		$delimiter	 = ' &raquo; ';
		$homeLink	 = home_url();
		if ( is_home() || is_front_page() ) {
			// no need for breadcrumbs in homepage
		} else {
			echo '<div id="breadcrumbs" >';
			echo '<div class="breadcrumbs-inner text-right">';
			// main breadcrumbs lead to homepage
			echo '<span><a href="' . esc_url( $homeLink ) . '">' . '<i class="fa fa-home"></i><span>' . $home . '</span>' . '</a></span>' . $delimiter . ' ';

			// if blog page exists

			if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
				echo '<span><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . '<span>' . esc_html__( 'Blog', 'first-mag' ) . '</span></a></span>' . $delimiter . ' ';
			}

			if ( is_category() ) {
				$thisCat = get_category( get_query_var( 'cat' ), false );
				if ( $thisCat->parent != 0 ) {
					$category_link = get_category_link( $thisCat->parent );
					echo '<span><a href="' . esc_url( $category_link ) . '">' . '<span>' . get_cat_name( $thisCat->parent ) . '</span>' . '</a></span>' . $delimiter . ' ';
				}

				$category_id	 = get_cat_ID( single_cat_title( '', false ) );
				$category_link	 = get_category_link( $category_id );
				echo '<span><a href="' . esc_url( $category_link ). '">' . '<span>' . single_cat_title( '', false ) . '</span>' . '</a></span>';
			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type	 = get_post_type_object( get_post_type() );
					$link = get_post_type_archive_link( get_post_type() );
					if ( $link ) {
						printf( '<span><a href="%s">%s</a></span>', esc_url( $link ), $post_type->labels->name );
						echo ' ' . $delimiter . ' ';
					}
					echo get_the_title();
				} else {
					$category = get_the_category();
					if ( $category ) {
						foreach ( $category as $cat ) {
							echo '<span><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . '<span>' . $cat->name . '</span>' . '</a></span>' . $delimiter . ' ';
						}
					}

					echo get_the_title();
				}
			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_search() ) {
				$post_type = get_post_type_object( get_post_type() );
				echo $post_type->labels->singular_name;
			} elseif ( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				echo '<span><a href="' . esc_url( get_permalink( $parent ) ). '">' . '<span>' . $parent->post_title . '</span>' . '</a></span>';
				echo ' ' . $delimiter . ' ' . get_the_title();
			} elseif ( is_page() && !$post->post_parent ) {
				$get_post_slug	 = $post->post_name;
				$post_slug		 = str_replace( '-', ' ', $get_post_slug );
				echo '<span><a href="' . esc_url( get_permalink() ). '">' . '<span>' . ucfirst( $post_slug ) . '</span>' . '</a></span>';
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id	 = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page			 = get_post( $parent_id );
					$breadcrumbs[]	 = '<span><a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . '<span>' . get_the_title( $page->ID ) . '</span>' . '</a></span>';
					$parent_id		 = $page->post_parent;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					echo $breadcrumbs[ $i ];
					if ( $i != count( $breadcrumbs ) - 1 )
						echo ' ' . $delimiter . ' ';
				}

				echo $delimiter . '<span><a href="' . esc_url( get_permalink() ). '">' . '<span>' . the_title_attribute( 'echo=0' ) . '</span>' . '</a></span>';
			}
			elseif ( is_tag() ) {
				$tag_id = get_term_by( 'name', single_cat_title( '', false ), 'post_tag' );
				if ( $tag_id ) {
					$tag_link = get_tag_link( $tag_id->term_id );
				}

				echo '<span><a href="' . esc_url( $tag_link ). '">' . '<span>' . single_cat_title( '', false ) . '</span>' . '</a></span>';
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				echo '<span><a href="' . esc_url( get_author_posts_url( $userdata->ID ) ). '">' . '<span>' . $userdata->display_name . '</span>' . '</a></span>';
			} elseif ( is_404() ) {
				echo esc_html__( 'Error 404', 'first-mag' );
			} elseif ( is_search() ) {
				echo esc_html__( 'Search results for', 'first-mag' ) . ' ' . get_search_query();
			} elseif ( is_day() ) {
				echo '<span><a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span>' . get_the_time( 'Y' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span><a href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . '<span>' . get_the_time( 'F' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span><a href="' . esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ) . '">' . '<span>' . get_the_time( 'd' ) . '</span>' . '</a></span>';
			} elseif ( is_month() ) {
				echo '<span><a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span>' . get_the_time( 'Y' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span><a href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . '<span>' . get_the_time( 'F' ) . '</span>' . '</a></span>';
			} elseif ( is_year() ) {
				echo '<span><a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span>' . get_the_time( 'Y' ) . '</span>' . '</a></span>';
			}

			if ( get_query_var( 'paged' ) ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					echo ' (';
				echo esc_html__( 'Page', 'first-mag' ) . ' ' . get_query_var( 'paged' );
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					echo ')';
			}

			echo '</div></div>';
		}
	}
endif;

/**
 * Theme Info page
 */
if ( is_admin() ) {
	require_once(trailingslashit( get_template_directory() ) . 'lib/theme-info.php');
}