<?php
/**
 * @package WP Macchiato
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">
    	<?php if ( '' != get_the_post_thumbnail()) { ?>
        <div class="col-md-6">
            <div class="image-container-responsive">
                <a href="<?php the_permalink('') ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('wp-macchiato-feature-image'); ?></a>
                <div id="read-more">
                    <a href="<?php the_permalink('') ?>" ><?php _e( 'Read More', 'wp-macchiato' ); ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="details-container">
            <h2 class="title"><a href="<?php the_permalink('') ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
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
            <div class="post_excerpt">
                <?php the_excerpt(); ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="col-md-12">
            <div class="details-container">
            <h2 class="title"><a href="<?php the_permalink('') ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
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
            <div class="post_excerpt">
                <?php the_excerpt(); ?>
            </div><br />
            <div id="read-more" class="no-feature-margin">
                <a href="<?php the_permalink('') ?>" ><?php _e( 'Read More', 'wp-macchiato' ); ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
</article><!-- #post-## -->

