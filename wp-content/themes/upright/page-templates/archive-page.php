<?php
/**
 * Template Name: Archives
 * A custom page to serve archive
 */

get_header(); ?>

	<div id="primary" class="content-area boxed">
		<?php upright_breadcrumb(); ?>
		
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>

						<?php if ( has_post_thumbnail() ): ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="home-thumb boxed">
								<?php the_post_thumbnail( 'large' ); ?>
							</a>
						<?php endif; ?>
					</header><!-- .entry-header -->

					<div class="entry-content boxed">
						<?php the_content(); ?>
						<h3><?php _e( 'Last 30 Posts', 'upright' ); ?></h3>
						<ul>
							<?php
							$r = new WP_Query( array( 'showposts'           => 30,
							                          'post_status'         => 'publish',
							                          'ignore_sticky_posts' => 1
							) );
							while ( $r->have_posts() ) : $r->the_post();
								?>
								<li>
									<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
								</li>
								<?php
							endwhile;
							?>
						</ul>
						<h3><?php _e( 'Archives by Month:', 'upright' ); ?></h3>
						<ul>
							<?php wp_get_archives( 'type=monthly' ); ?>
						</ul>
					</div><!-- .entry-content -->
				</article>

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