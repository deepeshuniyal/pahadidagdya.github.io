<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

?>
<table style="cellspacing:10px;">

	<tr>
		<td><input id='<?php echo $this->__('beta_image_id'); ?>' type= 'hidden' name= '<?php echo $this->__('beta_image'); ?>' value = '<?php echo $beta_image; ?>' />
		<?php /*?><input id='<?php echo $this->__('beta_image_button_id'); ?>' type= 'button' name= '<?php echo $this->__('beta_image_button'); ?>' value='Add Image' /><?php */?>
		<?php if(!empty($beta_image)){?><img class="beta_second_image" src="<?php echo $beta_image; ?>" /><?php } else { ?><img style="display:none;" class="beta_second_image" src="" /><?php } ?>
		<a id="<?php echo $this->__('beta_image_button_id'); ?>" href="javascript:void(0)" title="Set Featured Image B"><?php if(!empty($alpha_image)){ echo 'Remove'; } else { echo 'Set';}?> Featured Image B</a>
		</td>
	</tr>

	<tr style="display:none;">
	<td><label for='<?php echo $this->__('unit_id'); ?>'> <?php _e('Unit', $this->__('text_domain')); ?> </label></td>
	<td><input id='<?php echo $this->__('unit_id'); ?>' type= 'text' name= '<?php echo $this->__('unit'); ?>' value = '<?php if( !isset( $unit ) || empty( $unit ) ) echo 10; else echo $unit; ?>' /></td>
	</tr>

</table>
<script>

// For Beta Image
jQuery(document).ready(function(){
	jQuery('#<?php echo $this->__('beta_image_button_id'); ?>').click(function(e){
		e.preventDefault();
		var image = wp.media({title: 'Upload Image', multiple: false}).open().on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			jQuery('#<?php echo $this->__('beta_image_id'); ?>').val(uploaded_image.attributes.url);
			var image_url_uploaded_data2 = uploaded_image.attributes.url;
			if(image_url_uploaded_data2 != ''){
				jQuery('.beta_second_image').css('display', 'block'); 
				jQuery('.beta_second_image').attr('src', ''); 
				jQuery('.beta_second_image').attr('src', image_url_uploaded_data2);
			}
			
		});
	});
});

</script>
