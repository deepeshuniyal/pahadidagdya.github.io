(function() {
    tinymce.PluginManager.add('carousel_tinymce_added_button', function( editor, url ) {
        editor.addButton( 'carousel_tinymce_added_button', {
            title: 'Insert Carousel Shortcode',
            type: 'menubutton',
            icon: 'icon dashicons-format-gallery',
            menu: [
                {
                    text: 'Carousel Via Custom Post',
                    value: 'Text from Custom Post',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Carousel Via Custom Post',
							body: [
							{
								type: 'listbox', 
								name: 'show_items_slide', 
								label: 'Items Per Slide', 
								'values': [
									{text: '4', value: '4'},
									{text: '5', value: '5'},
									{text: '6', value: '6'},
									{text: '7', value: '7'},
									{text: '3', value: '3'},
									{text: '2', value: '2'},
									{text: '1', value: '1'}
								]
							},
							{
								type: 'textbox',
								name: 'carousel_category_height',
								label: 'Input Your Category ID'
							},
							{
								type: 'textbox',
								name: 'carousel_image_height',
								label: 'Image Height'
							},
							{
								type: 'listbox', 
								name: 'show_items_tablet', 
								label: 'Show Items In Tablet', 
								'values': [
									{text: '3', value: '3'},
									{text: '2', value: '2'},
									{text: '1', value: '1'}
								]
							},
							{
								type: 'listbox', 
								name: 'show_items_desktop', 
								label: 'Show Items In Desktop', 
								'values': [
									{text: '3', value: '3'},
									{text: '2', value: '2'},
									{text: '1', value: '1'}
								]
							},
							{
								type: 'listbox', 
								name: 'show_items_small_desktop', 
								label: 'Show Items In Small Desktop', 
								'values': [
									{text: '3', value: '3'},
									{text: '2', value: '2'},
									{text: '1', value: '1'}
								]
							},
							{
								type: 'listbox', 
								name: 'show_items_captions',
								label: 'Show/Hide Caption',
								'values': [
									{text: 'enable', value: 'block'},
									{text: 'Disable', value: 'none'}
								]
							}],
							onsubmit: function( e ) {
								editor.insertContent( '[tpmfcarousel category="' + e.data.carousel_category_height + '" items="' + e.data.show_items_slide + '" items_tablet="' + e.data.show_items_tablet + '" items_desktop="' + e.data.show_items_desktop + '" itemsdesktop_small="' + e.data.show_items_small_desktop + '" display_caption="' + e.data.show_items_captions + '" img_height="' + e.data.carousel_image_height + '"]');
							}
						});
					}
                },
                {
                    text: 'Post Carousel Shortcode',
                    value: '[tpmfcarouselpost items="3" img_height="190"]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Carousel Via Shortcode',
                    value: '[ultimatecarousels][carouselsimages]Insert image link[/carouselsimages][carouselsimages]Insert image link[/carouselsimages][carouselsimages]Insert image link[/carouselsimages][carouselsimages]Insert image link[/carouselsimages][carouselsimages]Insert image link[/carouselsimages][/ultimatecarousels]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                }
           ]
        });
    });
})();

