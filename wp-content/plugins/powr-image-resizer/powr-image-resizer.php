<?php
    /**
     * @package POWr Image Resizer
     * @version 1.6.3
     */
    /*
    Plugin Name: POWr Image Resizer
    Plugin URI: https://www.powr.io/plugins/image-resizer
    Description: Adjust the size, shape, and filter for a single image.  Drop the widget anywhere in your theme. Or use the POWr icon in your WP text editor to add to a page or post. Edit on your live page by clicking the settings icon. More plugins and tutorials at POWr.io.
    Author: POWr.io
    Version: 1.6.3
    Author URI: https://www.powr.io
    */

    ///////////////////////////////////////GENERATE JS IN HEADER///////////////////////////////
    //For local mode (testing)
    if(!function_exists('powr_local_mode')){
        function powr_local_mode(){
          return false;
        }
    }
    //Generates an instance key
    if(!function_exists('generate_powr_instance')){
        function generate_powr_instance() {
          $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
          $pass = array(); //remember to declare $pass as an array
          $alphaLength = strlen($alphabet) - 1; // Put the length -1 in cache.
          for ($i = 0; $i < 10; $i++) { // Add 10 random characters.
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
          }
          $pass_string = implode($pass) . time(); // Add the current time to avoid duplicate keys.
          return $pass_string; // Turn the array into a string.
        }
    }
    //Adds script to the header if necessary
    if(!function_exists('initialize_powr_js')){
        function initialize_powr_js(){
          //No matter what we want the javascript in the header:
          add_option( 'powr_token', generate_powr_instance(), '', 'yes' );	//Add a global powr token: (This will do nothing if the option already exists)
          $powr_token = get_option('powr_token'); //Get the global powr_token
          if(powr_local_mode()){//Determine JS url:
            $js_url = '//localhost:3000/powr_local.js';
          }else{
            $js_url = '//www.powr.io/powr.js';
          }
          ?>
          <script>
            (function(d){
              var js, id = 'powr-js', ref = d.getElementsByTagName('script')[0];
              if (d.getElementById(id)) {return;}
              js = d.createElement('script'); js.id = id; js.async = true;
              js.src = '<?php echo $js_url; ?>';
              js.setAttribute('powr-token','<?php echo $powr_token; ?>');
              js.setAttribute('external-type','wordpress');
              ref.parentNode.insertBefore(js, ref);
            }(document));
          </script>
          <?php
        }
        //CALL INITIALIZE
        add_action( 'wp_enqueue_scripts', 'initialize_powr_js' );
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////Create Image Resizer widget/////////////////////////////////
    class Powr_Image_Resizer extends WP_Widget{
      public $plugin;
      //Create the widget
      public function __construct(){
        parent::__construct( 'powr_image_resizer',
                             __( 'POWr Image Resizer' ),
                             array( 'description' => __( 'Image Resizer by POWr.io') )
        );
        
      }
      //This prints the div
      public function widget( $args, $instance ){
        $label = $instance['label'];
        
        ?>
        <div class='widget powr-image-resizer' label='<?php echo $label; ?>'></div>
        <?php
      }
      public function update( $new_instance, $old_instance ){
        $instance = $old_instance;
        //If no label, then set a label
        if( empty($instance['label']) ){
          $instance['label'] = 'wordpress_'.time();
        }
        
        return $instance;
      }
      public function form( $instance ){
        
        ?>
        <p>
          To edit, visit your live webpage and click the gears icon on your Image Resizer.
        </p>
        <p>
          Learn more at <a href='https://www.powr.io/knowledge-base'>POWr.io</a>
        </p>
        <?php
      }
    }
    //Register Widget With Wordpress
    function register_powr_image_resizer() {
      register_widget( 'Powr_Image_Resizer' );
    }
    //Use widgets_init action hook to execute custom function
    add_action( 'widgets_init', 'register_powr_image_resizer' );
    //Create short codes for adding plugins anywhere:
    function powr_image_resizer_shortcode( $atts ){
      if(isset($atts['id'])){
        $id = $atts['id'];
      	return "<div class='powr-image-resizer' id='$id'></div>";
      }else if(isset($atts['label'])){
        $label = $atts['label'];
		    return "<div class='powr-image-resizer' label='$label'></div>";
      }else{
      	"<div class='powr-image-resizer'></div>";
      }
    }
    add_shortcode( 'powr-image-resizer', 'powr_image_resizer_shortcode' );

    /* Add POWr Plug to tiny MCE */
    if( !function_exists('powr_tinymce_button') ){
      add_action( 'admin_init', 'powr_tinymce_button' ); //This calls the function below

      function powr_tinymce_button() {
           if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
                add_filter( 'mce_buttons', 'powr_register_tinymce_button' );
                add_filter( 'mce_external_plugins', 'powr_add_tinymce_button' );
           }
      }
      function powr_register_tinymce_button( $buttons ) {
           array_push( $buttons, 'powr');
           return $buttons;
      }
      function powr_add_tinymce_button( $plugin_array ) {
           $plugin_array['powr'] = plugins_url( '/powr_tinymce.js', __FILE__ ) ;
           return $plugin_array;
      }
      //CSS For icon
      function powr_tinymce_css() {
          wp_enqueue_style('powr_tinymce', plugins_url('/powr_tinymce.css', __FILE__));
      }
      add_action('admin_enqueue_scripts', 'powr_tinymce_css');
    }

    //ADD MENUS
    add_action( 'admin_menu', 'powr_image_resizer_menu' );
    function powr_image_resizer_menu() {
      add_menu_page( 'POWr Image Resizer', 'POWr Image Resizer', 'manage_options', 'powr-image-resizer-settings', 'powr_image_resizer_options', plugins_url('/powr-icon.png',__FILE__));
    }
    function powr_image_resizer_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      echo '<iframe id="powr-image-resizer" src="https://www.powr.io/wp-create/image-resizer" frameborder="0" width="100%" height="600px" style="position:absolute; top:0; left:0;z-index: 100;"></iframe>';
      echo '<script>';
      echo 'var ht = window.innerHeight; if(document.getElementById("wpadminbar")){ht -= document.getElementById("wpadminbar").offsetHeight;}';
      echo 'var iframe = document.getElementById("powr-image-resizer"); iframe.style.height = ht+"px"; iframe.height = ht;';
      echo '</script>';
    }
    //Redirecting to landing page when plugin is activated
    register_activation_hook(__FILE__, 'powr_image_resizer_plugin_activate');
      add_action('admin_init', 'powr_image_resizer_plugin_redirect');

      function powr_image_resizer_plugin_activate() {
      add_option('powr_image_resizer_plugin_do_activation_redirect', true);
      }

      function powr_image_resizer_plugin_redirect() {
      if (get_option('powr_image_resizer_plugin_do_activation_redirect', false)) {
          delete_option('powr_image_resizer_plugin_do_activation_redirect');
          if(!isset($_GET['activate-multi']))
          {
            wp_redirect( get_admin_url().'?platform=wordpress&page=powr-image-resizer-settings' );
          }
       }
      }


  ?>