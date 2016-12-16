
<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( has_post_thumbnail() ): ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="home-thumb boxed">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		<?php endif; ?>
	</header>

	<div class="entry-content boxed">
		<?php the_content(); ?>

		<?php wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'upright' ),
			'after'  => '</div>'
		) ); ?>
	</div>
</article>
