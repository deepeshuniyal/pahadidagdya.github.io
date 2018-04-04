<!-- start content container -->
<?php if ( has_post_thumbnail() ) : ?>                                
	<div class="single-thumbnail row"><?php echo get_the_post_thumbnail( $post->ID, 'first-mag-single' ); ?></div>                                     
	<div class="clear"></div>                            
<?php endif; ?> 
<div class="row rsrc-content">    
	<?php //left sidebar ?>    
	<?php get_sidebar( 'left' ); ?>    
	<article class="col-md-<?php first_mag_main_content_width(); ?> rsrc-main">        
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>         
				<?php
				if ( get_theme_mod( 'breadcrumbs-check', 1 ) != 0 ) {
					first_mag_breadcrumb();
				}
				?>              
				<div <?php post_class( 'rsrc-post-content' ); ?>>                            
					<header>                              
						<h1 class="entry-title page-header">
							<?php the_title(); ?>
						</h1> 
						<time class="posted-on published" datetime="<?php the_time( 'Y-m-d' ); ?>"></time>                                                        
					</header>                            
					<div class="entry-content" >                              
						<?php the_content(); ?>                            
					</div>                               
					<?php wp_link_pages(); ?>                             
					<div class="post-navigation row">
						<div class="post-previous col-md-6"><?php previous_post_link( '%link', '<span class="meta-nav">' . __( 'Previous:', 'first-mag' ) . '</span> %title' ); ?></div>
						<div class="post-next col-md-6"><?php next_post_link( '%link', '<span class="meta-nav">' . __( 'Next:', 'first-mag' ) . '</span> %title' ); ?></div>
					</div>                                                   
					<?php comments_template(); ?>                         
				</div>        
			<?php endwhile; ?>        
		<?php else: ?>            
			<?php get_template_part( 'content', 'none' ); ?>        
		<?php endif; ?>    
	</article>    
	<?php //get the right sidebar   ?>    
	<?php get_sidebar( 'right' ); ?>
</div>
<!-- end content container -->