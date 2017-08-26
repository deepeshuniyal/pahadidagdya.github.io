<?php
/**
 * Function describe for Red Mag 
 * 
 * @package red-mag
 */

add_action( 'wp_enqueue_scripts', 'red_mag_enqueue_styles', 999 );
function red_mag_enqueue_styles() {
  $parent_style = 'red-mag-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'red-mag-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );

}

function red_mag_theme_setup() {
    
    load_child_theme_textdomain( 'red-mag', get_stylesheet_directory() . '/languages' );
    
    // Add Custom logo Support
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		
		// Add Custom Background Support
		$args = array(
			'default-color' => 'ffffff',
		);
		add_theme_support( 'custom-background', $args );
    
}
add_action( 'after_setup_theme', 'red_mag_theme_setup' );

// remove admin options

function red_mag_admin_remove( $wp_customize ) {
    
    $wp_customize->remove_control( 'header-logo' );
    $wp_customize->remove_section('site_bg_section');
}

add_action( 'customize_register', 'red_mag_admin_remove', 100);

