<?php

class Upright_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		parent::__construct( 'upright_recent_posts', __( 'Upright - Recent Posts', 'upright' ), array(
			'classname'   => 'widget_posts_wrap',
			'description' => __( 'Display most recent Posts.', 'upright' )
		) );
	}

	public function widget( $args, $instance ) {
		$default  = array(
			'title'    => __( 'Recent Posts', 'upright' ),
			'cats'     => '',
			'cat'      => '',
			'quantity' => 5,
			'order'    => 'date',
		);
		$instance = wp_parse_args( $instance, $default );
		$title    = apply_filters( 'widget_title', $instance['title'] );
		$cats     = preg_replace( '|[^0-9,-]|', '', $instance['cats'] );
		$cat      = absint( $instance['cat'] );
		$quantity = absint( $instance['quantity'] );
		$order    = in_array( $instance['order'], array(
			'date',
			'rand',
			'comment_count'
		), true ) ? $instance['order'] : 'date';

		echo $args['before_widget'];
		?>
		<?php
		if ( ! empty( $instance['title'] ) && $cat === 0 ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		} else {
			echo $args['before_title'] . apply_filters( 'widget_title', get_cat_name( $cat ) ) . $args['after_title'];
		} ?>
		<div class="widget_posts<?php if ( $order == 'comment_count' ) {
			echo ' popular-posts';
		} ?>">
			<?php
			$r = new WP_Query( array(
				'showposts'           => $quantity,
				'cat'                 => $cat === 0 ? $cats : $cat,
				'orderby'             => $order,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			) );
			$i = 0;
			while ( $r->have_posts() ) : $r->the_post();
				?>
				<article>
					<header class="clearfix">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
							   class="home-thumb alignleft"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
						<?php endif; ?>
						<div class="entry-meta">
							<time
								datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
						</div>
						<h4 class="no-heading-style entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"
						                                            title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						</h4>
					</header>
				</article>
				<?php
				$i ++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<div class="clear"><!-- --></div>
		<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );

		$instance['cats']     = preg_replace( '|[^0-9,-]|', '', $new_instance['cats'] );
		$instance['cat']      = absint( $new_instance['cat'] );
		$instance['quantity'] = absint( $new_instance['quantity'] );
		$instance['order']    = in_array( $new_instance['order'], array(
			'date',
			'rand',
			'comment_count'
		), true ) ? $new_instance['order'] : 'date';

		$default = array(
			'title'    => __( 'Recent Posts', 'upright' ),
			'cats'     => '',
			'cat'      => '',
			'quantity' => 5,
			'order'    => 'date',
		);

		$instance = wp_parse_args( $instance, $default );

		return $instance;
	}

	public function form( $instance ) {
		$default  = array(
			'title'    => __( 'Recent Posts', 'upright' ),
			'cats'     => '',
			'cat'      => '',
			'quantity' => 5,
			'order'    => 'date',
		);
		$instance = wp_parse_args( $instance, $default );
		$title    = wp_strip_all_tags( $instance['title'] );
		$cats     = preg_replace( '|[^0-9,-]|', '', $instance['cats'] );
		$cat      = absint( $instance['cat'] );
		$quantity = absint( $instance['quantity'] );
		$order    = in_array( $instance['order'], array(
			'date',
			'rand',
			'comment_count'
		), true ) ? $instance['order'] : 'date';
		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget title (Will automaticly use category name when select single category):', 'upright' ); ?></label><br/>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" type="text"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php _e( 'Category:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'cat' ) ); ?>">
				<option
					value="0" <?php selected( 0, $cat ); ?>><?php _e( 'Multiple Categories', 'upright' ); ?></option>
				<?php
				$of_categories_obj = get_categories( 'hide_empty=0' );
				foreach ( $of_categories_obj as $of_cat ) {
					?>
					<option
						value="<?php echo intval( $of_cat->cat_ID ); ?>" <?php selected( intval( $of_cat->cat_ID ), $cat ); ?>><?php echo esc_html( $of_cat->cat_name ); ?></option>
					<?php
				}
				?>
			</select>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'cats' ) ); ?>"><?php _e( 'Enter ID of categories e.g. 1,2,3,4. Leave it blank to pull all categories (if multiple category choosed).:', 'upright' ); ?></label><br/>
			<input id="<?php echo esc_attr( $this->get_field_id( 'cats' ) ); ?>" class="widefat" type="text"
			       name="<?php echo esc_attr( $this->get_field_name( 'cats' ) ); ?>"
			       value="<?php echo esc_attr( $cats ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'quantity' ) ); ?>"><?php _e( 'Number of posts:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'quantity' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'quantity' ) ); ?>">
				<option value="1" <?php selected( 1, $quantity ); ?>><?php echo number_format_i18n( 1 ); ?></option>
				<option value="2" <?php selected( 2, $quantity ); ?>><?php echo number_format_i18n( 2 ); ?></option>
				<option value="3" <?php selected( 3, $quantity ); ?>><?php echo number_format_i18n( 3 ); ?></option>
				<option value="4" <?php selected( 4, $quantity ); ?>><?php echo number_format_i18n( 4 ); ?></option>
				<option value="5" <?php selected( 5, $quantity ); ?>><?php echo number_format_i18n( 5 ); ?></option>
				<option value="6" <?php selected( 6, $quantity ); ?>><?php echo number_format_i18n( 6 ); ?></option>
				<option value="7" <?php selected( 7, $quantity ); ?>><?php echo number_format_i18n( 7 ); ?></option>
				<option value="8" <?php selected( 8, $quantity ); ?>><?php echo number_format_i18n( 8 ); ?></option>
				<option value="9" <?php selected( 9, $quantity ); ?>><?php echo number_format_i18n( 9 ); ?></option>
				<option value="10" <?php selected( 10, $quantity ); ?>><?php echo number_format_i18n( 10 ); ?></option>
				<option value="11" <?php selected( 11, $quantity ); ?>><?php echo number_format_i18n( 11 ); ?></option>
				<option value="12" <?php selected( 12, $quantity ); ?>><?php echo number_format_i18n( 12 ); ?></option>
				<option value="13" <?php selected( 13, $quantity ); ?>><?php echo number_format_i18n( 13 ); ?></option>
				<option value="14" <?php selected( 14, $quantity ); ?>><?php echo number_format_i18n( 14 ); ?></option>
				<option value="15" <?php selected( 15, $quantity ); ?>><?php echo number_format_i18n( 15 ); ?></option>
			</select>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e( 'Order posts by:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option
					value="date" <?php selected( 'date', $order ); ?>><?php _e( 'date', 'upright' ); ?></option>
				<option
					value="rand" <?php selected( 'rand', $order ); ?>><?php _e( 'random', 'upright' ); ?></option>
				<option
					value="comment_count" <?php selected( 'comment_count', $order ); ?>><?php _e( 'popular', 'upright' ); ?></option>
			</select>
		</p>

		<?php
	}

}
