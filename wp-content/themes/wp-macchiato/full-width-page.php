<?php
/**
 * Template Name: Full-width, no sidebar
 * Description: A full-width template with no sidebar
 */
 ?>
<?php get_header(); ?>
<div class="row">
	<div id="primary" class="content-area col-md-12 ">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>				
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->	
</div>
<?php get_footer(); ?>