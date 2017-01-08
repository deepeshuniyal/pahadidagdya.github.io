<?php
	// @ require_once('algorithm.php');
// @
	// @ $data = array(
	  // @ 'alpha-image' => array(
		// @ 'impressions' => '17', // int : arbitary number
		// @ 'clicks' => '1', // int : arbitary number
		// @ ),
	  // @ 'beta-image' => array(
		// @ 'impressions' => '23', // int : arbitary number
		// @ 'clicks' => '3', // int : arbitary number
		// @ ),
	  // @ 'view' => array(
		// @ 'alpha' => '0', // int : arbitary number
		// @ 'beta' => '0', // int: arbitary number
		// @ )
	// @ );
// @
// @
	// @ $ab = new AB($data, 10);
	// @ $this->pr($ab->get_image());
	// @ $this->pr($ab->get_data());
	// @ $this->pr($ab->get_unit());

$post_id = '15163';

$this->pr(get_post_meta($post_id, $this->__('data'), true));

?>
<div><a href='nothing-to-say'>some random text</a></div>
<div class='container-fluid'>

	<div class='row-fluid'>

		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center page-header'>
			<h3 class=''><?php echo $this->name; ?></h3>
		</div>

		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center'>
			<?php $this->save_admin_form_data(); ?>
		</div>

		<button id='click-here'>Click here</button>
		<div id='display'></div>

		<script>

			jQuery(document).ready(function(){
				jQuery('a:contains("some random text")').click(function(e){
					href = jQuery('a:contains("some random text")').attr('href');
					e.preventDefault();
					jQuery('#display').html("Just Clicked");
					jQuery.post(
						ajaxurl,
						{
							'action': 'click',
							'time': 'night',
							'programmer': 'himel'
						},
						response
					);

					function response(response){
						jQuery('#display').html(response + href);
						// @ window.location = $(this).href;
					}
				});
			});

		</script>

		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6'>

			<form class='form-inline'method='post' action=''>

				<div class="form-group">
					<label for="<?php echo $this->__('count-admin');?>">Count Admin View : </label>
					<input type="checkbox" class="form-control" id="<?php echo $this->__('count-admin');?>" name='<?php echo $this->__('count-admin');?>' <?php if(get_option($this->__('count-admin')) == 'on') echo 'checked';?> >
					<p class="help-block">If this checkbox is checked then the product view of this site's admin(s) will also be counted alongside other users.</p>
				</div>

				<br />
				<br />

				<div class="form-group">
					<label for="<?php echo $this->__('available-to-all');?>">Available To All : </label>
					<input type="checkbox" class="form-control" id="<?php echo $this->__('available-to-all');?>" name='<?php echo $this->__('available-to-all');?>' <?php if(get_option($this->__('available-to-all')) == 'on') echo 'checked';?> >
					<p class="help-block">If this checkbox is checked then the product view data will be shown to all the registered and unregistered visitors of this site.</p>
				</div>

				<br />
				<br />

				<button type="submit" class="btn btn-primary">Save Settings</button>

			</form>

		</div>
		<hr style='border-color:red;' />
		<div class='xs-col-12 sm-col-12 md-col-6 lg-col-6 text-center page-header'>
			<h5>Thanks for creating with <a href="https://wordpress.org/plugins/<?php echo $this->prefix;?>"><?php echo $this->name; ?></a>.</h5>
		</div>

	</div>

</div>
