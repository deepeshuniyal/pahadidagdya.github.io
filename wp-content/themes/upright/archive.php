<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

	<div id="primary" class="content-area boxed">
		<?php upright_breadcrumb(); ?>
		
		<h3 class="section-title"><span>
				<?php
				if ( is_category() ) {
					printf( __( 'Category: %s', 'upright' ), '<span>' . single_cat_title( '', false ) . '</span>' );

				} elseif ( is_tag() ) {
					printf( __( 'Tag: %s', 'upright' ), '<span>' . single_tag_title( '', false ) . '</span>' );

				} elseif ( is_author() ) {
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					*/
					the_post();
					printf( __( 'Author: %s', 'upright' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();

				} elseif ( is_day() ) {
					printf( __( 'Daily Archives: %s', 'upright' ), '<span>' . get_the_date() . '</span>' );

				} elseif ( is_month() ) {
					printf( __( 'Archives: %s', 'upright' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				} elseif ( is_year() ) {
					printf( __( 'Yearly Archives: %s', 'upright' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				} else {
					_e( 'Archives', 'upright' );

				}
				?>
			</span></h3>

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