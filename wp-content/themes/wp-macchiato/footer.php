		</div> <!--end main-container-->
    </div><!-- end main-wrap -->  
    <footer id="footer" class="container">
        <div class="row">
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-one'); ?>
            </div>
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-two'); ?>
            </div>
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-three'); ?>
            </div>
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-four'); ?>
            </div>
        </div>
        <div id="copyright">
            <div class="row">
                <div class="col-md-12">
					<?php echo __('&copy; ', 'wp-macchiato') . esc_attr( get_bloginfo( 'name', 'display' ) );  ?>
						<?php if(is_home() && !is_paged()){?>            
                        <?php _e('- Powered by ', 'wp-macchiato'); ?><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'wp-macchiato' ) ); ?>" title="<?php esc_attr_e( '' ); ?>"><?php _e('WordPress' ,'wp-macchiato'); ?></a>
                        <?php _e(' and ', 'wp-macchiato'); ?><a href="<?php echo esc_url( __( 'http://invictusthemes.com/', 'wp-macchiato' ) ); ?>"><?php _e('Invictus Themes', 'wp-macchiato'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </footer>
<?php wp_footer(); ?>

</body>

</html>