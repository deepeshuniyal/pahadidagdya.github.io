<?php
if ( get_option( 'show_on_front' ) == 'page' ) {
	include( get_page_template() );
} else {
	?>
	<?php get_header(); ?>

	<?php get_template_part( 'template-part', 'head' ); ?>

	<?php get_template_part( 'template-part', 'topnav' ); ?>

	<!-- start content container -->
	<div class="row rsrc-content">

	<?php get_sidebar( 'left' ); ?>


	    <div class="col-md-<?php first_mag_main_content_width(); ?> rsrc-main">
			<?php //slider section ?>
			<?php if ( get_theme_mod( 'featured-categories', '1' ) != '' && get_theme_mod( 'get-featured', '1' ) != '0' ) : ?>
				<?php get_template_part( 'template-part', 'slider' ); ?>
			<?php endif; ?>


			<div id="content-top-section" class="clearfix">
				<?php
				if ( !is_paged()) {
					// Calling the sidebar if it exists.
					if ( !dynamic_sidebar( 'first-mag-front-page' ) ):
						the_widget( 'first_mag_featured_posts_widget_second', 'title=Widget Title&meta=on', 'before_title=<h3 class="widget-title"><div class="title-text">&after_title=</div><div class="widget-line"></div></h3>' );
						the_widget( 'first_mag_featured_posts_widget', 'title=Widget Title&meta=on', 'before_title=<h3 class="widget-title"><div class="title-text">&after_title=</div><div class="widget-line"></div></h3>' );
					endif;
				}
				?>
			</div>
			<div class="front-page-content">
				<?php
				if ( have_posts() ) : while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
					endwhile;
					?>
					<div class="footer-pagination"><?php the_posts_pagination(); ?></div>
					<?php
				else :
					get_template_part( 'content', 'none' );
				endif;
				?>
			</div>   
		</div>

	<?php get_sidebar( 'right' ); ?>

	</div>
	<!-- end content container -->
	<?php get_footer(); ?>
<?php } ?>
