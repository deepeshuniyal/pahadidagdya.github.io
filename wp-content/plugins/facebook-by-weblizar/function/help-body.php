	<div class="block ui-tabs-panel " id="option-general">		
		<div class="col-md-12">
			<div id="heading"><h2><?php _e( 'Facebook Like Box Shortcode Settings', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></h2></div>
			<div class="col-md-6">
			<form name='fb-form' id='fb-form'>
			<p>
				<p><label><?php _e( 'Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="facebook-page-url" name="facebook-page-url" type="text" value="<?php echo esc_attr( $FacebookPageUrl ); ?>">
			</p>
			<br>
			
			<p><label><?php _e( 'Show Faces', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
				<select id="show-fan-faces" name="show-fan-faces">
					<option value="true" <?php if($ShowFaces == "true") echo "selected=selected" ?>><?php _e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
					<option value="false" <?php if($ShowFaces == "false") echo "selected=selected" ?>><?php _e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
				</select>
			</p>
			<br>			
			<p>
				<label><?php _e( 'Show Live Stream', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
				<select id="show-live-stream" name="show-live-stream">
					<option value="true" <?php if($Stream == "true") echo "selected=selected" ?>><?php _e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
					<option value="false" <?php if($Stream == "false") echo "selected=selected" ?>><?php _e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
				</select>
			</p>
			<br>			
			<p>
				<p><label><?php _e( 'Widget Width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="widget-width" name="widget-width" type="text" value="<?php echo esc_attr( $Width ); ?>">
			</p>
			<br>			
			<p>
				<p><label><?php _e( 'Widget Height', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="widget-height" name="widget-height" type="text" value="<?php echo esc_attr( $Height ); ?>">
			</p>
			<br>			
			<p>
				<p><label><?php _e( 'Facebook App ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> (<?php _e('Required', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>)</label></p>
				<input class="widefat" id="fb-app-id" name="fb-app-id" type="text" value="<?php echo esc_attr( $FbAppId ); ?>">
				<?php _e('Get Your Own Facebook APP Id', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>: <a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><?php _e( 'HERE', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>
			</p>
			<br>			
			<p>
				<input onclick="return SaveSettings();" type="button" class="button button-primary button-hero" id="fb-save-settings" name="fb-save-settings" value="<?php _e( 'SAVE', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>">
			</p>
			<p>
				<div id="fb-img" style="display: none;"><img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/loading.gif'; ?>" /></div>
				<div id="fb-msg" style="display: none;" class"alert">
					<?php _e( 'Settings successfully saved. Reloading page for generating preview below.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> 
				</div>
			</p>
			<br>
			</form>
			</div>
			<div class="col-md-6">
			<?php
			if($FbAppId && $FacebookPageUrl) { ?>
			<div id="heading">
				<h2><?php _e('Facebook Likebox " [FBW] " Shortcode Preview', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>  </h2>
			</div>
			<p>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo $FbAppId; ?>&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like-box" data-small-header="<?php echo $Header; ?>" data-height="<?php echo $Height; ?>" data-href="<?php echo $FacebookPageUrl; ?>" data-show-border="<?php echo $ShowBorder; ?>" data-show-faces="<?php echo $ShowFaces; ?>" data-stream="<?php echo $Stream; ?>" data-width="<?php echo $Width; ?>" data-force-wall="<?php echo $ForceWall; ?>"></div>
			</p>
			<?php } ?>
		</div>
	</div>
</div>


<!---------------- need help tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-needhelp">
	<div class="col-md-12">
		<div id="heading">
			<h2><?php _e('Facebook Feed & Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></h2>
		</div>		
	</div>
	<div class="col-md-12">	
		<div class="col-md-6 col-xl-6">
			<p><strong><?php _e('Facebook Page Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></p>
			<hr>
			<p><strong>1 - <?php _e('Facebook Like Box Widget', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></p>
			<p><strong>2 - <?php _e('Facebook Like Box Shoertcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> [FBW]</strong></p>
			<hr>
			<p><?php _e('You can use the widget to display your Facebook Like Box in any theme Widget Sections', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>.</p>
			<p><?php _e('Simple go to your', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> <a href="<?php echo get_site_url(); ?>/wp-admin/widgets.php">
			<strong><?php _e('Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></a> <?php _e('section and activate available', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>  
			<strong>"<?php _e('Facebook Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>" </strong> 
			<?php _e('widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> .</p>
			<br><br>
			
			<p><strong><?php _e('Facebook Like Box Shoertcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> [FBW]</strong></p>
			<hr>
			<p><strong>[FBW]</strong> <?php _e('Shortcode give ability to display Facebook Like Box in any Page / Post with content', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>
			<p><?php _e('To use shortcode, just copy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?><strong>[FBW]</strong> 
			<?php _e('shortcode and paste into content editor of any Page / Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>		
			
		</div>
		<div class="col-md-6">
			<p><strong><?php _e('Facebook Page Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></p>
			<hr>
			<p><strong>1 - <?php _e('Facebook Page Feed Widget', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></p>
			<p><strong>2 - <?php _e('Facebook Page Feed Shoertcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> [facebook_feed]</strong></p><hr>
			<p><?php _e('You can use the widget to display your Facebook Page Feed in any theme Widget Sections', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>
			<p><?php _e('Simple go to your', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> 
			<a href="<?php echo get_site_url(); ?>/wp-admin/widgets.php"><strong><?php _e('Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></a> 
			<?php _e('section and activate available', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>  
			<strong><?php _e('Facebook Feed & Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			<?php _e('widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> .</p>
			<br><br>		
			<p><strong><?php _e('Facebook Page Feed Shoertcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> [facebook_feed]</strong></p>
			<hr>
			<p><strong>[facebook_feed]</strong> <?php _e('shortcode give ability to display Facebook Like Box in any Page / Post with content', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>
			<p><?php _e('To use shortcode, just copy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?><strong>[facebook_feed]</strong> 
			<?php _e('shortcode and paste into content editor of any Page / Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>
		</div>
		<div class="col-md-12 col-xl-12">
			<br><br>
			<p><strong>Q. <?php _e('What is Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> ?</strong></p>
			<p><strong> Ans. <?php _e('Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </strong> <?php _e('is your Facebook page your where you promote your business. Here your customers, clients, friends, guests can like, share, comment review your POST', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</p>
			<br><br>
			<p><strong>Q. <?php _e('What is Facebook APP ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> ?</strong></p>
			<p><strong>Ans. <?php _e('Facebook Application ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			<?php _e(' used to authenticate your Facebook Page data & settings. To get your own Facebook APP ID please read our 4 Steps very simple and easy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
			<a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><strong> <?php _e(' Tutorial', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</strong></a>
			</p>
		</div>
	</div>
</div>
<!---------------- our product tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-upgradetopro">
	<div class="row-fluid pricing-table pricing-three-column">
		<div id="get_pro-settings" class="container-fluid top get_pro-settings">
			<div class="col-md-12 form-group cs-back">	
				<div class="col-md-12 ms-links">
					<div class="cs-top">	
						<h2> <?php _e('Facebook Feed Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </h2>				
					</div>
					<div class="col-md-12">
						<ul class="cs-desc"><li> <?php _e('Unlimited Profile, Page & Group Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </li>
						  <li> <?php _e('Unlimited Feeds Per Page/Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </li>
						  <li><?php _e('Light-Box Layouts 9+', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Tons of Feed Shortcodes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Specific Content Facebook Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Many Loading & Hover CSS Effect', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Auto-Update Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Top Level & Stream Type Comment Display', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Sharing On Social Media', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('No Code Require', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Feed Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Like & Share Button For Each Feed in Like-box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Fast & Friendly Support', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </li>
						  <li><?php _e('Fully Responsive And Optimized', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </li>
						</ul>
					</div>
					<div class="col-md-12 row link-cont">
						<div class="col-md-4 col-sm-4 ms-btn">
							<b><?php _e('Try Live Demo', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></b>
							<a class="btn" target="_blank" href="http://demo.weblizar.com/facebook-feed-pro/" rel="nofollow"><?php _e('Click Here', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
						</div>
						<div class="col-md-4 col-sm-4 ms-btn">
							<b><?php _e('Try Before Buy Using Admin Demo', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></b>
							<a class="btn" target="_new" href="http://demo.weblizar.com/facebook-feed-pro-admin/" rel="nofollow"><?php _e('Click Here', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
							<br><span><b>Username:</b> userdemo</span><br><span><b>Password:</b> userdemo</span>
						</div>
						<div class="col-md-4 col-sm-4 ms-btn">					
							<a href="https://weblizar.com/plugins/facebook-feed-pro/" target="_blank" class="button-face"><?php _e('Buy Now ($19)', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </a>
						</div>
					</div> 
				</div>
			</div>		
		</div>
	</div>
</div>
<div class="block ui-tabs-panel deactive" id="option-fbadmin">
	<div class="row-fluid ">
		<div class="col-md-12"> <a href="http://demo.weblizar.com/facebook-feed-pro/" target="_blank"><img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/feed-tab-img.jpg'; ?>" class="img-responsive"/></a></div>
	</div>
</div>