<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

	<div id="primary" class="content-area boxed">
		<?php
		/**
		 * Display Slider based on Settings
		 */
		if ( upright_get_option( 'enable_slider', false ) ) {
			$slider_cat = upright_get_option( 'slider_category' );
			$slider_num = upright_get_option( 'slider_post_number' );

			upright_featured_posts( 'cat=' . $slider_cat . '&showposts=' . $slider_num );
		}
		?>
		<?php
		/**
		 * Display Carousel based on Settings
		 */
		if ( upright_get_option( 'enable_carousel', false ) ) {
			$carousel_cat = upright_get_option( 'carousel_category' );
			$carousel_num = upright_get_option( 'carousel_post_number' );

			upright_carousel_posts( 'cat=' . $carousel_cat . '&showposts=' . $carousel_num );
		}
		?>
		<h3 class="section-title"><span><?php echo esc_html( upright_get_option( 'recent_post_title', __( 'Recent Posts', 'upright' ) ) ); ?></span></h3>

		<?php $upright_layout_class = upright_get_option( 'post_layout_default' ) ? upright_get_option( 'post_layout_default' ) : 'two-column'; ?>

		<div id="content" class="site-content group <?php echo esc_attr( $upright_layout_class ); ?>" role="main">

			<?php if ( have_posts() ) : ?>
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );
					?>

				<?php endwhile; ?>

				<?php upright_pagination(); ?>

				<div class="layout-toggle group">
					<a class="layout-grid" href="#"><?php _e( 'Grid', 'upright' ); ?></a>
					<a class="layout-list" href="#"><?php _e( 'List', 'upright' ); ?></a>
				</div>

			<?php else : ?>

				<?php get_template_part( 'template-parts/no-results', 'index' ); ?>

			<?php endif; ?>

		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>