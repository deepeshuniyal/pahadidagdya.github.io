<?php
/**
 * @package WP Macchiato
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="image-container-responsive">
        <a href="<?php the_permalink('') ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('wp-macchiato-feature-image'); ?></a>
    </div>
    <div class="details-container">
    	<h1 class="title"><a href="<?php the_permalink('') ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
        <div class="entry-meta">
            <div class="featured-category">
                <ul class="meta-info">
                    <li><i class="fa fa-user"></i><?php the_author_posts_link(); ?></li>
                    <li><i class="fa fa-clock-o"></i><?php the_time( get_option( 'date_format' ) ); ?></li>
                    <li><i class="fa fa-comments"></i><a href="<?php comments_link(); ?>" class="meta-comment"><?php comments_number( '0 comment', '1 comment', '% comments' ); ?></a></li>
                </ul>
            </div>
        </div>
	</div>
	<div class="entry-content">
		<?php the_content(); ?>
        <?php if (get_theme_mod('wp_macchiato_author_bio') ) : ?>
            <div class="author-bio">        
                <?php 
                $author_avatar = get_avatar( get_the_author_meta('email'), '75' );
                if ($author_avatar) : ?>
                    <div class="author-thumb"><?php echo $author_avatar; ?></div>
                <?php endif; ?>
                <div class="author-info">
                    <?php $author_posts_url = get_author_posts_url( get_the_author_meta( 'ID' )); ?> 
                    <h4 class="author-title"><?php _e('Posted by ', 'wp-macchiato'); ?><a href="<?php echo esc_url($author_posts_url); ?>" title="<?php printf( __( 'View all posts by %s', 'wp-macchiato' ), get_the_author() ) ?>"><?php the_author(); ?></a></h4>
                    <?php $author_desc = get_the_author_meta('description');
                    if ( $author_desc ) : ?>
                    <p class="author-description"><?php echo $author_desc; ?></p>
                    <?php endif; ?>
                    <?php $author_url = get_the_author_meta('user_url');
                    if ( $author_url ) : ?>
                    <p><?php _e('Website: ', 'wp-macchiato') ?><a href="<?php echo $author_url; ?>"><?php echo $author_url; ?></a></p>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
        </div>
        <?php endif; ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'wp-macchiato' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
    <?php wp_macchiato_post_nav(); ?>
    <?php
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	?>
</article><!-- #post-## -->

