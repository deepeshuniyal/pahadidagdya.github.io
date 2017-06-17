<?php

class WPShortPixel {
    
    const BULK_EMPTY_QUEUE = 0;
    
    private $_affiliateSufix;
    
    private $_apiInterface = null;
    private $_settings = null;
    private $prioQ = null;
    private $view = null;
    
    private $hasNextGen = false;
    private $spMetaDao = null;
    
    public static $PROCESSABLE_EXTENSIONS = array('jpg', 'jpeg', 'gif', 'png', 'pdf');

    public function __construct() {
        if (!session_id()) {
            session_start();
        }

        load_plugin_textdomain('shortpixel-image-optimiser', false, plugin_basename(dirname( SHORTPIXEL_PLUGIN_FILE )).'/lang');
        
        $isAdminUser = current_user_can( 'manage_options' );
        
        $this->_affiliateSufix = (strlen(SP_AFFILIATE_CODE)) ? "/affiliate/" . SP_AFFILIATE_CODE : "";
        $this->_settings = new WPShortPixelSettings();
        $this->_apiInterface = new ShortPixelAPI($this->_settings);
        $this->hasNextGen = ShortPixelNextGenAdapter::hasNextGen();
        $this->spMetaDao = new ShortPixelCustomMetaDao(new WpShortPixelDb(), $this->_settings->excludePatterns);
        $this->prioQ = new ShortPixelQueue($this, $this->_settings);
        $this->view = new ShortPixelView($this);
        
        define('QUOTA_EXCEEDED', $this->view->getQuotaExceededHTML());        
            
        $this->setDefaultViewModeList();//set default mode as list. only @ first run

        //add hook for image upload processing
        //add_filter( 'wp_generate_attachment_metadata', array( &$this, 'handleMediaLibraryImageUpload' ), 10, 2 ); // now external
        add_filter( 'plugin_action_links_' . plugin_basename(SHORTPIXEL_PLUGIN_FILE), array(&$this, 'generatePluginLinks'));//for plugin settings page

        //add_action( 'admin_footer', array(&$this, 'handleImageProcessing'));
        
        //Media custom column
        add_filter( 'manage_media_columns', array( &$this, 'columns' ) );//add media library column header
        add_action( 'manage_media_custom_column', array( &$this, 'generateCustomColumn' ), 10, 2 );//generate the media library column
        //Edit media meta box
        add_action( 'add_meta_boxes', array( &$this, 'shortpixelInfoBox') );
        //for cleaning up the WebP images when an attachment is deleted
        add_action( 'delete_attachment', array( &$this, 'onDeleteImage') );
        
        //for NextGen
        if($this->_settings->hasCustomFolders) {
            add_filter( 'ngg_manage_images_columns', array( &$this, 'nggColumns' ) );
            add_filter( 'ngg_manage_images_number_of_columns', array( &$this, 'nggCountColumns' ) );
            add_filter( 'ngg_manage_images_column_7_header', array( &$this, 'nggColumnHeader' ) );
            add_filter( 'ngg_manage_images_column_7_content', array( &$this, 'nggColumnContent' ) );
            // hook on the NextGen gallery list update
            add_action('ngg_update_addgallery_page', array( &$this, 'addNextGenGalleriesToCustom'));
        }
        
        // integration with WP/LR Sync plugin
        add_action( 'wplr_update_media', array( &$this, 'onWpLrUpdateMedia' ), 10, 2);

        //custom hook
        add_action( 'shortpixel-optimize-now', array( &$this, 'optimizeNowHook' ), 10, 1);

        if($isAdminUser) {
            //add settings page
            add_action( 'admin_menu', array( &$this, 'registerSettingsPage' ) );//display SP in Settings menu
            add_action( 'admin_menu', array( &$this, 'registerAdminPage' ) );
            
            add_action('wp_ajax_shortpixel_browse_content', array(&$this, 'browseContent'));
            add_action('wp_ajax_shortpixel_get_backup_size', array(&$this, 'getBackupSize'));
        
            add_action( 'delete_attachment', array( &$this, 'handleDeleteAttachmentInBackup' ) );
            add_action( 'load-upload.php', array( &$this, 'handleCustomBulk'));

            //backup restore
            add_action('admin_action_shortpixel_restore_backup', array(&$this, 'handleRestoreBackup'));
            //reoptimize with a different algorithm (losless/lossy)
            add_action('wp_ajax_shortpixel_redo', array(&$this, 'handleRedo'));
            //optimize thumbnails
            add_action('wp_ajax_shortpixel_optimize_thumbs', array(&$this, 'handleOptimizeThumbs'));

            //toolbar notifications
            add_action( 'admin_bar_menu', array( &$this, 'toolbar_shortpixel_processing'), 999 );            
            //deactivate plugin
            add_action( 'admin_post_shortpixel_deactivate_plugin', array(&$this, 'deactivatePlugin'));
        }
        
        //automatic optimization
        add_action( 'wp_ajax_shortpixel_image_processing', array( &$this, 'handleImageProcessing') );
        //manual optimization
        add_action( 'wp_ajax_shortpixel_manual_optimization', array(&$this, 'handleManualOptimization'));
        //dismiss notices
        add_action( 'wp_ajax_shortpixel_dismiss_notice', array(&$this, 'dismissAdminNotice'));
        add_action( 'wp_ajax_shortpixel_dismiss_media_alert', array(&$this, 'dismissMediaAlert'));
        //check quota
        add_action('admin_action_shortpixel_check_quota', array(&$this, 'handleCheckQuota'));
        //This adds the constants used in PHP to be available also in JS
        add_action( 'admin_footer', array( &$this, 'shortPixelJS') );

        if($this->_settings->frontBootstrap) {
            //also need to have it in the front footer then
            add_action( 'wp_footer', array( &$this, 'shortPixelJS') );
            //need to add the nopriv action for when items exist in the queue and no user is logged in
            add_action( 'wp_ajax_nopriv_shortpixel_image_processing', array( &$this, 'handleImageProcessing') );
        }
        //register a method to display admin notices if necessary
        add_action('admin_notices', array( &$this, 'displayAdminNotices'));
        
        $this->migrateBackupFolder();

        if(!$this->_settings->redirectedSettings && !$this->_settings->verifiedKey && (!function_exists("is_multisite") || !is_multisite())) {
            $this->_settings->redirectedSettings = 1;
            wp_redirect(admin_url("options-general.php?page=wp-shortpixel"));
            exit();
        }
    }

    //handling older
    public function WPShortPixel() {
        $this->__construct();
    }

    public function registerSettingsPage() {
        add_options_page( __('ShortPixel Settings','shortpixel-image-optimiser'), 'ShortPixel', 'manage_options', 'wp-shortpixel', array($this, 'renderSettingsMenu'));
    }

    function registerAdminPage( ) {
        if($this->spMetaDao->hasFoldersTable() && count($this->spMetaDao->getFolders())) {
            /*translators: title and menu name for the Other media page*/
            add_media_page( __('Other Media Optimized by ShortPixel','shortpixel-image-optimiser'), __('Other Media','shortpixel-image-optimiser'), 'edit_others_posts', 'wp-short-pixel-custom', array( &$this, 'listCustomMedia' ) );
        }
        /*translators: title and menu name for the Bulk Processing page*/
        add_media_page( __('ShortPixel Bulk Process','shortpixel-image-optimiser'), __('Bulk ShortPixel','shortpixel-image-optimiser'), 'edit_others_posts', 'wp-short-pixel-bulk', array( &$this, 'bulkProcess' ) );
    }
    
    public static function shortPixelActivatePlugin()//reset some params to avoid trouble for plugins that were activated/deactivated/activated
    {
        self::shortPixelDeactivatePlugin();
        if(SP_RESET_ON_ACTIVATE === true && WP_DEBUG === true) { //force reset plugin counters, only on specific occasions and on test environments
            WPShortPixelSettings::debugResetOptions();
    
            $settings = new WPShortPixelSettings();
            $spMetaDao = new ShortPixelCustomMetaDao(new WpShortPixelDb(), $settings->excludePatterns);
            $spMetaDao->dropTables();
        }
        WPShortPixelSettings::onActivate();
    }
    
    public static function shortPixelDeactivatePlugin()//reset some params to avoid trouble for plugins that were activated/deactivated/activated
    {
        include_once dirname( SHORTPIXEL_PLUGIN_FILE ) . '/wp-shortpixel-req.php';
        ShortPixelQueue::resetBulk();
        ShortPixelQueue::resetPrio();
        WPShortPixelSettings::onDeactivate();
    }
    
    public static function getConflictingPlugins() {
        $conflictPlugins = array(
            'WP Smush - Image Optimization' => 'wp-smushit/wp-smush.php',
            'Imagify Image Optimizer' => 'imagify/imagify.php',
            'Compress JPEG & PNG images (TinyPNG)' => 'tiny-compress-images/tiny-compress-images.php',
            'Kraken.io Image Optimizer' => 'kraken-image-optimizer/kraken.php',
            'Optimus - WordPress Image Optimizer' => 'optimus/optimus.php',
            'EWWW Image Optimizer' => 'ewww-image-optimizer/ewww-image-optimizer.php',
            'ImageRecycle pdf & image compression' => 'imagerecycle-pdf-image-compression/wp-image-recycle.php',
            'CheetahO Image Optimizer' => 'cheetaho-image-optimizer/cheetaho.php',
            'Zara 4 Image Compression' => 'zara-4/zara-4.php',
            'Prizm Image' => 'prizm-image/wp-prizmimage.php',
            'CW Image Optimizer' => 'cw-image-optimizer/cw-image-optimizer.php'
        );
        $found = array();
        foreach($conflictPlugins as $name => $path) {
            if(is_plugin_active($path)) {
                $found[] = array('name' => $name, 'path' => $path);
            }
        }
        return $found;
    }
    
    public function displayAdminNotices() {
        $dismissed = $this->_settings->dismissedNotices ? $this->_settings->dismissedNotices : array();
        if(!$this->_settings->verifiedKey) {
            $now = time();
            $act = $this->_settings->activationDate ? $this->_settings->activationDate : $now;
            if($this->_settings->activationNotice && $this->_settings->redirectedSettings >= 2) {
                ShortPixelView::displayActivationNotice();
                $this->_settings->activationNotice = null;
            }
            if( ($now > $act + 7200)  && !isset($dismissed['2h'])) {
                ShortPixelView::displayActivationNotice('2h');
            } else if( ($now > $act + 72 * 3600) && !isset($dismissed['3d'])) {
                    ShortPixelView::displayActivationNotice('3d');
            }
        }
        if(!isset($dismissed['compat'])) {
            $conflictPlugins = self::getConflictingPlugins();
            if(count($conflictPlugins)) {
                ShortPixelView::displayActivationNotice('compat', $conflictPlugins);
            }
        }
    }
    
    public function dismissAdminNotice() {
        $noticeId = preg_replace('|[^a-z0-9]|i', '', $_GET['notice_id']);
        $dismissed = $this->_settings->dismissedNotices ? $this->_settings->dismissedNotices : array();
        $dismissed[$noticeId] = true;
        $this->_settings->dismissedNotices = $dismissed;
        die(json_encode(array("Status" => 'success', "Message" => 'Notice ID: ' . $noticeId . ' dismissed')));
    }        

    public function dismissMediaAlert() {
        $this->_settings->mediaAlert = 1;
        die(json_encode(array("Status" => 'success', "Message" => __('Media alert dismissed','shortpixel-image-optimiser'))));
    }        

    //set default move as "list". only set once, it won't try to set the default mode again.
    public function setDefaultViewModeList() 
    {
        if($this->_settings->mediaLibraryViewMode === false) 
        {
            $this->_settings->mediaLibraryViewMode = 1;
            $currentUserID = false;
            if ( function_exists('wp_get_current_user') ) {
                $current_user = wp_get_current_user();
                $currentUserID = $current_user->ID;
                update_user_meta($currentUserID, "wp_media_library_mode", "list");
            }
        }
        
    }

    static function log($message) {
        if (SHORTPIXEL_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }
   
    function shortPixelJS() { ?> 
        <script type="text/javascript" >
            var ShortPixelConstants = {
                STATUS_SUCCESS: <?php echo ShortPixelAPI::STATUS_SUCCESS; ?>,
                STATUS_EMPTY_QUEUE: <?php echo self::BULK_EMPTY_QUEUE; ?>,
                STATUS_ERROR: <?php echo ShortPixelAPI::STATUS_ERROR; ?>,
                STATUS_FAIL: <?php echo ShortPixelAPI::STATUS_FAIL; ?>,
                STATUS_QUOTA_EXCEEDED: <?php echo ShortPixelAPI::STATUS_QUOTA_EXCEEDED; ?>,
                STATUS_SKIP: <?php echo ShortPixelAPI::STATUS_SKIP; ?>,
                STATUS_NO_KEY: <?php echo ShortPixelAPI::STATUS_NO_KEY; ?>,
                STATUS_RETRY: <?php echo ShortPixelAPI::STATUS_RETRY; ?>,
                STATUS_QUEUE_FULL: <?php echo ShortPixelAPI::STATUS_QUEUE_FULL; ?>,
                STATUS_MAINTENANCE: <?php echo ShortPixelAPI::STATUS_MAINTENANCE; ?>,
                WP_PLUGIN_URL: '<?php echo plugins_url( '', SHORTPIXEL_PLUGIN_FILE ); ?>',
                WP_ADMIN_URL: '<?php echo admin_url(); ?>',
                API_KEY: "<?php echo $this->_settings->apiKey; ?>",
                DEFAULT_COMPRESSION: <?php echo $this->_settings->compressionType; ?>,
                MEDIA_ALERT: '<?php echo $this->_settings->mediaAlert ? "done" : "todo"; ?>',
                FRONT_BOOTSTRAP: <?php echo $this->_settings->frontBootstrap && (!isset($this->_settings->lastBackAction) || (time() - $this->_settings->lastBackAction > 600)) ? 1 : 0; ?>,
                AJAX_URL: '<?php echo admin_url('admin-ajax.php'); ?>'
            };
        </script> <?php
        wp_enqueue_style('short-pixel.css', plugins_url('/res/css/short-pixel.css',SHORTPIXEL_PLUGIN_FILE) );
        
        wp_register_script('short-pixel.js', plugins_url('/res/js/short-pixel.js',SHORTPIXEL_PLUGIN_FILE) );
        $jsTranslation = array(
                'optimizeWithSP' => __( 'Optimize with ShortPixel', 'shortpixel-image-optimiser' ),
                'changeMLToListMode' => __( 'In order to access the ShortPixel Optimization actions and info, please change to {0}List View{1}List View{2}Dismiss{3}', 'shortpixel-image-optimiser' ),
                'alertOnlyAppliesToNewImages' => __( 'This type of optimization will apply to new uploaded images. Images that were already processed will not be re-optimized unless you restart the bulk process.', 'shortpixel-image-optimiser' ),
                'areYouSureStopOptimizing' => __( 'Are you sure you want to stop optimizing the folder {0}?', 'shortpixel-image-optimiser' ),
                'reducedBy' => __( 'Reduced by', 'shortpixel-image-optimiser' ),
                'bonusProcessing' => __( 'Bonus processing', 'shortpixel-image-optimiser' ),
                'plusXthumbsOpt' => __( '+{0} thumbnails optimized', 'shortpixel-image-optimiser' ),
                'plusXretinasOpt' => __( '+{0} Retina images optimized', 'shortpixel-image-optimiser' ),
                'optXThumbs' => __( 'Optimize {0} thumbnails', 'shortpixel-image-optimiser' ),
                'reOptimizeAs' => __( 'Reoptimize {0}', 'shortpixel-image-optimiser' ),
                'restoreBackup' => __( 'Restore backup', 'shortpixel-image-optimiser' ),
                'getApiKey' => __( 'Get API Key', 'shortpixel-image-optimiser' ),
                'extendQuota' => __( 'Extend Quota', 'shortpixel-image-optimiser' ),
                'check__Quota' => __( 'Check&nbsp;&nbsp;Quota', 'shortpixel-image-optimiser' ),
                'retry' => __( 'Retry', 'shortpixel-image-optimiser' ),
                'thisContentNotProcessable' => __( 'This content is not processable.', 'shortpixel-image-optimiser' ),
                'imageWaitOptThumbs' => __( 'Image waiting to optimize thumbnails', 'shortpixel-image-optimiser' ),
                'pleaseDoNotSetLesserSize' => __( "Please do not set a {0} less than the {1} of the largest thumbnail which is {2}, to be able to still regenerate all your thumbnails in case you'll ever need this.", 'shortpixel-image-optimiser' ),
                'pleaseDoNotSetLesser1024' => __( "Please do not set a {0} less than 1024, to be able to still regenerate all your thumbnails in case you'll ever need this.", 'shortpixel-image-optimiser' )
            );
        wp_localize_script( 'short-pixel.js', '_spTr', $jsTranslation );
        wp_enqueue_script('short-pixel.js');
        
        wp_enqueue_script('jquery.knob.js', plugins_url('/res/js/jquery.knob.js',SHORTPIXEL_PLUGIN_FILE) );
        wp_enqueue_script('jquery.tooltip.js', plugins_url('/res/js/jquery.tooltip.js',SHORTPIXEL_PLUGIN_FILE) );
        wp_enqueue_script('punycode.js', plugins_url('/res/js/punycode.js',SHORTPIXEL_PLUGIN_FILE) );
    }

    function toolbar_shortpixel_processing( $wp_admin_bar ) {
        
        $extraClasses = " shortpixel-hide";
        /*translators: toolbar icon tooltip*/
        $id = 'short-pixel-notice-toolbar';
        $tooltip = __('ShortPixel optimizing...','shortpixel-image-optimiser');
        $icon = "shortpixel.png";
        $successLink = $link = current_user_can( 'edit_others_posts')? 'upload.php?page=wp-short-pixel-bulk' : 'upload.php';
        $blank = "";
        if($this->prioQ->processing()) {
            $extraClasses = " shortpixel-processing";
        }
        if($this->_settings->quotaExceeded && !isset($this->_settings->dismissedNotices['exceed'])) {
            $extraClasses = " shortpixel-alert shortpixel-quota-exceeded";
            /*translators: toolbar icon tooltip*/
            $id = 'short-pixel-notice-exceed';
            $tooltip = '';
            $exceedTooltip = __('ShortPixel quota exceeded. Click for details.','shortpixel-image-optimiser');
            //$link = "http://shortpixel.com/login/" . $this->_settings->apiKey;
            $link = "options-general.php?page=wp-shortpixel";
            //$blank = '_blank';
            //$icon = "shortpixel-alert.png";
        }
        $lastStatus = $this->_settings->bulkLastStatus;
        if($lastStatus && $lastStatus['Status'] != ShortPixelAPI::STATUS_SUCCESS) {
            $extraClasses = " shortpixel-alert shortpixel-processing";
            $tooltip = $lastStatus['Message'];
        }

        $args = array(
                'id'    => 'shortpixel_processing',
                'title' => '<div id="' . $id . '" title="' . $tooltip . '" ><img src="' 
                         . plugins_url( 'res/img/'.$icon, SHORTPIXEL_PLUGIN_FILE ) . '" success-url="' . $successLink . '"><span class="shp-alert">!</span>'
                         .'<div class="cssload-container"><div class="cssload-speeding-wheel"></div></div></div>',
                'href'  => $link,
                'meta'  => array('target'=> $blank, 'class' => 'shortpixel-toolbar-processing' . $extraClasses)
        );
        $wp_admin_bar->add_node( $args );
        if($this->_settings->quotaExceeded && !isset($this->_settings->dismissedNotices['exceed'])) {
            $wp_admin_bar->add_node( array(
                'id'    => 'shortpixel_processing-title',
                'parent' => 'shortpixel_processing',
                'title' => $exceedTooltip,
                'href'  => $link
            ));
            $wp_admin_bar->add_node( array(
                'id'    => 'shortpixel_processing-dismiss',
                'parent' => 'shortpixel_processing',
                'title' => '<div style="text-align: right;">Dismiss</div>',
                'href'  => "#",
                'meta'  => array('onclick'=> 'dismissShortPixelNoticeExceed(event)')
            ));
        }
    }

    public function handleCustomBulk() {
        // 1. get the action
        $wp_list_table = _get_list_table('WP_Media_List_Table');
        $action = $wp_list_table->current_action();

        switch($action) {
            // 2. Perform the action
            case 'short-pixel-bulk':
                // security check
                check_admin_referer('bulk-media');
                if(!is_array($_GET['media'])) {
                    break;
                }
                $mediaIds = array_reverse($_GET['media']);
                foreach( $mediaIds as $ID ) {
                    $meta = wp_get_attachment_metadata($ID);
                    if(   (   !isset($meta['ShortPixel']) //never touched by ShortPixel
                           || (isset($meta['ShortPixel']['WaitingProcessing']) && $meta['ShortPixel']['WaitingProcessing'] == true)) 
                       && (!isset($meta['ShortPixelImprovement']) || $meta['ShortPixelImprovement'] == __('Optimization N/A','shortpixel-image-optimiser'))) {
                        $this->prioQ->push($ID);
                        $meta['ShortPixel']['WaitingProcessing'] = true;
                        wp_update_attachment_metadata($ID, $meta);
                    }
                }
                break;
        }
    }

    /**
     * this is hooked onto the MediaLibrary image upload
     * @param array $meta - the wordpress postmeta structure
     * @param type $ID - the Media Library ID
     * @return the meta structure updated with ShortPixel info if case
     */
    public function handleMediaLibraryImageUpload($meta, $ID = null)
    {
            if( !$this->_settings->verifiedKey) {// no API Key set/verified -> do nothing here, just return
                return $meta;
            }
            //else
            //self::log("IMG: Auto-analyzing file ID #{$ID}");

            if(!$this->_settings->optimizePdfs && 'pdf' === pathinfo(get_attached_file($ID), PATHINFO_EXTENSION)) {
                //pdf is not optimized automatically as per the option, but can be optimized by button. Nothing to do.
                return $meta;
            }
            elseif( self::_isProcessable($ID, array(), $this->_settings->excludePatterns, $meta) == false )
            {//not a file that we can process
                $meta['ShortPixelImprovement'] = __('Optimization N/A','shortpixel-image-optimiser');
                return $meta;
            }
            else 
            {//the kind of file we can process. goody.

                $this->prioQ->push($ID);
                //that's a hack for watermarking plugins, don't send the image right away to processing, only add it in the queue
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if(   !is_plugin_active('image-watermark/image-watermark.php') 
                   && !is_plugin_active('easy-watermark/index.php')) {
                    $itemHandler = new ShortPixelMetaFacade($ID);
                    $itemHandler->setRawMeta($meta);
                    try {
                        $URLsAndPATHs = $this->getURLsAndPATHs($itemHandler);
                        //send a processing request right after a file was uploaded, do NOT wait for response   
                        $this->_apiInterface->doRequests($URLsAndPATHs['URLs'], false, $ID);
                    } catch(Exception $e) {
                        $meta['ShortPixelImprovement'] = $e->getMessage();
                        return $meta;
                    }
                    //self::log("IMG: sent: " . json_encode($URLsAndPATHs));
                }
                $meta['ShortPixel']['WaitingProcessing'] = true;
                return $meta;
            } 
    }//end handleMediaLibraryImageUpload

    /**
     * this is hooked onto the NextGen upload
     * @param type $image
     */
    public function handleNextGenImageUpload($image) {
        if($this->_settings->includeNextGen == 1) {
            $imageFsPath = ShortPixelNextGenAdapter::getImageAbspath($image);

            $customFolders = $this->spMetaDao->getFolders();

            $folderId = -1;
            foreach($customFolders as $folder) {
                if(strpos($imageFsPath, $folder->getPath()) === 0) {
                    $folderId = $folder->getId();
                    break;
                }
            }
            if($folderId == -1) { //if not found, create
                $galleryPath = dirname($imageFsPath);
                $folder = new ShortPixelFolder(array("path" => $galleryPath), $this->_settings->excludePatterns);
                $folderMsg = $this->spMetaDao->saveFolder($folder);
                $folderId = $folder->getId();
                //self::log("NG Image Upload: created folder from path $galleryPath : Folder info: " .  json_encode($folder));
            }
            $pathParts = explode('/', trim($imageFsPath));
            //Add the main image
            $meta = new ShortPixelMeta();
            $meta->setPath($imageFsPath);
            $meta->setName($pathParts[count($pathParts) - 1]);
            $meta->setFolderId($folderId);
            $meta->setExtMetaId($image->pid); // do this only for main image, not for thumbnais.
            $meta->setCompressionType($this->_settings->compressionType);
            $meta->setKeepExif($this->_settings->keepExif);
            $meta->setCmyk2rgb($this->_settings->CMYKtoRGBconversion);
            $meta->setResize($this->_settings->resizeImages);
            $meta->setResizeWidth($this->_settings->resizeWidth);
            $meta->setResizeHeight($this->_settings->resizeHeight);
            $ID = $this->spMetaDao->addImage($meta);
            $meta->setId($ID);
            $this->prioQ->push('C-' . $ID);
            //add the thumb image if exists
            $pathParts[] = "thumbs_" . $pathParts[count($pathParts) - 1];
            $pathParts[count($pathParts) - 2] = "thumbs";
            $thumbPath = implode('/', $pathParts);
            if(file_exists($thumbPath)) {
                $metaThumb = new ShortPixelMeta();
                $metaThumb->setPath($thumbPath);
                $metaThumb->setName($pathParts[count($pathParts) - 1]);
                $metaThumb->setFolderId($folderId);
                $metaThumb->setCompressionType($this->_settings->compressionType);
                $metaThumb->setKeepExif($this->_settings->keepExif);
                $metaThumb->setCmyk2rgb($this->_settings->CMYKtoRGBconversion);
                $metaThumb->setResize($this->_settings->resizeImages);
                $metaThumb->setResizeWidth($this->_settings->resizeWidth);
                $metaThumb->setResizeHeight($this->_settings->resizeHeight);
                $ID = $this->spMetaDao->addImage($metaThumb);
                $metaThumb->setId($ID);
                $this->prioQ->push('C-' . $ID);               
            }
            return $meta;
        }
    }
    
    public function optimizeCustomImage($id) {
        $meta = $this->spMetaDao->getMeta($id);
        if($meta->getStatus() != 2) {
            $meta->setStatus(1);
            $meta->setRetries(0);
            $this->spMetaDao->update($meta);
            $this->prioQ->push('C-' . $id);
        }
    }

    public function bulkRestore(){
        global $wpdb, $MAX_EXECUTION_TIME;
        
        $startQueryID = $crtStartQueryID = $this->prioQ->getStartBulkId();
        $endQueryID = $this->prioQ->getStopBulkId(); 

        if ( $startQueryID <= $endQueryID ) {
            return false;
        }

        $startTime = time(); 
        $maxTime = (is_numeric($MAX_EXECUTION_TIME)  && $MAX_EXECUTION_TIME > 10 ? $MAX_EXECUTION_TIME - 5 : 25);
        $maxResults = SP_MAX_RESULTS_QUERY * 2;
        $restored = array();
        
        //$ind = 0;
        while( $crtStartQueryID >= $endQueryID && time() - $startTime < $maxTime) {
            //if($ind > 1) break;
            //$ind++;
            $resultsPostMeta = WpShortPixelMediaLbraryAdapter::getPostMetaSlice($crtStartQueryID, $endQueryID, $maxResults);
            if ( empty($resultsPostMeta) ) {
                $crtStartQueryID -= $maxResults;
                $startQueryID = $crtStartQueryID;
                $this->prioQ->setStartBulkId($startQueryID);
                continue;
            }

            foreach ( $resultsPostMeta as $itemMetaData ) {
                $crtStartQueryID = $itemMetaData->post_id;
                $item = new ShortPixelMetaFacade($crtStartQueryID);
                $meta = $item->getMeta();//wp_get_attachment_metadata($crtStartQueryID);

                if($meta->getStatus() == 2) {
                    $res = $this->doRestore($crtStartQueryID);
                    $restored[] = array('id' => $crtStartQueryID, 'status' => $res ? 'success' : 'fail');
                }
            }            
        }
        $this->advanceBulk($crtStartQueryID);
        return $restored;
    }
    
    //TODO muta in bulkProvider
    public function getBulkItemsFromDb(){
        global $wpdb;
        
        $startQueryID = $this->prioQ->getStartBulkId();
        $endQueryID = $this->prioQ->getStopBulkId(); 
        $skippedAlreadyProcessed = 0;
        
        if ( $startQueryID <= $endQueryID ) {
            return false;
        }
        $idList = array();
        $itemList = array();
        for ($sanityCheck = 0, $crtStartQueryID = $startQueryID;  
             ($crtStartQueryID >= $endQueryID) && (count($itemList) < 3) && ($sanityCheck < 150); $sanityCheck++) {
 
            self::log("GETDB: current StartID: " . $crtStartQueryID);

            /* $queryPostMeta = "SELECT * FROM " . $wpdb->prefix . "postmeta 
                WHERE ( post_id <= $crtStartQueryID AND post_id >= $endQueryID ) 
                  AND ( meta_key = '_wp_attached_file' OR meta_key = '_wp_attachment_metadata' )
                ORDER BY post_id DESC
                LIMIT " . SP_MAX_RESULTS_QUERY;
            $resultsPostMeta = $wpdb->get_results($queryPostMeta);
            */
            $resultsPostMeta = WpShortPixelMediaLbraryAdapter::getPostMetaSlice($crtStartQueryID, $endQueryID, SP_MAX_RESULTS_QUERY);

            if ( empty($resultsPostMeta) ) {
                $crtStartQueryID -= SP_MAX_RESULTS_QUERY;
                $startQueryID = $crtStartQueryID;
                $this->prioQ->setStartBulkId($startQueryID);
                continue;
            }

            foreach ( $resultsPostMeta as $itemMetaData ) {
                $crtStartQueryID = $itemMetaData->post_id;
                if(!in_array($crtStartQueryID, $idList) && $this->isProcessable($crtStartQueryID, ($this->_settings->optimizePdfs ? array() : array('pdf')))) {
                    $item = new ShortPixelMetaFacade($crtStartQueryID);
                    $meta = $item->getMeta();//wp_get_attachment_metadata($crtStartQueryID);
                    
                    if($meta->getStatus() != 2) {
                        $itemList[] = $item;
                        $idList[] = $crtStartQueryID;
                    } 
                    elseif($meta->getCompressionType() !== null && $meta->getCompressionType() != $this->_settings->compressionType) {//a different type of compression was chosen in settings
                        if($this->doRestore($crtStartQueryID)) {
                            $itemList[] = $item = new ShortPixelMetaFacade($crtStartQueryID); //force reload after restore
                            $idList[] = $crtStartQueryID;
                        } else {
                            $skippedAlreadyProcessed++;
                        }
                    } 
                    elseif(   $this->_settings->processThumbnails && $meta->getThumbsOpt() !== null
                           && $meta->getThumbsOpt() == 0 && count($meta->getThumbs()) > 0) { //thumbs were chosen in settings
//if($crtStartQueryID == 44 || $crtStartQueryID == 49) {echo("No THuMBS?");die(var_dump($meta));}
                        $meta->setThumbsTodo(true);
                        $item->updateMeta($meta);//wp_update_attachment_metadata($crtStartQueryID, $meta);
                        $itemList[] = $item;
                        $idList[] = $crtStartQueryID;
                    } 
                    elseif($itemMetaData->meta_key == '_wp_attachment_metadata') { //count skipped
                        $skippedAlreadyProcessed++;
                    }
                }
            }
            if(!count($idList) && $crtStartQueryID <= $startQueryID) {
                //daca n-am adaugat niciuna pana acum, n-are sens sa mai selectez zona asta de id-uri in bulk-ul asta.
                $leapStart = $this->prioQ->getStartBulkId();
                $crtStartQueryID = $startQueryID = $itemMetaData->post_id - 1; //decrement it so we don't select it again
                $res = WpShortPixelMediaLbraryAdapter::countAllProcessableFiles($this->_settings->optimizePdfs, $leapStart, $crtStartQueryID);
                $skippedAlreadyProcessed += $res["mainProcessedFiles"] - $res["mainProc".($this->getCompressionType() == 1 ? "Lossy" : "Lossless")."Files"]; 
                $this->prioQ->setStartBulkId($startQueryID);
            } else {
                $crtStartQueryID--;
            }
        }
        return array("items" => $itemList, "skipped" => $skippedAlreadyProcessed, "searching" => ($sanityCheck >= 150));
    }

    /**
     * Get last added items from priority
     * @return type
     */
    //TODO muta in bulkProvider - prio
    public function getFromPrioAndCheck() {
        $items = array();
        foreach ($this->prioQ->getFromPrioAndCheck() as $id) {
            $items[] = new ShortPixelMetaFacade($id);
        }
        return $items;
    }
    
    private function checkKey($ID) {
        if( $this->_settings->verifiedKey == false) {
            if($ID == null){
                $ids = $this->getFromPrioAndCheck();
                $itemHandler = (count($ids) > 0 ? $ids[0] : null);
            }
            $response = array("Status" => ShortPixelAPI::STATUS_NO_KEY, "ImageID" => $itemHandler ? $itemHandler->getId() : "-1", "Message" => __('Missing API Key','shortpixel-image-optimiser'));
            $this->_settings->bulkLastStatus = $response;
            die(json_encode($response));
        }        
    }
    
    private function sendEmptyQueue() {
        $avg = $this->getAverageCompression();
        $fileCount = $this->_settings->fileCount;
        $response = array("Status" => self::BULK_EMPTY_QUEUE, 
            /* translators: console message Empty queue 1234 -> 1234 */
            "Message" => __('Empty queue ','shortpixel-image-optimiser') . $this->prioQ->getStartBulkId() . '->' . $this->prioQ->getStopBulkId(),
            "BulkStatus" => ($this->prioQ->bulkRunning() 
                    ? "1" : ($this->prioQ->bulkPaused() ? "2" : "0")),
            "AverageCompression" => $avg,
            "FileCount" => $fileCount,
            "BulkPercent" => $this->prioQ->getBulkPercent());
        die(json_encode($response));        
    }

    public function handleImageProcessing($ID = null) {
        //if(rand(1,2) == 2) {
        //    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        //    die("za stop");
        //}
        //0: check key
        $this->checkKey($ID);
        
        if($this->_settings->frontBootstrap && is_admin() && !ShortPixelTools::requestIsFrontendAjax()) {
            //if in backend, and front-end is activated, mark processing from backend to shut off the front-end for 10 min.
            $this->_settings->lastBackAction = time();
        }
        
        self::log("HIP: 0 Priority Queue: ".json_encode($this->prioQ->get()));
        //self::log("HIP: 0 Bulk running? " . $this->prioQ->bulkRunning() . " START " . $this->_settings->startBulkId . " STOP " . $this->_settings->stopBulkId);
        
        //handle the bulk restore and cleanup first - these are fast operations taking precedece over optimization
        if($this->prioQ->bulkRunning() && $this->prioQ->getBulkType() == ShortPixelQueue::BULK_TYPE_RESTORE) {
            $res = $this->bulkRestore();
            if($res === false) {
                $this->sendEmptyQueue();
            } else {
                die(json_encode(array("Status" => ShortPixelAPI::STATUS_RETRY, 
                                     "Message" => __('Restoring images...  ','shortpixel-image-optimiser') . $this->prioQ->getStartBulkId() . '->' . $this->prioQ->getStopBulkId(),
                                     "BulkPercent" => $this->prioQ->getBulkPercent(),
                                     "Restored" => $res )));
            }
            
        }
        
        //1: get 3 ids to process. Take them with priority from the queue
        $ids = $this->getFromPrioAndCheck();
        if(count($ids) < 3 ) { //take from bulk if bulk processing active
            if($this->prioQ->bulkRunning()) {
                $res = $this->getBulkItemsFromDb();
                $bulkItems = $res['items'];
                //merge them into the $ids array based on the ID (the same ID could be in prio also)
                if($bulkItems){
                    foreach($bulkItems as $bi) {
                        $add = true;
                        foreach($ids as $pi) {
                            if($pi->getType() == ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE && $bi->getId() == $pi->getId()) {
                                $add = false;
                            }
                        }
                        $ids[] = $bi;
                    }
                }
            }
        }

        self::log("HIP: 0 Bulk ran: " . $this->prioQ->bulkRan());
        $customIds = false;
        if(count($ids) < 3 && $this->prioQ->bulkRan() && $this->_settings->hasCustomFolders
           && (!$this->_settings->cancelPointer || $this->_settings->skipToCustom)
           && !$this->_settings->customBulkPaused) 
        { //take from custom images if any left to optimize - only if bulk was ever started
            $customIds = $this->spMetaDao->getPendingMetas( 3 - count($ids));
            if(is_array($customIds)) {
                $ids = array_merge($ids, array_map(array('ShortPixelMetaFacade', 'getNewFromRow'), $customIds));
            }
        }
        //var_dump($ids);
        //die("za stop 2");
        
        self::log("HIP: 1 Prio Queue: ".json_encode($this->prioQ->get()));
        self::log("HIP: 1 Selected IDs count: ".count($ids));

        //2: Send up to 3 files to the server for processing
        for($i = 0, $itemHandler = false; $ids !== false && $i < min(3, count($ids)); $i++) {
            $crtItemHandler = $ids[$i];
            $tmpMeta = $crtItemHandler->getMeta();
            $compType = ($tmpMeta->getCompressionType() !== null ? $tmpMeta->getCompressionType() : $this->_settings->compressionType);
            try {                    
                self::log("HIP: 1 sendToProcessing: ".$crtItemHandler->getId());
                $URLsAndPATHs = $this->sendToProcessing($crtItemHandler, $compType, $tmpMeta->getThumbsTodo());
                if(!$itemHandler) { //save for later use
                    $itemHandler = $ids[$i];
                    $firstUrlAndPaths = $URLsAndPATHs;
                }
            } catch(Exception $e) { // Exception("Post metadata is corrupt (No attachment URL)") or Exception("Image files are missing.")
                $crtItemHandler->incrementRetries(1, ShortPixelAPI::ERR_FILE_NOT_FOUND, $e->getMessage());
                if(! $this->prioQ->remove($crtItemHandler->getQueuedId()) ){
                    $this->advanceBulk($crtItemHandler->getId());
                    $res['searching'] = true;
                }
            }
        }
        
        if (!$itemHandler){
            //if searching, than the script is searching for not processed items and found none yet, should be relaunced
            if(isset($res['searching']) && $res['searching']) {
                    die(json_encode(array("Status" => ShortPixelAPI::STATUS_RETRY, 
                                          "Message" => __('Searching images to optimize...  ','shortpixel-image-optimiser') . $this->prioQ->getStartBulkId() . '->' . $this->prioQ->getStopBulkId() )));
            }
            //in this case the queue is really empty
            self::log("HIP: 1 STOP BULK");
            $bulkEverRan = $this->prioQ->stopBulk();
            $this->sendEmptyQueue();
        }

        self::log("HIP: 2 Prio Queue: ".json_encode($this->prioQ->get()));
        //3: $itemHandler contains the first element of the list
        $itemId = $itemHandler->getQueuedId();
        $result = $this->_apiInterface->processImage($firstUrlAndPaths['URLs'], $firstUrlAndPaths['PATHs'], $itemHandler);

        $result["ImageID"] = $itemId;
        $meta = $itemHandler->getMeta();
        $result["Filename"] = ShortPixelAPI::MB_basename($meta->getPath());

        self::log("HIP: 3 Prio Queue: ".json_encode($this->prioQ->get()));

        //4: update counters and priority list
        if( $result["Status"] == ShortPixelAPI::STATUS_SUCCESS) {
            self::log("HIP: Image ID " . $itemId . " optimized successfully: ".json_encode($result));
            $prio = $this->prioQ->remove($itemId);
            //remove also from the failed list if it failed in the past
            $prio = $this->prioQ->removeFromFailed($itemId);
            $result["Type"] = $meta->getCompressionType() !== null ? ShortPixelAPI::getCompressionTypeName($meta->getCompressionType()) : '';
            $result["ThumbsTotal"] = $meta->getThumbs() && is_array($meta->getThumbs()) ? WpShortPixelMediaLbraryAdapter::countNonWebpSizes($meta->getThumbs()): 0;
            $result["ThumbsTotal"] -= count($meta->getThumbsMissing());
            $result["ThumbsCount"] = $meta->getThumbsOpt()
                ? $meta->getThumbsOpt() //below is the fallback for old optimized images that don't have thumbsOpt
                : ($this->_settings->processThumbnails ? $result["ThumbsTotal"] : 0);
            
            $result["RetinasCount"] = $meta->getRetinasOpt();
            $result["BackupEnabled"] = ($this->getBackupFolderAny($meta->getPath(), $meta->getThumbs()) ? true : false);//$this->_settings->backupImages;
            
            if(!$prio && $itemId <= $this->prioQ->getStartBulkId()) {
                $this->advanceBulk($itemId);
                $this->setBulkInfo($itemId, $result);
            }

            $result["AverageCompression"] = $this->getAverageCompression();
            
            if($itemHandler->getType() == ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE) {                
                
                $thumb = $bkThumb = "";
                //$percent = 0;
                $percent = $meta->getImprovementPercent();
                if($percent){
                    $filePath = explode("/", $meta->getPath());
                    
                    //Get a suitable thumb
                    $sizes = $meta->getThumbs();
                    if('pdf' == strtolower(pathinfo($result["Filename"], PATHINFO_EXTENSION))) {
//                        echo($result["Filename"] . " ESTE --> "); die(var_dump(strtolower(pathinfo($result["Filename"], PATHINFO_EXTENSION))));
                        $thumb = plugins_url( 'shortpixel-image-optimiser/res/img/logo-pdf.png' );
                        $bkThumb = '';
                    } else {
                        if(count($sizes)) {
                            $thumb = (isset($sizes["medium"]) ? $sizes["medium"]["file"] : (isset($sizes["thumbnail"]) ? $sizes["thumbnail"]["file"]: ""));
                            if(!strlen($thumb)) { //fallback to the first in the list
                                $sizeVals = array_values($sizes);
                                $thumb = count($sizeVals) ? $sizeVals[0]['file'] : '';
                            }
                        } else { //fallback to the image itself
                            $thumb = is_array($filePath) ? $filePath[count($filePath) - 1] : $filePath;
                        }

                        if(strlen($thumb) && $this->_settings->backupImages && $this->_settings->processThumbnails) {
                            $backupUrl = content_url() . "/" . SP_UPLOADS_NAME . "/" . SP_BACKUP . "/";
                            //$urlBkPath = $this->_apiInterface->returnSubDir(get_attached_file($ID));
                            $urlBkPath = ShortPixelMetaFacade::returnSubDir($meta->getPath(), ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE);
                            $bkThumb = $backupUrl . $urlBkPath . $thumb;
                        }
                        if(strlen($thumb)) {
                            $uploadsUrl = ShortPixelMetaFacade::getHomeUrl();
                            $urlPath = ShortPixelMetaFacade::returnSubDir($meta->getPath(), ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE);
                            //$urlPath = implode("/", array_slice($filePath, 0, count($filePath) - 1));
                            $thumb = $uploadsUrl . $urlPath . $thumb;
                        }
                    }

                    $result["Thumb"] = $thumb;
                    $result["BkThumb"] = $bkThumb;
                }
            }
            elseif( is_array($customIds)) { // this item is from custom bulk
                foreach($customIds as $customId) {
                    $rootUrl = ShortPixelMetaFacade::getHomeUrl();
                    if($customId->id == $itemHandler->getId()) {
                        if('pdf' == strtolower(pathinfo($meta->getName(), PATHINFO_EXTENSION))) {
                            $result["Thumb"] = plugins_url( 'shortpixel-image-optimiser/res/img/logo-pdf.png' );
                            $result["BkThumb"] = "";
                        } else {
                            $result["Thumb"] = $thumb = $rootUrl . $meta->getWebPath();
                            if($this->_settings->backupImages) {
                                $result["BkThumb"] = str_replace($rootUrl, $rootUrl. "/" . basename(dirname(dirname(SP_BACKUP_FOLDER))) . "/" . SP_UPLOADS_NAME . "/" . SP_BACKUP . "/", $thumb);
                            }
                        }
                        $this->setBulkInfo($itemId, $result);
                        break;
                    }
                }
            }
        }
        elseif ($result["Status"] == ShortPixelAPI::STATUS_ERROR) {
            if($meta->getRetries() > MAX_ERR_RETRIES) {
                if(! $this->prioQ->remove($itemId) ){
                    $this->advanceBulk($meta->getId());
                }
                if($itemHandler->getType() == ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE) {
                    $itemHandler->deleteMeta(); //this deletes only the ShortPixel fields from meta, in case of WP Media library
                }
                $result["Status"] = ShortPixelAPI::STATUS_SKIP;
                $result["Message"] .= __(' Retry limit reached. Skipping file ID ','shortpixel-image-optimiser') . $itemId . ".";
                $itemHandler->setError(isset($result['Code']) ? $result['Code'] : ShortPixelAPI::ERR_INCORRECT_FILE_SIZE, $result["Message"] );
            }
            else {
                if(isset($result['Code'])) {
                    $itemHandler->incrementRetries(1, $result['Code'], $result["Message"]);
                } else {
                    $itemHandler->incrementRetries(1, ShortPixelAPI::ERR_UNKNOWN, "Connection error (" . $result["Message"] . ")" );
                }
            }
        }
        elseif ($result["Status"] == ShortPixelAPI::STATUS_SKIP
             || $result["Status"] == ShortPixelAPI::STATUS_FAIL) {
            $meta = $itemHandler->getMeta();
            //$prio = $this->prioQ->remove($ID);
            $prio = $this->prioQ->remove($itemId);
            if(isset($result["Code"])
               && (   $result["Code"] == "write-fail" //could not write
                   || (in_array(0+$result["Code"], array(-201)) && $meta->getRetries() >= 3))) { //for -201 (invalid image format) we retry only 3 times.
                //put this one in the failed images list - to show the user at the end
                $prio = $this->prioQ->addToFailed($itemHandler->getQueuedId());
            }
            $this->advanceBulk($meta->getId());
            if($itemHandler->getType() == ShortPixelMetaFacade::CUSTOM_TYPE) {
                $result["CustomImageLink"] = ShortPixelMetaFacade::getHomeUrl() . $meta->getWebPath();
            }
        }
        elseif($result["Status"] == ShortPixelAPI::STATUS_QUEUE_FULL) {
            //nimic?
        }
        elseif($result["Status"] == ShortPixelAPI::STATUS_MAINTENANCE) {
            //nimic?
        }
        elseif ($this->prioQ->isPrio($itemId) && $result["Status"] == ShortPixelAPI::STATUS_QUOTA_EXCEEDED) {
            if(!$this->prioQ->skippedCount()) {
                $this->prioQ->reverse(); //for the first prio item with quota exceeded, revert the prio queue as probably the bottom ones were processed
            }
            if($this->prioQ->allSkipped()) {
                $result["Stop"] = true;
            } else {
                $result["Stop"] = false;
                $this->prioQ->skip($itemId);
            }
            self::log("HIP: 5 Prio Skipped: ".json_encode($this->prioQ->getSkipped()));
        }
        elseif($result["Status"] == ShortPixelAPI::STATUS_RETRY && is_array($customIds)) {
            $result["CustomImageLink"] = $thumb = ShortPixelMetaFacade::getHomeUrl() . $meta->getWebPath();
        }
        
        if($result["Status"] !== ShortPixelAPI::STATUS_RETRY) {
            $this->_settings->bulkLastStatus = $result;
        }
        die(json_encode($result));
    }
    
    
    private function advanceBulk($processedID) {
        if($processedID <= $this->prioQ->getStartBulkId()) {
            $this->prioQ->setStartBulkId($processedID - 1);
            $this->prioQ->logBulkProgress();
       }
    }
    
    private function setBulkInfo($processedID, &$result) {
        $deltaBulkPercent = $this->prioQ->getDeltaBulkPercent(); 
        $minutesRemaining = $this->prioQ->getTimeRemaining();
        $pendingMeta = $this->_settings->hasCustomFolders ? $this->spMetaDao->getPendingMetaCount() : 0;
        $percent = $this->prioQ->getBulkPercent();
        if(0 + $pendingMeta > 0) {
            $customMeta = $this->spMetaDao->getCustomMetaCount();
            $totalPercent = round(($percent * $this->_settings->currentTotalFiles + ($customMeta - $pendingMeta) * 100) / ($this->_settings->currentTotalFiles + $customMeta));
            $minutesRemaining = round($minutesRemaining * (100 - $totalPercent) / max(1, 100 - $percent));
            $percent = $totalPercent;
        }
        $result["BulkPercent"] = $percent;
        $result["BulkMsg"] = $this->bulkProgressMessage($deltaBulkPercent, $minutesRemaining);
    }
    
    private function sendToProcessing($itemHandler, $compressionType = false, $onlyThumbs = false) {
        //WpShortPixelMediaLbraryAdapter::cleanupFoundThumbs($itemHandler);
        $URLsAndPATHs = $this->getURLsAndPATHs($itemHandler, NULL, $onlyThumbs);

        $meta = $itemHandler->getMeta();
        //find thumbs that are not listed in the metadata and add them in the sizes array
        if($itemHandler->getType() == ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE) {
            $mainFile = $meta->getPath();
            
            $foundThumbs = WpShortPixelMediaLbraryAdapter::findThumbs($mainFile);
            //first identify which thumbs are not in the sizes
            $sizes = $meta->getThumbs();
            $mimeType = false;
            foreach($foundThumbs as $id => $found) {
                //get the mime-type from one of the thumbs metas
                foreach($sizes as $size) {
                    if(pathinfo($mainFile, PATHINFO_EXTENSION) !== pathinfo($size['file'], PATHINFO_EXTENSION)){
                        continue;
                    }
                    $mimeType = $size['mime-type'];
                    if($size['file'] === ShortPixelAPI::MB_basename($found)) {
                        $foundThumbs[$id] = false;
                    }
                }
            }
            // add the unfound ones to the sizes array
            $ind = 1;
            while (isset($sizes[ShortPixelMeta::FOUND_THUMB_PREFIX . str_pad("".$ind, 2, '0', STR_PAD_LEFT)])) $ind++;
            $start = $ind;
            foreach($foundThumbs as $found) {
                if($found !== false) {
                    $size = getimagesize($found);
                    $sizes[ShortPixelMeta::FOUND_THUMB_PREFIX . str_pad("".$ind, 2, '0', STR_PAD_LEFT)]= array( // it's a file that has no corresponding thumb so it's the WEBP for the main file
                        'file' => ShortPixelAPI::MB_basename($found),
                        'width' => $size[0],
                        'height' => $size[1],
                        'mime-type' => $mimeType
                    );
                    $ind++;
                }
            }       
            if($ind > $start) { // at least one thumbnail added, update
                $meta->setThumbs($sizes);
                $itemHandler->updateMeta($meta);
                $URLsAndPATHs = $this->getURLsAndPATHs($itemHandler, NULL, $onlyThumbs);
            }
        }
        
        //find any missing thumbs files and mark them as such
        $miss = $meta->getThumbsMissing();
        /* TODO remove */if(is_numeric($miss)) $miss = array();
        if(   isset($URLsAndPATHs['sizesMissing']) && count($URLsAndPATHs['sizesMissing']) 
           && (null === $miss || count(array_diff_key($miss, array_merge($URLsAndPATHs['sizesMissing'], $miss))))) {
            //fix missing thumbs in the metadata before sending to processing
            $meta->setThumbsMissing($URLsAndPATHs['sizesMissing']);
            $itemHandler->updateMeta();                
        }
        //die(var_dump($itemHandler));
        $refresh = $meta->getStatus() === ShortPixelAPI::ERR_INCORRECT_FILE_SIZE;
        //echo("URLS: "); die(var_dump($URLsAndPATHs));
        $this->_apiInterface->doRequests($URLsAndPATHs['URLs'], false, $itemHandler, 
                $compressionType === false ? $this->_settings->compressionType : $compressionType, $refresh);//send a request, do NOT wait for response
        $itemHandler->setWaitingProcessing();
        //$meta = wp_get_attachment_metadata($ID);
        //$meta['ShortPixel']['WaitingProcessing'] = true;
        //wp_update_attachment_metadata($ID, $meta);
        return $URLsAndPATHs;
    }

    public function handleManualOptimization() {
        $imageId = $_GET['image_id'];
        $cleanup = $_GET['cleanup'];
        switch(substr($imageId, 0, 2)) {
            case "N-":
                return "Add the gallery to the custom folders list in ShortPixel settings.";
                // Later
                if(class_exists("C_Image_Mapper")) { //this is a NextGen image but not added to our tables, so add it now.
                    $image_mapper = C_Image_Mapper::get_instance();
                    $image = $image_mapper->find(intval(substr($imageId, 2)));
                    if($image) {
                        $this->handleNextGenImageUpload($image, true);
                        return array("Status" => ShortPixelAPI::STATUS_SUCCESS, "message" => "");
                    }
                }
                return array("Status" => ShortPixelAPI::STATUS_FAIL, "message" => __('NextGen image not found','shortpixel-image-optimiser'));
                break;
            case "C-":
                throw new Exception("HandleManualOptimization for custom images not implemented");
            default: 
                $this->optimizeNowHook(intval($imageId), true);
                break;
        }
        //do_action('shortpixel-optimize-now', $imageId);
        
    }
    
    //custom hook
    public function optimizeNowHook($imageId, $manual = false) {
        if($this->isProcessable($imageId)) {
            $this->prioQ->push($imageId);
            $itemHandler = new ShortPixelMetaFacade($imageId);
            $path = get_attached_file($imageId);//get the full file PATH
            if(!$manual && 'pdf' === pathinfo($path, PATHINFO_EXTENSION) && !$this->_settings->optimizePdfs) {
                $ret = array("Status" => ShortPixelAPI::STATUS_SKIP, "Message" => $imageId);
            } else {
                try {
                    $this->sendToProcessing($itemHandler, false, $itemHandler->getMeta()->getThumbsTodo());
                    $ret = array("Status" => ShortPixelAPI::STATUS_SUCCESS, "Message" => "");
                } catch(Exception $e) { // Exception("Post metadata is corrupt (No attachment URL)")
                    $itemHandler->getMeta();
                    $itemHandler->setError(ShortPixelAPI::ERR_FILE_NOT_FOUND, $e->getMessage());
                    $ret = array("Status" => ShortPixelAPI::STATUS_FAIL, "Message" => $e->getMessage());
                }
            }
        } else {
            $ret = array("Status" => ShortPixelAPI::STATUS_SKIP, "Message" => $imageId);
        }
        die(json_encode($ret));
    }
    
    //WP/LR Sync plugin integration
    public function onWpLrUpdateMedia($imageId, $galleryIdsUnused) {
        $meta = wp_get_attachment_metadata($imageId);
        if(is_array($meta)) {
            unset($meta['ShortPixel']);
            $meta['ShortPixel'] = array();
            $meta['ShortPixel']['WaitingProcessing'] = true;
            $this->prioQ->push($imageId);
            wp_update_attachment_metadata($imageId, $meta);
        }
    }
    
    
    //save error in file's meta data
    public function handleError($ID, $result)
    {
        $meta = wp_get_attachment_metadata($ID);
        $meta['ShortPixelImprovement'] = $result;
        wp_update_attachment_metadata($ID, $meta);
    }

    public function getBackupFolder($file) {
        if(realpath($file)) {
            $file = realpath($file); //found cases when $file contains for example /wp/../wp-content - clean it up
        }
        $fileExtension = strtolower(substr($file,strrpos($file,".")+1));
        $SubDir = ShortPixelMetaFacade::returnSubDir($file, ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE);
        $SubDirOld = ShortPixelMetaFacade::returnSubDirOld($file, ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE);

        if (   !file_exists(SP_BACKUP_FOLDER . '/' . $SubDir . ShortPixelAPI::MB_basename($file))
            && !file_exists(SP_BACKUP_FOLDER . '/' . date("Y") . "/" . date("m") . "/" . ShortPixelAPI::MB_basename($file)) ) {
            $SubDir = $SubDirOld; //maybe the folder was saved with the old method that returned the full path if the wp-content was not inside the root of the site.
        }
        if (   !file_exists(SP_BACKUP_FOLDER . '/' . $SubDir . ShortPixelAPI::MB_basename($file))
            && !file_exists(SP_BACKUP_FOLDER . '/' . date("Y") . "/" . date("m") . "/" . ShortPixelAPI::MB_basename($file)) ) {
            $SubDir = trailingslashit(substr(dirname($file), 1)); //try this too
        }
        //sometimes the month of original file and backup can differ
        if ( !file_exists(SP_BACKUP_FOLDER . '/' . $SubDir . ShortPixelAPI::MB_basename($file)) ) {
            $SubDir = date("Y") . "/" . date("m") . "/";
            if( !file_exists(SP_BACKUP_FOLDER . '/' . $SubDir . ShortPixelAPI::MB_basename($file)) ) {
                return false;
            }
        }
        return SP_BACKUP_FOLDER . '/' . $SubDir;
    }
    
    public function getBackupFolderAny($file, $thumbs) {
            if(!file_exists($file)) {
                //try with the thumbnails
                if(isset($thumbs)) foreach($thumbs as $size) {
                    $backup = $this->getBackupFolder(trailingslashit(dirname($file)) . $size['file']);
                    if($backup) return $backup;
                }
        } else {
            return $this->getBackupFolder($file);
        }        
    }
    
    protected function setFilePerms($file) {
        //die(getenv('USERNAME') ? getenv('USERNAME') : getenv('USER'));
        if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            //on *nix platforms check also the owner
            $owner = fileowner($file);
            if($owner !== false && function_exists('posix_getuid') && $owner != posix_getuid()) { //files with changed owner
                return false;
            }
        }
        $perms = @fileperms($file);
        if(!($perms & 0x0100) || !($perms & 0x0080)) {
            if(!@chmod($file, $perms | 0x0100 | 0x0080)) {
                return false;
            }
        }
        return true;
    }

    protected function doRestore($attachmentID, $meta = null) {
        $file = $origFile = get_attached_file($attachmentID);
        if(!$meta) {
            $meta = wp_get_attachment_metadata($attachmentID);
        }
        $pathInfo = pathinfo($file);
    
        $bkFolder = $this->getBackupFolderAny($file, isset($meta["sizes"]) ? $meta["sizes"] : array());
        $bkFile = trailingslashit($bkFolder) . ShortPixelAPI::MB_basename($file);

        //first check if the file is readable by the current user - otherwise it will be unaccessible for the web browser
        // - collect the thumbs paths in the process
        $bkCount = 0; $main = false;
        if(file_exists($bkFile)) {
            if(!$this->setFilePerms($bkFile) || (file_exists($file) && !$this->setFilePerms($file)) ) { 
                return false; 
            }
            $bkCount++; 
            $main = true;
        }
        $thumbsPaths = array();
        if( !empty($meta['file']) && is_array($meta["sizes"]) ) {
            foreach($meta["sizes"] as $size => $imageData) {
                $dest = $pathInfo['dirname'] . '/' . $imageData['file'];
                $source = trailingslashit($bkFolder) . $imageData['file'];
                if(!file_exists($source)) continue; // if thumbs were not optimized, then the backups will not be there.
                if(!$this->setFilePerms($source) || (file_exists($dest) && !$this->setFilePerms($dest))) {
                    return false;
                }
                $bkCount++;
                $thumbsPaths[$source] = $dest;
            }
        }
        if(!$bkCount) {
            return false;
        }
     
        if($bkFolder) {
            try {
                //main file    
                if($main) {
                    $this->renameWithRetina($bkFile, $file);
                }
                //getSize to update meta if image was resized by ShortPixel
                $width = false;
                if(file_exists($file)) {
                    $size = getimagesize($file);
                    $width = $size[0];
                    $height = $size[1];
                }

                //overwriting thumbnails
                foreach($thumbsPaths as $source => $destination) {
                    $this->renameWithRetina($source, $destination);
                }
                $duplicates = ShortPixelMetaFacade::getWPMLDuplicates($attachmentID);
                foreach($duplicates as $ID) {
                    $crtMeta = $attachmentID == $ID ? $meta : wp_get_attachment_metadata($ID);
                    if(is_numeric($crtMeta["ShortPixelImprovement"]) && 0 + $crtMeta["ShortPixelImprovement"] < 5 && $this->_settings->under5Percent > 0) {
                        $this->_settings->under5Percent = $this->_settings->under5Percent - 1; // - (isset($crtMeta["ShortPixel"]["thumbsOpt"]) ? $crtMeta["ShortPixel"]["thumbsOpt"] : 0);
                    }
                    unset($crtMeta["ShortPixelImprovement"]);
                    unset($crtMeta['ShortPixel']);
                    if($width && $height) {
                        $crtMeta['width'] = $width;
                        $crtMeta['height'] = $height;
                    }
                    wp_update_attachment_metadata($ID, $crtMeta);
                }
                unset($meta["ShortPixelImprovement"]);
                unset($meta['ShortPixel']);
                
            } catch(Exception $e) {
                //what to do, what to do?
                return false;
            }
        } else {
            return false;
        }
        
        return $meta;
    }
    
    protected function renameWithRetina($bkFile, $file) {
        @rename($bkFile, $file);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        @rename(substr($bkFile, 0, strlen($bkFile) - 1 - strlen($ext)) . "@2x." . $ext, substr($file, 0, strlen($file) - 1 - strlen($ext)) . "@2x." . $ext);
        
    }

    public function doCustomRestore($ID) {
        $meta = $this->spMetaDao->getMeta($ID);
        if(!$meta || $meta->getStatus() != 2) return false;
        
        $file = $meta->getPath();
        $fullSubDir = str_replace(get_home_path(), "", dirname($file)) . '/';
        $bkFile = SP_BACKUP_FOLDER . '/' . $fullSubDir . ShortPixelAPI::MB_basename($file);     

        if(file_exists($bkFile)) {
            @rename($bkFile, $file);
            $meta->setStatus(3);
            $this->spMetaDao->update($meta);
        }
        
        return $meta;
    }
    
    public function handleRestoreBackup() {
        $attachmentID = intval($_GET['attachment_ID']);
        
        $this->doRestore($attachmentID);

        // get the referring webpage location
        $sendback = wp_get_referer();
        // sanitize the referring webpage location
        $sendback = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $sendback);
        // send the user back where they came from
        wp_redirect($sendback);
        // we are done
    }
    
    public function handleRedo() {
        die(json_encode($this->redo($_GET['attachment_ID'], $_GET['type'])));
    }
    
    public function redo($qID, $type = false) {
        $compressionType = ($type == 'lossless' ? 'lossless' : ($type == 'glossy' ? 'glossy' : 'lossy')); //sanity check

        if(ShortPixelMetaFacade::isCustomQueuedId($qID)) {
            $ID = ShortPixelMetaFacade::stripQueuedIdType($qID);
            $meta = $this->doCustomRestore($ID);
            if($meta) {
                $meta->setCompressionType(ShortPixelAPI::getCompressionTypeCode($compressionType));
                $meta->setStatus(1);
                $this->spMetaDao->update($meta);
                $this->prioQ->push($qID);
                $ret = array("Status" => ShortPixelAPI::STATUS_SUCCESS, "Message" => "");
            } else {
                $ret = array("Status" => ShortPixelAPI::STATUS_SKIP, "Message" => __('Could not restore from backup: ','shortpixel-image-optimiser') . $qID);
            }  
        } else {
            $ID = intval($qID);
            $meta = $this->doRestore($ID);
            if($meta) { //restore succeeded
                $meta['ShortPixel'] = array("type" => $compressionType);
                wp_update_attachment_metadata($ID, $meta);
                try {
                    $this->sendToProcessing(new ShortPixelMetaFacade($ID), ShortPixelAPI::getCompressionTypeCode($compressionType));
                    $this->prioQ->push($ID);
                    $ret = array("Status" => ShortPixelAPI::STATUS_SUCCESS, "Message" => "");
                } catch(Exception $e) { // Exception("Post metadata is corrupt (No attachment URL)") or Exception("Image files are missing.")
                    $meta['ShortPixelImprovement'] = $e->getMessage();
                    $meta['ShortPixel']['ErrCode'] = ShortPixelAPI::STATUS_FAIL;
                    unset($meta['ShortPixel']['WaitingProcessing']);
                    wp_update_attachment_metadata($ID, $meta);
                    $ret = array("Status" => ShortPixelAPI::STATUS_FAIL, "Message" => $e->getMessage());
                }                    
            } else {
                $ret = array("Status" => ShortPixelAPI::STATUS_SKIP, "Message" => __('Could not restore from backup: ','shortpixel-image-optimiser') . $ID);
            }
        }
        return $ret;
    }
    
    public function handleOptimizeThumbs() {
        $ID = intval($_GET['attachment_ID']);
        $meta = wp_get_attachment_metadata($ID);
        //die(var_dump($meta));
        if(   isset($meta['ShortPixelImprovement']) 
           && isset($meta['sizes']) && WpShortPixelMediaLbraryAdapter::countNonWebpSizes($meta['sizes'])
           && ( !isset($meta['ShortPixel']['thumbsOpt']) || $meta['ShortPixel']['thumbsOpt'] == 0)) { //optimized without thumbs, thumbs exist
            $meta['ShortPixel']['thumbsTodo'] = true;
            wp_update_attachment_metadata($ID, $meta);
            $this->prioQ->push($ID);
            try {
                $this->sendToProcessing(new ShortPixelMetaFacade($ID));
                $ret = array("Status" => ShortPixelAPI::STATUS_SUCCESS, "message" => "");
            } catch(Exception $e) { // Exception("Post metadata is corrupt (No attachment URL)") or Exception("Image files are missing.")
                $meta['ShortPixelImprovement'] = $e->getMessage();
                $meta['ShortPixel']['ErrCode'] = ShortPixelAPI::STATUS_FAIL;
                unset($meta['ShortPixel']['WaitingProcessing']);
                wp_update_attachment_metadata($ID, $meta);
                $ret = array("Status" => ShortPixelAPI::STATUS_FAIL, "Message" => $e->getMessage());
            }                    
        } else {
            $ret = array("Status" => ShortPixelAPI::STATUS_SKIP, "message" => (isset($meta['ShortPixelImprovement']) ? __('No thumbnails to optimize for ID: ','shortpixel-image-optimiser') : __('Please optimize image for ID: ','shortpixel-image-optimiser')) . $ID);
        }
        die(json_encode($ret));
    }
    
    public function handleCheckQuota() {
        $this->getQuotaInformation();
        // store the referring webpage location
        $sendback = wp_get_referer();
        // sanitize the referring webpage location
        $sendback = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $sendback);
        // send the user back where they came from
        wp_redirect($sendback);
        // we are done
    }

    public function handleDeleteAttachmentInBackup($ID) {
        $file = get_attached_file($ID);
        $meta = wp_get_attachment_metadata($ID);
        
        if(self::_isProcessable($ID) != false) //we use the static isProcessable to bypass the exclude patterns 
        {
            try {
                    $SubDir = ShortPixelMetaFacade::returnSubDir($file, ShortPixelMetaFacade::MEDIA_LIBRARY_TYPE);
                        
                    @unlink(SP_BACKUP_FOLDER . '/' . $SubDir . ShortPixelAPI::MB_basename($file));
                    
                    if ( !empty($meta['file']) )
                    {
                        $filesPath =  SP_BACKUP_FOLDER . '/' . $SubDir;//base BACKUP path
                        //remove thumbs thumbnails
                        if(isset($meta["sizes"])) {
                            foreach($meta["sizes"] as $size => $imageData) {
                                @unlink($filesPath . ShortPixelAPI::MB_basename($imageData['file']));//remove thumbs
                            }
                        }
                    }            
                
                } catch(Exception $e) {
                //what to do, what to do?
            }
        }
    }
    
    public function deactivatePlugin() {
        if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'sp_deactivate_plugin_nonce' ) ) {
                wp_nonce_ays( '' );
        }
        deactivate_plugins( $_GET['plugin'] );
        wp_safe_redirect( wp_get_referer() );
        die();
    }

    public function checkQuotaAndAlert($quotaData = null, $recheck = false) {
        if(!$quotaData) {
            $quotaData = $this->getQuotaInformation();
        }
        if ( !$quotaData['APIKeyValid']) {
            $this->view->displayActivationNotice('generic', $quotaData['Message']);
            return $quotaData;
        }
        //$tempus = microtime(true);
        $imageCount = WpShortPixelMediaLbraryAdapter::countAllProcessableFiles($this->_settings->optimizePdfs);
        
        $this->_settings->currentTotalFiles = $imageCount['totalFiles'];
 
        //echo("Count took (seconds): " . (microtime(true) - $tempus));
        foreach($imageCount as $key => $val) {
            $quotaData[$key] = $val;
        }

        if($this->_settings->hasCustomFolders) {
            $customImageCount = $this->spMetaDao->countAllProcessableFiles();
            foreach($customImageCount as $key => $val) {
                $quotaData[$key] = isset($quotaData[$key]) 
                                   ? (is_array($quotaData[$key]) ? array_merge($quotaData[$key], $val) : $quotaData[$key] + $val) 
                                   : $val;
            }
        }

        if($quotaData['APICallsQuotaNumeric'] + $quotaData['APICallsQuotaOneTimeNumeric'] > $quotaData['APICallsMadeNumeric'] + $quotaData['APICallsMadeOneTimeNumeric']) {
            $this->_settings->quotaExceeded = '0';
            $this->_settings->prioritySkip = NULL;
            self::log("CHECK QUOTA: Skipped: ".json_encode($this->prioQ->getSkipped()));

            ?><script>var shortPixelQuotaExceeded = 0;</script><?php
        }
        else {
            $this->view->displayQuotaExceededAlert($quotaData, self::getAverageCompression(), $recheck);
            ?><script>var shortPixelQuotaExceeded = 1;</script><?php
        }
        return $quotaData;
    }
    
    public function isValidMetaId($id) {
        return substr($id, 0, 2 ) == "C-" ? $this->spMetaDao->getMeta(substr($id, 2)) : wp_get_attachment_url($id);
    }

    public function listCustomMedia() {
        if( ! class_exists( 'ShortPixelListTable' ) ) {
            require_once('view/shortpixel-list-table.php');
        }  
        if(isset($_REQUEST['refresh']) && esc_attr($_REQUEST['refresh']) == 1) { 
            $notice = null;
            $this->refreshCustomFolders($notice);
        }
        if(isset($_REQUEST['action']) && esc_attr($_REQUEST['action']) == 'optimize' && isset($_REQUEST['image'])) {
            //die(ShortPixelMetaFacade::queuedId(ShortPixelMetaFacade::CUSTOM_TYPE, $_REQUEST['image']));
            $this->prioQ->push(ShortPixelMetaFacade::queuedId(ShortPixelMetaFacade::CUSTOM_TYPE, $_REQUEST['image']));
        }
        $customMediaListTable = new ShortPixelListTable($this, $this->spMetaDao, $this->hasNextGen);
        $items = $customMediaListTable->prepare_items();
        if ( isset($_GET['noheader']) ) {
            require_once(ABSPATH . 'wp-admin/admin-header.php');
        }
        
        ?>
	<div class="wrap shortpixel-other-media">
            <h2>
                <div style="float:right;">
                    <a href="upload.php?page=wp-short-pixel-custom&refresh=1" id="refresh" class="button button-primary" title="<?php _e('Refresh custom folders content','shortpixel-image-optimiser');?>">
                        <?php _e('Refresh folders','shortpixel-image-optimiser');?>
                    </a>
                </div>
                <?php _e('Other Media optimized by ShortPixel','shortpixel-image-optimiser');?>
            </h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post" class="shortpixel-table">
                                <?php
                                $customMediaListTable->display();
                                //push to the processing list the pending ones, just in case
                                //$count = $this->spMetaDao->getCustomMetaCount();
                                foreach ($items as $item) {
                                    if($item->status == 1){
                                        $this->prioQ->push(ShortPixelMetaFacade::queuedId(ShortPixelMetaFacade::CUSTOM_TYPE, $item->id));
                                    }
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
	</div> <?php
    }
    
    public function bulkProcess() {
        global $wpdb;

        if( $this->_settings->verifiedKey == false ) {//invalid API Key
            ShortPixelView::displayActivationNotice();
            return;
        }
        
        $quotaData = $this->checkQuotaAndAlert(null, isset($_GET['checkquota']));
        if($this->_settings->quotaExceeded != 0) {
            return;
        }
        
        if(isset($_POST['bulkProcessPause'])) 
        {//pause an ongoing bulk processing, it might be needed sometimes
            $this->prioQ->pauseBulk();
            if($this->_settings->hasCustomFolders && $this->spMetaDao->getPendingMetaCount()) {
                $this->_settings->customBulkPaused = 1;
            }
        }

        if(isset($_POST['bulkProcessStop'])) 
        {//stop an ongoing bulk processing
            $this->prioQ->cancelBulk();
            if($this->_settings->hasCustomFolders && $this->spMetaDao->getPendingMetaCount()) {
                $this->_settings->customBulkPaused = 1;
            }
        }

        if(isset($_POST["bulkProcess"])) 
        {
            //set the thumbnails option 
            if ( isset($_POST['thumbnails']) ) {
                $this->_settings->processThumbnails = 1;
            } else {
                $this->_settings->processThumbnails = 0;
            }
            //clean the custom files errors in order to process them again
            if($this->_settings->hasCustomFolders) {
                $this->spMetaDao->resetFailed();
            }
            
            $this->prioQ->startBulk(ShortPixelQueue::BULK_TYPE_OPTIMIZE);
            $this->_settings->customBulkPaused = 0;
            self::log("BULK:  Start:  " . $this->prioQ->getStartBulkId() . ", stop: " . $this->prioQ->getStopBulkId() . " PrioQ: "
                 .json_encode($this->prioQ->get()));
        }//end bulk process  was clicked    
        
        if(isset($_POST["bulkRestore"])) 
        {
            $this->prioQ->startBulk(ShortPixelQueue::BULK_TYPE_RESTORE);
            $this->_settings->customBulkPaused = 0;
        }//end bulk restore  was clicked    
        
        if(isset($_POST["bulkProcessResume"]))
        {
            $this->prioQ->resumeBulk();
            $this->_settings->customBulkPaused = 0;
        }//resume was clicked

        if(isset($_POST["skipToCustom"])) 
        {
            $this->_settings->skipToCustom = true;
        }//resume was clicked

        //figure out the files that are left to be processed
        $qry_left = "SELECT count(*) FilesLeftToBeProcessed FROM " . $wpdb->prefix . "postmeta
        WHERE meta_key = '_wp_attached_file' AND post_id <= " . (0 + $this->prioQ->getStartBulkId());
        $filesLeft = $wpdb->get_results($qry_left);
        
        //check the custom bulk
        $pendingMeta = $this->_settings->hasCustomFolders ? $this->spMetaDao->getPendingMetaCount() : 0;
        
        if (   ($filesLeft[0]->FilesLeftToBeProcessed > 0 && $this->prioQ->bulkRunning()) 
            || (0 + $pendingMeta > 0 && !$this->_settings->customBulkPaused && $this->prioQ->bulkRan())//bulk processing was started
                && (!$this->prioQ->bulkPaused() || $this->_settings->skipToCustom)) //bulk not paused or if paused, user pressed Process Custom button
        {
            $msg = $this->bulkProgressMessage($this->prioQ->getDeltaBulkPercent(), $this->prioQ->getTimeRemaining());

            $this->view->displayBulkProcessingRunning($this->getPercent($quotaData), $msg, $quotaData['APICallsRemaining'], $this->getAverageCompression(), 
                    $this->prioQ->getBulkType() == ShortPixelQueue::BULK_TYPE_RESTORE ? 0 : ($pendingMeta !== null ? ($this->prioQ->bulkRunning() ? 3 : 2) : 1));

        } else 
        {
            if($this->prioQ->bulkRan() && !$this->prioQ->bulkPaused()) {
                $this->prioQ->markBulkComplete();
            }

            //image count 
            $thumbsProcessedCount = $this->_settings->thumbsCount;//amount of optimized thumbnails
            $under5PercentCount =  $this->_settings->under5Percent;//amount of under 5% optimized imgs.

            //average compression
            $averageCompression = self::getAverageCompression();
            $percent = $this->prioQ->bulkPaused() ? $this->getPercent($quotaData) : false;

            $this->view->displayBulkProcessingForm($quotaData, $thumbsProcessedCount, $under5PercentCount,
                    $this->prioQ->bulkRan(), $averageCompression, $this->_settings->fileCount,
                    self::formatBytes($this->_settings->savedSpace), $percent, $pendingMeta);
        }
    }
    //end bulk processing
    
    public function getPercent($quotaData) {
            if($this->_settings->processThumbnails) {
                return $quotaData["totalFiles"] ? min(99, round($quotaData["totalProcessedFiles"]  *100.0 / $quotaData["totalFiles"])) : 0;
            } else {
                return $quotaData["mainFiles"] ? min(99, round($quotaData["mainProcessedFiles"]  *100.0 / $quotaData["mainFiles"])) : 0;
            }
    }
    
    public function bulkProgressMessage($percent, $minutes) {
        $timeEst = "";
        self::log("bulkProgressMessage(): percent: " . $percent);
        if($percent < 1 || $minutes == 0) {
            $timeEst = "";
        } elseif( $minutes > 2880) {
            $timeEst = "~ " . round($minutes / 1440) . " days left";
        } elseif ($minutes > 240) {
            $timeEst = "~ " . round($minutes / 60) . " hours left";
        } elseif ($minutes > 60) {
            $timeEst = "~ " . round($minutes / 60) . " hours " . round($minutes % 60 / 10) * 10 . " min. left";
        } elseif ($minutes > 20) {
            $timeEst = "~ " . round($minutes / 10) * 10 . " minutes left";
        } else {
            $timeEst = "~ " . $minutes . " minutes left";
        }
        return $timeEst;
    }
    
    public function emptyBackup(){
            if(file_exists(SP_BACKUP_FOLDER)) {
                
                //extract all images from DB in an array. of course
                $attachments = null;
                $attachments = get_posts( array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image'
                ));
                
            
                //delete the actual files on disk
                $this->deleteDir(SP_BACKUP_FOLDER);//call a recursive function to empty files and sub-dirs in backup dir
            }
    }
    
    public function backupFolderIsEmpty() {
        return count(scandir(SP_BACKUP_FOLDER)) > 2 ? false : true;
    }

    public function getBackupSize() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die(__('You do not have sufficient permissions to access this page.','shortpixel-image-optimiser'));
        }
        die(self::formatBytes(self::folderSize(SP_BACKUP_FOLDER)));
    }
    
    public function browseContent() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die(__('You do not have sufficient permissions to access this page.','shortpixel-image-optimiser'));
        }
        
        $root = self::getCustomFolderBase();
        

        $postDir = rawurldecode($root.(isset($_POST['dir']) ? trim($_POST['dir']) : null ));
        // set checkbox if multiSelect set to true
        $checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
        $onlyFolders = ($_POST['dir'] == '/' || isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
        $onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;

        if( file_exists($postDir) ) {

            $files = scandir($postDir);
            $returnDir	= substr($postDir, strlen($root));
            
            natcasesort($files);

            if( count($files) > 2 ) { // The 2 accounts for . and ..
                echo "<ul class='jqueryFileTree'>";
                foreach( $files as $file ) {
                    
                    if($file == 'ShortpixelBackups') continue;
                    
                    $htmlRel	= str_replace("'", "&apos;", $returnDir . $file);
                    $htmlName	= htmlentities($file);
                    $ext	= preg_replace('/^.*\./', '', $file);

                    if( file_exists($postDir . $file) && $file != '.' && $file != '..' ) {
                        //KEEP the spaces in front of the rel values - it's a trick to make WP Hide not replace the wp-content path
                        if( is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders) ) {
                            echo "<li class='directory collapsed'>{$checkbox}<a rel=' " .$htmlRel. "/'>" . $htmlName . "</a></li>";
                        } else if (!$onlyFolders || $onlyFiles) {
                            echo "<li class='file ext_{$ext}'>{$checkbox}<a rel=' " . $htmlRel . "'>" . $htmlName . "</a></li>";
                        }
                    }
                }

                echo "</ul>";
            }
        }
        die();
    }
    
    public static function getCustomFolderBase() {
        if(is_main_site()) {
            $base = get_home_path();
            return realpath(rtrim($base, '/'));
        } else {
            $up = wp_upload_dir();
            return realpath($up['basedir']);
        }
    }
    
    protected function fullRefreshCustomFolder($path, &$notice) {
        $folder = $this->spMetaDao->getFolder($path);
        $diff = $folder->checkFolderContents(array('ShortPixelCustomMetaDao', 'getPathFiles'));
    }
    
    protected function refreshCustomFolders(&$notice, $ignore = false) {
        $customFolders = array();
        if($this->_settings->hasCustomFolders) {
            $customFolders = $this->spMetaDao->getFolders();
            foreach($customFolders as $folder) {
                if($folder->getPath() === $ignore) continue;
                try {
                    $mt = $folder->getFolderContentsChangeDate();
                    if($mt > strtotime($folder->getTsUpdated())) {
                        $fileList = $folder->getFileList(strtotime($folder->getTsUpdated()));
                        $this->spMetaDao->batchInsertImages($fileList, $folder->getId());
                        $folder->setTsUpdated(date("Y-m-d H:i:s", $mt));
                        $folder->setFileCount($folder->countFiles());
                        $this->spMetaDao->update($folder);
                    }
                    //echo ("mt: " . $mt);
                    //die(var_dump($folder));
                } catch(SpFileRightsException $ex) {
                    if(is_array($notice)) {
                        if($notice['status'] == 'error') {
                            $notice['msg'] .= " " . $ex->getMessage();
                        }
                    } else {
                        $notice = array("status" => "error", "msg" => $ex->getMessage());
                    }
                }            
            }
        }
        return $customFolders;
    }
    
    public function renderSettingsMenu() {
        if ( !current_user_can( 'manage_options' ) )  { 
            wp_die(__('You do not have sufficient permissions to access this page.','shortpixel-image-optimiser'));
        }

        wp_enqueue_style('sp-file-tree.css', plugins_url('/res/css/sp-file-tree.css',SHORTPIXEL_PLUGIN_FILE) );
        wp_enqueue_script('sp-file-tree.js', plugins_url('/res/js/sp-file-tree.js',SHORTPIXEL_PLUGIN_FILE) );
        
        //die(var_dump($_POST));
        $noticeHTML = "";
        $notice = null;
        $folderMsg = false;
        $addedFolder = false;
                
        $this->_settings->redirectedSettings = 2;
        
        //by default we try to fetch the API Key from wp-config.php (if defined)
        if ( defined("SHORTPIXEL_API_KEY") && strlen(SHORTPIXEL_API_KEY) == 20)
        {
            if(!isset($_POST['save']) && (strlen($this->getApiKey()) == 0 || SHORTPIXEL_API_KEY != $this->getApiKey())) {
                $_POST['validate'] = "validate";
            }
            $_POST['key'] = SHORTPIXEL_API_KEY;
        }
        
        //check all custom folders and update meta table if files appeared
        $customFolders = $this->refreshCustomFolders($notice, isset($_POST['removeFolder']) ? $_POST['removeFolder'] : null);
        
        if(isset($_POST['request']) && $_POST['request'] == 'request') {
            //a new API Key was requested
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                
            }
            else {
                $notice = array("status" => "error", 
                    "msg" => __("Please provide a valid e-mail.",'shortpixel-image-optimiser')
                           . "<BR> " 
                           . __('For any question regarding obtaining your API Key, please contact us at ','shortpixel-image-optimiser')
                           . "<a href='mailto:help@shortpixel.com?Subject=API Key issues' target='_top'>help@shortpixel.com</a>"
                           . __(' or ','shortpixel-image-optimiser') 
                           . "<a href='https://shortpixel.com/contact' target='_blank'>" . __('here','shortpixel-image-optimiser') . "</a>.");
            }
        }
        
        if(   isset($_POST['save']) || isset($_POST['saveAdv']) 
           || (isset($_POST['validate']) && $_POST['validate'] == "validate")
           || isset($_POST['removeFolder']) || isset($_POST['recheckFolder'])) {
            
            //handle API Key - common for save and validate.
            $_POST['key'] = trim(str_replace("*", "", isset($_POST['key']) ? $_POST['key'] : $this->_settings->apiKey)); //the API key might not be set if the editing is disabled.
            
            if ( strlen($_POST['key']) <> 20 )
            {
                $KeyLength = strlen($_POST['key']);
    
                $notice = array("status" => "error", 
                    "msg" => sprintf(__("The key you provided has %s characters. The API key should have 20 characters, letters and numbers only.",'shortpixel-image-optimiser'), $KeyLength)
                           . "<BR> <b>" 
                           . __('Please check that the API key is the same as the one you received in your confirmation email.','shortpixel-image-optimiser') 
                           . "</b><BR> " 
                           . __('If this problem persists, please contact us at ','shortpixel-image-optimiser')
                           . "<a href='mailto:help@shortpixel.com?Subject=API Key issues' target='_top'>help@shortpixel.com</a>"
                           . __(' or ','shortpixel-image-optimiser') 
                           . "<a href='https://shortpixel.com/contact' target='_blank'>" . __('here','shortpixel-image-optimiser') . "</a>.");
            }
            else
            {
                $validityData = $this->getQuotaInformation($_POST['key'], true, isset($_POST['validate']) && $_POST['validate'] == "validate");
    
                $this->_settings->apiKey = $_POST['key'];
                if($validityData['APIKeyValid']) {
                    if(isset($_POST['validate']) && $_POST['validate'] == "validate") {
                        // delete last status if it was no valid key
                        $lastStatus = $this->_settings->bulkLastStatus;
                        if(isset($lastStatus['Status']) && $lastStatus['Status'] == ShortPixelAPI::STATUS_NO_KEY) {
                            $this->_settings->bulkLastStatus = null;
                        }
                        //display notification
                        $urlParts = explode("/", get_site_url());
                        if( $validityData['DomainCheck'] == 'NOT Accessible'){
                            $notice = array("status" => "warn", "msg" => __("API Key is valid but your site is not accessible from our servers. Please make sure that your server is accessible from the Internet before using the API or otherwise we won't be able to optimize them.",'shortpixel-image-optimiser'));
                        } else {
                            if ( function_exists("is_multisite") && is_multisite() && !defined("SHORTPIXEL_API_KEY"))
                                $notice = array("status" => "success", "msg" => __("Great, your API Key is valid! <br>You seem to be running a multisite, please note that API Key can also be configured in wp-config.php like this:",'shortpixel-image-optimiser') 
                                    . "<BR> <b>define('SHORTPIXEL_API_KEY', '".$this->_settings->apiKey."');</b>");
                            else
                                $notice = array("status" => "success", "msg" => __('Great, your API Key is valid. Please take a few moments to review the plugin settings below before starting to optimize your images.','shortpixel-image-optimiser'));
                        }
                    }
                    $this->_settings->verifiedKey = true;
                    //test that the "uploads"  have the right rights and also we can create the backup dir for ShortPixel
                    if ( !file_exists(SP_BACKUP_FOLDER) && !@mkdir(SP_BACKUP_FOLDER, 0777, true) )
                        $notice = array("status" => "error", 
                            "msg" => sprintf(__("There is something preventing us to create a new folder for backing up your original files.<BR>Please make sure that folder <b>%s</b> has the necessary write and read rights.",'shortpixel-image-optimiser'), 
                                             WP_CONTENT_DIR . '/' . SP_UPLOADS_NAME ));
                } else {
                    if(isset($_POST['validate'])) {
                        //display notification
                        $notice = array("status" => "error", "msg" => $validityData["Message"]);
                    }
                    $this->_settings->verifiedKey = false;
                }
            }

            //if save button - we process the rest of the form elements
            if(isset($_POST['save']) || isset($_POST['saveAdv'])) {
                $this->_settings->compressionType = $_POST['compressionType'];
                if(isset($_POST['thumbnails'])) { $this->_settings->processThumbnails = 1; } else { $this->_settings->processThumbnails = 0; }
                if(isset($_POST['backupImages'])) { $this->_settings->backupImages = 1; } else { $this->_settings->backupImages = 0; }
                if(isset($_POST['cmyk2rgb'])) { $this->_settings->CMYKtoRGBconversion = 1; } else { $this->_settings->CMYKtoRGBconversion = 0; }
                $this->_settings->keepExif = isset($_POST['removeExif']) ? 0 : 1;
                //delete_option('wp-short-pixel-keep-exif');
                $this->_settings->resizeImages = (isset($_POST['resize']) ? 1: 0);
                $this->_settings->resizeType = (isset($_POST['resize_type']) ? $_POST['resize_type']: false);
                $this->_settings->resizeWidth = (isset($_POST['width']) ? $_POST['width']: $this->_settings->resizeWidth);
                $this->_settings->resizeHeight = (isset($_POST['height']) ? $_POST['height']: $this->_settings->resizeHeight);
                $this->_settings->siteAuthUser = (isset($_POST['siteAuthUser']) ? $_POST['siteAuthUser']: $this->_settings->siteAuthUser);
                $this->_settings->siteAuthPass = (isset($_POST['siteAuthPass']) ? $_POST['siteAuthPass']: $this->_settings->siteAuthPass);
                
                $uploadDir = wp_upload_dir();
                $uploadPath = realpath($uploadDir["basedir"]);

                if(isset($_POST['nextGen'])) { 
                    WpShortPixelDb::checkCustomTables(); // check if custom tables are created, if not, create them
                    $prevNextGen = $this->_settings->includeNextGen;
                    $this->_settings->includeNextGen = 1; 
                    $ret = $this->addNextGenGalleriesToCustom($prevNextGen);
                    $folderMsg = $ret["message"];
                    $customFolders = $ret["customFolders"];
                } else { 
                    $this->_settings->includeNextGen = 0; 
                }
                if(isset($_POST['addCustomFolder']) && strlen($_POST['addCustomFolder']) > 0) {
                    $folderMsg = $this->spMetaDao->newFolderFromPath(stripslashes($_POST['addCustomFolder']), $uploadPath, self::getCustomFolderBase());
                    if(!$folderMsg) {
                        $notice = array("status" => "success", "msg" => __('Folder added successfully.','shortpixel-image-optimiser'));
                    }
                    $customFolders = $this->spMetaDao->getFolders();
                    $this->_settings->hasCustomFolders = true;                    
                }
                
                $this->_settings->createWebp = (isset($_POST['createWebp']) ? 1: 0);
                $this->_settings->optimizeRetina = (isset($_POST['optimizeRetina']) ? 1: 0);
                $this->_settings->optimizePdfs = (isset($_POST['optimizePdfs']) ? 1: 0);
                
                //die(var_dump($_POST['excludePatterns']));
                
                if(isset($_POST['excludePatterns']) && strlen($_POST['excludePatterns'])) {
                    $patterns = array(); 
                    $items = explode(',', $_POST['excludePatterns']);
                    foreach($items as $pat) {
                        $parts = explode(':', $pat);
                        if(count($parts) == 1) {
                            $patterns[] = array("type" =>"name", "value" => str_replace('\\\\','\\',trim($pat)));
                        } else {
                            $patterns[] = array("type" =>trim($parts[0]), "value" => str_replace('\\\\','\\',trim($parts[1])));
                        }
                    }
                    $this->_settings->excludePatterns = $patterns;
                } else {
                    $this->_settings->excludePatterns = array();
                }
                $this->_settings->frontBootstrap = (isset($_POST['frontBootstrap']) ? 1: 0);
                $this->_settings->autoMediaLibrary = (isset($_POST['autoMediaLibrary']) ? 1: 0);

                //Redirect to bulk processing if requested
                if(   isset($_POST['save']) && $_POST['save'] == __("Save and Go to Bulk Process",'shortpixel-image-optimiser')  
                   || isset($_POST['saveAdv']) && $_POST['saveAdv'] == __("Save and Go to Bulk Process",'shortpixel-image-optimiser')) {
                    wp_redirect("upload.php?page=wp-short-pixel-bulk");
                    exit();
                }                
            }
            if(isset($_POST['removeFolder']) && strlen(($_POST['removeFolder']))) { 
                $this->spMetaDao->removeFolder($_POST['removeFolder']);
                $customFolders = $this->spMetaDao->getFolders();
                $_POST["saveAdv"] = true;
            }
            if(isset($_POST['recheckFolder']) && strlen(($_POST['recheckFolder']))) { 
                //$folder->fullRefreshCustomFolder($_POST['recheckFolder']); //aici singura solutie pare callback care spune daca exita url-ul complet
            }
        }

        //now output headers. They were prevented with noheaders=true in the form url in order to be able to redirect if bulk was pressed
        if(isset($_REQUEST['noheader'])) {
            require_once(ABSPATH . 'wp-admin/admin-header.php');
        }
        
        //empty backup
        if(isset($_POST['emptyBackup'])) {
            $this->emptyBackup();
        }

        $quotaData = $this->checkQuotaAndAlert(isset($validityData) ? $validityData : null, isset($_GET['checkquota']));
        
        if($this->hasNextGen) {
            $ngg = array_map(array('ShortPixelNextGenAdapter','pathToAbsolute'), ShortPixelNextGenAdapter::getGalleries());
            //die(var_dump($ngg));
            for($i = 0; $i < count($customFolders); $i++) {
                if(in_array($customFolders[$i]->getPath(), $ngg )) {
                    $customFolders[$i]->setType("NextGen");
                }
            }
        }

        $showApiKey = (   (is_main_site() || (function_exists("is_multisite") && is_multisite() && !defined("SHORTPIXEL_API_KEY")))
                       && !defined("SHORTPIXEL_HIDE_API_KEY"));
        $editApiKey = !defined("SHORTPIXEL_API_KEY") && $showApiKey;
        
        if($this->_settings->verifiedKey) {
            $fileCount = number_format($this->_settings->fileCount);
            $savedSpace = self::formatBytes($this->_settings->savedSpace,2);
            $averageCompression = $this->getAverageCompression();
            $savedBandwidth = self::formatBytes($this->_settings->savedSpace * 10000,2);
            if (is_numeric($quotaData['APICallsQuota'])) {
                $quotaData['APICallsQuota'] .= "/month";
            }
            $remainingImages = $quotaData['APICallsRemaining'];
            $remainingImages = ( $remainingImages < 0 ) ? 0 : number_format($remainingImages);
            $totalCallsMade = number_format($quotaData['APICallsMadeNumeric'] + $quotaData['APICallsMadeOneTimeNumeric']);

            $resources = wp_remote_post($this->_settings->httpProto . "://shortpixel.com/resources-frag");
            if(is_wp_error( $resources )) {
                $resources = array();
            }
            $this->view->displaySettings($showApiKey, $editApiKey,
                   $quotaData, $notice, $resources, $averageCompression, $savedSpace, $savedBandwidth, $remainingImages, 
                   $totalCallsMade, $fileCount, null /*folder size now on AJAX*/, $customFolders, 
                   $folderMsg, $folderMsg ? $addedFolder : false, isset($_POST['saveAdv']));        
        } else {
            $this->view->displaySettings($showApiKey, $editApiKey, $quotaData, $notice);        
        }
        
    }

    public function addNextGenGalleriesToCustom($silent) {
        $customFolders = array(); 
        $folderMsg = "";
        if($this->_settings->includeNextGen) {
            //add the NextGen galleries to custom folders
            $ngGalleries = ShortPixelNextGenAdapter::getGalleries();
            foreach($ngGalleries as $gallery) {
                $folderMsg = $this->spMetaDao->newFolderFromPath($gallery, get_home_path(), self::getCustomFolderBase());
                $this->_settings->hasCustomFolders = true;                    
            }
            $customFolders = $this->spMetaDao->getFolders();
        }
        return array("message" => $silent? "" : $folderMsg, "customFolders" => $customFolders);
    }
                    
    public function getAverageCompression(){
        return $this->_settings->totalOptimized > 0 
               ? round(( 1 -  ( $this->_settings->totalOptimized / $this->_settings->totalOriginal ) ) * 100, 2) 
               : 0;
    }
    
    /**
     * 
     * @param type $apiKey
     * @param type $appendUserAgent
     * @param type $validate - true if we are validating the api key, send also the domain name and number of pics
     * @return type
     */
    public function getQuotaInformation($apiKey = null, $appendUserAgent = false, $validate = false) {
    
        if(is_null($apiKey)) { $apiKey = $this->_settings->apiKey; }
        
        if($this->_settings->httpProto != 'https' && $this->_settings->httpProto != 'http') {
            $this->_settings->httpProto = 'https';
        }

        $requestURL = $this->_settings->httpProto . '://api.shortpixel.com/v2/api-status.php';
        $args = array(
            'timeout'=> SP_VALIDATE_MAX_TIMEOUT,
            'body' => array('key' => $apiKey)
        );
        $argsStr = "?key=".$apiKey;

        if($appendUserAgent) {
            $args['body']['useragent'] = "Agent" . urlencode($_SERVER['HTTP_USER_AGENT']);
            $argsStr .= "&useragent=Agent".$args['body']['useragent'];
        }
        if($validate) {
            $args['body']['DomainCheck'] = get_site_url();
            $args['body']['Info'] = get_bloginfo('version') . '|' . phpversion();
            $imageCount = WpShortPixelMediaLbraryAdapter::countAllProcessableFiles($this->_settings->optimizePdfs);
            $args['body']['ImagesCount'] = $imageCount['mainFiles'];
            $args['body']['ThumbsCount'] = $imageCount['totalFiles'] - $imageCount['mainFiles'];
            $argsStr .= "&DomainCheck={$args['body']['DomainCheck']}&Info={$args['body']['Info']}&ImagesCount={$imageCount['mainFiles']}&ThumbsCount={$args['body']['ThumbsCount']}";
        }
        if(strlen($this->_settings->siteAuthUser)) { 
            $args['body']['url'] = parse_url(get_site_url(),PHP_URL_HOST);
            $args['body']['user'] = $this->_settings->siteAuthUser; 
            $args['body']['pass'] = urlencode($this->_settings->siteAuthPass);
            $argsStr .= "&url={$args['body']['url']}&user={$args['body']['user']}&pass={$args['body']['pass']}";
        }

        $comm = array();

        //Try first HTTPS post. add the sslverify = false if https
        if($this->_settings->httpProto === 'https') {
            $args['sslverify'] = false;
        }
        $response = wp_remote_post($requestURL, $args);
        $comm[] = array("sent" => "POST: " . $requestURL, "args" => $args, "received" => $response);
            
        //some hosting providers won't allow https:// POST connections so we try http:// as well
        if(is_wp_error( $response )) {
            //echo("protocol " . $this->_settings->httpProto . " failed. switching...");
            $requestURL = $this->_settings->httpProto == 'https' ? 
                str_replace('https://', 'http://', $requestURL) :
                str_replace('http://', 'https://', $requestURL);
            // add or remove the sslverify
            if($this->_settings->httpProto === 'http') {
                $args['sslverify'] = false;
            } else {
                unset($args['sslverify']);
            }
            $response = wp_remote_post($requestURL, $args);    
            $comm[] = array("sent" => "POST: " . $requestURL, "args" => $args, "received" => $response);
            
            if(!is_wp_error( $response )){
                $this->_settings->httpProto = ($this->_settings->httpProto == 'https' ? 'http' : 'https');
                //echo("protocol " . $this->_settings->httpProto . " succeeded");
            } else {
                //echo("protocol " . $this->_settings->httpProto . " failed too");                    
            }
        }
        //Second fallback to HTTP get
        if(is_wp_error( $response )){
            $args['body'] = null;
            $requestURL .= $argsStr;
            $response = wp_remote_get($requestURL, $args);
            $comm[] = array("sent" => "POST: " . $requestURL, "args" => $args, "received" => $response);
        }
   
        $defaultData = array(
            "APIKeyValid" => false,
            "Message" => __('API Key could not be validated due to a connectivity error.<BR>Your firewall may be blocking us. Please contact your hosting provider and ask them to allow connections from your site to api.shortpixel.com (IP 176.9.106.46).<BR> If you still cannot validate your API Key after this, please <a href="https://shortpixel.com/contact" target="_blank">contact us</a> and we will try to help. ','shortpixel-image-optimiser'),
            "APICallsMade" => __('Information unavailable. Please check your API key.','shortpixel-image-optimiser'),
            "APICallsQuota" => __('Information unavailable. Please check your API key.','shortpixel-image-optimiser'),
            "DomainCheck" => 'NOT Accessible');

        if(is_object($response) && get_class($response) == 'WP_Error') {
            
            $urlElements = parse_url($requestURL);
            $portConnect = @fsockopen($urlElements['host'],8,$errno,$errstr,15);
            if(!$portConnect) {
                $defaultData['Message'] .= "<BR>Debug info: <i>$errstr</i>";
            }
            return $defaultData;
        }

        if($response['response']['code'] != 200) {
            //$defaultData['Message'] .= "<BR><i>Debug info: response code {$response['response']['code']} URL $requestURL , Response ".json_encode($response)."</i>";
            return $defaultData;
        }

        $data = $response['body'];
        $data = ShortPixelTools::parseJSON($data);

        if(empty($data)) { return $defaultData; }

        if($data->Status->Code != 2) {
            $defaultData['Message'] = $data->Status->Message;
            return $defaultData;
        }

        if ( ( $data->APICallsMade + $data->APICallsMadeOneTime ) < ( $data->APICallsQuota + $data->APICallsQuotaOneTime ) ) //reset quota exceeded flag -> user is allowed to process more images. 
            $this->resetQuotaExceeded();
        else
            $this->_settings->quotaExceeded = 1;//activate quota limiting            

        //if a non-valid status exists, delete it
        $lastStatus = $this->_settings->bulkLastStatus = null;
        if($lastStatus && $lastStatus['Status'] == ShortPixelAPI::STATUS_NO_KEY) {
            $this->_settings->bulkLastStatus = null;
        }
            
        return array(
            "APIKeyValid" => true,
            "APICallsMade" => number_format($data->APICallsMade) . __(' images','shortpixel-image-optimiser'),
            "APICallsQuota" => number_format($data->APICallsQuota) . __(' images','shortpixel-image-optimiser'),
            "APICallsMadeOneTime" => number_format($data->APICallsMadeOneTime) . __(' images','shortpixel-image-optimiser'),
            "APICallsQuotaOneTime" => number_format($data->APICallsQuotaOneTime) . __(' images','shortpixel-image-optimiser'),
            "APICallsMadeNumeric" => $data->APICallsMade,
            "APICallsQuotaNumeric" => $data->APICallsQuota,
            "APICallsMadeOneTimeNumeric" => $data->APICallsMadeOneTime,
            "APICallsQuotaOneTimeNumeric" => $data->APICallsQuotaOneTime,
            "APICallsRemaining" => $data->APICallsQuota + $data->APICallsQuotaOneTime - $data->APICallsMade - $data->APICallsMadeOneTime,
            "APILastRenewalDate" => $data->DateSubscription,
            "DomainCheck" => (isset($data->DomainCheck) ? $data->DomainCheck : null)
        );
    }
    
    public function resetQuotaExceeded() {
        if( $this->_settings->quotaExceeded == 1) {
            $dismissed = $this->_settings->dismissedNotices ? $this->_settings->dismissedNotices : array();
            unset($dismissed['exceed']);
            $this->_settings->dismissedNotices = $dismissed;
        }
        $this->_settings->quotaExceeded = 0;
    }

    public function generateCustomColumn( $column_name, $id, $extended = false ) {
        if( 'wp-shortPixel' == $column_name ) {

            if(!$this->isProcessable($id)) {
                $renderData['status'] = 'n/a';    
                $this->view->renderCustomColumn($id, $renderData, $extended);
                return;
            }

            $file = get_attached_file($id);                        
            $data = wp_get_attachment_metadata($id);
            $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $invalidKey = !$this->_settings->verifiedKey;
            $quotaExceeded = $this->_settings->quotaExceeded;
            $renderData = array("id" => $id, "showActions" => (current_user_can( 'manage_options' ) || current_user_can( 'upload_files' ) || current_user_can( 'edit_posts' )));

            if($invalidKey) { //invalid key - let the user first register and only then
                $renderData['status'] = 'invalidKey';
                $this->view->renderCustomColumn($id, $renderData, $extended);
                return;
            }
            
            //empty data means document, we handle only PDF
            elseif (empty($data)) { //TODO asta devine if si decomentam returnurile
                if($fileExtension == "pdf") {
                    $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'optimizeNow';
                    $renderData['message'] = __('PDF not processed.','shortpixel-image-optimiser');
                } 
                else { //Optimization N/A
                    $renderData['status'] = 'n/a';
                }
                $this->view->renderCustomColumn($id, $renderData, $extended);
                return;
            } 
                
            if(!isset($data['ShortPixelImprovement'])) { //new image
                $data['ShortPixelImprovement'] = '';
            }
            
            if(   is_numeric($data['ShortPixelImprovement']) 
               && !($data['ShortPixelImprovement'] == 0 && isset($data['ShortPixel']['WaitingProcessing'])) //for images that erroneously have ShortPixelImprovement = 0 when WaitingProcessing
              ) { //already optimized
                $sizesCount = isset($data['sizes']) ? WpShortPixelMediaLbraryAdapter::countNonWebpSizes($data['sizes']) : 0;
                
                $renderData['status'] = $fileExtension == "pdf" ? 'pdfOptimized' : 'imgOptimized';
                $renderData['percent'] = $data['ShortPixelImprovement'];
                $renderData['bonus'] = ($data['ShortPixelImprovement'] < 5);
                $renderData['backup'] = $this->getBackupFolderAny($file, $sizesCount? $data['sizes'] : array());
                $renderData['type'] = isset($data['ShortPixel']['type']) ? $data['ShortPixel']['type'] : '';
                $renderData['invType'] = ShortPixelAPI::getCompressionTypeName($this->getOtherCompressionType(ShortPixelAPI::getCompressionTypeCode($renderData['type'])));
                $renderData['thumbsTotal'] = $sizesCount;
                $renderData['thumbsOpt'] = isset($data['ShortPixel']['thumbsOpt']) ? $data['ShortPixel']['thumbsOpt'] : $sizesCount;
                $renderData['thumbsMissing'] = isset($data['ShortPixel']['thumbsMissing']) ? $data['ShortPixel']['thumbsMissing'] : array();
                $renderData['retinasOpt'] = isset($data['ShortPixel']['retinasOpt']) ? $data['ShortPixel']['retinasOpt'] : null;
                $renderData['exifKept'] = isset($data['ShortPixel']['exifKept']) ? $data['ShortPixel']['exifKept'] : null;
                $renderData['date'] = isset($data['ShortPixel']['date']) ? $data['ShortPixel']['date'] : null;
                $renderData['quotaExceeded'] = $quotaExceeded;                
                $webP = 0;
                if($extended) {
                    if(file_exists(dirname($file) . '/' . ShortPixelAPI::MB_basename($file, '.'.$fileExtension) . '.webp' )){
                        $webP++;
                    }
                    if(isset($data['sizes'])) {
                    foreach($data['sizes'] as $key => $size) {
                        if (strpos($key, ShortPixelMeta::WEBP_THUMB_PREFIX) === 0) continue;
                        $sizeName = $size['file'];
                        if(file_exists(dirname($file) . '/' . ShortPixelAPI::MB_basename($sizeName, '.'.$fileExtension) . '.webp' )){
                            $webP++;
                        }
                    }
                    }
                }
                $renderData['webpCount'] = $webP;
            }
/*            elseif($data['ShortPixelImprovement'] == __('Optimization N/A','shortpixel-image-optimiser')) { //We don't optimize this
                $renderData['status'] = 'n/a';
            }*/
            elseif(isset($meta['ShortPixel']['BulkProcessing'])) { //Scheduled to bulk.
                $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'optimizeNow';
                $renderData['message'] = 'Waiting for bulk processing.';
            }
            elseif( trim(strip_tags($data['ShortPixelImprovement'])) == __("Cannot write optimized file",'shortpixel-image-optimiser') ) {
                $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'retry';
                $renderData['message'] = __("Cannot write optimized file",'shortpixel-image-optimiser') . " - <a href='https://shortpixel.com/faq#cannot-write-optimized-file' target='_blank'>"
                                       . __("Why?",'shortpixel-image-optimiser') . "</a>";
            }
            elseif( strlen(trim(strip_tags($data['ShortPixelImprovement']))) > 0 ) {
                $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'retry';
                $renderData['message'] = $data['ShortPixelImprovement'];
                if(strpos($renderData['message'], __('The file(s) do not exist on disk: ','shortpixel-image-optimiser')) !== false) {
                    $renderData['cleanup'] = true;
                }
            }
             elseif(isset($data['ShortPixel']['NoFileOnDisk'])) {
                $renderData['status'] = 'notFound';
                $renderData['message'] = __('Image does not exist','shortpixel-image-optimiser');
            }
            elseif(isset($data['ShortPixel']['WaitingProcessing'])) {
                $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'retry';
                $renderData['message'] = "<img src=\"" . plugins_url( 'res/img/loading.gif', SHORTPIXEL_PLUGIN_FILE ) . "\" class='sp-loading-small'>&nbsp;" . __("Image waiting to be processed.",'shortpixel-image-optimiser');
                if(!$quotaExceeded && ($id > $this->prioQ->getFlagBulkId() || !$this->prioQ->bulkRunning())) {
                    $this->prioQ->push($id); //should be there but just to make sure
                }
            }
            else { //finally
                $renderData['status'] = $quotaExceeded ? 'quotaExceeded' : 'optimizeNow';
                $sizes = isset($data['sizes']) ? WpShortPixelMediaLbraryAdapter::countNonWebpSizes($data['sizes']) : 0;
                $renderData['thumbsTotal'] = $sizes;
                $renderData['message'] = ($fileExtension == "pdf" ? 'PDF' : 'Image') . ' not processed.';
            }  
            
            $this->view->renderCustomColumn($id, $renderData, $extended);
        }
    }

    function shortpixelInfoBox() {
        if(get_post_type( ) == 'attachment') {
            add_meta_box(
                'shortpixel_info_box',          // this is HTML id of the box on edit screen
                __('ShortPixel Info', 'shortpixel-image-optimiser'),    // title of the box
                array( &$this, 'shortpixelInfoBoxContent'),   // function to be called to display the info
                null,//,        // on which edit screen the box should appear
                'side'//'normal',      // part of page where the box should appear
                //'default'      // priority of the box
            );
        }
    }
    
    function shortpixelInfoBoxContent( $post ) {
        $this->generateCustomColumn( 'wp-shortPixel', $post->ID, true );
    }
    
    function onDeleteImage($post_id) {
        $itemHandler = new ShortPixelMetaFacade($post_id);
        $urlsPaths = $itemHandler->getURLsAndPATHs(true, false, false);
        foreach($urlsPaths['PATHs'] as $path) {
            $pos = strrpos($path, ".");
            if ($pos !== false) {
                //$webpPath = substr($path, 0, $pos) . ".webp";
                //echo($webpPath . "<br>");
                @unlink(substr($path, 0, $pos) . ".webp");
                @unlink(substr($path, 0, $pos) . "@2x.webp");
            }
        }
    }

    public function columns( $defaults ) {
        $defaults['wp-shortPixel'] = 'ShortPixel Compression';
        if(current_user_can( 'manage_options' )) {
            $defaults['wp-shortPixel'] .= '&nbsp;<a href="options-general.php?page=wp-shortpixel#stats" title="' 
                                       . __('ShortPixel Statistics','shortpixel-image-optimiser') . '"><span class="dashicons dashicons-dashboard"></span></a>';
        }
        return $defaults;
    }

    public function nggColumns( $defaults ) {
        $this->nggColumnIndex = count($defaults) + 1;
        add_filter( 'ngg_manage_images_column_' . $this->nggColumnIndex . '_header', array( &$this, 'nggColumnHeader' ) );
        add_filter( 'ngg_manage_images_column_' . $this->nggColumnIndex . '_content', array( &$this, 'nggColumnContent' ), 10, 2 );
        $defaults['wp-shortPixelNgg'] = 'ShortPixel Compression';
        return $defaults;
    }

    public function nggCountColumns( $count ) {
        return $count + 1;
    }
    
    public function nggColumnHeader( $default ) {
        return __('ShortPixel Compression','shortpixel-image-optimiser');
    }

    public function nggColumnContent( $unknown, $picture ) {
        
        $meta = $this->spMetaDao->getMetaForPath($picture->imagePath);
        if($meta) {
            switch($meta->getStatus()) {
                case "0": echo("<div id='sp-msg-C-{$meta->getId()}' class='column-wp-shortPixel' style='color: #928B1E'>Waiting</div>"); break;
                case "1": echo("<div id='sp-msg-C-{$meta->getId()}' class='column-wp-shortPixel' style='color: #1919E2'>Pending</div>"); break;
                case "2": $this->view->renderCustomColumn("C-" . $meta->getId(), array(
                    'showActions' => false && current_user_can( 'manage_options' ),
                    'status' => 'imgOptimized',
                    'type' => ShortPixelAPI::getCompressionTypeName($meta->getCompressionType()),
                    'percent' => $meta->getImprovementPercent(),
                    'bonus' => $meta->getImprovementPercent() < 5,
                    'thumbsOpt' => 0,
                    'thumbsTotal' => 0,
                    'retinasOpt' => 0,
                    'backup' => true
                )); 
                break;
            }
        } else {
            $this->view->renderCustomColumn($meta ? "C-" . $meta->getId() : "N-" . $picture->pid, array(
                    'showActions' => false && current_user_can( 'manage_options' ),
                    'status' => 'optimizeNow',
                    'thumbsOpt' => 0,
                    'thumbsTotal' => 0,
                    'retinasOpt' => 0,
                    'message' => "Not optimized"
                )); 
        }
//        return var_dump($meta);
    }

    public function generatePluginLinks($links) {
        $in = '<a href="options-general.php?page=wp-shortpixel">Settings</a>';
        array_unshift($links, $in);
        return $links;
    }

    static public function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    public function isProcessable($ID, $excludeExtensions = array()) {
        $excludePatterns = $this->_settings->excludePatterns;
        return self::_isProcessable($ID, $excludeExtensions, $excludePatterns);
    }
    
    public function isProcessablePath($path, $excludeExtensions = array()) {
        $excludePatterns = $this->_settings->excludePatterns;
        return self::_isProcessablePath($path, $excludeExtensions, $excludePatterns);
    }
    
    static public function _isProcessable($ID, $excludeExtensions = array(), $excludePatterns = array(), $meta = false) {
        $path = get_attached_file($ID);//get the full file PATH
        if(isset($excludePatterns) && is_array($excludePatterns)) {
            foreach($excludePatterns as $excludePattern) {
                $type = $excludePattern["type"];
                if($type == "size") {
                    $meta = $meta? $meta : wp_get_attachment_metadata($ID);
                    if(   isset($meta["width"]) && isset($meta["height"])
                       && self::isProcessableSize($meta["width"], $meta["height"], $excludePattern["value"]) === false){
                        return false;
                    }
                }
            }
        }        
        return $path ? self::_isProcessablePath($path, $excludeExtensions, $excludePatterns) : false;
    }
    
    static public function _isProcessablePath($path, $excludeExtensions = array(), $excludePatterns = array()) {
        $pathParts = pathinfo($path);
        $ext = $pathParts['extension'];
        if( isset($ext) && in_array(strtolower($ext), array_diff(self::$PROCESSABLE_EXTENSIONS, $excludeExtensions))) {
            //apply patterns defined by user to exclude some file names or paths
            if(!$excludePatterns || !is_array($excludePatterns)) { return true; }
            foreach($excludePatterns as $item) {
                $type = trim($item["type"]);
                if(in_array($type, array("name", "path"))) {
                    $pattern = trim($item["value"]);
                    $target = $type == "name" ? ShortPixelAPI::MB_basename($path) : $path;
                    if(   $pattern[0] == '/' && @preg_match($pattern, false) !== false && preg_match($pattern,  $target) //search as regex pattern if starts with a / and regex is valid
                       || $pattern[0] != '/' && strpos($target, $pattern) !== false) { //search as a substring if not
                        return false;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
    static public function isProcessableSize($width, $height, $excludePattern) {
        $ranges = preg_split("/(x|×)/",$excludePattern);
        $widthBounds = explode("-", $ranges[0]);
        $heightBounds = isset($ranges[1]) ? explode("-", $ranges[1]) : false;
        if(   $width >= 0 + $widthBounds[0] 
           && (!isset($widthBounds[1]) || isset($widthBounds[1]) && $width <= 0 + $widthBounds[1])
           && (   $heightBounds === false 
               || ($height >= 0 + $heightBounds[0]
                   && (!isset($heightBounds[1]) || isset($heightBounds[1]) && $height <= 0 + $heightBounds[1])))) {
            return false;
        }
        return true;
    }


    //return an array with URL(s) and PATH(s) for this file
    public function getURLsAndPATHs($itemHandler, $meta = NULL, $onlyThumbs = false) {
        return $itemHandler->getURLsAndPATHs($this->_settings->processThumbnails, $onlyThumbs, $this->_settings->optimizeRetina);
    }
    

    public static function deleteDir($dirPath) {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
                @rmdir($file);//remove empty dir
            } else {
                @unlink($file);//remove file
            }
        }
    }

    static public function folderSize($path) {
        $total_size = 0;
        if(file_exists($path)) {
            $files = scandir($path);
        } else {
            return $total_size;
        }
        $cleanPath = rtrim($path, '/'). '/';
        foreach($files as $t) {
            if ($t<>"." && $t<>"..") 
            {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = self::folderSize($currentFile);
                    $total_size += $size;
                }
                else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }
        }
        return $total_size;
    }
    
    public function migrateBackupFolder() {
        $oldBackupFolder = WP_CONTENT_DIR . '/' . SP_BACKUP;

        if(file_exists($oldBackupFolder)) {  //if old backup folder does not exist then there is nothing to do

            if(!file_exists(SP_BACKUP_FOLDER)) {
                //we check that the backup folder exists, if not we create it so we can copy into it
                if(!mkdir(SP_BACKUP_FOLDER, 0777, true)) return;
            }

            $scannedDirectory = array_diff(scandir($oldBackupFolder), array('..', '.'));
            foreach($scannedDirectory as $file) {
                @rename($oldBackupFolder.'/'.$file, SP_BACKUP_FOLDER.'/'.$file);
            }
            $scannedDirectory = array_diff(scandir($oldBackupFolder), array('..', '.'));
            if(empty($scannedDirectory)) {
                @rmdir($oldBackupFolder);
            }
        }
        //now if the backup folder does not contain the uploads level, create it
        if(   !is_dir(SP_BACKUP_FOLDER . '/' . SP_UPLOADS_NAME )
           && !is_dir(SP_BACKUP_FOLDER . '/' . basename(WP_CONTENT_DIR))) {
            @rename(SP_BACKUP_FOLDER, SP_BACKUP_FOLDER."_tmp");
            @mkdir(SP_BACKUP_FOLDER);
            @rename(SP_BACKUP_FOLDER."_tmp", SP_BACKUP_FOLDER.'/'.SP_UPLOADS_NAME);
            if(!file_exists(SP_BACKUP_FOLDER)) {//just in case..
                @rename(SP_BACKUP_FOLDER."_tmp", SP_BACKUP_FOLDER); 
            }
        }
        //then create the wp-content level if not present
        if(!is_dir(SP_BACKUP_FOLDER . '/' . basename(WP_CONTENT_DIR))) {
            @rename(SP_BACKUP_FOLDER, SP_BACKUP_FOLDER."_tmp");
            @mkdir(SP_BACKUP_FOLDER);
            @rename(SP_BACKUP_FOLDER."_tmp", SP_BACKUP_FOLDER.'/' . basename(WP_CONTENT_DIR));
            if(!file_exists(SP_BACKUP_FOLDER)) {//just in case..
                @rename(SP_BACKUP_FOLDER."_tmp", SP_BACKUP_FOLDER); 
            }
        }
        return;
    }
    
    function getMaxIntermediateImageSize() {
        global $_wp_additional_image_sizes;

        $width = 0;
        $height = 0;
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {
            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $width = max($width, get_option( $_size . '_size_w' ));
                $height = max($height, get_option( $_size . '_size_h' ));
                //$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $width = max($width, $_wp_additional_image_sizes[ $_size ]['width']);
                $height = max($height, $_wp_additional_image_sizes[ $_size ]['height']);
                //'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            }
        }
        return array('width' => max(100, $width), 'height' => max(100, $height));
    }

    public function getOtherCompressionType($compressionType = false) {
        $defCompType = 0 + $this->getCompressionType();
        if($compressionType === false) {
            return $defCompType == 1 ? 2 : $defCompType == 2 ? 0 : 1;
        }
        elseif($defCompType == 0 || $defCompType == 2) {
            return (0 + $compressionType == 0 ? 2 : 0);
        }
        return $compressionType == 1 ? 0 : 1;
    }    
    
/*    public function getEncryptedData() {
        return base64_encode(self::encrypt($this->getApiKey() . "|" . get_site_url(), "sh0r+Pix3l8im1N3r"));
    }
*/

    /**
     * Returns an encrypted & utf8-encoded
     */
/*    public static function encrypt($pure_string, $encryption_key)
    {
        if(!function_exists("mcrypt_get_iv_size") || !function_exists('utf8_encode')) {
            return "";
        }
        $iv_size = \mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = \mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = \mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return $encrypted_string;
    }
*/

    public function getApiKey() {
        return $this->_settings->apiKey;
    }
    
    public function getPrioQ() {
        return $this->prioQ;
    }
    
    public function backupImages() {
        return $this->_settings->backupImages;
    }

    public function processThumbnails() {
        return $this->_settings->processThumbnails;
    }
    
    public function getCMYKtoRGBconversion() {
        return $this->_settings->CMYKtoRGBconversion;
    }
    
    public function getSettings() {
        return $this->_settings;
    }

    public function getResizeImages() {
        return $this->_settings->resizeImages;
    }

    public function getResizeWidth() {
        return $this->_settings->resizeWidth;
    }

    public function getResizeHeight() {
        return $this->_settings->resizeHeight;
    }
    public function getAffiliateSufix() {
        return $this->_affiliateSufix;
    }
    public function getVerifiedKey() {
        return $this->_settings->verifiedKey;
    }
    public function getCompressionType() {
        return $this->_settings->compressionType;
    }
    public function hasNextGen() {
        return $this->hasNextGen;
    }
    
    public function getSpMetaDao() {
        return $this->spMetaDao;
    }

}
