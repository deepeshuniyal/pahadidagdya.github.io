<?php

class Upright_Widget_Tabs extends WP_Widget {

	function __construct() {
		parent::__construct( 'upright_tabs', __( 'Upright - Tabs', 'upright' ), array(
			'classname'   => 'tabs-widget',
			'description' => __( 'Displays recent posts, comments, categories etc in multiple tabs.', 'upright' )
		) );
	}

	public function widget( $args, $instance ) {
		$default            = array(
			'widget_one'         => 1,
			'widget_one_title'   => '',
			'widget_two'         => 2,
			'widget_two_title'   => '',
			'widget_three'       => 3,
			'widget_three_title' => ''
		);
		$instance           = wp_parse_args( $instance, $default );
		$widget_one         = absint( $instance['widget_one'] );
		$widget_two         = absint( $instance['widget_two'] );
		$widget_three       = absint( $instance['widget_three'] );
		$widget_one_title   = apply_filters( 'widget_title', $instance['widget_one_title'] );
		$widget_two_title   = apply_filters( 'widget_title', $instance['widget_two_title'] );
		$widget_three_title = apply_filters( 'widget_title', $instance['widget_three_title'] );

		$tab[1] = apply_filters( 'widget_title', __( 'Recent', 'upright' ) );
		$tab[2] = apply_filters( 'widget_title', __( 'Comments', 'upright' ) );
		$tab[3] = apply_filters( 'widget_title', __( 'Tags', 'upright' ) );
		$tab[4] = apply_filters( 'widget_title', __( 'Popular', 'upright' ) );
		$tab[5] = apply_filters( 'widget_title', __( 'Categories', 'upright' ) );
		$tab[6] = apply_filters( 'widget_title', __( 'Archives', 'upright' ) );
		$tab[7] = apply_filters( 'widget_title', __( 'Pages', 'upright' ) );

		$class[1] = 'tab-recent-post';
		$class[2] = 'tab-recent-comment';
		$class[3] = 'tab-tags';
		$class[4] = 'tab-popular-post';
		$class[5] = 'tab-category';
		$class[6] = 'tab-archive';
		$class[7] = 'tab-page';

		echo $args['before_widget'];
		?>
		<h4 class="widgettitle"><span></span></h4>

		<div class="tabs">
			<ul class="nav-tab group">
				<?php if ( $widget_one ) : ?>
					<li class="first_tab tab-active <?php echo esc_attr( $class[ $widget_one ] ); ?> boxed"><a
							href="#tabs-1"><?php echo ( $widget_one_title ) ? esc_html( $widget_one_title ) : esc_html( $tab[ $widget_one ] ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $widget_two ) : ?>
					<li class="second_tab <?php echo esc_attr( $class[ $widget_two ] ); ?> boxed"><a
							href="#tabs-2"><?php echo ( $widget_two_title ) ? esc_html( $widget_two_title ) : esc_html( $tab[ $widget_two ] ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $widget_three ) : ?>
					<li class="third_tab <?php echo esc_attr( $class[ $widget_three ] ); ?> boxed"><a
							href="#tabs-3"><?php echo ( $widget_three_title ) ? esc_html( $widget_three_title ) : esc_html( $tab[ $widget_three ] ); ?></a>
					</li>
				<?php endif; ?>
			</ul>
			<?php if ( $widget_one ) : ?>
				<div class="tab-content tabs-1 active clearfix">
					<?php $this->show_widget( $widget_one ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $widget_two ) : ?>
				<div class="tab-content tabs-2 hide"><?php $this->show_widget( $widget_two ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $widget_three ) : ?>
				<div class="tab-content tabs-3 hide"><?php $this->show_widget( $widget_three ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance                       = $old_instance;
		$instance['widget_one']         = absint( $new_instance['widget_one'] );
		$instance['widget_two']         = absint( $new_instance['widget_two'] );
		$instance['widget_three']       = absint( $new_instance['widget_three'] );
		$instance['widget_one_title']   = wp_strip_all_tags( $new_instance['widget_one_title'] );
		$instance['widget_two_title']   = wp_strip_all_tags( $new_instance['widget_two_title'] );
		$instance['widget_three_title'] = wp_strip_all_tags( $new_instance['widget_three_title'] );

		return $instance;
	}

	public function form( $instance ) {
		$default            = array(
			'widget_one'         => 1,
			'widget_one_title'   => '',
			'widget_two'         => 2,
			'widget_two_title'   => '',
			'widget_three'       => 3,
			'widget_three_title' => ''
		);
		$instance           = wp_parse_args( $instance, $default );
		$widget_one         = absint( $instance['widget_one'] );
		$widget_two         = absint( $instance['widget_two'] );
		$widget_three       = absint( $instance['widget_three'] );
		$widget_one_title   = wp_strip_all_tags( $instance['widget_one_title'] );
		$widget_two_title   = wp_strip_all_tags( $instance['widget_two_title'] );
		$widget_three_title = wp_strip_all_tags( $instance['widget_three_title'] );
		?>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_one_title' ) ); ?>"><?php _e( 'Tab One Title:', 'upright' ); ?></label><br/>
			<input id="<?php echo esc_attr( $this->get_field_id( 'widget_one_title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'widget_one_title' ) ); ?>"
			       type="text"
			       value="<?php echo esc_attr( $widget_one_title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_one' ) ); ?>"><?php _e( 'Tab One Content:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'widget_one' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'widget_one' ) ); ?>">
				<option
					value="0" <?php selected( 0, $widget_one ); ?>>&nbsp;</option>
				<option
					value="1" <?php selected( 1, $widget_one ); ?>><?php _e( 'Recent Posts', 'upright' ); ?></option>
				<option
					value="2" <?php selected( 2, $widget_one ); ?>><?php _e( 'Recent Comments', 'upright' ); ?></option>
				<option
					value="3" <?php selected( 3, $widget_one ); ?>><?php _e( 'Tag Clouds', 'upright' ); ?></option>
				<option
					value="4" <?php selected( 4, $widget_one ); ?>><?php _e( 'Popular Posts', 'upright' ); ?></option>
				<option
					value="5" <?php selected( 5, $widget_one ); ?>><?php _e( 'Categories', 'upright' ); ?></option>
				<option
					value="6" <?php selected( 6, $widget_one ); ?>><?php _e( 'Archives', 'upright' ); ?></option>
				<option
					value="7" <?php selected( 7, $widget_one ); ?>><?php _e( 'Pages', 'upright' ); ?></option>
			</select>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_two_title' ) ); ?>"><?php _e( 'Tab Two Title:', 'upright' ); ?></label><br/>
			<input id="<?php echo esc_attr( $this->get_field_id( 'widget_two_title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'widget_two_title' ) ); ?>"
			       type="text"
			       value="<?php echo esc_attr( $widget_two_title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_two' ) ); ?>"><?php _e( 'Tab Two Content:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'widget_two' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'widget_two' ) ); ?>">
				<option
					value="0" <?php selected( 0, $widget_two ); ?>>&nbsp;</option>
				<option
					value="1" <?php selected( 1, $widget_two ); ?>><?php _e( 'Recent Posts', 'upright' ); ?></option>
				<option
					value="2" <?php selected( 2, $widget_two ); ?>><?php _e( 'Recent Comments', 'upright' ); ?></option>
				<option
					value="3" <?php selected( 3, $widget_two ); ?>><?php _e( 'Tag Clouds', 'upright' ); ?></option>
				<option
					value="4" <?php selected( 4, $widget_two ); ?>><?php _e( 'Popular Posts', 'upright' ); ?></option>
				<option
					value="5" <?php selected( 5, $widget_two ); ?>><?php _e( 'Categories', 'upright' ); ?></option>
				<option
					value="6" <?php selected( 6, $widget_two ); ?>><?php _e( 'Archives', 'upright' ); ?></option>
				<option
					value="7" <?php selected( 7, $widget_two ); ?>><?php _e( 'Pages', 'upright' ); ?></option>
			</select>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_three_title' ) ); ?>"><?php _e( 'Tab Three Title:', 'upright' ); ?></label><br/>
			<input id="<?php echo esc_attr( $this->get_field_id( 'widget_three_title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'widget_three_title' ) ); ?>"
			       type="text"
			       value="<?php echo esc_attr( $widget_three_title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'widget_three' ) ); ?>"><?php _e( 'Tab Three Content:', 'upright' ); ?></label><br/>
			<select id="<?php echo esc_attr( $this->get_field_id( 'widget_three' ) ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'widget_three' ) ); ?>">
				<option
					value="0" <?php selected( 0, $widget_three ); ?>>&nbsp;</option>
				<option
					value="1" <?php selected( 1, $widget_three ); ?>><?php _e( 'Recent Posts', 'upright' ); ?></option>
				<option
					value="2" <?php selected( 2, $widget_three ); ?>><?php _e( 'Recent Comments', 'upright' ); ?></option>
				<option
					value="3" <?php selected( 3, $widget_three ); ?>><?php _e( 'Tag Clouds', 'upright' ); ?></option>
				<option
					value="4" <?php selected( 4, $widget_three ); ?>><?php _e( 'Popular Posts', 'upright' ); ?></option>
				<option
					value="5" <?php selected( 5, $widget_three ); ?>><?php _e( 'Categories', 'upright' ); ?></option>
				<option
					value="6" <?php selected( 6, $widget_three ); ?>><?php _e( 'Archives', 'upright' ); ?></option>
				<option
					value="7" <?php selected( 7, $widget_three ); ?>><?php _e( 'Pages', 'upright' ); ?></option>
			</select>
		</p>

		<?php
	}

	public function show_widget( $num ) {
		$id = '';

		if ( $num == 1 ) {
			$widget_name = esc_html( 'Upright_Widget_Recent_Posts' );
			$atts        = 'quantity=5&display=type-3&excerpt=hide&cat=multiple_cat';

			the_widget( $widget_name, $atts, array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 2 ) {
			$widget_name = esc_html( 'Upright_Widget_Recent_Comments' );
			$atts        = 'quantity=5';

			the_widget( $widget_name, $atts, array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 3 ) {
			$widget_name = esc_html( 'WP_Widget_Tag_Cloud' );

			the_widget( $widget_name, array(), array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div class="tag_tab">',
				'after_widget'  => '<div class="clear"></div></div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 4 ) {
			$widget_name = esc_html( 'Upright_Widget_Recent_Posts' );
			$atts        = 'quantity=5&display=type-3&excerpt=hide&order=comment_count&cat=multiple_cat';

			the_widget( $widget_name, $atts, array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 5 ) {
			$widget_name = esc_html( 'WP_Widget_Categories' );

			the_widget( $widget_name, array(), array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 6 ) {
			$widget_name = esc_html( 'WP_Widget_Archives' );

			the_widget( $widget_name, array(), array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
		if ( $num == 7 ) {
			$widget_name = esc_html( 'WP_Widget_Pages' );

			the_widget( $widget_name, array(), array(
				'widget_id'     => 'arbitrary-instance-' . $id,
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 style="display:none">',
				'after_title'   => '</h3>'
			) );
		}
	}
}
