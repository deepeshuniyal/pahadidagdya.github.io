<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP Macchiato
 */
get_header();
$wp_macchiato_home_layout =  get_theme_mod( 'wp_macchiato_home_layout' );
if($wp_macchiato_home_layout =='three'){
?>
<div class="row">
	<section id="primary" class="content-area col-lg-6 col-lg-push-3">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();
						elseif ( is_tag() ) :
							single_tag_title();
						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'wp-macchiato' ), '<span class="vcard">' . get_the_author() . '</span>' );
						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'wp-macchiato' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'wp-macchiato' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'wp-macchiato' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'wp-macchiato' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'wp-macchiato' ) ) . '</span>' );
						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'wp-macchiato' );
						else :
							_e( 'Archives', 'wp-macchiato' );
						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', 'archive' );
				?>
			<?php endwhile; ?>
			<?php wp_macchiato_paging_nav(); ?>
		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
	</section><!-- #primary -->
	<div class="col-lg-3 col-lg-pull-6">
            <aside id="left-widget" class="widget-container">
                <?php if ( is_active_sidebar( 'left-sidebar' ) ) { ?>
					<?php dynamic_sidebar( 'left-sidebar' ); ?>
                <?php } ?>

            </aside>
        </div>
        <div class="col-md-3 ">
            <aside id="right-widget" class="widget-container">
                <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
					<?php dynamic_sidebar( 'rigth-sidebar' ); ?>
                <?php } ?>
            </aside>
        </div>
</div>
<?php } else {?>
<div class="row">
	<section id="primary" class="content-area col-md-9">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();
						elseif ( is_tag() ) :
							single_tag_title();
						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'wp-macchiato' ), '<span class="vcard">' . get_the_author() . '</span>' );
						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'wp-macchiato' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'wp-macchiato' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'wp-macchiato' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'wp-macchiato' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'wp-macchiato' ) ) . '</span>' );
						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'wp-macchiato' );
						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'wp-macchiato' );
						else :
							_e( 'Archives', 'wp-macchiato' );
						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', 'archive' );
				?>
			<?php endwhile; ?>
			<?php wp_macchiato_paging_nav(); ?>
		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
	</section><!-- #primary -->
	<div class="col-md-3 ">
        <aside id="right-widget" class="widget-container">
            <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
                <?php dynamic_sidebar( 'rigth-sidebar' ); ?>
            <?php }  ?>

        </aside>
    </div>
</div>
<?php } ?>
<?php get_footer(); ?>

