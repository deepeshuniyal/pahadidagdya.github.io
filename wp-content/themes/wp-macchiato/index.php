<?php get_header(); ?>
<?php
$wp_macchiato_home_layout =  get_theme_mod( 'wp_macchiato_home_layout' );
if($wp_macchiato_home_layout =='three'){
?>
    <div  class="row">
        <div id="primary" class="content-area col-lg-6 col-lg-push-3">
                <?php if ( have_posts() ) : ?>           	
                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                        /* Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'content', get_post_format() );
                    ?>
                <?php endwhile; ?>
                <?php wp_macchiato_pagination(); ?>
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>
        </div>
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
	    <div  class="row">
        <div id="primary" class="content-area col-lg-9">
                <?php if ( have_posts() ) : ?>           	
                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                        /* Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'content', get_post_format() );
                    ?>
                <?php endwhile; ?>
                <?php wp_macchiato_pagination(); ?>
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3 ">
            <aside id="right-widget" class="widget-container">
                <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
					<?php dynamic_sidebar( 'rigth-sidebar' ); ?>
                <?php } ?>
            </aside>
        </div>
    </div>
<?php } ?>
<?php get_footer(); ?>