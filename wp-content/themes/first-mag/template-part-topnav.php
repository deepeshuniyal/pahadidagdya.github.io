<?php if ( has_nav_menu( 'main_menu' ) ) : ?>
	<div class="row rsrc-top-menu" >
		<nav id="site-navigation" class="navbar navbar-inverse" role="navigation"> 
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-1-collapse">
					<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'first-mag' ); ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="visible-xs navbar-brand"><?php esc_html_e( 'Menu', 'first-mag' ); ?></div>
				<?php
				if ( get_theme_mod( 'get-home-icon', 1 ) == 1 ) {
					if ( is_front_page() ) {
						$home_icon_class = 'home-icon front_page_on';
					} else {
						$home_icon_class = 'home-icon';
					}
					?>
					<div class="<?php echo $home_icon_class; ?> hidden-xs">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><i class="fa fa-home"></i></a>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			wp_nav_menu( array(
				'theme_location'	 => 'main_menu',
				'depth'				 => 4,
				'container'			 => 'div',
				'container_class'	 => 'collapse navbar-collapse navbar-1-collapse',
				'menu_class'		 => 'nav navbar-nav',
				'fallback_cb'		 => 'wp_bootstrap_navwalker::fallback',
				'walker'			 => new wp_bootstrap_navwalker() )
			);
			?>
		</nav>
	</div>
<?php endif; ?>
<?php if ( is_active_sidebar( 'first-mag-top-ad-section' ) ) { ?>
	<div class="fullwidth-ad-section row" class="clearfix">
		<?php dynamic_sidebar( 'first-mag-top-ad-section' ) ?>
	</div>
<?php } ?>

