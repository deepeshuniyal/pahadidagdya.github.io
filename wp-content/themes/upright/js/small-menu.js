/**
 * Handles toggling the main navigation menu for small screens.
 */
jQuery( document ).ready( function( $ ) {
	var $masthead = $( '#masthead' ),
	    timeout = false;

	$.fn.smallMenu = function() {
		$masthead.find( '.main-navigation' ).addClass( 'main-small-navigation' );
		$masthead.find( '.main-navigation div.assistive-text' ).addClass( 'menu-toggle' ).removeClass('assistive-text');
		$masthead.find( '.main-navigation .menu-toggle a > span' ).addClass( 'inline-icon-list' );

		$( '.menu-toggle a' ).unbind( 'click' ).click( function() {
			$masthead.find( '.main-navigation .menu' ).slideToggle();
			$( this ).toggleClass( 'toggled-on' );
			return false;
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() < 720 )
		$.fn.smallMenu();

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( browserWidth < 720 ) {
				$.fn.smallMenu();
			} else {
				$masthead.find( '.main-navigation' ).removeClass( 'main-small-navigation' ).addClass( 'main-navigation' );
				$masthead.find( '.main-navigation .menu-toggle a > span' ).removeClass( 'icon-list' );
				$masthead.find( '.main-navigation .menu-toggle' ).addClass( 'assistive-text' ).removeClass( 'menu-toggle' );
				$masthead.find( '.menu' ).removeAttr( 'style' );
			}
		}, 200 );
	} );
} );