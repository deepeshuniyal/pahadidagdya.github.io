<?php
/**
 * Custom template tags for this theme.
 */


if ( ! function_exists( 'upright_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function upright_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="post pingback">
				<p><?php _e( 'Pingback:', 'upright' ); ?><?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'upright' ), ' ' ); ?></p>
				<?php
				break;
			default :
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<footer>
						<div class="comment-author vcard">
							<?php echo get_avatar( $comment, 40 ); ?>
							<?php printf( __( '%s <span class="says">says:</span>', 'upright' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
						</div><!-- .comment-author .vcard -->
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em><?php _e( 'Your comment is awaiting moderation.', 'upright' ); ?></em>
							<br/>
						<?php endif; ?>

						<div class="comment-meta commentmetadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php
									/* translators: 1: date, 2: time */
									printf( __( '%1$s at %2$s', 'upright' ), get_comment_date(), get_comment_time() ); ?>
								</time>
							</a>,
							<?php comment_reply_link( array_merge( $args, array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth']
							) ) ); ?>
							<?php edit_comment_link( __( '(Edit)', 'upright' ), ' ' );
							?>
						</div><!-- .comment-meta .commentmetadata -->
					</footer>

					<div class="comment-content"><?php comment_text(); ?></div>
				</article>
				<?php
				break;
		endswitch;
	}
endif;


if ( ! function_exists( 'upright_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function upright_posted_on() {
		printf( __( '<time class="entry-date" datetime="%1$s">%2$s</time>', 'upright' ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	}
endif;


/**
 * Returns true if a blog has more than 1 category
 */
function upright_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'upright_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'upright_the_cool_cats', $all_the_cool_cats );
	}

	if ( 1 < $all_the_cool_cats ) {
		// This blog has more than 1 category so upright_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so upright_categorized_blog should return false
		return false;
	}
}


/**
 * Flush out the transients used in upright_categorized_blog
 */
function upright_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'upright_the_cool_cats' );
}

add_action( 'edit_category', 'upright_category_transient_flusher' );
add_action( 'save_post', 'upright_category_transient_flusher' );


/**
 * Featured Posts
 *
 * @param string $args
 */
function upright_featured_posts( $args = '' ) {

	$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $paged;

	if ( 1 < $paged && upright_get_option( 'slider_hide_next' ) ) {
		return;
	}

	$recent = new WP_Query( $args );

	if ( ! $recent->have_posts() ) {
		return;
	} ?>

	<div id="featured-slider" class="flexslider">
		<ul class="slides group">
			<?php $i = 1; ?>
			<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
				<li class="post group">
					<?php if ( has_post_thumbnail() ): ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
						   class="featured-thumb"><?php the_post_thumbnail( 'large' ); ?></a>
					<?php endif; ?>
					<article class="boxed">
						<header>
							<div class="entry-meta">
								<?php upright_posted_on(); ?>
								<span class="sep">/</span> <span
									class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'upright' ), __( '1 Comment', 'upright' ), __( '% Comments', 'upright' ) ); ?></span>
							</div>
							<h2 class="post-title h1"><a href="<?php the_permalink() ?>" rel="bookmark"
							                             title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</h2>
						</header>
					</article>
				</li>
				<?php $i ++; ?>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</ul>
	</div>
	<?php
}


/**
 * Carousel Posts
 *
 * @param string $args
 *
 * @return int
 */
function upright_carousel_posts( $args = '' ) {

	$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $paged;

	if ( 1 < $paged && upright_get_option( 'carousel_hide_next' ) ) {
		return;
	}

	$recent = new WP_Query( $args );

	if ( ! $recent->have_posts() ) {
		return;
	} ?>

	<div id="carousel-slider" class="flexslider">
		<h3 class="section-title"><span><?php echo esc_html( upright_get_option( 'carousel_title', __( 'Headlines', 'upright' ) ) ); ?></span></h3>
		<ul id="featured-items" class="slides group">
			<?php $i = 1; ?>
			<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
				<li class="post group">
					<?php if ( has_post_thumbnail() ): ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
						   class="featured-thumb"><?php the_post_thumbnail(); ?></a>
					<?php endif; ?>
					<article class="boxed">
						<header>
							<div class="entry-meta">
								<?php upright_posted_on(); ?>
								<span class="sep">/</span> <span
									class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'upright' ), __( '1 Comment', 'upright' ), __( '% Comments', 'upright' ) ); ?></span>
							</div>
							<h2 class="entry-title h3"><a href="<?php the_permalink() ?>" rel="bookmark"
							                              title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</h2>
						</header>

					</article>
				</li>
				<?php $i ++; ?>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</ul>
	</div>
	<?php
}


/**
 * Get related post, with the same category
 */
function upright_related_posts() {
	$post          = get_post();
	$category      = get_the_category( $post->ID );
	$cat_id        = $category[0]->cat_ID;
	$args          = array(
		'cat'          => $cat_id,
		'showposts'    => 3, /* you can change this to show more */
		'post__not_in' => array( $post->ID )
	);
	$related_posts = new WP_Query( $args );

	if ( ! $related_posts->have_posts() ) {
		return;
	}

	echo '<ul>';
	if ( $related_posts ) {
		while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
			<li class="clearfix boxed">
				<div class="entry-header">
					<div class="entry-meta">
						<?php upright_posted_on(); ?>
					</div>
					<h2 class="no-heading-style entry-title"><a href="<?php the_permalink(); ?>"
					                                            title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'upright' ), the_title_attribute( 'echo=0' ) ) ); ?>"
					                                            rel="bookmark"><?php the_title(); ?></a></h2>
				</div>
			</li>
		<?php endwhile;
	} else { ?>
		<li class="no_related_post type-3"><?php _e( 'Cannot Retrieved a Related Posts Yet!', 'upright' ); ?></li><?php
	}

	echo '</ul>';

	wp_reset_postdata();
}


/**
 * Breadcrumbs
 */
function upright_breadcrumb() {
	if ( ! is_front_page() ) {
		echo '<div id="breadcrumbs"> <a href="' . esc_url( home_url() ) . '">' . __( 'Home', 'upright' ) . '</a> ';
	}

	if ( ( is_category() || is_single() ) && ! is_attachment() ) {
		$category = get_the_category();

		if ( count( $category ) > 0 ) {
			$ID = $category[0]->cat_ID;
			if ( $ID ) {
				echo get_category_parents( $ID, true, ' ', false );
			}
		}
	}

	if ( ! is_front_page() && ( is_single() || is_page() ) ) {
		the_title();
	}
	if ( is_tag() ) {
		printf(  __( 'Tag: %s', 'upright' ), single_tag_title( '', false ) );
	}
	if ( is_404() ) {
		_e( '404 - Page not Found', 'upright' );
	}
	if ( is_search() ) {
		_e( 'Search', 'upright' );
	}
	if ( is_year() ) {
		echo get_the_time( 'Y' );
	}
	if ( is_month() ) {
		echo get_the_time( 'F Y' );
	}
	if ( is_author() ) {
		printf( __( 'Posts by %s', 'upright' ), get_the_author() );
	}

	if ( ! is_front_page() ) {
		echo "</div>";
	}
}


/**
 * Pagination for Posts listing.
 * Use paginate_links() to create custom markups for the theme.
 *
 * @see paginate_links()
 */
function upright_pagination() {
	global $wp_query;

	$show_number = 2;
	$total       = $wp_query->max_num_pages;

	if ( $total > 1 ) {
		if ( ! $current_page = get_query_var( 'paged' ) ) {
			$current_page = 1;
		}

		echo '<nav class="page-navigation">';

		$paginate = paginate_links( array(
			'current'   => $current_page,
			'total'     => $total,
			'show_all'  => true,
			'type'      => 'array',
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
		) );

		$fi       = 0;
		$prev     = '';
		$first    = '';
		$left_dot = '';

		if ( strpos( $paginate[0], 'prev' ) !== false ) {
			$fi   = 1;
			$prev = '<li>' . $paginate[0] . '</li>';
			if ( ( $current_page - $show_number ) > 1 ) {
				$fi       = $current_page - $show_number;
				$first    = '<li>' . preg_replace( '/>[^>]*[^<]</', '>'. __( 'First', 'upright' ) .'<', $paginate[1] ) . '</li>';
				$left_dot = '<li><span>' . __( '...', 'upright' ) . '</span></li>';
			}
		}

		$la        = count( $paginate ) - 1;
		$next      = '';
		$last      = '';
		$right_dot = '';
		if ( strpos( $paginate[ count( $paginate ) - 1 ], 'next' ) !== false ) {
			$la   = count( $paginate ) - 2;
			$next = '<li>' . $paginate[ count( $paginate ) - 1 ] . '</li>';
			if ( ( $current_page + $show_number ) < $total ) {
				$la        = $current_page + $show_number;
				$last      = '<li>' . preg_replace( '/>[^>]*[^<]</', '>' . __( 'Last', 'upright' ) . '<', $paginate[ count( $paginate ) - 2 ] ) . '</li>';
				$right_dot = '<li><span>' . __( '...', 'upright' ) . '</span></li>';
			}
		}

		/* translators: 1. Current Page No 2. Total Pages Count */
		echo '<span class="page-of">'. sprintf( __('Page %1$s of %2$s', 'upright'), $current_page, $total ) . '</span>';

		echo '<ul class="page_navi clearfix">';
		echo $first . $left_dot;
		echo $prev;
		for ( $i = $fi; $i <= $la; $i ++ ) {
			echo '<li>' . $paginate[ $i ] . '</li>';
		}
		echo $right_dot . $last;
		echo $next;
		echo '</ul>';
		echo '</nav>';
	} else {
		echo '<nav class="page-navigation">';
		echo '<span class="page-of">' . __( 'Page 1 of 1', 'upright' ) . '</span>';
		echo '</nav>';
	}
}