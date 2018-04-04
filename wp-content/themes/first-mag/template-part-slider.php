<div id="slider" class="flexslider slider-loading" >										   
	<ul class="slides">										        									     
		<?php
		$get_featured_posts = new WP_Query( array(
			'posts_per_page' => 5,
			'post_type'		 => 'post',
			'category__in'	 => get_theme_mod( 'featured-categories' )
		) );
		while ( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
			?>                    	     
			<li class="col-md-12">                    	        
				<div class="flex-img">                    	           
					<a href="<?php the_permalink(); ?>">                                         
						<?php if ( has_post_thumbnail() ) { ?>        												           
							<div class="featured-thumbnail"><?php the_post_thumbnail( 'first-mag-slider' ); ?></div>      											         
						<?php } else { ?>                                       
							<img src="<?php echo get_template_directory_uri(); ?>/img/noprew-slider.jpg" alt="<?php the_title_attribute(); ?>">       									               
						<?php } ?>                              
					</a>                                                                
				</div>                               
				<div class="flex-caption">		                     						         
					<div class="flex-title home-header">                      						                                                                                                     
						<header>
							<h2 class="page-header">                                
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>                            
							</h2> 
						</header>                                                      
						<div class="entry-summary">
							<?php $content = get_the_content();
							echo wp_trim_words( strip_shortcodes( $content ), '15', $more	 = '...' ); ?> 
						</div><!-- .entry-summary -->                                                                                                                                                                                                              
					</div>                                  
				</div>                            
			</li>  											     
		<?php endwhile;
		wp_reset_postdata(); ?>  										   
	</ul>									 
</div>
