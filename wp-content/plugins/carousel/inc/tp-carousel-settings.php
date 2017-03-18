<?php
if ( ! defined( 'ABSPATH' ) )

	die("Can't load this file directly");	

	/*----------------------------------------------------------------------
		Columns Declaration Function
	----------------------------------------------------------------------*/
	function csp_carousel_free_columns($csp_carousel_free_columns){
		
		$order='asc';
		
		if($_GET['order']=='asc') {
			$order='desc';
		}
		
		$csp_carousel_free_columns = array(
			"cb" => "<input type=\"checkbox\" />",
							
			"thumbnail" => __('Image', 'carosuelfree'),

			"title" => __('Name', 'carosuelfree'),
			
			"csp_carousel_catcols" => __('Categories', 'carosuelfree'),
			
			"date" => __('Date', 'carosuelfree'),

		);

		return $csp_carousel_free_columns;

	}
	
	/*----------------------------------------------------------------------
		testimonial Value Function
	----------------------------------------------------------------------*/
	function csp_carousel_free_columns_display($csp_carousel_free_columns, $post_id){
		
		global $post;
		
		$width = (int) 80;
		$height = (int) 80;
		
		if ( 'thumbnail' == $csp_carousel_free_columns ) {
			
			if ( has_post_thumbnail($post_id)) {
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				echo $thumb;
			}
			else 
			{
				echo __('None');
			}

		}
		
		if ( 'csp_carousel_catcols' == $csp_carousel_free_columns ) {
			
			$terms = get_the_terms( $post_id , 'tpmfcarouselcat');
			$count = count($terms);
			
			if ( $terms ){
				
				$i = 0;
				
				foreach ( $terms as $term ) {
					echo '<a href="'.admin_url( 'edit.php?post_type=tpmfcarousel&tpmfcarouselcat='.$term->slug ).'">'.$term->name.'</a>';	
					
					if($i+1 != $count) {
						echo " , ";
					}
					$i++;
				}
				
			}
		}
		
	}
	
	/*----------------------------------------------------------------------
		Add manage_tmls_posts_columns Filter 
	----------------------------------------------------------------------*/
	add_filter("manage_tpmfcarousel_posts_columns", "csp_carousel_free_columns");
	
	/*----------------------------------------------------------------------
		Add manage_tmls_posts_custom_column Action
	----------------------------------------------------------------------*/
	add_action("manage_tpmfcarousel_posts_custom_column",  "csp_carousel_free_columns_display", 10, 2 );	



?>