
<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?>>
	<header class="entry-header">
		<div class="entry-meta-single">
			<?php
			echo '<span class="inline-icon-clock">';
			upright_posted_on();
			echo '</span> ';
			
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'upright' ) );
			
			if ( $categories_list && upright_categorized_blog() ) {
				printf( __( '<span class="inline-icon-ribbon">%1$s</span>', 'upright' ), $categories_list );
			}
			?>
			<span
				class="inline-icon-comment"><?php comments_popup_link( __( 'Leave a comment', 'upright' ), __( '1 Comment', 'upright' ), __( '% Comments', 'upright' ) ); ?></span>

			<?php edit_post_link( __( 'Edit', 'upright' ), '<span class="inline-icon-pencil">', '</span>' ); ?>
		</div>
		
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( has_post_thumbnail() ): ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="home-thumb boxed">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		<?php endif; ?>
	</header>

	<div class="entry-content boxed">
		<?php the_content(); ?>
		<p class="post-tags"><?php the_tags( '<span class="inline-icon-tag hidden-text-icon">' . __( 'Tags:', 'upright' ) . '</span> ', ', ', '' ); ?></p>
		<?php wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'upright' ),
			'after'  => '</div>'
		) ); ?>
	</div>

	<?php if ( 'post' == get_post_type() ) : ?>
		<aside class="related-box boxed">
			<h3 class="section-title"><?php _e( 'Related Posts', 'upright' ); ?></h3>
			<?php upright_related_posts(); ?>
		</aside>
	<?php endif; ?>

	<footer class="boxed">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), 50, '', '', array( 'class' => 'author-avatar alignleft' ) ) ?>
		<p><span class="inline-icon-user"><?php the_author_posts_link(); ?></span></p>
		<p class="author-description"><?php the_author_meta( 'description' ); ?></p>
	</footer>
</article>
