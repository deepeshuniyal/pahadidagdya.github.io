<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WP Macchiato
 */
get_header();
$wp_macchiato_home_layout =  get_theme_mod( 'wp_macchiato_home_layout' );
if($wp_macchiato_home_layout =='three'){
?>
<div class="row">
	<div id="primary" class="content-area col-lg-6 col-lg-push-3">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>				
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->
	<div class="col-lg-3 col-lg-pull-6">
        <aside id="left-widget" class="widget-container">
            <?php if ( is_active_sidebar( 'left-sidebar' ) ) { ?>
                <?php dynamic_sidebar( 'left-sidebar' ); ?>
            <?php } ?>
        </aside>
    </div>
    <div class="col-md-3 ">
        <aside id="right-widget" class="widget-container">
            <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
                <?php dynamic_sidebar( 'rigth-sidebar' ); ?>
            <?php } ?>
        </aside>
    </div>
</div>
<?php } else {?>
<div class="row">
	<div id="primary" class="content-area col-lg-9">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>				
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->
    <div class="col-md-3 ">
        <aside id="right-widget" class="widget-container">
            <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
                <?php dynamic_sidebar( 'rigth-sidebar' ); ?>
            <?php } ?>
        </aside>
    </div>
</div>
<?php } ?>
<?php get_footer(); ?>

