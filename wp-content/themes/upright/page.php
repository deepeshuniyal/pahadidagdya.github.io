<?php
/**
 * The Template for displaying all pages.
 *


 */

get_header(); ?>

	<div id="primary" class="content-area boxed">
		<?php upright_breadcrumb(); ?>
		
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) {
					comments_template( '', true );
				}
				?>

			<?php endwhile; // end of the loop. ?>

		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>