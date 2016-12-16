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
		<?php if (function_exists( 'has_custom_logo' ) && has_custom_logo( ) ) : ?>
			<div class="rsrc-header-img col-md-4">
				<?php	the_custom_logo( ); ?>
			</div>
		<?php else : ?>
			<div class="rsrc-header-text">
				<<?php echo $heading ?> class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></<?php echo $heading ?>>
				<<?php echo $desc ?> class="site-desc"><?php esc_attr( bloginfo( 'description' ) ); ?></<?php echo $desc ?>>
			</div>
		<?php endif; ?>
		<div class="header-ad col-md-8">
			<?php if ( is_active_sidebar( 'first-mag-header-top-section' ) ) { ?>
				<div id="header-ad-section" class="clearfix">
					<?php
					// Calling the header sidebar if it exists.
					dynamic_sidebar( 'first-mag-header-top-section' );
					?>
				</div>
			<?php } ?>
		</div>
    </header> 

