<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

?>
<style>
.alpha_second_image, .beta_second_image {
    display: block;
    height: auto;
    width: 100%;
}
.content_alpha_image, .content_beta_image {
    display: inline-block;
    padding: 3%;
    width: 94%;
}
.cab_align{ margin-top:5% !important; }
#images-optimizer__beta_image_button_id, #images-optimizer__alpha_image_button_id{ margin-top:10px; display:block; }
#statistics-table {
    border: 1px solid #000;
    width: 100%;
}
#statistics-table tr {
    border: 1px solid #000;
    text-align: center;
}
#statistics-table th {
    border: 1px solid #000;
    color: #4aeef0;
    padding: 10px;
    text-align: center;
}
#statistics-table td {
    border: 1px solid #000;
    width: 100%;
}
.reset_data_container {
    display: inline-block;
    margin-top: 5%;
    width: 100%;
}
.reset_data_container > input {
    margin-top: 3px;
}
</style>

<table style="cellspacing:10px;width:100%;">
	<?php if(empty($alpha_image)){ $alpha_image = ''; }
	if(empty($beta_image)){ $beta_image = ''; }
	$giphyStatus = ( get_post_meta( get_the_ID() , $this->__('alpha_image_giphy') , true ) == '1' ) ? get_post_meta( get_the_ID() , $this->__('alpha_image_giphy') , true ) : 0 ;
	
	$giphyFeatured = ( get_post_meta( get_the_ID() , 'featured_image_giphy' , true ) == '1' ) ? get_post_meta( get_the_ID() , 'featured_image_giphy' , true ) : 0 ;
	?>
	<tr>
		<td>
		
		<input type="hidden" class="giphy-featured" value="<?php echo $giphyFeatured; ?>" name="<?php echo 'featured_image_giphy'; ?>" >
		
		<input type="hidden" class="giphy-alpha" value="<?php echo $giphyStatus; ?>" name="<?php echo $this->__('alpha_image_giphy'); ?>" >
		
		
		<input id='<?php echo $this->__('alpha_image_id'); ?>' type= 'hidden' name= '<?php echo $this->__('alpha_image'); ?>' value = '<?php echo $alpha_image; ?>' />
		<?php /*?><input id='<?php echo $this->__('alpha_image_button_id'); ?>' type= 'button' name= '<?php echo $this->__('alpha_image_button'); ?>' value='Add Image' /><?php */?>
		<?php if(!empty($alpha_image)){?><img class="alpha_second_image" src="<?php echo $alpha_image; ?>" /><?php } else { ?> <img style="display:none;" class="alpha_second_image" src="" /><?php } ?>
		<a id="<?php echo $this->__('alpha_image_button_id'); ?>" href="javascript:void(0)" title="Set Featured Image" class="media-uploader"><?php if(!empty($alpha_image)){ echo 'Remove'; } else { echo 'Set';}?> featured image</a>
		</td>
	</tr>

</table>
<script>

// For Alpha Image
jQuery(document).ready(function(){
	jQuery('#<?php echo $this->__('alpha_image_button_id'); ?>').click(function(e){
		e.preventDefault();
		var image = wp.media({title: 'Upload Image', multiple: false}).open().on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			jQuery('#<?php echo $this->__('alpha_image_id'); ?>').val(uploaded_image.attributes.url);
			var image_url_uploaded_data1 = uploaded_image.attributes.url;
			if(image_url_uploaded_data1 != ''){
				jQuery('.alpha_second_image').css('display', 'block'); 
				jQuery('.alpha_second_image').attr('src', ''); 
				jQuery('.alpha_second_image').attr('src', image_url_uploaded_data1);
			}
			
		});
	});
});

</script>
