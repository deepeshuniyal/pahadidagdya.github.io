<style>
<?php   global $ABfonts; 
 foreach( $ABfonts as $key=>$font ){
?>	
	@font-face {
		font-family: <?php echo $font; ?>;
		src: url(<?php echo plugin_dir_url( __FILE__ ).'font/'.$key; ?>.ttf);
	}
<?php } ?>

</style>


<div tabindex="0" id="__wp-uploader-id-ab-popup" style="position: relative;display:none;" class="supports-drag-drop">
	<div class="media-modal wp-core-ui">
		<button type="button" class="button-link media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
		<div class="media-modal-content">
			<div class="media-frame mode-select wp-core-ui hide-toolbar hide-router" id="__wp-uploader-id-0">
				
				<!-- header -->
				<div class="media-frame-title" style="left:0;">
					<h1>Ideas<span class="dashicons dashicons-arrow-down"></span></h1>
				</div>
				
				<div class="media-frame-router" style="display:block;left:0;top: 58px;">
					<div class="media-router image-optimizer-tab">
						<a href="javascript:void(0)" id="giphy" class="media-menu-item active">Giphy</a>
						<a href="javascript:void(0)" id="auto-generated" class="media-menu-item">Auto Generated</a>
					</div>
				</div>
				
				
				<!-- Body -->
				<div class="media-frame-content" style="left:0;bottom: 61px;">
				
					<div class="loader">
						<img src="<?php echo plugin_dir_url( __FILE__ ); ?>loader.gif">
					</div>
				
					<div class="attachments-browser">	
						<ul id="add-grid-image-panel-top" class="attachments ui-sortable ui-sortable canvas" tabindex="-1">
						
							<div class="giphy">
								<img class="powered-by-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>powered-by-giphy.png">
								<div id="add-grid-image-panel"></div>
							</div>
							
							<div class="auto-generated" style="display:none;">
							 
								<div class="toolbar-options" style="float:left;width:100%;padding:10px 0;text-align:right;">
								
									 <!-- Font Size -->
									 <select id="ab-font" style="margin-right: 13px;">
										<?php for( $fontSize=1; $fontSize<=90; $fontSize++ ){ ?>
										   <option value="<?php echo $fontSize; ?>" <?php if( $fontSize == 42 ){ echo 'selected'; } ?> ><?php echo $fontSize; ?>px</option>
									    <?php } ?>
									  </select>
 
									  <!--Font Family -->
									  <select id="ab-font-family" style="margin-right: 13px;">
										  <?php
											foreach( $ABfonts as $fontFamily ){
												echo '<option value="'.$fontFamily.'">'.$fontFamily.'</option>';
											}
										  ?>
									  </select>
									  
									  <!-- Update Button -->
									  <button id="ab-update-canvas" class="button media-button button-default button-large"  style="margin-right: 13px;">Update</button>
									  
								</div>
							
							<?php for( $i=0; $i<8; $i++ ){ ?>
								<li tabindex="0" role="checkbox" aria-label="bored-megan-fox-" aria-checked="false" id="ab-canvas-item-container-<?php echo $i; ?>" class="attachment save-ready ab-custom-image">
									<img style="display:none;" class="ab-featured-canvas-iamge-<?php echo $i; ?> canvas-image">
									<div style="height:150px;" class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
										<div class="thumbnail">
											<canvas id="ab-featured-canvas-iamge-<?php echo $i; ?>" height="150" width="300"></canvas>
										</div>
									</div>
									<button type="button" class="button-link check" tabindex="-1">
										<span class="media-modal-icon"></span>
										<span class="screen-reader-text">Deselect</span>
									</button>
								</li>
							<?php } ?>
						 </div>
						</ul>
					</div>
				</div>
				
				<canvas style="visibility:hidden" id="ab-featured-canvas-image-full" height="630" width="1200"></canvas>
				
				<!-- footer -->
				<div class="media-frame-toolbar" style="left:0;"> 
					
					<div class="media-toolbar">
						<div class="ab-powere-by">
							Powered By <a href="http://giphy.com/">giphy</a>
						</div>
						<div class="media-toolbar-primary search-form">
							<button type="button" class="button media-button button-primary button-large ab-media-button-insert" disabled="disabled" >Insert into post</button>
							<input type="hidden" id="ab-current-tab">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop">
	</div>
</div>
