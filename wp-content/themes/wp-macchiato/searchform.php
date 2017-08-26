<form method="get" class="searchform" action="<?php echo home_url(); ?>/">
	<input type="text" name="s" class="s" value="<?php _e('Search here..', 'wp-macchiato'); ?>" onfocus='if (this.value == "<?php _e('Search here..', 'wp-macchiato'); ?>") { this.value = ""; }' onblur='if (this.value == "") { this.value = "<?php _e('Search here..', 'wp-macchiato'); ?>"; }' />
	<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search-button.png" value="" class="search-button">
</form>