<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header sticky-nav" role="banner">
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav role="navigation" class="site-navigation main-navigation group">
				<div class="assistive-text skip-link">
					<a href="#content" title="<?php esc_attr_e( 'Skip to content', 'upright' ); ?>"><span>&nbsp;</span><span><?php _e( 'Skip to content', 'upright' ); ?></span></a>
				</div>
				<div>
					<?php add_filter( 'wp_page_menu', 'upright_strip_div_menu_page' ); ?>

					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'menu group',
						'container'      => false
					) ); ?>

					<?php remove_filter( 'wp_page_menu', 'upright_strip_div_menu_page' ); ?>

					<?php get_search_form(); ?>
				</div>
			</nav>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-header' ) ) : ?>
			<div id="sidebar-top" class="boxed">
				<?php dynamic_sidebar( 'sidebar-header' ); ?>
			</div>
		<?php endif; ?>

		<div class="logo boxed">
			<?php if ( has_custom_logo() ) : ?>

				<?php the_custom_logo(); ?>

			<?php else : ?>

				<h2 class="site-title h1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				                             title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
				                             rel="home"><?php bloginfo( 'name' ); ?></a></h2>
				<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>

			<?php endif; ?>
		</div>

		<?php if ( has_nav_menu( 'secondary' ) ) : ?>
			<nav role="navigation" class="site-navigation secondary-navigation group">
				<?php wp_nav_menu( array(
					'theme_location' => 'secondary',
					'menu_class' => 'menu group'
				) ); ?>
			</nav>
		<?php endif; ?>
	</header>

	<div id="main" class="site-main boxed group">