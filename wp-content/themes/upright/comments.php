<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to upright_comment() which is
 * located in the functions.php file.
 */
?>

<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area boxed">

	<?php if ( have_comments() ) : ?>
		<h3 class="section-title">
			<?php
			printf( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'upright' ),
				number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
				<h1 class="assistive-text"><?php _e( 'Comment navigation', 'upright' ); ?></h1>
				<div
					class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'upright' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'upright' ) ); ?></div>
			</nav><!-- #comment-nav-before .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
			/* Loop through and list the comments. Tell wp_list_comments()
			 * to use upright_comment() to format the comments.
			 * If you want to overload this in a child theme then you can
			 * define upright_comment() and that will be used instead.
			 * See upright_comment() in inc/template-tags.php for more.
			 */
			wp_list_comments( array( 'callback' => 'upright_comment' ) );
			?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
				<h1 class="assistive-text"><?php _e( 'Comment navigation', 'upright' ); ?></h1>
				<div
					class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'upright' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'upright' ) ); ?></div>
			</nav><!-- #comment-nav-below .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'upright' ); ?></p>
	<?php endif; ?>

	<?php
	if ( comments_open() ) :

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields        = array(
			'author' => '<p class="comment-form-author comment-field shadow-inset">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="' . esc_attr__( 'Your Name', 'upright' ) . ( $req ? ' *' : '' ) . '" ' . $aria_req . ' ></p>',
			'email'  => '<p class="comment-form-email comment-field shadow-inset">' . '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" placeholder="' . esc_attr__( 'Your Email', 'upright' ) . ( $req ? ' *' : '' ) . '" ' . $aria_req . ' ></p>',
			'url'    => '<p class="comment-form-url comment-field shadow-inset">' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="' . esc_attr__( 'Your Website', 'upright' ) . '" ></p>'
		);
		$comment_field = '<textarea name="comment" id="comment" placeholder="' . esc_attr__( 'Your Comment Here...', 'upright' ) . '" rows="6" class="shadow-inset" tabindex="4"></textarea>';
		comment_form( array( 'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		                     'comment_field'        => $comment_field,
		                     'comment_notes_before' => '',
		                     'comment_notes_after'  => '<p class="required-attr meta">' . __( '(*) Required, Your email will not be published', 'upright' ) . '</p>',
		                     'respond_title'        => '<h3 id="reply-title" class="section-title">' . __( 'Leave a comment', 'upright' ) . '</h3>'
		) );

	endif; ?>

</div><!-- #comments .comments-area -->
