<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package WP Macchiato
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="top-bar">
    	<div class="container">
            <div id="top-nav" class="row">
                <div class="navbar-default main-navigation">                             
                    <!-- Home and toggle get grouped for better mobile display -->                
                    <div class="navbar-header">                
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">                        
                            <span class="sr-only"><?php _e( 'Top navigation', 'wp-macchiato' ); ?></span>                        
                            <span class="icon-bar"></span>                        
                            <span class="icon-bar"></span>                        
                            <span class="icon-bar"></span>                        
                        </button>                
                    </div>          
                    <!-- Collect the nav links, forms, and other content for toggling -->                    
                    <div class="collapse navbar-collapse" id="navbar-collapse-1">                    
                        <?php
                            if (has_nav_menu('top-menu')) {
                              wp_nav_menu( array(
                                  'theme_location' => 'top-menu',
                                  'depth'	=> 3,            
                                  'container'	=> '',
                                  'menu_class'	=> 'nav navbar-nav',
                              ) );}                                   
                        ?>    
                    </div><!-- /.navbar-collapse -->
                </div>
            </div>
        </div>
    </div>
    <div class="main-wrap">
    	<div id="main-container" class="container">
        	<header id="masthead" class="header" role="banner">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="logo site-branding">
                        	<?php if ( get_theme_mod( 'wp_macchiato_logo' ) ) : ?>								
                            <div id="site-logo"><a href="<?php echo esc_url(home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name') ); ?>" rel="home"><img src="<?php echo esc_url(get_theme_mod( 'wp_macchiato_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a></div>
							<?php else : ?>
                            	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name') ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                            <?php endif; ?>
                        </div>      
                        <?php dynamic_sidebar('top-right-widget'); ?>        
                   	</div>
                </div>	
            </header>
			<nav id="main-navigation" class="navbar navbar-default main-navigation" role="navigation">
                  <div class="container-fluid">
                    <!-- Home and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only"><?php _e( 'Toggle navigation', 'wp-macchiato' ); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>               
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar-collapse">
                      <?php
                            $args = array(
                            'theme_location' => 'primary-menu',
                            'depth'	=> 3,
                            'container'	=> '',
                            'fallback_cb' => 'wp_macchiato_menu',
                            );							
                            wp_nav_menu($args);
                    ?>                  
                    </div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>