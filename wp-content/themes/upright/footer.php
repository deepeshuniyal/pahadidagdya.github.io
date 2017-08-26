<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>

</div><!-- #main .site-main -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="site-info">
		<?php if ( has_nav_menu( 'footer' ) ) :
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'menu_class'     => 'footer-menu',
				'depth'          => 0
			) );
		endif; ?>

		<span class="footer-credit">
			<?php if ( upright_get_option( 'footer_credit' ) ): ?>
				<?php echo esc_html( upright_get_option( 'footer_credit' ) ); ?>
			<?php else: ?>
				<?php
				/* translators: 1. Site name 2. WordPress Link 3. FancyThemes Link */
				printf(
					__( '&copy; %1$s is powered by %2$s &amp; %3$s.', 'upright' ),
					get_bloginfo( 'name' ),
					sprintf(
						'<a href="%1$s" rel="nofollow" title="%2$s">%3$s</a>',
						'https://wordpress.org/',
						esc_attr__( 'WordPress', 'upright' ),
						esc_html__( 'WordPress', 'upright' )
					),
					sprintf(
						'<a href="%1$s" rel="nofollow" title="%2$s">%3$s</a>',
						'https://fancythemes.com/',
						esc_attr__( 'FancyThemes', 'upright' ),
						esc_html__( 'FancyThemes', 'upright' )
					)
				); ?>
			<?php endif; ?>
		</span>
	</div>
</footer>

</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>