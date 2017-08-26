<?php
/**
 * Plugin Name: Featured Image Optimizer
 * Author Name: swetz, aihimel
 * Version: 3.3
 * Author Email: toaihimel@gmail.com
 * License: GPLv3
 * Description: Like Optimizely, but automated. A/B test any Featured Image on a post, page or Woocommerce product.
 *
 */

class ABT{

	private $prefix, // Plugin wide prefix
		$path, // Path of the plugin
		$name, // Name of the plugin
		$table // Table Name
		;

	public function __construct(){
		// Prohibiting Direct Access
		defined('ABSPATH') or die(require_once('404.php'));
		$this->prefix = 'images-optimizer';
		$this->name = 'Image Optimizer';
		$this->path = __FILE__;
		register_activation_hook($this->path, array($this, 'activate'));
		register_deactivation_hook($this->path, array($this, 'deactivate'));
		register_uninstall_hook('uninstall.php', 'a_b_tester_uninstall');
		// Loading assets
		add_action('wp_enqueue_scripts', array($this, 'assets'));
		add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
		// @ add_action('admin_menu', array($this, 'menu'));

		// Meta box on post loading hooks.
		add_action('add_meta_boxes', array($this, 'meta_boxes'));

		// Seving meta box data
		add_action('save_post', array($this, 'save'), 11);

		// Managing Post thumbnail html
		add_filter('post_thumbnail_html', array($this, 'show_image'), 10, 3);

		// Ajax hooks to capture impression
		add_action('wp_ajax_click', array( $this , 'click' ) );
		add_action('wp_ajax_nopriv_click', array( $this , 'click' ));
		
		//Settings page action Hooks
		
		add_action( 'admin_init', array( $this, 'theme_options_init_abfimage' ) );
		add_action( 'admin_menu', array( $this, 'theme_options_add_ab_settings_page' ) );
		add_action( 'theme_update_actions', array( $this, 'theme_options_do_ab_settings_page' ) );
		add_action('wp_dashboard_setup', array( $this, 'ab_plugin_dashboard_info') );
		
		// custom code
		add_action('admin_footer', array( $this, 'custom_media_uploader') );
		add_action( 'wp_ajax_get_api_images', array( $this, 'get_api_images_callback' ) );		
		add_action( 'wp_ajax_ab_save_image_locally', array( $this, 'ab_save_image_locally_callback' ) );
		add_action('do_meta_boxes',  array( $this , 'replace_featured_image_box') ); 
		
	}

	
	function ab_save_image_locally_callback(){
		
		$upload_dir = wp_upload_dir();
		$randomName = time().'.gif';
		$actualImagePath = $upload_dir['path'].'/'.$randomName;
		$image = file_get_contents( $_POST['imageUrl'] );
		file_put_contents( $actualImagePath , $image); 
		
		$filename = $actualImagePath;

		// The ID of the post this attachment is for.
		$parent_post_id = 0;

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		echo json_encode( array('id'=> $attach_id , 'url' => $upload_dir['url'].'/'.$randomName ) );
		wp_die();
	}
	

	// Replace metabox 
    public function replace_featured_image_box()  
    {  
        remove_meta_box( 'postimagediv', 'post', 'side' );  
        add_meta_box('postimagediv', __('Featured Image | <span class="api_suggestion" data-id="ab_featured_image">Ideas</span><span class="dashicons dashicons-lightbulb"></span>'), 'post_thumbnail_meta_box', 'post', 'side', 'low');  
    }  
	
		
	// Popup Content	
	public function custom_media_uploader(){
		require_once('ad-api-image-popup.php');
	}

	// Admin ajax
	public function get_api_images_callback() {
		
		$title = explode( ' ' , $_POST['title'] );

		foreach( $title as $keyword ){

			if( strlen($keyword) > 2 ){
				$keywords[] = $keyword;
			}
		}
		
		if( count($keywords) > 0 ){
			$title = implode( ',' , $keywords );
		}else{
			$title = $_POST['title'];
		}

		$content = file_get_contents('http://api.giphy.com/v1/gifs/search?q='.$title.'&limit=8'.'&api_key=dc6zaTOxFJmzC');
		echo $content;
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	
	
	

 	// Adding dash Borad Widgets For plugin contact
	
	public function ab_plugin_dashboard_info() {
	
		global $wp_meta_boxes;
		
		wp_add_dashboard_widget('custom_help_widget', 'Plugin A/B Support', 'ABT::ab_plugin_dashboard_help');
		
	}
	
	public function ab_plugin_dashboard_help() {
	
		echo '<p>Thank you for downloading Plugin A/B.<br>Please feel free to contact us on Twitter <a href="https://twitter.com/pluginab" target="_blank">@Pluginab</a>.</p>';
	
	}

	// This function capture click form ajax call
	public function click(){
	
		$post_id = $_POST['post_id'];
		$image_url = $_POST['image_url'];

		$alpah_image = get_post_meta($post_id, $this->__('alpha_image'), true);
		$beta_image = get_post_meta($post_id, $this->__('beta_image'), true);

		$data = get_post_meta($post_id, $this->__('data'), true);

		if($image_url == $alpah_image) $data['alpha-image']['clicks']++;
			else $data['beta-image']['clicks']++;

		update_post_meta($post_id, $this->__('data'), $data);

		die();

	}

	// Showing Image
	public function show_image($html, $post_id, $image_id){

		// If the current user is an admin. Then plugin skips everything.
		// if(current_user_can('manage_options')) return $html;


		// Checks if the tester is turned on.
		if(get_post_meta($post_id, $this->__('status'), true) == 'off') return $html;

		// Checking if both alpha and beta image is set
		if(!get_post_meta($post_id, $this->__('alpha_image'), true) || !get_post_meta($post_id, $this->__('beta_image'), true)) return $html;

		$data = get_post_meta($post_id, $this->__('data'), true);
		$unit = get_post_meta($post_id, $this->__('unit'), true);

		// Implementing the algorithm
		require_once('algorithm.php');
		$ab = new AB($data, $unit);
		
		$poweredBy = '';
		
		if($ab->get_image() ==  'alpha'){
			if( get_post_meta( get_the_ID() , $this->__('alpha_image_giphy') , true ) == 1 ){
				$poweredBy = '<img src="'.plugin_dir_url( __FILE__ ).'powered-by-giphy.png">';
			}
			$image_url = get_post_meta($post_id, $this->__('alpha_image'), true);
			$data['alpha-image']['impressions']++;
		} else {
			if( get_post_meta( get_the_ID() , 'featured_image_giphy' , true ) == 1 ){
				$poweredBy = '<img src="'.plugin_dir_url( __FILE__ ).'powered-by-giphy.png">';
			}
			$image_url = get_post_meta($post_id, $this->__('beta_image'), true);
			$data['beta-image']['impressions']++;
		}

		// Updating the database
		update_post_meta($post_id, $this->__('data'), $ab->get_data()); // saving back the data.
		update_post_meta($post_id, $this->__('unit'), $ab->get_unit()); // saving back the unit.

		// Replacing the image url
		
		$pattern = '~src="(.*)"~isU';
		preg_match($pattern, $html, $matches);

		//$pattern1 = '~srcset="(.*)"~isU';
		//preg_match($pattern1, $html, $matches1);
		
		//$html = str_replace('matches1[1]', '', $html);
		
		$html = str_replace($matches[1], $image_url, $html);

		// Click Tracking Script
		require('script.php');

		return '<div style="position:relative;float:left;"><img src="'.$image_url.'"><span style="right: 0px; position: absolute; top: 16px;">'.$poweredBy.'</span></div>';
		
	
	}
	//Adding Setting page for this plugin
	
	/************************************
	Creating Theme Options For Facebook page setting
	*************************************/

	public static function theme_options_init_abfimage() {
			register_setting( 'theme_options', 'theme_options');
	} 
	
			/************************************
			Add this options in separate page
			*************************************/
			
	public static function theme_options_add_ab_settings_page() {
			add_options_page( __( 'A/B Image Settings Page', 'sampletheme' ), __( 'A/B Settings Page', 'sampletheme' ), 'edit_theme_options', 'ab_fimage_setting_page', 'ABT::theme_options_do_ab_settings_page' );
	} 

			/************************************
			Creating Form Enter and update the theme option Values
			*************************************/

	public static function theme_options_do_ab_settings_page() {
			
		  // if ( $_POST['update_themeoptions_ab_setting'] == 'true' ) { self::themeoptions_ab_settings_update(); }
?>	
			<div class="wrap">
				<div id="social_options">
				<style>
				table, th, td {
					border: 1px solid black;
				}
				th {
					background: #333333 none repeat scroll 0 0;
					color: #fff;
					text-align: center !important;
					width: 22%;
				}
				th, td {
					padding: 5px;
					text-align: left;
				}
				h1{ margin-bottom:3% !important; }
				</style>
				<h1>Plugin A/B</h1>
				<a href="#" style=" margin-bottom:10px; float:right; " class="export button button-primary button-large">Export Table data into Excel</a>	
			<div id="dvData" style="display:none;">			
					<table style="width:100%">
					  <tr>
						<th><td><b>Article Name</b></td></th>
						<th><td><b>Images</b></td></th>
						<th><td><b>Published Date</b></td></th>		
						<th><td><b>Clicks</b></td></th>
						<th><td><b>Views</b></td></th>
						<th><td><b>CTR</b></td></th>
					  </tr>
				<?php 
				
				$args = array('post_type' => array( 'post', 'page'),'posts_per_page' => -1,'post_status' => 'publish');
				$the_query = new WP_Query( $args );
				// The Loop
				if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
				
				$status = get_post_meta(get_the_ID(), 'images-optimizer__status', true);
				if($status == 'on'){
				
				$ab_data = get_post_meta(get_the_ID(), 'images-optimizer__data', true);
				
				$alpha_image_src = get_post_meta(get_the_ID(), 'images-optimizer__alpha_image', true);
				$beta_image_src = get_post_meta(get_the_ID(), 'images-optimizer__beta_image', true);
				//				echo '<pre>';
				//				print_r($ab_data);
				//				echo '<pre>';
               	$alpha_image_src_image_alt = self::get_attachment_name_from_src($alpha_image_src);
				//$alpha_image_src_image_alt = get_post_meta( $alpha_image_src_attach_id, '_wp_attachment_image_alt', true);
				
				 $beta_image_src_image_alt = self::get_attachment_name_from_src($beta_image_src);
				//$beta_image_src_image_alt = get_post_meta( $beta_image_src_attach_id, '_wp_attachment_image_alt', true);
				?>
					<tr>
						<td style="text-align:center; font-weight:bold;"><?php the_title(); ?></td>
						<td style="text-align:center; font-weight:bold;">
						<?php if($alpha_image_src){echo $alpha_image_src_image_alt; }
						
						if($beta_image_src){echo ",".$beta_image_src_image_alt; }
						
						 ?>
						</td>
						<td style="text-align:center;"><?php the_time('M j, Y @ g:ia'); ?></td>		
						<td style="text-align:center;">
							Image A: (<?php echo $ab_data['alpha-image']['clicks']; ?>)<br />
							Image B: (<?php echo $ab_data['beta-image']['clicks']; ?>)
						</td>
						<td style="text-align:center;">						
							Image A: (<?php echo $ab_data['alpha-image']['impressions']; ?>)<br />
							Image B: (<?php echo $ab_data['beta-image']['impressions']; ?>)
						</td>
						<?php 
						if($ab_data['alpha-image']['impressions'] > 0){  $ctr_first_image = ($ab_data['alpha-image']['clicks'] / $ab_data['alpha-image']['impressions']); 
						} else { $ctr_first_image = 0; }
						if($ab_data['beta-image']['impressions'] > 0){  $ctr_second_image = ($ab_data['beta-image']['clicks'] / $ab_data['beta-image']['impressions']); 
						} else { $ctr_second_image = 0; } ?>
						<td style="text-align:center;">						
							CTR Image A: (<?php echo $ctr_first_image; ?>)%<br />
							CTR Image B: (<?php echo $ctr_second_image; ?>)%
						</td>
						
					</tr>
					  
					<?php	
					}			
					endwhile;
					endif;
					// Reset Post Data
					wp_reset_postdata();				
					?>
					</table>
				</div>
				<div id="dvData1">			
					<table style="width:100%">
					  <tr>
						<th>Article Name</th>
						<th>Images</th>
						<th>Published Date</th>		
						<th>Clicks</th>
						<th>Views</th>
						<th>CTR</th>
					  </tr>
				<?php 
				$args = array('post_type' => array( 'post', 'page'),'posts_per_page' => -1,'post_status' => 'publish');
				$the_query = new WP_Query( $args );
				// The Loop
				if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
				$status = get_post_meta(get_the_ID(), 'images-optimizer__status', true);
				if($status == 'on'){
				$ab_data = get_post_meta(get_the_ID(), 'images-optimizer__data', true);
				
				$alpha_image_src = get_post_meta(get_the_ID(), 'images-optimizer__alpha_image', true);
				$beta_image_src = get_post_meta(get_the_ID(), 'images-optimizer__beta_image', true);
				//				echo '<pre>';
				//				print_r($ab_data);
				//				echo '<pre>';
				?>
					<tr>
						<td style="text-align:center; font-weight:bold;"><?php the_title(); ?></td>
						<td style="text-align:center; font-weight:bold;">
						<?php if($alpha_image_src){?><img src="<?php echo $alpha_image_src; ?>" width="100" height="100" /><br /><?php } ?>
						<?php if($beta_image_src){?><img src="<?php echo $beta_image_src; ?>" width="100" height="100" /><?php } ?>
						</td>
						<td style="text-align:center;"><?php the_time('M j, Y @ g:ia'); ?></td>		
						<td style="text-align:center;">
							Image A: (<?php echo $ab_data['alpha-image']['clicks']; ?>)<br />
							Image B: (<?php echo $ab_data['beta-image']['clicks']; ?>)
						</td>
						<td style="text-align:center;">						
							Image A: (<?php echo $ab_data['alpha-image']['impressions']; ?>)<br />
							Image B: (<?php echo $ab_data['beta-image']['impressions']; ?>)
						</td>
						<?php 
						if($ab_data['alpha-image']['impressions'] > 0){  $ctr_first_image = ($ab_data['alpha-image']['clicks'] / $ab_data['alpha-image']['impressions']); 
						} else { $ctr_first_image = 0; }
						if($ab_data['beta-image']['impressions'] > 0){  $ctr_second_image = ($ab_data['beta-image']['clicks'] / $ab_data['beta-image']['impressions']); 
						} else { $ctr_second_image = 0; } ?>
						<td style="text-align:center;">						
							CTR Image A: (<?php echo $ctr_first_image; ?>)%<br />
							CTR Image B: (<?php echo $ctr_second_image; ?>)%
						</td>
						
					</tr>
					  
					<?php	
					}			
					endwhile;
					endif;
					// Reset Post Data
					wp_reset_postdata();				
					?>
					</table>
				</div>					
				</div>
		</div>

<?php 
		} 
		 
//	/************************************
//	Update the Contact theme option values
//	*************************************/
//		
//
//	 public static function themeoptions_ab_settings_update() {
//	  
//			update_option( 'test_fields', $_POST['test_fields'] );
//					
//	 }
	 
	/************************************
	Get attachment name from the image source URL
	*************************************/

	public static function get_attachment_name_from_src ($image_src) {
		
		global $wpdb;
		$query = "SELECT * FROM {$wpdb->posts} WHERE guid='".$image_src."'";
		$id = $wpdb->get_results($query);
		return $id[0]->post_title;
	
	}
	 
	public static function convert_to_csv($input_array, $output_file_name, $delimiter) {
	
		/** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
		$f = fopen('php://memory', 'w');
		/** loop through array  */
		foreach ($input_array as $line) {
			/** default php csv handler **/
			fputcsv($f, $line, $delimiter);
		}
		/** rewrind the "file" with the csv lines **/
		fseek($f, 0);
		/** modify header to be downloadable csv file **/
		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
		/** Send file to browser for download */
		fpassthru($f);
		
	}

	// Meta box general function
	public function meta_boxes(){

		$screens = array('post', 'page', 'product');
		$priority = '';
		foreach($screens as $screen){
			/*
			 * @parameters
			 * id
			 * title
			 * callback
			 * screen
			 * context
			 * priority
			 * callback_args
			 * */
			add_meta_box($this->__('area'), __('A/B Test Featured Image | <span class="api_suggestion" data-id="ab_test_image" >Ideas</span><span class="dashicons dashicons-lightbulb"></span> ', $this->__('text_domain')), array($this, 'area'), $screen, 'side', $priority);
			
			//add_meta_box($this->__('area1'), __('', $this->__('text_domain')), array($this, 'area1'), $screen, 'side', $priority);
			
			add_meta_box($this->__('area2'), __('Featured Image Performance', $this->__('text_domain')), array($this, 'area2'), $screen, 'side', $priority);
			
		}
	}

	// Checkbox generationg function
	//Featured Image Area A
	public function area($post){

		// Adding a nonce field
		wp_nonce_field($this->__('3223423ljkf9'), $this->__('nonce'));

		// Retriving the previous value of the fields
		$status = get_post_meta($post->ID, $this->__('status'), true);
		$alpha_image = get_post_meta($post->ID, $this->__('alpha_image'), true);
		$beta_image = get_post_meta($post->ID, $this->__('beta_image'), true);
		$unit = get_post_meta($post->ID, $this->__('unit'), true);

		require_once('post-meta-form.php');
		//require_once('stat.php');

	}

	// Checkbox generationg function
	//Featured Image Area B
//	public function area1($post){
//
//		// Adding a nonce field
//		wp_nonce_field($this->__('3223423ljkf9'), $this->__('nonce'));
//
//		// Retriving the previous value of the fields
//		$status = get_post_meta($post->ID, $this->__('status'), true);
//		$alpha_image = get_post_meta($post->ID, $this->__('alpha_image'), true);
//		$beta_image = get_post_meta($post->ID, $this->__('beta_image'), true);
//		$unit = get_post_meta($post->ID, $this->__('unit'), true);
//
//		require_once('post-meta-formB.php');
//		//require_once('stat.php');
//
//	}
	
	// Checkbox generationg function
	//Featured Image Performance
	public function area2($post){

		// Adding a nonce field
		wp_nonce_field($this->__('3223423ljkf9'), $this->__('nonce'));

		// Retriving the previous value of the fields
		$status = get_post_meta($post->ID, $this->__('status'), true);
		$alpha_image = get_post_meta($post->ID, $this->__('alpha_image'), true);
		$beta_image = get_post_meta($post->ID, $this->__('beta_image'), true);
		$unit = get_post_meta($post->ID, $this->__('unit'), true);

		require_once('stat.php');

	}

	public function save($post_id){

		// Checking if nonce is set
		if(!isset($_POST[$this->__('nonce')])){
			$this->log('NOnce is not set!!');
			return;
		} else $this->log('NOnce is set');

		// Cheacking if nonce data is correct
		if(!wp_verify_nonce($_POST[$this->__('nonce')], $this->__('3223423ljkf9'))){
			$this->log('NONCE verification failed');
			return;
		} else $this->log('Nonce Varified!!!');


		// Cheacking user permissions
		if(isset($_POST['post_type']) && $_POST['post_type'] == 'page') if(!current_user_can('edit_page', $post_id)) return;
			elseif (!current_user_can('edit_post', $post_id)) return;

		$post = $_POST;

		if( isset( $post[$this->__('alpha_image_giphy')] ) ){
			update_post_meta($post_id, $this->__('alpha_image_giphy'), $post[$this->__('alpha_image_giphy')] );
		}		
		
		if( isset( $post['featured_image_giphy'] ) ){
			update_post_meta( $post_id, 'featured_image_giphy' , $post['featured_image_giphy'] );
		}

		// Status
		if( isset( $post[$this->__('status')] ) && !empty( $post[$this->__('status')] ) && $post[$this->__('status')] == 'on' ){

			update_post_meta($post_id, $this->__('status'), 'on');

			// For first time
			$data = array(
				'alpha-image' => array(
					'impressions' => '0',
					'clicks' => '0'
				),
				'beta-image' => array(
					'impressions' => '0',
					'clicks' => '0'
				),
				'view' => array(
		 			'alpha' => '0',
		 			'beta' => '0',
		  		)
			);
			update_post_meta($post_id, $this->__('data'), $data);

		} else update_post_meta($post_id, $this->__('status'), 'on');

		if( isset( $post[$this->__('alpha_image')] ) && !empty( $post[$this->__('alpha_image')] ) ){
			update_post_meta($post_id, $this->__('alpha_image'), sanitize_text_field( $post[$this->__('alpha_image')] ));
		}

		//if( isset( $post[$this->__('beta_image')] ) && !empty( $post[$this->__('beta_image')] ) ){
			$default_featured_image_id = get_post_meta($post_id, '_thumbnail_id', true);
			if(!empty($default_featured_image_id)){ $custom_data_url_from_featured = wp_get_attachment_image_src( $default_featured_image_id, 'full' ); 
			$custom_data_url_from_featured_section = $custom_data_url_from_featured[0];
			} else { $custom_data_url_from_featured_section = ''; }
			update_post_meta($post_id, $this->__('beta_image'), $custom_data_url_from_featured_section );
		//}

		if( isset( $post[$this->__('unit')] ) && !empty( $post[$this->__('unit')] ) ){
			update_post_meta($post_id, $this->__('unit'), sanitize_text_field( $post[$this->__('unit')] ));
		}


		// Working on data reset.
		if( isset( $post[$this->__('reset')] ) && !empty( $post[$this->__('reset')] ) && $post[$this->__('reset')] == 'on' ){
			$data = array(
				'alpha-image' => array(
					'impressions' => '0',
					'clicks' => '0'
				),
				'beta-image' => array(
					'impressions' => '0',
					'clicks' => '0'
				),
				'view' => array(
		 			'alpha' => '0',
		 			'beta' => '0',
		  		)
			);
			update_post_meta($post_id, $this->__('data'), $data);

		}

		// Setting featured image if the featured image is not set by the user.
		$this->set_featured_image($post_id);
	}

	private function set_featured_image($post_id){

		global $wpdb;
		// Getting status
		$status = get_post_meta($post_id, $this->__('status'), true);
		$thumbnail = get_post_meta($post_id, '_thumbnail_id', true);

		if( $status == 'on' && empty($thumbnail) ){

			$alpha = get_post_meta($post_id, $this->__('alpha_image'), true);
			$beta = get_post_meta($post_id, $this->__('beta_image'), true);

			if(!empty($alpha)) $image_url = $alpha;
				else $image_url = $beta;

			// If alpha or beta no image is set then it has nothing to do except return.
			if(empty($image_url)) return;
			$error = media_sideload_image($image_url, $post_id, ""); $this->log($error);
			$last_attachment = $wpdb->get_row($query = "SELECT * FROM {$wpdb->prefix}posts ORDER BY ID DESC LIMIT 1", ARRAY_A);
			update_post_meta($post_id, '_thumbnail_id', $last_attachment['ID']);
		}

	}

	// Activation Function
	public function activate(){
		// @ add_option($this->__('count-admin'), 'off', $deprecated = '', $atuoload = 'yes');
		// @ add_option($this->__('available-to-all'), 'off', $deprecated = '', $atuoload = 'yes');
	}

	// Deactivation Function
	public function deactivate(){
		// @ delete_option($this->__('count-admin'));
		// @ delete_option($this->__('available-to-all'));
	}

	// Adds Admin Menu
	public function menu(){
		add_menu_page( $this->name, $this->name, 'manage_options', $this->prefix, array($this, 'admin_control_form'));
	}

	// Saving Admin Form Data
	public function save_admin_form_data(){
		$post = $_POST;
		// Out of stock message
		if(!empty($post) && (!empty($post[$this->__('count-admin')])  || !empty($post[$this->__('available-to-all')]) ) ) {

			if(!empty($post[$this->__('count-admin')])) update_option($this->__('count-admin'), $post[$this->__('count-admin')]);
				else update_option($this->__('count-admin'), 'off');

			if(!empty($post[$this->__('available-to-all')])) update_option($this->__('available-to-all'), $post[$this->__('available-to-all')]);
				else update_option($this->__('available-to-all'), 'off');

			// Success Message
			echo '<div class="alert alert-success" role="alert">Saved Successfully</div>';
		}
	}

	// Admin Controll Form
	public function admin_control_form(){

		if(!current_user_can('manage_options')) wp_die(__('You don\'t have permission to access this page'));

		require_once('admin-contorl-form.php');


	}

	public function assets(){
		wp_enqueue_style($this->__('style-custom'), $this->url('css/style.css'), false);
	}

	public function admin_assets(){
	
		wp_enqueue_script('jquery');
		
		// Custom code
		wp_enqueue_style( 'custom-css', plugin_dir_url( __FILE__ ) . 'css/custom-style.css' );
		wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array(), '' );
	
		wp_register_script( 'images-optimizer-custom', plugin_dir_url( __FILE__ ) . '/js/images-optimizer-custom.js', array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'images-optimizer-custom', plugin_dir_url( __FILE__ ) . '/js/images-optimizer-custom.js', array( 'jquery' ), '1.0' );
		
	}

	// Prefixes all the option names
	private function __($string){return $string = $this->prefix.'__'.$string;}

	// Absolute file path inside the plugin
	private function url($string){return plugins_url('/'.$this->prefix.'/'.$string);}

	// Getting absolute file path
	private function path($relative_path){
		return dirname(__FILE__).'/'.$relative_path;
	}

	// Array printing function
	private function pr($data){echo "<pre>"; print_r($data);echo "</pre>";}

	// logging function
	private function log($string){
		$date_time = getdate();
		$date_time = $date_time['mday'].', '. $date_time['month'].', '.$date_time['year'];
		$string .= "\r\n";
		// Open file
		$recent = fopen($this->path('log/recent-log.txt'), 'w');
		$all = fopen($this->path('log/log.txt'), 'w');
		$string = $date_time.' : '. $string . file_get_contents($this->path('log/log.txt'));
		// writing
		fwrite($recent, $string);
		fwrite($all, $string);

		fclose($recent);fclose($all);
	}
	
}
$abt = new ABT();
$ABfonts = array(
			'open-sans'					=> 'open sans' ,
			'roboto'					=> 'roboto'    ,
			'lato'						=> 'lato'		,
			'oswald'					=> 'oswald'    ,
			'montserrat'				=> 'montserrat' ,
			'pt-sans'					=> 'pt sans',
			'raleway'					=> 'raleway',
			'roboto-slab'				=> 'roboto slab',
			'merriweather'				=> 'merriweather',
			'droid-sans'				=> 'droid sans',
			'ubuntu'					=> 'ubuntu',
			'rubik'						=> 'rubik',
			'titillium-web'				=> 'titillium web',
			'bungee-hairline'			=> 'bungee hairline',
			'indie-flower'				=> 'indie flower',
			'eczar'						=> 'eczar',
			'inconsolata'				=> 'inconsolata',
			'oxygen'					=> 'oxygen',
			'bitter'					=> 'bitter',
			'dosis'						=> 'dosis',
			'fjalla-one'				=> 'fjalla one',
			'lobster'					=> 'lobster',
			'cabin'						=> 'cabin',
			'arvo'						=> 'arvo',
			'bree-serif'				=> 'bree serif',
			'poppins'					=> 'poppins',
			'anton'						=> 'anton',
			'abel'						=> 'abel',
			'josefin-sans'				=> 'josefin sans',
			'pacifico'					=> 'pacifico',
			'mountains-of-christmas'	=> 'mountains of christmas',
			'signika'					=> 'signika',
			'francois-one'				=> 'francois one',
			'shadows-into-light'		=> 'shadows into light',
			'quicksand'					=> 'quicksand',
			'amatic-SC'					=> 'amatic SC',
			'questrial'					=> 'questrial',
			'dancing-script'			=> 'dancing script',
			'exo'						=> 'exo',
			'maven-pro'					=> 'maven pro',
			'orbitron'					=> 'orbitron',
			'gloria-hallelujah' 		=> 'gloria hallelujah' ,
);



