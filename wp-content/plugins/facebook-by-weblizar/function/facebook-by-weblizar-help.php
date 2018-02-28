<div class="wrap" id="weblizar_wrap">		
	<div id="content_wrap">
		   <!-- tabs left -->
      <div class="home-content-top feed-head-cont">
         <!--our-quality-shadow-->
         <div class="clearfix"></div>
		 <div class="bg-feed-head">
         <div class="feed-head clearfix">
			<div class="col-md-6 ">
            <div class="col-md-3 feed-head-logo">
               <img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'/images/logo.png'; ?>" class="img-responsive" alt="Weblizar">
            </div>
				<div class="feed-head-cont-text ">
                  <h3> <span class=""><?php _e( 'Facebook Feed & LikeBox', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> </span> </h3>
                  <p><?php _e('Display a completely responsive & customizable facebook feed on your website which match with the look and feel of your website', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>. </p>
				</div>
			</div>
            <div class="col-md-6 feed-head-cont ">
			<div class="feed-head-cont-inner">
				<div class="col-md-12 col-sm-12 search1 text-right pull-right">
			   		<a href="http://wordpress.org/plugins/facebook-by-weblizar/" class="btn button button-primary" target="_blank" title="Support Forum"> <span class="fa fa-comment-o"></span> <?php _e('Support Forum', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>
					<a href="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'readme.txt'; ?>" class="btn button button-primary" target="_blank" title="Theme Changelog"> <span class="fa fa-pencil-square-o"></span> <?php _e('Plugin Change Log', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a></li>      
					<a href="https://weblizar.com/plugins/facebook-feed-pro/" class="text-right btn button rating"><?php _e('Upgrade To Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> </a>
					<div class="wporg-ratings rating-stars">
						<strong><?php _e('Do you like this plugin', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> ? <br></strong> <?php _e('Please take a few seconds to', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> 
						<a class="weblizar-rate-it"  href="https://wordpress.org/support/plugin/facebook-by-weblizar/reviews/?rate=5#new-post">
						<?php _e('Rate it on WordPress.org', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>!<br>				
						<a href="https://wordpress.org/support/plugin/facebook-by-weblizar/reviews/?rate=5#new-post" data-rating="5" title="Fantastic!" class="startrat" target="_blank" >
							<span class="dashicons dashicons-star-filled" ></span>
							<span class="dashicons dashicons-star-filled" ></span>
							<span class="dashicons dashicons-star-filled" ></span>
							<span class="dashicons dashicons-star-filled" ></span>
							<span class="dashicons dashicons-star-filled" ></span>
						</a>
					</div>
				</div>
            </div>
            </div>
         </div>	
         </div>	
		<div class="tabbable-panel  col-m margin-tops4">
            <div class="tabbable-line">
			<div id="content">
				<div id="options_tabs" class="">
					<ul class="nav nav-tabs tabtop  tabsetting " role="tablist" id="nav">					
						<li class="active"><a id="general"><div class="dashicons dashicons-admin-generic"></div><?php _e('Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
						<li><a id="fbfeed"><div class="dashicons dashicons-align-right"></div><?php _e('Facebook Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
						<li><a id="fbadmin" ><?php _e('Feed Tab', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
						<li><a id="needhelp"><div class="dashicons dashicons-editor-help"></div><?php _e('Need Help', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
						<li><a id="upgradetopro" ><?php _e('Upgrade to Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>						
					</ul>					
					<?php require_once('help-body.php'); ?>
					<?php require_once('facebook-feed.php'); ?>
				</div>		
			</div>
		<div class="clear"></div>
	</div>
</div>