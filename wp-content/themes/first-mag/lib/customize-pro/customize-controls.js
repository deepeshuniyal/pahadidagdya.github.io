( function( api ) {

	// Extends our custom "first-mag" section.
	api.sectionConstructor['first-mag'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
