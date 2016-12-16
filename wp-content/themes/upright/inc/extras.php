<?php
/**
 * Custom functions that act independently of the theme templates
 */


/**
 * adding a class to the ul of menu, this is used in fallback menu
 *
 * @param $menu
 *
 * @return string
 */
function upright_strip_div_menu_page( $menu ) {
	$menu = str_replace( '<ul>', '<ul class="group">', $menu );

	return strip_tags( $menu, '<ul><li><a><span>' );
}


/**
 * Adds custom classes to the array of body classes.
 */
function upright_body_classes( $classes ) {

	$classes[] = upright_get_option( 'layout_default' ) ? sanitize_html_class( upright_get_option( 'layout_default' ) ) : 'left-sidebar';

	if ( upright_get_option( 'hide_layout_toggle' ) ) {
		$classes[] = 'hide-layout-toggle';
	}

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

add_filter( 'body_class', 'upright_body_classes' );


/**
 * Set the excerpt length.
 *
 * @param $length
 *
 * @return int
 */
function upright_excerpt_length( $length ) {
	return 20;
}

add_filter( 'excerpt_length', 'upright_excerpt_length', 999 );


/**
 * Set the excerpt more text
 *
 * @param $more
 *
 * @return string
 */
function upright_excerpt_more( $more ) {
	return '...';
}

add_filter( 'excerpt_more', 'upright_excerpt_more' );


/**
 * Exclude some posts with certain categories from recent posts stream
 *
 * @param WP_Query $query
 */
function upright_exclude_categories( $query ) {

	if ( $query->is_home() && $query->is_main_query() && upright_get_option( 'exclude_categories' ) ) {
		$query->query_vars['category__not_in'] = upright_get_option( 'exclude_categories' );
	}

}

add_action( 'pre_get_posts', 'upright_exclude_categories' );
