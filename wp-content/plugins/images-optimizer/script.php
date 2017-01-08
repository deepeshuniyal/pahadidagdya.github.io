<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

$post_title = get_the_title($post_id);

?>
<!--<div id='<?php echo $post_id; ?>'></div>-->
<script>

	jQuery(document).ready(function(){
		jQuery('a:contains("<?php echo $post_title; ?>")').click(function(e){
			href = jQuery('a:contains("<?php echo $post_title; ?>")').attr('href');
			e.preventDefault();
			jQuery('#<?php echo $post_id; ?>').html("Just Clicked");
			jQuery.post(
				'<?php echo admin_url('admin-ajax.php'); ?>',
				{
					'action': 'click',
					'post_id': '<?php echo $post_id; ?>',
					'image_url': '<?php echo $image_url; ?>'
				},
				response
			);

			function response(response){
				jQuery('#<?php echo $post_id; ?>').html(response);
				if(response == 'click saved'){
					// @ jQuery('#<?php echo $post_id; ?>').html(response);
				}
				// @ jQuery('#display').html(response);
				window.location = href;
			}
		});
	});

</script>
