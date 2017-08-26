<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

	<div id="primary" class="content-area boxed">
		<?php upright_breadcrumb(); ?>
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<div class="entry-meta">
							<?php
							$metadata = wp_get_attachment_metadata();
							printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'upright' ),
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_url( wp_get_attachment_url() ),
								intval( $metadata['width'] ),
								intval( $metadata['height'] ),
								esc_url( get_permalink( get_post()->post_parent ) ),
								esc_attr( get_the_title( get_post()->post_parent ) ),
								get_the_title( get_post()->post_parent )
							);
							?>
							<?php edit_post_link( __( 'Edit', 'upright' ), '<span class="sep"> | </span> <span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry-meta -->
						<h1 class="entry-title"><?php the_title(); ?></h1>

					</header><!-- .entry-header -->

					<div class="entry-content boxed">
						<div class="entry-content">

							<div class="entry-attachment">
								<div class="attachment">
									<?php
									/**
									 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
									 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
									 */
									$attachments = array_values( get_children( array(
										'post_parent'    => $post->post_parent,
										'post_status'    => 'inherit',
										'post_type'      => 'attachment',
										'post_mime_type' => 'image',
										'order'          => 'ASC',
										'orderby'        => 'menu_order ID'
									) ) );
									foreach ( $attachments as $k => $attachment ) {
										if ( $attachment->ID == $post->ID ) {
											break;
										}
									}
									$k ++;
									// If there is more than 1 attachment in a gallery
									if ( count( $attachments ) > 1 ) {
										if ( isset( $attachments[ $k ] ) ) // get the URL of the next image attachment
										{
											$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
										} else // or get the URL of the first image attachment
										{
											$next_attachment_url = get_attachment_link( $attachments[0]->ID );
										}
									} else {
										// or, if there's only 1 image, get the URL of the image
										$next_attachment_url = wp_get_attachment_url();
									}
									?>

									<a href="<?php echo $next_attachment_url; ?>"
									   title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
										echo wp_get_attachment_image( $post->ID, 'large' );
										?></a>
								</div><!-- .attachment -->

								<?php if ( ! empty( $post->post_excerpt ) ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div><!-- .entry-caption -->
								<?php endif; ?>
							</div><!-- .entry-attachment -->

							<?php the_content(); ?>
							<?php wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'upright' ),
								'after'  => '</div>'
							) ); ?>
						</div><!-- .entry-content -->

				</article>

			<?php endwhile; // end of the loop. ?>

		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>