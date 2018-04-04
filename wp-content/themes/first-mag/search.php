<?php get_header(); ?>

<?php get_template_part( 'template-part', 'head' ); ?>

<?php get_template_part( 'template-part', 'topnav' ); ?>

<!-- start content container -->
<div class="row rsrc-content">
	<?php //left sidebar ?>
	<?php get_sidebar( 'left' ); ?> 
    <div class="col-md-<?php first_mag_main_content_width(); ?> rsrc-main">
		<?php
		if ( get_theme_mod( 'breadcrumbs-check', 1 ) != 0 ) {
			first_mag_breadcrumb();
		}
		$total_results = $wp_query->found_posts;

		echo "<div class='rsrc-post-content'><h2 class='text-center'>" . sprintf( __( '%s Search Results for "%s"', 'first-mag' ), $total_results, get_search_query() ) . "</h2>";

		if ( have_posts() ) : while ( have_posts() ) : the_post();

				/* Include the Post-Format-specific template for the content.
				 * If you want to overload this in a child theme then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
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
<?php //get the right sidebar    ?>
<?php get_sidebar( 'right' ); ?>

</div>
<!-- end content container -->
<?php get_footer(); ?>
