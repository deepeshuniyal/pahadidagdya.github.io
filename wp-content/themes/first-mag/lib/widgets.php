<?php
/**
 * Widgets
 *
 */

/**
 * Featured Posts widget
 */
class first_mag_featured_posts_widget extends WP_Widget {

	function __construct() {
		$widget_ops	 = array( 'classname' => 'widget_featured_posts first-mag-widget row', 'description' => __( 'Display latest posts or posts of specific category. Only for Front Page: Content Section!', 'first-mag' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		parent::__construct( false, $name		 = __( 'First Mag: Category Widget 1', 'first-mag' ), $widget_ops );
	}

	function form( $instance ) {
		$first_mag_defaults[ 'title' ]	 = '';
		$first_mag_defaults[ 'text' ]		 = '';
		$first_mag_defaults[ 'number' ]	 = 5;
		$first_mag_defaults[ 'meta' ]		 = 'off';
		$first_mag_defaults[ 'type' ]		 = 'latest';
		$first_mag_defaults[ 'category' ]	 = '';
		$instance						 = wp_parse_args( (array) $instance, $first_mag_defaults );
		$title							 = esc_attr( $instance[ 'title' ] );
		$text							 = esc_textarea( $instance[ 'text' ] );
		$number							 = $instance[ 'number' ];
		$type							 = $instance[ 'type' ];
		$category						 = $instance[ 'category' ];
		$meta							 = $instance[ 'meta' ];
		?>
		<p><?php _e( 'Widget Layout:', 'first-mag' ) ?></p>
		<div style="text-align: center;"><img src="<?php echo get_template_directory_uri() . '/img/feat-cat1.jpg' ?>"></div>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php _e( 'Description', 'first-mag' ); ?>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to display:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e( 'Enable or disable post meta', 'first-mag' ); ?>:</label><br />
			<input type="radio" <?php checked( $meta, 'on' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="on"/><?php _e( 'Enable', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $meta, 'off' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="off"/><?php _e( 'Disable', 'first-mag' ); ?><br />
		</p>
		<p><input type="radio" <?php checked( $type, 'latest' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $type, 'category' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category:', 'first-mag' ); ?><br /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'first-mag' ); ?>:</label>
			<?php wp_dropdown_categories( array( 'show_option_none' => ' ', 'name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance				 = $old_instance;
		$instance[ 'title' ]	 = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'text' ]		 = stripslashes( wp_filter_post_kses( addslashes( $new_instance[ 'text' ] ) ) );
		$instance[ 'number' ]	 = absint( $new_instance[ 'number' ] );
		$instance[ 'type' ]		 = strip_tags( $new_instance[ 'type' ] );
		$instance[ 'category' ]	 = absint( $new_instance[ 'category' ] );
		$instance[ 'meta' ]		 = strip_tags( $new_instance[ 'meta' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$title		 = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$text		 = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
		$number		 = empty( $instance[ 'number' ] ) ? 5 : $instance[ 'number' ];
		$type		 = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest';
		$category	 = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';
		$meta		 = isset( $instance[ 'meta' ] ) ? $instance[ 'meta' ] : 'off';


		$category_link = get_category_link( $category );
		if ( $category != '' && $type != 'latest' ) {
			$cat_link = esc_url( $category_link );
		} else {
			$cat_link = get_permalink( get_option( 'page_for_posts' ) );
		}

		if ( $type == 'latest' ) {
			$get_posts = new WP_Query( array(
				'posts_per_page'		 => $number,
				'post_type'				 => 'post',
				'ignore_sticky_posts'	 => true
			) );
		} else {
			$get_posts = new WP_Query( array(
				'posts_per_page' => $number,
				'post_type'		 => 'post',
				'category__in'	 => $category
			) );
		}
		echo $before_widget;
		?>
		<?php
		if ( !empty( $title ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}
		if ( !empty( $text ) ) {
			?> <p class="col-md-12"> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
		<?php
		$i = 1;
		while ( $get_posts->have_posts() ) : $get_posts->the_post();
			?>
			<?php if ( $i == 1 ) {
				$thumb		 = 'first-mag-home';
				$grid		 = 'col-md-12';
				$grid_title	 = 'col-md-12';
			} else {
				$thumb		 = 'first-mag-home-small';
				$grid		 = 'col-xs-4';
				$grid_title	 = 'col-xs-8';
			} ?>
					<?php if ( $i == 1 ) {
						echo '<div class="first-post col-md-6">';
					} elseif ( $i == 2 ) {
						echo '<div class="small-post col-md-6">';
					} ?>
			<article > 
				<div <?php post_class(); ?>>                            
							<?php if ( has_post_thumbnail() ) : ?>                             
						<div class="featured-thumbnail <?php echo $grid ?>">
							<a href="<?php the_permalink(); ?>" rel="bookmark">    
								<?php the_post_thumbnail( $thumb ); ?>
							<?php if ( function_exists( 'wp_review_show_total' ) ) wp_review_show_total(); ?>
							</a>
						</div>                                                           
							<?php endif; ?>
					<div class="home-header <?php echo $grid_title ?>"> 
						<header>
							<h2 class="page-header">                                
			                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'first-mag' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
									<?php the_title(); ?>
			                    </a>                            
							</h2> 
							<?php if ( $meta == 'on' ) : ?>
								<?php get_template_part( 'template-part', 'postmeta' ); ?>
							<?php endif; ?>   
						</header>                                                      
						<?php if ( $i == 1 ) : ?>
							<div class="entry-summary">
								<?php $content = get_the_content();
								echo wp_trim_words( strip_shortcodes( $content ), '25', $more	 = '...' ); ?> 
							</div><!-- .entry-summary -->                                                                                                                       
						<?php endif; ?>                                         
					</div>                      
				</div>
			</article>
			<?php if ( $i == 1 ) {
				echo '</div>';
			} ?>
			<?php
			$i++;
		endwhile;
		if ( $i > 2 ) {
			echo '</div>';
		}
		// Reset Post Data
		wp_reset_query();
		?>
		<?php if ( $type == 'category' ) { ?>
			<div class="widget-footer col-md-12 text-center">
				<a class="btn btn-primary btn-md outline" href="<?php echo esc_url( $cat_link ); ?>" >
					<?php _e( 'All ', 'first-mag' ) ?><?php $categorys = get_category( $category );
					echo esc_attr( $categorys->category_count ); ?><?php _e( ' Articles', 'first-mag' ) ?>
				</a>
			</div>
		<?php } ?> 
		<!-- </div> -->
		<?php
		echo $after_widget;
	}

}

/**
 * Featured Posts widget 2
 */
class first_mag_featured_posts_widget_second extends WP_Widget {

	function __construct() {
		$widget_ops	 = array( 'classname' => 'widget_featured_posts_second first-mag-widget row', 'description' => __( 'Display latest posts or posts of specific category. Only for Front Page: Content Section!', 'first-mag' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		parent::__construct( false, $name		 = __( 'First Mag: Category Widget 2', 'first-mag' ), $widget_ops );
	}

	function form( $instance ) {
		$first_mag_defaults[ 'title' ]	 = '';
		$first_mag_defaults[ 'text' ]		 = '';
		$first_mag_defaults[ 'number' ]	 = 5;
		$first_mag_defaults[ 'meta' ]		 = 'off';
		$first_mag_defaults[ 'type' ]		 = 'latest';
		$first_mag_defaults[ 'category' ]	 = '';
		$instance						 = wp_parse_args( (array) $instance, $first_mag_defaults );
		$title							 = esc_attr( $instance[ 'title' ] );
		$text							 = esc_textarea( $instance[ 'text' ] );
		$number							 = $instance[ 'number' ];
		$type							 = $instance[ 'type' ];
		$category						 = $instance[ 'category' ];
		$meta							 = $instance[ 'meta' ];
		?>
		<p><?php _e( 'Widget Layout:', 'first-mag' ) ?></p>
		<div style="text-align: center;"><img src="<?php echo get_template_directory_uri() . '/img/feat-cat2.jpg' ?>"></div>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php _e( 'Description', 'first-mag' ); ?>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to display:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e( 'Enable or disable post meta', 'first-mag' ); ?>:</label><br />
			<input type="radio" <?php checked( $meta, 'on' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="on"/><?php _e( 'Enable', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $meta, 'off' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="off"/><?php _e( 'Disable', 'first-mag' ); ?><br />
		</p>
		<p><input type="radio" <?php checked( $type, 'latest' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $type, 'category' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category:', 'first-mag' ); ?><br /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'first-mag' ); ?>:</label>
		<?php wp_dropdown_categories( array( 'show_option_none' => ' ', 'name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance				 = $old_instance;
		$instance[ 'title' ]	 = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'text' ]		 = stripslashes( wp_filter_post_kses( addslashes( $new_instance[ 'text' ] ) ) );
		$instance[ 'number' ]	 = absint( $new_instance[ 'number' ] );
		$instance[ 'type' ]		 = strip_tags( $new_instance[ 'type' ] );
		$instance[ 'category' ]	 = absint( $new_instance[ 'category' ] );
		$instance[ 'meta' ]		 = strip_tags( $new_instance[ 'meta' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$title		 = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$text		 = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
		$number		 = empty( $instance[ 'number' ] ) ? 5 : $instance[ 'number' ];
		$type		 = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest';
		$category	 = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';
		$meta		 = isset( $instance[ 'meta' ] ) ? $instance[ 'meta' ] : 'off';


		$category_link = get_category_link( $category );
		if ( $category != '' && $type != 'latest' ) {
			$cat_link = esc_url( $category_link );
		} else {
			$cat_link = get_permalink( get_option( 'page_for_posts' ) );
		}

		if ( $type == 'latest' ) {
			$get_posts = new WP_Query( array(
				'posts_per_page'		 => $number,
				'post_type'				 => 'post',
				'ignore_sticky_posts'	 => true
			) );
		} else {
			$get_posts = new WP_Query( array(
				'posts_per_page' => $number,
				'post_type'		 => 'post',
				'category__in'	 => $category
			) );
		}
		echo $before_widget;
		?>
		<?php
		if ( !empty( $title ) ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}
		if ( !empty( $text ) ) {
			?> <p class="col-md-12"> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
						<?php
						$i = 1;
						while ( $get_posts->have_posts() ) : $get_posts->the_post();
							?>
						<?php if ( $i == 1 ) {
							$thumb		 = 'first-mag-home';
							$grid		 = 'col-md-6';
							$grid_title	 = 'col-md-6';
							$grid_block	 = '';
						} else {
							$thumb		 = 'first-mag-home-small';
							$grid		 = 'col-xs-4';
							$grid_title	 = 'col-xs-8';
							$grid_block	 = 'col-md-6';
						} ?>
			<?php if ( $i == 1 ) {
				echo '<div class="first-post first-padding col-md-12">';
			} elseif ( $i == 2 ) {
				echo '<div class="small-post">';
			} ?>
			<article class="<?php echo $grid_block ?>"> 
				<div <?php post_class(); ?>>                            
					<?php if ( has_post_thumbnail() ) : ?>                               
						<div class="featured-thumbnail <?php echo $grid ?>">
							<a href="<?php the_permalink(); ?>" rel="bookmark">    
								<?php the_post_thumbnail( $thumb ); ?>
								<?php if ( function_exists( 'wp_review_show_total' ) ) wp_review_show_total(); ?>
							</a>
						</div>                                                          
					<?php endif; ?>
					<div class="home-header <?php echo $grid_title ?>"> 
						<header>
							<h2 class="page-header">                                
			                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'first-mag' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
									<?php the_title(); ?>
			                    </a>                            
							</h2> 
							<?php if ( $meta == 'on' ) : ?>
								<?php get_template_part( 'template-part', 'postmeta' ); ?>
							<?php endif; ?>   
						</header>                                                      
						<?php if ( $i == 1 ) : ?>
							<div class="entry-summary">
								<?php $content = get_the_content();
								echo wp_trim_words( strip_shortcodes( $content ), '25', $more	 = '...' ); ?> 
							</div><!-- .entry-summary -->                                                                                                                       
						<?php endif; ?>                                         
					</div>                      
				</div>
			</article>
			<?php if ( $i == 1 ) {
				echo '</div>';
			} ?>
			<?php
			$i++;
		endwhile;
		if ( $i > 2 ) {
			echo '</div>';
		}
		// Reset Post Data
		wp_reset_query();
		?>
		<?php if ( $type == 'category' ) { ?>
			<div class="widget-footer col-md-12 text-center">
				<a class="btn btn-primary btn-md outline" href="<?php echo esc_url( $cat_link ); ?>" >
			<?php _e( 'All ', 'first-mag' ) ?><?php $categorys = get_category( $category );
			echo esc_attr( $categorys->category_count ); ?><?php _e( ' Articles', 'first-mag' ) ?>
				</a>
			</div>
		<?php } ?> 
		<!-- </div> -->
		<?php
		echo $after_widget;
	}

}

/**
 * Featured FullWidth post Widget
 */
class first_mag_fullwidth_posts_widget extends WP_Widget {

	function __construct() {
		$widget_ops	 = array( 'classname' => 'widget_fullwidth_posts_second first-mag-widget row', 'description' => __( 'Display latest posts or posts of specific category. Only for Front Page: Content Section!', 'first-mag' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		parent::__construct( false, $name		 = __( 'First Mag: FullWidth post widget', 'first-mag' ), $widget_ops );
	}

	function form( $instance ) {
		$first_mag_defaults[ 'title' ]	 = '';
		$first_mag_defaults[ 'text' ]		 = '';
		$first_mag_defaults[ 'number' ]	 = 5;
		$first_mag_defaults[ 'meta' ]		 = 'off';
		$first_mag_defaults[ 'type' ]		 = 'latest';
		$first_mag_defaults[ 'category' ]	 = '';
		$instance						 = wp_parse_args( (array) $instance, $first_mag_defaults );
		$title							 = esc_attr( $instance[ 'title' ] );
		$text							 = esc_textarea( $instance[ 'text' ] );
		$number							 = $instance[ 'number' ];
		$type							 = $instance[ 'type' ];
		$category						 = $instance[ 'category' ];
		$meta							 = $instance[ 'meta' ];
		?>
		<p><?php _e( 'Widget Layout:', 'first-mag' ) ?></p>
		<div style="text-align: center;"><img src="<?php echo get_template_directory_uri() . '/img/fullwidth-cat.jpg' ?>"></div>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php _e( 'Description', 'first-mag' ); ?>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to display:', 'first-mag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e( 'Enable or disable post meta', 'first-mag' ); ?>:</label><br />
			<input type="radio" <?php checked( $meta, 'on' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="on"/><?php _e( 'Enable', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $meta, 'off' ) ?> id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" value="off"/><?php _e( 'Disable', 'first-mag' ); ?><br />
		</p>
		<p><input type="radio" <?php checked( $type, 'latest' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'first-mag' ); ?><br />
			<input type="radio" <?php checked( $type, 'category' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category:', 'first-mag' ); ?><br /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'first-mag' ); ?>:</label>
		<?php wp_dropdown_categories( array( 'show_option_none' => ' ', 'name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance				 = $old_instance;
		$instance[ 'title' ]	 = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'text' ]		 = stripslashes( wp_filter_post_kses( addslashes( $new_instance[ 'text' ] ) ) );
		$instance[ 'number' ]	 = absint( $new_instance[ 'number' ] );
		$instance[ 'type' ]		 = strip_tags( $new_instance[ 'type' ] );
		$instance[ 'category' ]	 = absint( $new_instance[ 'category' ] );
		$instance[ 'meta' ]		 = strip_tags( $new_instance[ 'meta' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$title		 = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$text		 = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
		$number		 = empty( $instance[ 'number' ] ) ? 5 : $instance[ 'number' ];
		$type		 = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest';
		$category	 = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';
		$meta		 = isset( $instance[ 'meta' ] ) ? $instance[ 'meta' ] : 'off';


		$category_link = get_category_link( $category );
		if ( $category != '' && $type != 'latest' ) {
			$cat_link = esc_url( $category_link );
		} else {
			$cat_link = get_permalink( get_option( 'page_for_posts' ) );
		}

		if ( $type == 'latest' ) {
			$get_posts = new WP_Query( array(
				'posts_per_page'		 => $number,
				'post_type'				 => 'post',
				'ignore_sticky_posts'	 => true
			) );
		} else {
			$get_posts = new WP_Query( array(
				'posts_per_page' => $number,
				'post_type'		 => 'post',
				'category__in'	 => $category
			) );
		}
		echo $before_widget;
		?>
				<?php
				if ( !empty( $title ) ) {
					echo $before_title . esc_html( $title ) . $after_title;
				}
				if ( !empty( $text ) ) {
					?> <p class="col-md-12"> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
		<?php
		while ( $get_posts->have_posts() ) : $get_posts->the_post();
			?>
			<div class="col-md-12">
				<article> 
					<div <?php post_class(); ?>>                            
						<?php if ( has_post_thumbnail() ) : ?>                                
							<div class="featured-thumbnail">
								<a href="<?php the_permalink(); ?>" rel="bookmark">  
									<?php the_post_thumbnail( 'first-mag-slider' ); ?>
									<?php if ( function_exists( 'wp_review_show_total' ) ) wp_review_show_total(); ?>
								</a>
							</div>                                                           
						<?php endif; ?>
						<div class="home-header"> 
							<header>
								<h2 class="page-header">                                
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'first-mag' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
										<?php the_title(); ?>
									</a>                            
								</h2> 
								<?php if ( $meta == 'on' ) : ?>
									<?php get_template_part( 'template-part', 'postmeta' ); ?>
								<?php endif; ?>   
							</header>                                                      
							<div class="entry-summary">
								<?php $content = get_the_content();
								echo wp_trim_words( strip_shortcodes( $content ), '25', $more	 = '...' ); ?> 
							</div><!-- .entry-summary -->                                                                                                                                                                
						</div>                      
					</div>
				</article>
			</div>
			<?php
		endwhile;
		wp_reset_query();
		?>
		<?php if ( $type == 'category' ) { ?>
			<div class="widget-footer col-md-12 text-center">
				<a class="btn btn-primary btn-md outline" href="<?php echo esc_url( $cat_link ); ?>" >
			<?php _e( 'All ', 'first-mag' ) ?><?php $categorys = get_category( $category );
			echo esc_attr( $categorys->category_count ); ?><?php _e( ' Articles', 'first-mag' ) ?>
				</a>
			</div>
		<?php } ?> 
		<!-- </div> -->
		<?php
		echo $after_widget;
	}

}

/****************************************************************************************/
