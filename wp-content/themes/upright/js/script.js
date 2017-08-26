
// as the page loads, call these scripts
jQuery(document).ready( function($) {

	$('.layout-grid').click( function(){
		if ( $('#content').hasClass('one-column')){
		$('#content')
			.hide()
			.removeClass('one-column')
			.addClass('two-column')
			.fadeIn();
		}
		return false;
	});
	$('.layout-list').click( function(){
		if ( $('#content').hasClass('two-column')){
		$('#content')
			.hide()
			.removeClass('two-column')
			.addClass('one-column')
			.fadeIn();
		}
		return false;
	});
	
	$('#primary').fitVids();
	$('#secondary').fitVids();
	$('.tabs').fwTabs();
	
	function fixSidebarTop(){
		$('#sidebar-top').css('marginTop','30px').css('marginBottom','30px');
		$('.logo').css('marginTop','30px').css('marginBottom','30px');
		if ( $(window).width() > 720 ){

			if ($('.logo').height() > $('#sidebar-top').height()){
				$('#sidebar-top').css('marginTop', ( ( ($('.logo').height() )/2) - ($('#sidebar-top').height()/2) )+ 30 + 'px' );
			}else{
				$('.logo').css('marginTop', ( ( ($('#sidebar-top').height() )/2) - ($('.logo').height()/2) ) + 30 + 'px' );
			}
		}else{
			$('#sidebar-top').css('marginTop','30px').css('marginBottom','0');
			$('.logo').css('marginTop','30px').css('marginBottom','30px');
		}
	}
    
	$('#masthead img').imagesLoaded( function(){ fixSidebarTop();} );
	
	$(window).resize( function(){
		fixSidebarTop();			   
	});


    // Slider
    var featured = $('#featured-slider');

    featured.flexslider({
		smoothHeight: true,
        controlNav: false,
        slideshow: _uprightJS.featured.slideshow,
        slideshowSpeed: _uprightJS.featured.slideshowSpeed
    });
    
    
    // Carousel
    var carousel = $('#carousel-slider');

    if ( carousel.width() < 720) {
        slideWidth = (carousel.width() / 2);
    } else {
        slideWidth = (carousel.width() / 3);
    }

    carousel.flexslider({
        controlNav: false,
        itemWidth: slideWidth,
        minItems: 2,
        maxItem: 3,
        animation: 'slide',
        move: 1,
        slideshow: _uprightJS.carousel.slideshow,
        slideshowSpeed: _uprightJS.carousel.slideshowSpeed
    });
    
});	


function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}

( function($) {

	$.fn.fwTabs = function(options){
	
		var settings = $.extend({}, $.fn.fwTabs.defaults, options);
		var active = 0;
		
		var tabs = $(this);
			
		$('.nav-tab li', tabs ).click( function(){
			idx = $(this).index();
			if ( idx == $('.nav-tab li.tab-active', tabs ).index() ) return false;
			startTab(tabs, idx);
			return false;
		});
		
		function startTab(tabs, idx){
			$('.active', tabs).fadeOut().removeClass('active').addClass('hide');
			$('.tab-content', tabs).eq(idx).removeClass('hide').addClass('active').fadeIn();
			$('li.tab-active', tabs).removeClass('tab-active');
			$('.nav-tab li', tabs).eq(idx).addClass('tab-active');
		}
		
	}

})( jQuery );