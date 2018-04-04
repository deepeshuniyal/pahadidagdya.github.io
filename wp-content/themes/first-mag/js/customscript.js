// menu dropdown link clickable
jQuery(document).ready(function( $ ){
 $('.navbar .dropdown > a, .dropdown-menu > li > a').click(function(){
            location.href = this.href;
        });
});
 
// scroll to top button
jQuery(document).ready(function( $ ){
   $("#back-top").hide();
  	$(function () {
  		$(window).scroll(function () {
  			if ($(this).scrollTop() > 100) {
  				$('#back-top').fadeIn();
  			} else {
  				$('#back-top').fadeOut();
  			}
  		});
  
  		// scroll body to 0px on click
  		$('#back-top a').click(function () {
  			$('body,html').animate({
  				scrollTop: 0
  			}, 800);
  			return false;
  		});
  	});
}); 
// FlexSlider
jQuery(document).ready(function( $ ) {
    $(window).load(function() {  
      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: true,
        start: function(slider) {
          slider.removeClass('slider-loading');
        }
      }); 
    });
});

