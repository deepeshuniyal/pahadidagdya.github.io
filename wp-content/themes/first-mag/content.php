<article class="rsrc-archive col-md-6"> 
	<div <?php post_class(); ?>>                            
		<?php if ( has_post_thumbnail() ) : ?>                                
			<div class="featured-thumbnail col-md-12">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_post_thumbnail( 'first-mag-home' ); ?>
					<?php if ( function_exists( 'wp_review_show_total' ) ) wp_review_show_total(); ?>
				</a>
			</div>                                                           
		<?php endif; ?>
		<div class="home-header col-md-12"> 
			<header>
				<h2 class="page-header">                                
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
						<?php the_title(); ?>
					</a>                            
				</h2> 
				<?php get_template_part( 'template-part', 'postmeta' ); ?>
			</header>                                                      
			<div class="entry-summary">
				<?php $content = get_the_content();
				echo wp_trim_words( strip_shortcodes( $content ), '15', $more	 = '...' );
				?> 
			</div><!-- .entry-summary -->                                                                                                                       
			<div class="clear"></div>                                                            
		</div>                      
	</div>
	<div class="clear"></div>
</article>