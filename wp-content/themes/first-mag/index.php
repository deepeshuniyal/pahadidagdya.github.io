<?php get_header(); ?>

<?php get_template_part( 'template-part', 'head' ); ?>

<?php get_template_part( 'template-part', 'topnav' ); ?>

<!-- start content container -->
<div class="row rsrc-content">

	<?php //left sidebar ?>
	<?php get_sidebar( 'left' ); ?>


    <div class="col-md-<?php first_mag_main_content_width(); ?> rsrc-main">
		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;
			?>
			<div class="footer-pagination"><?php the_posts_pagination(); ?></div>
			<?php
		else :
			get_template_part( 'content', 'none' );
		endif;
		?>
	</div>

	<?php //get the right sidebar  ?>
	<?php get_sidebar( 'right' ); ?>

</div>
<!-- end content container -->

<?php get_footer(); ?>
