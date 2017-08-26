<?php
/**
 * WP Macchiato Theme Customizer
 *
 * @package WP Macchiato
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wp_macchiato_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'wp_macchiato_customize_register' );
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wp_macchiato_customize_preview_js() {
	wp_enqueue_script( 'wp_macchiato_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'wp_macchiato_customize_preview_js' );

/*******************************************************************
* These are settings for the Theme Customizer in the admin panel. 
*******************************************************************/
if ( ! function_exists( 'wp_macchiato_theme_customizer' ) ) :
	function wp_macchiato_theme_customizer( $wp_customize ) {
		$wp_customize->remove_section( 'title_tagline');		
		/* logo option */
		$wp_customize->add_section( 'wp_macchiato_logo_section' , array(
			'title'       => __( 'Site Logo', 'wp-macchiato' ),
			'priority'    => 20,
			'description' => __( 'Upload a logo to replace the default site name in the header', 'wp-macchiato' ),
		) );
		$wp_customize->add_setting( 'wp_macchiato_logo', array (
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wp_macchiato_logo', array(
			'label'    => __( 'Choose your logo (ideal width is 100-300px and ideal height is 40-100px)', 'wp-macchiato' ),
			'section'  => 'wp_macchiato_logo_section',
			'settings' => 'wp_macchiato_logo',
		) ) );

		/* color theme */
		$wp_customize->add_setting( 'wp_macchiato_primary_theme_color', array (
			'default' => '#e4402b',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wp_macchiato_primary_theme_color', array(
			'label'    => __( 'Primary Menu Color', 'wp-macchiato' ),
			'section'  => 'colors',
			'settings' => 'wp_macchiato_primary_theme_color',
			'priority' => 21,
		) ) );

		$wp_customize->add_setting( 'wp_macchiato_secondary_theme_color', array (
			'default' => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wp_macchiato_secondary_theme_color', array(
			'label'    => __( 'Top Menu Colors', 'wp-macchiato' ),
			'section'  => 'colors',
			'settings' => 'wp_macchiato_secondary_theme_color',
			'priority' => 22,
		) ) );		

		// author bio in posts option 
		$wp_customize->add_section( 'wp_macchiato_author_bio_section' , array(
			'title'       => __( 'Display Author Bio', 'wp-macchiato' ),
			'priority'    => 23,
			'description' => __( 'Option to show/hide the author bio in the posts.', 'wp-macchiato' ),
		) );
		
		$wp_customize->add_setting( 'wp_macchiato_author_bio', array (
			'default'        => 0,
			'sanitize_callback' => 'wp_macchiato_sanitize_checkbox',
		) );

		 $wp_customize->add_control('author_bio', array(
			'settings' => 'wp_macchiato_author_bio',
			'label' => __('Show the author bio in posts?', 'wp-macchiato'),
			'section' => 'wp_macchiato_author_bio_section',
			'type' => 'checkbox',
		));
		
		// home_layout
		$wp_customize->add_section( 'wp_macchiato_home_layout_section' , array(
			'title'       => __( 'Page Layout', 'wp-macchiato' ),
			'priority'    => 23,
			'description' => __( 'Select your page layout.', 'wp-macchiato' ),
		) );
		
		$wp_customize->add_setting('wp_macchiato_home_layout', array(
			'default'        => 'three',
			'sanitize_callback' => 'wp_macchiato_sanitize_text_field',
	 
		));
		$wp_customize->add_control( 'home_layout', array(
			'settings' => 'wp_macchiato_home_layout',
			'label'   => 'Select layout:',
			'section' => 'wp_macchiato_home_layout_section',
			'type'    => 'select',
			'choices'    => array(
				'two' => '2 Column',
				'three' => '3 Column',
			),
		));		
	}

endif;

add_action('customize_register', 'wp_macchiato_theme_customizer');

if ( ! function_exists( 'wp_macchiato_sanitize_text_field' ) ){
	function wp_macchiato_sanitize_text_field( $str ) {
		return sanitize_text_field( $str );
	}
}		
/**
 * Sanitize integer input
 */
if ( ! function_exists( 'wp_macchiato_sanitize_integer' ) ) :
	function wp_macchiato_sanitize_integer( $input ) {		
		return absint($input);
	}
endif;

/**
 * Sanitize checkbox
 */
if ( ! function_exists( 'wp_macchiato_sanitize_checkbox' ) ) :
	function wp_macchiato_sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return 0;
		}
	}
endif;

/**
* Apply Color Scheme
*/
if ( ! function_exists( 'wp_macchiato_apply_color' ) ) :
  function wp_macchiato_apply_color() {
	 if ( get_theme_mod('wp_macchiato_primary_theme_color') ) {
	?>
	<style id="color-settings">
	<?php if ( get_theme_mod('wp_macchiato_primary_theme_color') ) : ?>
	.navbar-default .navbar-collapse, .dropdown-menu >li, .pagination .fa, .navbar-default .navbar-toggle, #respond #submit, .post-content form input[type=submit], .post-content form input[type=button], .main-navigation ul ul a, .tagcloud a, #footer .widget_calendar thead tr, .archive #read-more{
		background:<?php echo get_theme_mod('wp_macchiato_primary_theme_color'); ?>;
		}
		#top-bar, #top-bar .dropdown-menu >li, aside ul li a, .widget-container, h2.comments-title{border-color:<?php echo get_theme_mod('wp_macchiato_primary_theme_color'); ?>;}
		aside.widget_recent_comments ul li:before, aside.widget_archive ul li:before, aside.widget_categories ul li:before, aside.widget_meta ul li:before{
			border-color:transparent transparent transparent <?php echo get_theme_mod('wp_macchiato_primary_theme_color'); ?>;
			}
		#top-nav .main-navigation ul.sub-menu > li, section.no-results{border-color:<?php echo get_theme_mod('wp_macchiato_primary_theme_color'); ?>;}
		.widget_calendar td a, h1.entry-title a:hover, cite.fn, cite.fn a, a.comment-reply-link, aside ul li:before, #top-nav .current_page_item > a, #top-nav .current-menu-item > a, #top-nav .current_page_ancestor > a, #top-bar .navbar-nav > li > a:hover, aside ul li a:hover, .meta-info a:hover, #copyright a:hover, h1.title a:hover, h2.title a, a{color:<?php echo get_theme_mod('wp_macchiato_primary_theme_color'); ?>;}
	<?php endif; ?>

	<?php if ( get_theme_mod('wp_macchiato_secondary_theme_color') ) : ?>
		#top-bar .dropdown-menu >li, #top-bar, #top-bar .navbar-default .navbar-collapse, .navbar-default .navbar-toggle .icon-bar{background:<?php echo get_theme_mod('wp_macchiato_secondary_theme_color'); ?>;}		
		.main-navigation ul.sub-menu > li{border-color:<?php echo get_theme_mod('wp_macchiato_secondary_theme_color'); ?>; }
		#top-nav .main-navigation ul ul a, #copyright, .archive #read-more:hover{background:<?php echo get_theme_mod('wp_macchiato_secondary_theme_color'); ?>; }
		h2.title a:hover{color:<?php echo get_theme_mod('wp_macchiato_secondary_theme_color'); ?>; }
	<?php endif; ?>
	</style>
	<?php	  
	} 
  }
endif;

add_action( 'wp_head', 'wp_macchiato_apply_color' );