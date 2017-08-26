jQuery(function($){
 
      $('#images-optimizer__area , #postimagediv').removeClass('closed');

      var fontSize = jQuery('#ab-font').val();
	  
      // Set all variables to be used in scope
      var popup = $('#__wp-uploader-id-ab-popup') ,
	  closePopupButton = $('.media-modal-close') ,
      addImgLink = $('.api_suggestion'),
	  submitButton =  $('.ab-media-button-insert');

	  // Show popup
	  addImgLink.on( 'click', function(event){
		  
		var fontFamily = jQuery('#ab-font-family').val();  
 
		if( $(this).attr('data-id') == 'ab_featured_image' ){
			$('.giphy-alpha').removeClass('giphy-enabled');
			$('.giphy-featured').addClass('giphy-enabled');
		}
		
		if( $(this).attr('data-id') == 'ab_test_image' ){
			$('.giphy-featured').removeClass('giphy-enabled');
			$('.giphy-alpha').addClass('giphy-enabled');
		}

	  
	    var postTitle = $('input[name="post_title"]').val();
	  
	    $('.giphy').show();
		$('.auto-generated').hide();
		
		$('#giphy').addClass('active');
		$('#auto-generated').removeClass('active');
	  	  
		drawCanvasToDisplay( postTitle , (parseInt(fontSize)/4) , fontFamily );
	
		$('#ab-current-tab').val( $(this).attr('data-id') );

		popup.show();
		
		$('.loader').show();
		
		var data = {
			'action' : 'get_api_images',
			'title'  : postTitle
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			
			parseRespose = JSON.parse(response);
			var content = parseRespose.data;
			var output = '';
			
			if( content.length > 0 ){	
				for( i=0; i<content.length; i++ ){
					output += '<li tabindex="0" role="checkbox" aria-label="'+content[i].slug+'" aria-checked="false" data-id="'+content[i].id+'" class="attachment save-ready"><div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape"><div class="thumbnail"><div class="centered"><img src="'+content[i].images.original.url+'" draggable="false" alt=""></div></div></div><button type="button" class="button-link check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button></li>';
				}	
			}else{
				output = '<h1 style="color: #c7c7c7; text-align:center; padding:7% 0;">No Results, Please <a href="javascript:void(0);" onclick="searchAgainForImages()" style="text-decoration:none;">search again.</a></h1>';
			}
			$('#add-grid-image-panel').html(output);
			$('.loader').hide();

		}).fail(function(response){
			alert('Oops something went wrong');
			$('.loader').hide();
		});
		
		event.preventDefault();
		
	  });
	  	  
	 // hide popup 	  
	  closePopupButton.on( 'click', function(){
		popup.hide();
	  }); 
	  
	  // on submi button
	  submitButton.on( 'click', function(){
		
		$('.loader').show();

		var imgSrc = $('#add-grid-image-panel-top li.selected.details img').attr('src');  

		var data = {
			'action'    : 'ab_save_image_locally',
			'imageUrl'  :  imgSrc
		};

		$.post(ajaxurl, data, function(response) {
			
			var image = JSON.parse(response);
			
			if( image.url != '' ){
				if( $('#ab-current-tab').val() == 'ab_featured_image' ){
					$('#set-post-thumbnail').html('<img src="'+image.url+'">');
					$('#_thumbnail_id').val(image.id);
				}
				if( $('#ab-current-tab').val() == 'ab_test_image' ){
					$('#images-optimizer__alpha_image_id').val(image.url);
					$('.alpha_second_image').css('display', 'block'); 
					$('.alpha_second_image').attr('src', ''); 
					$('.alpha_second_image').attr('src', image.url);
				}
			}  
			
			popup.hide();
			
			$('.loader').hide();
			
		}).fail(function(response){
			alert('Oops something went wrong');
			$('.loader').hide();
		});
		
		$('#images-optimizer__area , #postimagediv').removeClass('closed');
		
	  });

      // On image select
      $('#add-grid-image-panel-top li').live( 'click' , function(){	
        var postTitle = $('input[name="post_title"]').val();
		var fontSize = jQuery('#ab-font').val();
		var fontFamily = jQuery('#ab-font-family').val();
		submitButton.removeAttr('disabled');
		$('.canvas li').removeClass('selected details');
		$(this).addClass('selected details');
		if( $(this).children('img').length > 0 ){
			var canvasSelectedId = $(this).attr('id').replace('ab-canvas-item-container-',''); 
			var canvasTemp = document.getElementById( 'ab-featured-canvas-iamge-'+canvasSelectedId );  
			
			var ctx = canvasTemp.getContext("2d");  

			drawImageOnCanvas(  "ab-featured-canvas-image-full" , fontSize+"px "+fontFamily , canvasTemp.style.backgroundColor , postTitle  , 1150 , 50 , 630 , 1200);
			
			var canvasNew = document.getElementById( 'ab-featured-canvas-image-full' );
			
			if (canvasNew.getContext) {
				 var ctx = canvasNew.getContext("2d");                
				 var drawedImage = canvasNew.toDataURL("image/png");      
			}
			$('.ab-featured-canvas-iamge-'+canvasSelectedId).attr( 'src' , drawedImage ); 
			$('.giphy-enabled').val('0');
		}else{
			$('.giphy-enabled').val('1');
		} 
		
     });
	 
	 
	 $('.image-optimizer-tab a').click(function(){
		 
		submitButton.attr('disabled',true);
		
		$('.image-optimizer-tab a').removeClass('active');
		$(this).addClass('active');
	 
		if( $(this).attr('id') == 'auto-generated' ){
			$('.giphy').hide();
			$('.auto-generated').show();
		}else{
			$('.auto-generated').hide();
			$('.giphy').show();
		}
	 
     });
	 
	 
	 $('#ab-font-family').change(function(){
		var postTitle = $('input[name="post_title"]').val();
		var fontSize = jQuery('#ab-font').val();
		var fontFamily = jQuery('#ab-font-family').val();
		drawImageOnCanvas(  "ab-featured-canvas-image-full" , fontSize+"px "+fontFamily , '#00000', postTitle  , 1150 , 50 , 630 , 1200);
	 });	  
	 
	 // update on font change
	 $('#ab-update-canvas').click(function(){
		$('.loader').show();
		var fontFamily = $('#ab-font-family').val();
		var fontSize = $('#ab-font').val();
		var postTitle = $('input[name="post_title"]').val();
		drawCanvasToDisplay( postTitle , parseInt(fontSize)/4 , fontFamily );	
		$('.loader').hide();
	 });
	 
   
});


function drawCanvasToDisplay( postTitle , fontSize , fontFamily ){
	var canvasSuffix = 'ab-featured-canvas-iamge-';
	var font = fontSize+"px "+fontFamily;
	
	// Various backgrounds
	var backgroundColor = [ '#2EA1D9' , '#FA4659' , '#9660DE' , '#00032D' , '#FEFFE4' , '#F7825D' , '#2EB872' , '#FF347F' ];
	var maxWidth = 250;
	var lineHeight = 12;
	
	// Draw image on canvas
	for( var i=0; i< backgroundColor.length; i++ ){
		var canvasId = canvasSuffix+i;
		var canvasTemp = document.getElementById( canvasId );
		
		// Calculate Width and Height of canvas
		h=parseInt(canvasTemp.getAttribute("height"));
		w=parseInt(canvasTemp.getAttribute("width"));
		drawImageOnCanvas(  canvasId , font , backgroundColor[i] , postTitle  , maxWidth , lineHeight , h , w);
	}  
}



// word wrap canvas
function wrapText(context, text, x, y, maxWidth, lineHeight) {
 
	var words = text.split(' ');
	var line = '';
	for(var n = 0; n < words.length; n++) {
	  var testLine = line + words[n] + ' ';
	  var metrics = context.measureText(testLine);
	  var testWidth = metrics.width;
	  if (testWidth > maxWidth && n > 0) {
		context.fillText(line, x, y );
		line = words[n] + ' ';
		y += lineHeight;
	  }
	  else {
		line = testLine;
	  }
	}

	context.fillText(line, x , y ); 
	  
 }
 
 
function wrapTextVCentered( context , text , x , y , maxWidth , lineHeight){
	
	var lines = 0;
	var words = text.split(' ');
	var line = '';
	for(var n = 0; n < words.length; n++) {
	  var testLine = line + words[n] + ' ';
	  var metrics = context.measureText(testLine);
	  var testWidth = metrics.width;
	  if (testWidth > maxWidth && n > 0) {
		//context.fillText(line, x, y );
		line = words[n] + ' ';
		y += lineHeight;
	    lines++;
	  }
	  else {
		line = testLine;
	  }
	}	
	
  return(lines/2);
}

 
 // dra image on canvas
function drawImageOnCanvas( canvasId , fontfamily , background , text , maxWidth , lineHeight , h , w ){
	
	var align = "center"; 
	
	// Get canvas object
	var canvasTemp = document.getElementById( canvasId );

	var ctx = canvasTemp.getContext("2d");

	// Set Background
	ctx.fillStyle = background;
	document.getElementById( canvasId ).style.backgroundColor = background;
	ctx.fillRect(0,0,w,h); 
	
	ctx.textAlign = align;
	var fontColor = '#FFFFFF';
	
	if( background == '#FEFFE4' || background == 'rgb(254, 255, 228)' ){
		fontColor = '#000000';
	}
	
	// Set Text
	
	ctx.font = fontfamily;
	ctx.fillStyle = fontColor;
	
	lines = wrapTextVCentered( ctx , text, w/2 , h , maxWidth, lineHeight );
	wrapText( ctx , text, w/2 , (h/2) - (lines * lineHeight) , maxWidth, lineHeight );
	
}

// search again for api images
function searchAgainForImages(){
	jQuery('.api_suggestion').trigger('click');
}