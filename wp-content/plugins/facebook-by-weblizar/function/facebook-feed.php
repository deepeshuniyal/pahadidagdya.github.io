<?php  
if(isset($_POST['security']))  {
	if (wp_verify_nonce( $_POST['security'], 'feed_security_action' ) ) {	
		if(isset($_REQUEST['ffp_page_url']))  {
		$facebook_feed = serialize( array(
		'ffp_page_url' =>sanitize_text_field($_REQUEST['ffp_page_url']),
		'ffp_limit' => sanitize_text_field($_REQUEST['ffp_limit']),
		'ffp_timeline_layout' => sanitize_text_field($_REQUEST['ffp_timeline_layout']),
		'feed_customs_css' => sanitize_text_field($_REQUEST['feed_customs_css']),
		));
		update_option("weblizar_facebook_feed_option_settings", $facebook_feed);
		}
	}
}
$facebook_feed_fetch = unserialize(get_option("weblizar_facebook_feed_option_settings"));
if(isset($facebook_feed_fetch["ffp_limit"])) { $ffp_limit=$facebook_feed_fetch["ffp_limit"]; } else {$ffp_limit="5";}
if(isset($facebook_feed_fetch["ffp_timeline_layout"])) { $ffp_timeline_layout=$facebook_feed_fetch["ffp_timeline_layout"]; } else {$ffp_timeline_layout="full_width"; }
if(isset($facebook_feed_fetch["ffp_page_url"])) { $ffp_page_url=$facebook_feed_fetch["ffp_page_url"]; } else {$ffp_page_url="https://www.facebook.com/weblizarstyle/"; }
if(isset($facebook_feed_fetch["feed_customs_css"])){  $feed_customs_css=$facebook_feed_fetch["feed_customs_css"]; } else { $feed_customs_css=""; } ?>
<!---------------- facebook feed tab------------------------>
<?php $feed_security_action_nonce = wp_create_nonce("feed_security_action"); ?>
<div class="block ui-tabs-panel deactive" id="option-fbfeed">
  <div class="section">
	<form method="post" id="weblizar_feed_setting_option">
	  <div class="feed_setting_page">
		 <div class="feedheading_cls">
			<h3 class="feedheading"><?php _e('Feed Settings', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></h3>
		 </div>
	     <div class="col-md-6 fpp_border_left">
			<div class="col-md-12 no-pad form-group">
			   <h3></h3>
	            <div class="col-md-12 no-pad">
	               <div class="ffp_set_l col-md-6"> <label> <?php _e('Choose page, Group or Profile:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
	               <div class="col-md-6">
	                 <select name="feed_type" id="feed_type" style="width:100px;" onchange="feed_type_change_function()">
	                   <option value="page" selected="selected" > <?php _e('Page', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
	                   <option value="group"> <?php _e('Group', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
	                   <option value="profile"> <?php _e('Profile', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
	                 </select>
	              </div>
	            </div>
	            <!-- fb type page -->
	             <div id="ffp_type_page" class="col-md-12 no-pad">
			        <div>
					  <div  class="ffp_set_l col-md-6"><label> <?php _e('Page url:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
					  <div class="ffp_set_l col-md-6"><input type="text" id="ffp_page_url" name="ffp_page_url" value="<?php if(isset($ffp_page_url)) { echo $ffp_page_url;} else { echo "https:www.facebook.com/weblizarstyle/";} ?>" style="width:100%"></div>
				   </div>
	            </div>
				  <!-- fb type group -->
	            <div id="ffp_type_group" class="col-md-12 no-pad" style="display:none; color:red;">
	               <div class="col-md-6"><label for="ffp_group_url"> <?php _e('Group id:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </label></div>
	               <div class="col-md-6"><?php _e('This Options Available in pro version', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></div>
	            </div>
	            <div id="ffp_type_group_token" class="col-md-12 no-pad" style="display:none; color:red;">
	             <div class="col-md-6"><label for="ffp_group_token"> <?php _e('Acess Token:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </label></div>
	             <div class="col-md-6"><?php _e('This Option Available in pro version', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></div>
	           </div>
	           <!-- fb type profile -->
	           <!-- fb content type -->
	            <div class="col-md-12 no-pad">
	              <div class="col-md-6"><label> <?php _e('Content type:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </label></div>
	              <div class="col-md-6 no-pad">
	                <input type="radio" class="inputbox" id="ffp_content_timeline" name="ffp_content_type" checked="checked"  value="timeline"  onclick="feed_timelineChanged();">
	                 <label> <?php _e('Timeline', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>&nbsp;
	                 <input type="radio"   class="inputbox" id="ffp_content_specific" name="ffp_content_type" value="specific"   onclick="feed_specificChanged();" >
	                 <label> <?php _e('Specific', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	              </div>
				  <br>
	              <div  class="col-md-12 no-pad timeline_content" style="display:none">
	               <div class="col-md-6"><label> <?php _e('Show posts by:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> </label></div>
	                 <div class="col-md-6">
	                  <select name="ffp_timeline_type" disabled id="ffp_timeline_type">
	                    <option value="posts"> <?php _e('Owner', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
	                    <option value="feed"> <?php _e('Owner and other', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
	                  </select>
					    <p class="description"><?php _e('Available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
	                 </div>
	              </div>
				  <div  class="col-md-12 no-pad timeline_content" style="display:none">
	                <div class="col-md-6"><label> <?php _e('Post type:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
					<div class="col-md-6">
	                  <input type="checkbox"  class="video_light_box" id="ffp_timeline_statuses" name="ffp_timeline_statuses" value="statuses" disabled >
	                    <label> <?php _e('Statuses', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>&nbsp;
	                     <br>
	                     <input type="checkbox" class="video_light_box" id="ffp_timeline_photos" checked="checked" name="ffp_timeline_photos"  value="photos"  >
	                     <label> <?php _e('Photos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                     <br>
	                     <input type="checkbox"  class="video_light_box" id="ffp_timeline_videos" name="ffp_timeline_videos"  value="videos" disabled >
	                     <label> <?php _e('Videos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                     <br>
	                     <input type="checkbox"  class="video_light_box" id="ffp_timeline_links" name="ffp_timeline_links"  value="links" disabled >
	                     <label> <?php _e('Links', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                     <br>
	                     <input type="checkbox" class="video_light_box" id="ffp_timeline_events" name="ffp_timeline_events"  value="events" disabled  >
	                     <label style="display:inline-block;"> <?php _e('Events', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                  </div>
	              </div>
				   <div style="display:none;" class="col-md-12 no-pad specific_content">
	                 <div class="col-md-6"><label> <?php _e('Use page s:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
		               <div class="col-md-6">
	                     <input type="radio" checked="checked" disabled class="inputbox" id="ffp_specific_photos"  name="ffp_specific" value="photos" onclick="ffp_video_light_box_func();">
	                     <label> <?php _e('Photos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                      <br>
	                      <input type="radio"  disabled class="inputbox" id="ffp_specific_videos"  name="ffp_specific" value="videos"  onclick="ffp_video_light_box_func();">
	                      <label> <?php _e('Videos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                       <br>
	                      <input type="radio" disabled class="inputbox" id="ffp_specific_albums" name="ffp_specific" value="albums"  onclick="ffp_video_light_box_func();">
	                      <label> <?php _e('Albums', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
	                       <br>
	                       <input type="radio" disabled class="inputbox" id="ffp_specific_events"  name="ffp_specific" value="events"   onclick="ffp_video_light_box_func();">
	                       <label> <?php _e('Events', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label>
							<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
	                    </div>
				    </div>
					<div class="col-md-12 no-pad specific_content" style="display:none;">
					 <div class="col-md-6" ><label><?php _e('Layout', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></div>
					  <div class="col-md-6" >
						<select name="ffp_gallery_Layout" disabled id="ffp_gallery_Layout">
						   <optgroup label="Select Gallery Layout">
							<option value="col-md-12"> <?php _e('5+ Column Layouts ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>							
						 </optgroup>
				        </select>
						<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
					  </div>
		            </div>
					<div class="col-md-6" ><label> <?php _e('hover Effect', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
				  <div class="col-md-6" >
						<select  name="ffp_gallery_effect" id="ffp_gallery_effect" class="form-control" disabled>
							<option value="gallery_effect_1"><?php _e('60+ Image Effects', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>					 
						</select>
					<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
			   </div>
			   </div>	
		  </div>
	 </div>
	  <div class="col-md-6">
	   <div class="col-md-12">
	        <div class="col-md-12 no-pad form-group">
			  <div class="col-md-12 no-pad specific_contents">
				 <h3></h3>
	         </div>
		   <div class="col-md-12 no-pad">
			<div class="col-md-6" ><label> <?php _e('hover color', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
			 <div class="col-md-6" >
			  <input type="text" disabled class="" name="ffp_hover_color"  id="ffp_hover_color" value="#2266a5" />
			 <p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>  
			</div> 
		  </div>
		  <div class="col-md-12 no-pad">
		   <div class="col-md-6 "><label> <?php _e('Number of posts:',WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
			 <div class="col-md-6">
			 <input  type="number"  min="1" max="50" id="ffp_limit" name="ffp_limit" value="<?php if(isset($ffp_limit)){ echo $ffp_limit;} else { echo "5";}?>">
			 </div>
		  </div>
	      <div class="col-md-12 no-pad">
			   <div class="col-md-6 "><label> <?php _e('Loading effect:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
				 <div class="col-md-6">
				<select id="ffp_loading_effect" disabled name="ffp_loading_effect">
				  <option value="none"> <?php _e('None', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>				  
				</select>
				<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
			</div>
        </div>
		<div class="col-md-12 no-pad">
		  <div class="col-md-6 "><label><?php _e('Show Header:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
		  <div class="col-md-6">
			  <input type="checkbox" disabled checked="checked" class="" id="ffp_header_check" name="ffp_header_check"  value="yes"  >
			<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
		  </div>
		</div>
		 <div class="col-md-12 no-pad timeline_content" style="display:none">
		   <div class="col-md-6"><label> <?php _e('Select layout:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </label></div>
		   <?php if((!isset($ffp_timeline_layout))) {$ffp_timeline_layout='full_width';}?>
		   <div class="col-md-6">
			<select id="ffp_timeline_layout"  name="ffp_timeline_layout">
			  <optgroup label="Select layout"> 
				<option value="full_width" <?php selected($ffp_timeline_layout,'full_width' ); ?>> <?php _e('Full-width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
				<option value="half_width" <?php selected($ffp_timeline_layout,'half_width' ); ?>> <?php _e('Half-width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
				<option value="thumbnail" <?php selected($ffp_timeline_layout,'thumbnail' ); ?>> <?php _e('Thumbnail', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>
			 </optgroup>
		   </select>
			<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></p>
		   </div>
		</div>	
		<div class="col-md-12 no-pad">
			<div class="col-md-6"><label><?php _e('Light Box Styles', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></label></div>
			<div class="col-md-6">
			 <select name="ffp_light_Box" id="ffp_light_Box" disabled >
				<optgroup label="Select Light Box">
					<option value="lightbox_photo_box"><?php _e(' 9+Light-Box Layouts', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></option>					 
			  </optgroup>
			</select>
			<p class="description"><?php _e('available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>.</p>
			<input type="hidden"  name="feed_security" id="feed_security" value="<?php echo $feed_security_action_nonce; ?>" />
		   </div>
		</div>		
     </div>
   </div>
	  </div>
	   <div class="col-md-12 col-sm-12 col-xs-12 plugin_desc form-group">
		 <div class="col-md-3"><label>Custom Css</label></div>
		  <div class="col-md-9">
		    <textarea class="form-control" name="feed_customs_css" id="feed_customs_css" placeholder="Custom Css" rows="8">
			 <?php echo $feed_customs_css; ?>
		   </textarea>
		 </div>
	  </div>
		<div style="clear:both"></div>
		</form>
		<div class="col-md-12">
			<button type="button" name="button" class="button-face-feed" onclick="save_feed_general('<?php echo $feed_security_action_nonce; ?>')">
			<?php _e('Save', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></button>
			 <div style="text-align:center;">
				<img id="loading-image" src="<?php echo plugin_dir_url( __FILE__ ) ?>images/loader.gif" alt="Weblizar" height="200" style="margin-top:-10px; margin-right:10px;" alt="Loading..."  class="admin_loading_css"/>
			 </div>
			<div class="success-msg"  style="display:none;">
				<div class="alert alert-success">
				<strong><?php _e('Success!', WEBLIZAR_FACEBOOK_TEXT_DOMAIN );?></strong> <?php _e('Data Save Successfully.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN );?>
				</div>			
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 form-group plugin_desc">
          <h1 class="feed_plugin_details"><?php _e('Plugin Shortcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></h1>
           <p style="font-size: 15px;line-height: 1.5;"><?php _e('copy this shortcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> &nbsp; <strong><b>[facebook_feed]</b></strong> &nbsp<?php _e('to any page, post or widget where you want to showcase your Facebook feed.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> </p>
         </div>
		 <div class="col-md-12 col-sm-12 col-xs-12">
		   <a href="http://demo.weblizar.com/facebook-feed-pro/" target="_blank">
			<img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL; ?>images/available-pro.png" class="img-responsive" />
		   </a>
		 </div>
	</div>
	<div class="clearfix"></div>
</div>