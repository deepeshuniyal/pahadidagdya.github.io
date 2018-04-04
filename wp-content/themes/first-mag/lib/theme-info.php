<?php
/***
 * Theme Info
 *
 * Adds a simple Theme Info page to the Appearance section of the WordPress Dashboard. 
 *
 */


// Add Theme Info page to admin menu
add_action('admin_menu', 'first_mag_add_theme_info_page');
function first_mag_add_theme_info_page() {
	
	// Get Theme Details from style.css
	$theme = wp_get_theme(); 
	
	add_theme_page( 
		sprintf( esc_html__( 'Welcome to %1$s %2$s', 'first-mag' ), $theme->get( 'Name' ), $theme->get( 'Version' ) ), 
		esc_html__( 'Theme Info', 'first-mag' ), 
		'edit_theme_options', 
		'first-mag', 
		'first_mag_display_theme_info_page'
	);
	
}


// Display Theme Info page
function first_mag_display_theme_info_page() { 
	
	// Get Theme Details from style.css
	$theme = wp_get_theme(); 
	
?>
			
	<div class="wrap theme-info-wrap">

		<h1><?php printf( esc_html__( 'Welcome to %1$s %2$s', 'first-mag' ), $theme->get( 'Name' ), $theme->get( 'Version' ) ); ?></h1>

		<div class="theme-description"><?php echo $theme->get( 'Description' ); ?></div>
		
		<hr>
		<div class="important-links clearfix">
			<p><strong><?php esc_html_e( 'Theme Links', 'first-mag' ); ?>:</strong>
				<a href="<?php echo esc_url( 'http://themes4wp.com/theme/first-mag' ); ?>" target="_blank"><?php esc_html_e( 'Theme Page', 'first-mag' ); ?></a>
				<a href="<?php echo esc_url( 'http://demo.themes4wp.com/firstmag/' ); ?>" target="_blank"><?php esc_html_e( 'Theme Demo', 'first-mag' ); ?></a>
				<a href="<?php echo esc_url( 'http://demo.themes4wp.com/documentation/category/firstmag/' ); ?>" target="_blank"><?php esc_html_e( 'Theme Documentation', 'first-mag' ); ?></a>
				<a href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/first-mag?filter=5' ); ?>" target="_blank"><?php esc_html_e( 'Rate this theme', 'first-mag' ); ?></a>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/kirki/' ); ?>" target="_blank"><?php esc_html_e( 'Kirki (Theme options toolkit)', 'first-mag' ); ?></a>
			</p>
		</div>
		<hr>
				
		<div id="getting-started">
		
			<h3><?php printf( esc_html__( 'Getting Started with %s', 'first-mag' ), $theme->get( 'Name' ) ); ?></h3>
			
			<div class="columns-wrapper clearfix">

				<div class="column column-half clearfix">
						
					<div class="section">
						<h4><?php esc_html_e( 'Theme Documentation', 'first-mag' ); ?></h4>
						
						<p class="about">
							<?php esc_html_e( 'You need help to setup and configure this theme? We got you covered with an extensive theme documentation on our website.', 'first-mag' ); ?>
						</p>
						<p>
							<a href="<?php echo esc_url( 'http://demo.themes4wp.com/documentation/category/firstmag/' ); ?>" target="_blank" class="button button-secondary">
								<?php printf( esc_html__( 'View %s Documentation', 'first-mag' ), 'First Mag' ); ?>
							</a>
						</p>
					</div>
					
					<div class="section">
						<h4><?php esc_html_e( 'Theme Options', 'first-mag' ); ?></h4>
						
						<p class="about">
							<?php printf( esc_html__( '%s makes use of the Customizer for all theme settings. First install Kirki Toolkit and than click on "Customize Theme" to open the Customizer.', 'first-mag' ), $theme->get( 'Name' ) ); ?>
						</p>
						<p>
							<a href="<?php echo wp_customize_url(); ?>" class="button button-primary">
								<?php esc_html_e( 'Customize Theme', 'first-mag' ); ?>
							</a>
						</p>
					</div>
					
					<div class="section">
						<h4><?php esc_html_e( 'Pro Version', 'first-mag' ); ?></h4>
						
						<p class="about">
							<?php printf( esc_html__( 'Purchase the Pro Version of %s to get additional features and advanced customization options.', 'first-mag' ), 'First Mag'); ?>
						</p>
						<ul>
            	<li><?php esc_html_e( 'Unlimited colors', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( '600+ Google fonts', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( 'Advertisement spaces', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( 'Floating sidebars', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( '9 homepage blocks', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( '2 sliders', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( 'Widget animations', 'first-mag' ); ?></li>
            	<li><?php esc_html_e( 'And much more...', 'first-mag' ); ?></li>
            </ul>
						<p>
							<a href="<?php echo esc_url( 'http://themes4wp.com/product/first-mag-pro/' ); ?>" target="_blank" class="button button-secondary">
								<?php printf( esc_html__( 'Learn more about %s Pro', 'first-mag' ), 'First Mag'); ?>
							</a>
						</p>
					</div>

				</div>
				
				<div class="column column-half clearfix">
					
					<img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" />
					
				</div>
				
			</div>
			
		</div>
		
		<hr>
		
		<div id="theme-author">
			
			<p><?php printf( esc_html__( '%1$s is proudly brought to you by %2$s. If you like this theme, %3$s :)', 'first-mag' ), 
				$theme->get( 'Name' ),
				'<a target="_blank" href="http://themes4wp.com/" title="Themes4WP">Themes4WP</a>',
				'<a target="_blank" href="http://wordpress.org/support/view/theme-reviews/first-mag?filter=5" title="First Mag Review">' . esc_html__( 'rate it', 'first-mag' ) . '</a>'); ?>
			</p>
		
		</div>
	
	</div>

<?php
}


// Add CSS for Theme Info Panel
add_action('admin_enqueue_scripts', 'first_mag_theme_info_page_css');
function first_mag_theme_info_page_css( $hook ) { 

	// Load styles and scripts only on theme info page
	if ( 'appearance_page_first-mag' != $hook ) {
		return;
	}
	
	// Embed theme info css style
	wp_enqueue_style('first-mag-theme-info-css', get_template_directory_uri() .'/css/theme-info.css');

}