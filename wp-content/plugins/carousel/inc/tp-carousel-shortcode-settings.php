<?php
if ( ! defined( 'ABSPATH' ) )

	die("Can't load this file directly");	

//load options Array
$csp_free_options = get_option('csp_free_options');


$cspunique_id = strtotime("now");
$output = '[cspscode id="' . $cspunique_id . '" ';
?>
<style>
.widefat th { font-family:Arial, Helvetica, sans-serif; font-size:12px; }
small { font-size:11px; }
</style>

<div class="wrap csp-admin" style="padding:0px; margin-top:20px;">

    <h2><?php _e('Shortcode Option Settings', 'carosuelfree'); ?></h2>
    <?php if ( isset($_REQUEST['settings-updated']) ) echo '<div id="message" class="updated fade"><p><strong>' . 'Shortcode' . '&nbsp;-&nbsp;' . __('Saved.','carosuelfree') . '</strong></p></div>' ?>

    <form method="post" action="options.php" id="form_shortcode">
        <?php settings_fields( 'csp-free-options-settings' ); ?>

        <table class="wp-list-table widefat posts">
			<tr valign="top" class="widefat" style="border-top:#999 solid 2px;">
            <th scope="row"><?php _e('Select Post Type', 'carosuelfree') ?></th>
            <td>
            <select style="width:200px;" name="csp_free_options[CspCarouselCategory]" id="field_CspCarouselCategory">
                <option value="tpmfcarouselcat" <?php if($csp_free_options['CspCarouselCategory'] == "tpmfcarouselcat") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Carousel', 'carosuelfree'); ?></option>
                <option value="default_cat" <?php if($csp_free_options['CspCarouselCategory'] == "default_cat") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Post', 'carosuelfree') ?></option>
            </select>

            <input type="submit" name="save" class="button-secondary" value="<?php _e('Click to update the categories', 'carosuelfree'); ?>" />
          
            <!-- <a href="#" id="update_categories">Update</a> -->
            </td>
            </tr>

            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Choose the Categories', 'carosuelfree'); ?></th>
            <td>

			<?php 

			if ($csp_free_options['CspCarouselCategory']  == 'tpmfcarouselcat') {	
				$CsptaxName = "tpmfcarouselcat";
				$CsppostTypeName = "tpmfcarousel";
			} else {
				$CsptaxName = "category";
				$CsppostTypeName = "post";
			}

			if ($CsptaxName == "tpmfcarouselcat") {
				$categories = get_terms($CsptaxName);
			} else {
				$categories = get_categories();
			}

			$count = count($categories); 
			$i=0;
			//print_r($categories);

			if ( $count > 0 ) {

				$output .= 'categories="';
				$tmp = '';

				foreach ($categories as $cat) {

					$option = '<input type="checkbox" name="csp_free_options[cspCategories][]"';

					if (isset($csp_free_options['cspCategories'])) {
						foreach ($csp_free_options['cspCategories'] as $cats) {
							if($cats == $cat->slug) { //$cat->term_id
								$option = $option.' checked="checked"';
								$tmp .= $cat->slug . ',';
							}
						}
					}
					$option .= ' value="' . $cat->slug . '" />';
					$option .= '&nbsp;' . $cat->name . '&nbsp;<br>';

					echo $option;
					$i++;
				}//foreach

				$output .= substr_replace($tmp ,"",-1);
				$output .= '"';
			} else {
				echo "Category not found.";
			}	

			?>
            </td>

			<!-- Post Type -->
            <tr style="display:none;" valign="top" class="widefat">
            <th scope="row"><?php _e('Post Type', 'carosuelfree'); ?></th>
            <td><input type="text" name="csp_free_options[tpmfcarousel]" value="<?php echo $CsppostTypeName; ?>" size="10" disabled="disabled" style="color:#888; background:#f9f9f9;" />&nbsp;</br><small><?php _e('Carousel or post', 'carosuelfree'); ?></small></td>
            </tr>

            <?php $output .= ' post_type="' . $CsppostTypeName . '" ';	?>

			<!-- Carousel / Post Style -->
            <tr valign="top" class="widefat">
				<th scope="row"><?php _e('Select Carousel / Post Style', 'carosuelfree'); ?></th>
				<td>
					<select style="width:200px;" name="csp_free_options[post_styles]">
						<option value="default" <?php if($csp_free_options['post_styles'] == "default") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Post default', 'carosuelfree') ?></option>
						<option value="style1" <?php if($csp_free_options['post_styles'] == "style1") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Post style1', 'carosuelfree') ?></option>
						<option disabled value="style2" <?php if($csp_free_options['post_styles'] == "style2") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Post style2 (Only Pro)', 'carosuelfree') ?></option>
						<option disabled value="style3" <?php if($csp_free_options['post_styles'] == "style3") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Post style3 (Only Pro)', 'carosuelfree') ?></option>
						<option disabled value="style4" <?php if($csp_free_options['post_styles'] == "style4") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Post style4 (Only Pro)', 'carosuelfree') ?></option>
						<option value="style5" <?php if($csp_free_options['post_styles'] == "style5") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Default', 'carosuelfree') ?></option>
						<option value="style6" <?php if($csp_free_options['post_styles'] == "style6") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Style 6', 'carosuelfree') ?></option>
						<option value="style7" <?php if($csp_free_options['post_styles'] == "style7") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Slider 7', 'carosuelfree') ?></option>
						<option disabled value="style8" <?php if($csp_free_options['post_styles'] == "style8") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Style 8 (Only Pro)', 'carosuelfree') ?></option>
						<option disabled value="style9" <?php if($csp_free_options['post_styles'] == "style9") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Style 9 (Only Pro)', 'carosuelfree') ?></option>
						<option disabled value="style10" <?php if($csp_free_options['post_styles'] == "style10") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Carousel Style 10 (Only Pro)', 'carosuelfree') ?></option>
					</select>
				</td>
            </tr>

            <?php $output .= 'post_styles="' . $csp_free_options['post_styles'] . '" '; ?>
			
			
			<!-- Items per slide -->
            <tr valign="top" class="widefat">
				<th scope="row"><?php _e('Items Per Slide', 'carosuelfree'); ?></th>
				<td>
					<select style="width:200px;" name="csp_free_options[slide_items]">
						<option value="3" <?php if($csp_free_options['slide_items'] == "3") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('default', 'carosuelfree') ?></option>
						<option value="1" <?php if($csp_free_options['slide_items'] == "1") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Item 1', 'carosuelfree') ?></option>
						<option value="2" <?php if($csp_free_options['slide_items'] == "2") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Item 2', 'carosuelfree') ?></option>
						<option value="3" <?php if($csp_free_options['slide_items'] == "3") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Item 3', 'carosuelfree') ?></option>
						<option value="4" <?php if($csp_free_options['slide_items'] == "4") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Item 4', 'carosuelfree') ?></option>
						<option disabled value="5" <?php if($csp_free_options['slide_items'] == "5") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Unlimited Items (Only Pro)', 'carosuelfree') ?></option>
					</select>
					</br><small><?php _e('Show how many Carousel / posts items display per slider.', 'carosuelfree') ?></small>
				</td>
				
            </tr>

            <?php $output .= 'slide_items="' . $csp_free_options['slide_items'] . '" '; ?>
			
			
			
			<!-- Auto Play -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Auto Play', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[autoplay]">
                <option value="true" <?php if($csp_free_options['autoplay'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('True', 'carosuelfree') ?></option>
                <option value="false" <?php if($csp_free_options['autoplay'] == "false") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('False', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'autoplay="' . $csp_free_options['autoplay'] . '" '; ?>

			<!-- Auto Play Stop Hover -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Auto Play Stop Hover', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[autoplay_stop]">
                <option value="true" <?php if($csp_free_options['autoplay_stop'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('True', 'carosuelfree') ?></option>
                <option value="false" <?php if($csp_free_options['autoplay_stop'] == "false") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('False', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'autoplay_stop="' . $csp_free_options['autoplay_stop'] . '" '; ?>
			
			<!-- Post Excerpt Length -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Slider Speed', 'carosuelfree') ?></th>
            <td><input disabled type="text" name="csp_free_options[csp_slider_speed]" value="<?php echo $csp_free_options['csp_slider_speed']; ?>" size="8" />&nbsp;</br><small><?php _e('Slider Speed Option Only Available In Premium Version', 'carosuelfree') ?></small></td>
            </tr>

            <?php $output .= 'slider_speed="' . $csp_free_options['csp_slider_speed'] . '" '; ?>
			
			<!-- Post Excerpt Length -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Post Excerpt Length', 'carosuelfree') ?></th>
            <td><input type="text" name="csp_free_options[textlength]" value="<?php echo $csp_free_options['textlength']; ?>" size="8" />&nbsp;</br><small><?php _e('Default length is 50 words but sometimes 50 words aren’t enough to give a meaningful gist to the content. In such scenarios, you should change it to give a better idea to the readers about the post’s content..', 'carosuelfree') ?></small></td>
            </tr>

            <?php $output .= 'textlength="' . $csp_free_options['textlength'] . '" '; ?>
			
			<!-- Show Pagination -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Show Pagination', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[showpaginationbtn]">
                <option value="true" <?php if($csp_free_options['showpaginationbtn'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('True', 'carosuelfree') ?></option>
                <option value="false" <?php if($csp_free_options['showpaginationbtn'] == "false") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('False', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'showpaginationbtn="' . $csp_free_options['showpaginationbtn'] . '" '; ?>

			<!-- Show Navigation -->
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Show Navigation', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[shownavigationbtn]">
                <option value="true" <?php if($csp_free_options['shownavigationbtn'] == "true") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('True', 'carosuelfree') ?></option>
                <option value="false" <?php if($csp_free_options['shownavigationbtn'] == "false") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('False', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'shownavigationbtn="' . $csp_free_options['shownavigationbtn'] . '" '; ?>
			

            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Align Pagination', 'carosuelfree') ?></th>
            <td>
			<!-- Aling Pagination -->
            <select style="width:100px;" name="csp_free_options[CarouselAlignPagination]">
                <option value="left" <?php if($csp_free_options['CarouselAlignPagination'] == "left") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('left', 'carosuelfree') ?></option>
                <option value="center" <?php if($csp_free_options['CarouselAlignPagination'] == "center") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('center', 'carosuelfree') ?></option>
                <option value="right" <?php if($csp_free_options['CarouselAlignPagination'] == "right") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('right', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'align_pagination="' . $csp_free_options['CarouselAlignPagination'] . '" '; ?>

			<!-- Pagination Style -->			
            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Pagination Style', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[CarouselPaginationStyle]">
                <option value="default" <?php if($csp_free_options['CarouselPaginationStyle'] == "default") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Default', 'carosuelfree') ?></option>
                <option disabled value="square" <?php if($csp_free_options['CarouselPaginationStyle'] == "square") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Square', 'carosuelfree') ?></option>
                <option disabled value="round" <?php if($csp_free_options['CarouselPaginationStyle'] == "round") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Round', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'pagination_style="' . $csp_free_options['CarouselPaginationStyle'] . '" '; ?>


            <tr valign="top" class="widefat">
            <th scope="row"><?php _e('Thumbnail Size', 'carosuelfree') ?></th>
            <td>
            <input type="text" name="csp_free_options[carouselThumbHeight]" value="<?php echo $csp_free_options['carouselThumbHeight']; ?>" size="8" maxlength="4" />&nbsp;</br><small><?php _e('Image height (in pixels). use 170 default height', 'carosuelfree') ?></small>
            </td>
            </tr>

            <?php $output .= 'image_height="' . $csp_free_options['carouselThumbHeight'] . '" '; ?>



            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Show/Hide Title', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[CarouselShowTitle]">
                <option value="block" <?php if($csp_free_options['CarouselShowTitle'] == "block") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Show', 'carosuelfree') ?></option>
                <option value="none" <?php if($csp_free_options['CarouselShowTitle'] == "none") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Hide', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'show_title="' . $csp_free_options['CarouselShowTitle'] . '" '; ?>



            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Title Size', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[CarouselTitleSize]">
                <option value="15" <?php if($csp_free_options['CarouselTitleSize'] == "15") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Default', 'carosuelfree') ?></option>
                <option value="13" <?php if($csp_free_options['CarouselTitleSize'] == "13") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('13 px', 'carosuelfree') ?></option>
                <option value="14" <?php if($csp_free_options['CarouselTitleSize'] == "14") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('14 px', 'carosuelfree') ?></option>
                <option value="15" <?php if($csp_free_options['CarouselTitleSize'] == "15") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('15 Px', 'carosuelfree') ?></option>
                <option value="16" <?php if($csp_free_options['CarouselTitleSize'] == "16") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('16 Px', 'carosuelfree') ?></option>
                <option value="17" <?php if($csp_free_options['CarouselTitleSize'] == "17") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('17 Px', 'carosuelfree') ?></option>
                <option value="18" <?php if($csp_free_options['CarouselTitleSize'] == "18") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('18 Px', 'carosuelfree') ?></option>
                <option value="19" <?php if($csp_free_options['CarouselTitleSize'] == "19") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('19 Px', 'carosuelfree') ?></option>
                <option value="20" <?php if($csp_free_options['CarouselTitleSize'] == "20") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('20 Px', 'carosuelfree') ?></option>
                <option value="21" <?php if($csp_free_options['CarouselTitleSize'] == "21") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('21 Px', 'carosuelfree') ?></option>
                <option value="22" <?php if($csp_free_options['CarouselTitleSize'] == "22") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('22 Px', 'carosuelfree') ?></option>
                <option value="23" <?php if($csp_free_options['CarouselTitleSize'] == "23") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('23 Px', 'carosuelfree') ?></option>
                <option value="24" <?php if($csp_free_options['CarouselTitleSize'] == "24") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('24 Px', 'carosuelfree') ?></option>
                <option value="25" <?php if($csp_free_options['CarouselTitleSize'] == "25") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('25 Px', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'titlefont_size="' . $csp_free_options['CarouselTitleSize'] . '" '; ?>

            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Title Text Align', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[CarouselTitleAlign]">
                <option value="left" <?php if($csp_free_options['CarouselTitleAlign'] == "left") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Left', 'carosuelfree') ?></option>
                <option value="center" <?php if($csp_free_options['CarouselTitleAlign'] == "center") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Center', 'carosuelfree') ?></option>
                <option value="right" <?php if($csp_free_options['CarouselTitleAlign'] == "right") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Right', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'titletext_align="' . $csp_free_options['CarouselTitleAlign'] . '" '; ?>
			

            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Show/Hide Read More', 'carosuelfree') ?></th>
            <td>
            <select style="width:100px;" name="csp_free_options[CarouselShowReadmore]">
                <option value="block" <?php if($csp_free_options['CarouselShowReadmore'] == "block") { echo "selected='selected'"; } else { echo ""; } ?>><?php _e('Show', 'carosuelfree') ?></option>
                <option value="none" <?php if($csp_free_options['CarouselShowReadmore'] == "none") { echo "selected='selected'"; } else { echo ""; }?>><?php _e('Hide', 'carosuelfree') ?></option>
            </select>
            </td>
            </tr>

            <?php $output .= 'show_readmore="' . $csp_free_options['CarouselShowReadmore'] . '" '; ?>
			
			
            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Title Color', 'carosuelfree') ?></th>
            <td><input class='custom-accordion-columns-bg-color' id="custom-accordion-columns-bg-color" type="text" name="csp_free_options[cspTitleStyles][2]" value="<?php echo $csp_free_options['cspTitleStyles'][2]; ?>"/>&nbsp;<small><?php _e('Hexadecimal. Example: 006D9F', 'carosuelfree') ?></small></td>
            </tr>
			
			<?php $output .= 'title_color="' . $csp_free_options['cspTitleStyles'][2] . '" '; ?>
			
			
            <tr valign="top" class="widefat alternate">
            <th scope="row"><?php _e('Title Hover Color', 'carosuelfree') ?></th>
            <td><input class='custom-title-hover-color' id="custom-title-hover-color" type="text" name="csp_free_options[cspTitleStylehover][3]" value="<?php echo $csp_free_options['cspTitleStylehover'][3]; ?>"/>&nbsp;<small><?php _e('Hexadecimal. Example: 006D9F', 'carosuelfree'); ?></small></td>
            </tr>
			
			<?php $output .= 'titlehover_color="' . $csp_free_options['cspTitleStylehover'][3] . '" '; ?>
			
			
			<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#custom-accordion-columns-bg-color, #custom-title-hover-color').each( function() {
					//
					// Dear reader, it's actually very easy to initialize MiniColors. For example:
					//
					//  $(selector).minicolors();
					//
					// The way I've done it below is just for the demo, so don't get confused
					// by it. Also, data- attributes aren't supported at this time...they're
					// only used for this demo.
					//
					$(this).minicolors({
						control: $(this).attr('data-control') || 'hue',
						defaultValue: $(this).attr('data-defaultValue') || '',
						format: $(this).attr('data-format') || 'hex',
						keywords: $(this).attr('data-keywords') || '',
						inline: $(this).attr('data-inline') === 'true',
						letterCase: $(this).attr('data-letterCase') || 'lowercase',
						opacity: $(this).attr('data-opacity'),
						position: $(this).attr('data-position') || 'bottom left',
						swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
						change: function(value, opacity) {
							if( !value ) return;
							if( opacity ) value += ', ' + opacity;
							if( typeof console === 'object' ) {
								console.log(value);
							}
						},
						theme: 'bootstrap'
					});

				});
			});
			</script>

			
			
			
            <?php
				$output .= ']';
            ?>

            </td>
            </tr>
        </table>

        <br><br>
        <strong><?php _e('Note:- Save Changes. After copy and paste the shortcode below in a Post, Page or Widget (Text)', 'carosuelfree'); ?>
        </strong>
        <br><br>

		<textarea name="csp_free_options[cspscode]" cols="123" rows="7"><?php echo $output; ?></textarea>

        <p class="submit">
			<input type="submit" name="save" class="button-primary" value="<?php _e('Save Changes', 'carosuelfree'); ?>" />
			<input type="hidden" name="update_taxonomy" value="0" />
        </p>

    </form>
</div>
	
