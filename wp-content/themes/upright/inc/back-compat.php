<?php
/**
 * Upright back compat functionality
 *
 * Prevents Upright from running on WordPress versions prior to 4.5,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.5.
 */

/**
 * Prevent switching to Upright on old versions of WordPress.
 *
 * Switches to the default theme.
 */
function upright_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

	unset( $_GET['activated'] );

	add_action( 'admin_notices', 'upright_upgrade_notice' );
}
add_action( 'after_switch_theme', 'upright_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Upright on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function upright_upgrade_notice() {
	$message = sprintf( __( 'Upright requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'upright' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function upright_customize() {
	wp_die( sprintf( __( 'Upright requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'upright' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'upright_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function upright_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Upright requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'upright' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'upright_preview' );
