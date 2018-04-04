<div class="container rsrc-container" role="main">
	<?php
	if ( is_front_page() || is_home() || is_404() ) {
		$heading = 'h1';
		$desc	 = 'h2';
	} else {
		$heading = 'h2';
		$desc	 = 'h3';
	}
	?> 
    <header id="site-header" class="row rsrc-header" role="banner">  
		<?php if ( get_theme_mod( 'header-logo', '' ) != '' ) : ?>
			<div class="rsrc-header-img <?php echo esc_attr( get_theme_mod( 'header-style', 'col-md-4' ) ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_mod( 'header-logo' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
			</div>
		<?php else : ?>
			<div class="rsrc-header-text <?php echo esc_attr( get_theme_mod( 'header-style', 'col-md-4' ) ); ?>">
				<<?php echo $heading ?> class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></<?php echo $heading ?>>
				<<?php echo $desc ?> class="site-desc"><?php esc_attr( bloginfo( 'description' ) ); ?></<?php echo $desc ?>>
			</div>
		<?php endif; ?>
		<div class="header-ad <?php if ( get_theme_mod( 'header-style', 'col-md-4' ) == 'col-md-4' ) { echo 'col-md-8'; } else { echo 'col-md-12 text-center'; }; ?>">
			<?php if ( is_active_sidebar( 'first-mag-header-top-section' ) ) { ?>
				<div id="header-ad-section" class="clearfix">
					<?php dynamic_sidebar( 'first-mag-header-top-section' ) ?>
				</div>
			<?php } ?>
		</div>
    </header> 

