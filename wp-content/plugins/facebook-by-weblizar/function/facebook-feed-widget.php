<?php
/**
 * Adds widget.
 */
class Weblizar_facebook_feed_widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
				'weblizar_facebook_feed_widget', // Base ID
				 'Facebook Page Feed ', // Name
			   array( 'description' => 'Display Facebook Page Feed', 'wl_facebook'  ) // Args
			   );
	}
			     /*
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		  @param array $instance Saved values from database.
		 */

		  public function widget( $args, $instance ) {
		  	$Title    	=   apply_filters( 'feed_widget_title', $instance['Title'] );
		  	echo $args['before_widget'];
		  		if ( ! empty( $instance['Title'] ) )
		  		{
		  			echo $args['before_title'] . apply_filters( 'widget_title', $instance['Title'] ). $args['after_title'];
		  		}
				 require("facebook-feed-shortcode-data.php");
				 set_error_handler(
				 create_function(
				'$severity, $weblizar_data_postssage, $file, $line',
				'throw new ErrorException($weblizar_data_postssage, $severity, $severity, $file, $line);'
			     )
		         );
	          try {
					$jsondata = file_get_contents($header_string);
					$weblizar_header_data = json_decode($jsondata,true);
					$jsondata_post = file_get_contents($page_timeline_string);
					$weblizar_data_post = json_decode($jsondata_post,true); ?>
		              <div class="clearfix"> </div>
		               <div class=" feed_main_widget wp-weblizar_fb-plugin">
					    <!--header code start-->
						<div class="weblizar_fb-main-banner">
						   <!--header code cover image--> 
							<div class="weblizar_fb-main-banner-img">
								<img src="<?php if(isset($weblizar_header_data['cover']['source'])) { echo $weblizar_header_data['cover']['source']; }?>" class="img-responsive fb-banner-img" >
							</div>
							<div class="weblizar_fb-facebook-feed-top-area">
								<div class="weblizar_fb-facebook-feed-top-img ">
								 <!-- header link on logo --> 
								  <a href="<?php if(isset($weblizar_header_data['link'])) { echo $weblizar_header_data['link']; } else { echo "#";}?>" class="weblizar_fb-main-pic pull-left">
									<!-- header logo image --> 
									<img src="<?php if(isset($weblizar_header_data['picture']['data']['url'])) { echo $weblizar_header_data['picture']['data']['url']; } ?>" class="img-responsive fb_main_pic " >
								  </a>
									<div class="weblizar_fb-facebook-feed-top-info pull-left">
										<div class="weblizar_fb-facebook-feed-top-info-inner  pull-left">
											<h3 class="fb-top-info-inner_header">
											<!-- header page name --> 
											<a class="fb_top_info_inner_header_link" href="<?php if(isset($weblizar_header_data['link'])) { echo $weblizar_header_data['link']; } else { echo "#"; } ?>">  
												<?php if(isset($weblizar_header_data['name'])) { echo $weblizar_header_data['name']; } ?> 
											</a>
											</h3>
											<!-- Like count --> 
											<?php if(isset($weblizar_header_data['fan_count'])) { ?>
												<p class="fb_fan_count"> <span class="fb_fan_count_limit"> <?php  echo $weblizar_header_data['fan_count']; ?> </span>  <?php _e('Likes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>  </p>
											<?php } ?>
										 <div class="clearfix"> </div>
										</div>
									</div>
									</div>
                                 <!-- Page link--> 
								<div class="weblizar_fb-facebook-feed-inner-text">
									<div class="button-group">
										<a type="button" target="_blank" class="weblizar_fb_btn_secondary feed_header_link" href="<?php if(isset($weblizar_header_data['link'])) { echo $weblizar_header_data['link']; } else { echo "#";}?>"> <i class="fa fa-share"> </i> Share </a>						
									</div>
								</div>
			             </div>
						</div>
						<!-- header code end -->
		                <div class="clearfix"> </div>
						 <!-- Page post display start -->
		                <div class="container-flut gallaries">
	                      <?php for($y = 0; $y <$ffp_limit; $y++) 
                                { 
     		                      if(isset($weblizar_data_post["posts"]['data'][$y]['object_id'])){ $id_obj=$weblizar_data_post["posts"]['data'][$y]['object_id']; }  ?> 	
						  <?php  if($weblizar_data_post["posts"]['data'][$y]['type']=='photo')
                                 { ?>
							      <div class="col-md-12 weblizar_fb-post-box weblizar_fb-bg custom_box_gallary weblizar_fb-post_background_color "> 
							        <!-- post auther display -->
								   <div class=" col-md-12 box weblizar_fb-post_background_color">
								   
									<?php // auther fetch curl
									   $data_var=explode("_",$weblizar_data_post["posts"]['data'][$y]['id']);
									   $auther_url="https://graph.facebook.com/". $data_var[0]."?access_token=".$token."&fields=link,name,id";
									   $auther_uri = file_get_contents($auther_url);
									   $auher1 = json_decode($auther_uri,true);?>  
                                       <!-- author picture-->										   
									   <div class="col-md-2 col-sm-2 col-xs-3 weblizar_fb-logo-left"><img src="https://graph.facebook.com/<?php echo $auher1['id'];?>/picture" class="img-responsive fb_logo_left" alt="img"></div>
										 <div class="col-md-10 col-sm-10 col-xs-9 weblizar_fb-logo-right">
										 <!-- author name-->
											<h5 class="weblizar_fb-post_font_color"><a class="weblizar_fb-athr-name" href="<?php echo $auher1["link"];?>"><?php  echo  $auher1['name']; ?></a></h5>
									       <!-- post create time-->
										   <h6 class="weblizar_fb-post_font_color fb_create_time"><?php $s = strtotime($weblizar_data_post["posts"]['data'][$y]['created_time']); ?><i class="fa fa-clock-o"></i><span class="fb_date_size"><?php printf( _x( '%s Ago', '%s = human-readable time difference', WEBLIZAR_FACEBOOK_TEXT_DOMAIN), human_time_diff( date( 'U', $s), current_time( 'timestamp' ) ) ); ?><?php //echo date_i18n('F jS, g:i a',$s);?></span> </h6>
										 </div>
							        </div>
							    
						     <?php if(isset($weblizar_data_post["posts"]['data'][$y]['message'])) //post text message
						            {
								   $text=$weblizar_data_post["posts"]['data'][$y]['message'];
								   $reg_exUrl = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
								    if(preg_match($reg_exUrl, $text, $url)) 
								     {
                                     // make the urls hyper links
                                      $text=preg_replace($reg_exUrl, "<a class='fb_messaage_tag' href='".$url[0]."' target='_blank'>".$url[0]."</a> ", $text);
                                      }
									  // fetch url tag from post  message
								     if(isset($weblizar_data_post["posts"]['data'][$y]['message_tags']))
								     {
									  //count url tag from post message 
									   $tag_size=sizeof($weblizar_data_post["posts"]['data'][$y]['message_tags']);
									   //fetch tag from  post message
									   for($t=0;$t<$tag_size;$t++)
									   {
									   $ar=$weblizar_data_post["posts"]['data'][$y]['message_tags'][$t]["name"]; 
									   $br=$weblizar_data_post["posts"]['data'][$y]['message_tags'][$t]["id"];
									   $text=str_replace($weblizar_data_post["posts"]['data'][$y]['message_tags'][$t]["name"],"<a class='fb_messaage_tag' href='http://facebook.com/".$br."' target='_blank' >".$ar."</a>",$text);
									  }
								   }
								   ?>
									<div class="col-md-12 col-sm-12 col-xs-12">
									  <p class="text weblizar_fb-post_font_color">
									 <?php if (strlen(strip_tags($text)) >=50)   // post message display
									 { ?>
										<span class="weblizar_fb_teaser weblizar_fb-post_font_color"><?php echo substr(strip_tags($text),0,50);?></span>
										<span class="weblizar_fb_complete weblizar_fb-post_font_color" style="display:none"><?php echo $text;?></span>
										<span data-text="...show less" class="facebook_feed_more_page"><?php _e('...See more', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></span>
								<?php } else { ?> <span class="weblizar_fb-post_font_color"> <?php echo $text;?></span> <?php }  ?>
									  </p>
									</div>  
					        <?php } ?>
							    <!--post image data display start-->
				           <?php if(isset($weblizar_data_post["posts"]['data'][$y]['full_picture'])) 
						         { ?>
						       <div class="col-md-12 col-sm-12 col-xs-12 post-img animated gallery-img">
								 <a href="#">
								  <span class="weblizar_span_img">
									 <img  src="<?php echo $weblizar_data_post["posts"]['data'][$y]['full_picture'];?>" class="img-responsive" >
								  </span>
							     </a>
								  <dialog style="display:none" id="box_<?php echo $weblizar_data_post["posts"]['data'][$y]['id']; ?>" data-id="<?php  echo $weblizar_data_post["posts"]['data'][$y]['id']; ?>" feed-type="post" feed-post="<?php echo $weblizar_data_post["posts"]['data'][$y]['type'];?>"  >
					                 <div class="inner_box_<?php  echo $weblizar_data_post["posts"]['data'][$y]['id']; ?>"></div>
					               </dialog>
							   </div>
						 <?php   }  // post image data display end ?>
						             
								   <div class="col-md-12 col-sm-12 col-xs-12 bar weblizar_fb-post_background_color">
									 <?php //comment data assign to variable
							        if(isset($weblizar_data_post["posts"]['data'][$y]['comments']['data'])) { $comment=$weblizar_data_post["posts"]['data'][$y]['comments']['data']; } else {$comment="";} 
									//post share data assign to variable
									if(isset($weblizar_data_post["posts"]['data'][$y]['shares']))  { $share=$weblizar_data_post["posts"]['data'][$y]['shares']; } else {$share="";} 
									//post id data assign to variable
									if(isset($weblizar_data_post["posts"]['data'][$y]['id']))  { $id=$weblizar_data_post["posts"]['data'][$y]['id']; } else {$id="";} ?> 	
									  <div class=" weblizar_fb-bar-left">
								 	    <!-- like count display -->
										  <!-- like count display -->
										  <span class="like weblizar_fb-post_font_color"><i class="fa fa-thumbs-o-up"></i><?php echo $weblizar_data_post["posts"]['data'][$y]['reactions_like']['summary']['total_count'];?></span>
										   <!-- post share count display -->
										  <span class="share weblizar_fb-post_font_color"><a class="weblizar_fb-post_font_color" href="https://www.facebook.com/sharer/sharer.php?u=<?php if(isset($weblizar_data_post["posts"]['data'][$y]['link'])) { echo $weblizar_data_post["posts"]['data'][$y]['link'];} else { echo "#";}?>" target="_blank"><i class="fa fa-share"></i><?php  if($share !="") { echo $share['count']; } else { echo "0";} ?></a></span>
										  <!-- post view on facebook display -->
										  <span class="share weblizar_fb-post_font_color"><a href="https://www.facebook.com/<?php echo $id;?>" target="_blank" class="weblizar_fb-post_font_color"><i class="fa fa-eye"></i></a></span>
										   <!-- post comment count display -->
										  <span class="weblizar_comment accordion weblizar_fb-post_font_color"><i class="fa fa-comments-o"></i><?php if(isset($weblizar_data_post["posts"]['data'][$y]['comments']['summary']['total_count'])) {  echo $weblizar_data_post["posts"]['data'][$y]['comments']['summary']['total_count']; }?></span>
											
											<!--panel -->
											<div class="panel clearfix">
											  <!--like reaction start -->
											  <p class="weblizar_fb-post-comment-likes weblizar_fb-post_font_color">
												<span class="weblizar_fb-post-reactions weblizar_fb-post_font_color">
													<!-- like count display -->
													<span class="post-like weblizar_fb-post_font_color"><i class="fa fa-thumbs-o-up"></i><?php if(isset($weblizar_data_post["posts"]['data'][$y]['reactions_like']['summary']['total_count'])) { echo $weblizar_data_post["posts"]['data'][$y]['reactions_like']['summary']['total_count']; }?></span>
													<!-- love count display -->
													<span class="post-love weblizar_fb-post_font_color"><i class="fa fa-heart"></i><?php if(isset($weblizar_data_post["posts"]['data'][$y]['reactions_love']['summary']['total_count'])) { echo $weblizar_data_post["posts"]['data'][$y]['reactions_love']['summary']['total_count']; } ?></span>
													<!-- haha count display -->
													<span class="post-wow weblizar_fb-post_font_color"><i class="fa fa-smile-o"></i><?php if(isset($weblizar_data_post["posts"]['data'][$y]['reactions_haha']['summary']['total_count'])) { echo $weblizar_data_post["posts"]['data'][$y]['reactions_haha']['summary']['total_count']; }?></span>
												</span>		
												<span class="post-likes-text weblizar_fb-post_font_color">
													<?php _e('people reacted to this', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
												</span>					
										      </p>
											  <!--end-like-comments-reaction -->
											  
											  <p class="weblizar_fb-post-comments weblizar_fb-post_font_color" style="color:#;">
												 <i class="fa fa-comments-o"></i>  <?php _e('Comment on Facebook', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
											  </p>
											  
											  <!--comments-box-start -->
											  <?php if($comment !="")
													 {
													  $comments=sizeof($comment);
													   for($c=0;$c < $comments; $c++)
														{ ?>
														  <div class="col-md-12 col-sm-12 col-xs-12 weblizar_fb-post-comment">
														   <!--comment curl url -->
														  <?php $c_auther_url="https://graph.facebook.com/".$comment[$c]['id']."?access_token=".$token."";
																$c_auther_uri = file_get_contents($c_auther_url);
																$c_auher1 = json_decode($c_auther_uri,true);
																if(isset($c_auher1['from']['id'])) 
																{ ?>
															     <!--comment author image -->
																<div class="col-md-2 col-sm-2 col-xs-2 weblizar_fb-post-comment-img">
																  <a href="<?php echo "https://graph.facebook.com/".$c_auher1['from']['id'];?>"><img src="https://graph.facebook.com/<?php echo $c_auher1['from']['id'];?>/picture" class="img-responsive" /></a>
																</div>
															<?php } ?>
															 <!--comment author data -->
															<div class="col-md-10 col-sm-10 col-xs-10 weblizar_fb-post-comment-text-wrapper">
																<div class="weblizar_fb-post-comment-text">
																	<p class="weblizar_fb-post_font_color"> 
																	 <!--comment author name -->
															 <?php if(isset($c_auher1['from']['id'])) 
															       { ?>
																	<a class="weblizar_fb-post_font_color" href="<?php echo $c_auher1['from']['id'];?>"><?php echo $c_auher1['from']['name'];?></a>
															  <?php } 
															  
																	//comment message
																	echo $comment[$c]['message'];?>
																	</p>
																	<span class="weblizar_fb-post_font_color"><?php _e('Posted:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?> <?php $s = strtotime($comment[$c]['created_time']);printf( _x( '%s Ago', '%s = human-readable time difference', WEBLIZAR_FACEBOOK_TEXT_DOMAIN), human_time_diff( date( 'U', $s), current_time( 'timestamp' ) ) ); ?></span>
																	<!--comment reply count-->
																	<p class="weblizar_fb-post_font_color">
																		<a href="" class="weblizar_fb-post_font_color"><i class="fa fa-reply"></i> <?php  if(isset($comment[$c]['comment_count'])) {echo $comment[$c]['comment_count'];} else { echo "0";}?><?php _e('Replies', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a>
																	</p>
																
																	<div class="weblizar_fb-post-comment-replies-box"></div>
																</div>
															</div>						
														  </div>
											   <?php }
											   } ?>
											  <!--comments-box-end-->
											 </div>
											<!--end-panel -->
										</div>
								  </div>
							</div>
					     <?php }
						   } ?>
	
                        </div>
                    <div class="clearfix"> </div>
                 </div>		
		<?php }
	catch (Exception $e) 
		{
			echo "<h3>Error in processing</h3>";
	    }  
		echo $args['after_widget'];
		  }

	 /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
	 public function form( $instance )
	 {

	 	if ( isset( $instance[ 'Title' ] ) )
	 	{
	 		$Title = $instance[ 'Title' ];
	 	} else
	 	{
	 		$Title = "Facebook Feed";
	 	}

	 	if ( isset( $instance[ 'Shortcode' ] ) )
	 	{
	 		$Shortcode = $instance[ 'Shortcode' ];
	 	} else
	 	{
	 		$Shortcode = "Select Any Facebook Feed Shortcode";
	 	}
	 	?>
	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'Title' ); ?>"><?php _e( 'Widget Title', 'wl_facebook' ); ?></label>
	 		<input class="widefat" id="<?php echo $this->get_field_id( 'Title' ); ?>" name="<?php echo $this->get_field_name( 'Title' ); ?>" type="text" value="<?php echo esc_attr( $Title ); ?>">
	 	</p>
<?php
}
public  function update( $new_instance, $old_instance )
{
	$instance = array();
	$instance['Title'] = ( ! empty( $new_instance['Title'] ) ) ? strip_tags( $new_instance['Title'] ) : '';
	return $instance;
}
	} 


	add_action( 'widgets_init', 'register_Weblizar_facebook_feed_widget' );
	function register_Weblizar_facebook_feed_widget()
	{
		register_widget( 'Weblizar_facebook_feed_widget' );
	}
?>