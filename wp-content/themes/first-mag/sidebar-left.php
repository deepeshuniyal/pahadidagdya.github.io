<?php if ( get_theme_mod( 'left-sidebar-check', 0 ) != 0 ) : ?>
	<aside id="sidebar-secondary" class="col-md-<?php echo absint( get_theme_mod( 'left-sidebar-size', 3 ) ); ?> rsrc-left" role="complementary">
		<?php dynamic_sidebar( 'first-mag-left-sidebar' ); ?>
	</aside>
<?php endif; ?>