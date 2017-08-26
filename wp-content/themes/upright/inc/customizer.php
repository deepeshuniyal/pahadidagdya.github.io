<?php
/**
 * Theme Customizer
 */


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function upright_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->get_section( 'colors' )->priority           = 35;
	$wp_customize->get_section( 'colors' )->title              = __( 'Background & Sidebar Colors', 'upright' );
	$wp_customize->get_setting( 'background_color' )->priority = 1;

	/**
	 * Remove Background-Image since the Theme does not supports it.
	 */
	$wp_customize->remove_section( 'background_image' );

	/* Section : Featured post slider
	---------------------------------*/
	$wp_customize->add_section( 'featured_post_slider', array(
		'title'    => __( 'Featured Post Slider Options', 'upright' ),
		'priority' => 31,
	) );

	/* Section : Featured post carousel
	---------------------------------*/
	$wp_customize->add_section( 'featured_post_carousel', array(
		'title'    => __( 'Featured Post Carousel Options', 'upright' ),
		'priority' => 32,
	) );

	/* Section : Footer setting
	---------------------------------*/
	$wp_customize->add_section( 'footer', array(
		'title'    => __( 'Footer Setting', 'upright' ),
		'priority' => 42,
	) );

	/* Section : Layout Setting
	---------------------------------*/
	$wp_customize->add_section( 'layout', array(
		'title'    => __( 'Layout Setting', 'upright' ),
		'priority' => 34,
	) );

	/* Section : Top Menu Colors
	---------------------------------*/
	$wp_customize->add_section( 'menu_colors', array(
		'title'    => __( 'Top Menu Colors', 'upright' ),
		'priority' => 36,
	) );

	/* Section : Secondary Menu Colors
	---------------------------------*/
	$wp_customize->add_section( 'secondary_menu_colors', array(
		'title'    => __( 'Secondary Menu Colors', 'upright' ),
		'priority' => 37,
	) );

	/* Section : Main Content Colors
	---------------------------------*/
	$wp_customize->add_section( 'content_colors', array(
		'title'    => __( 'Main Content Colors', 'upright' ),
		'priority' => 38,
	) );

	/* Section : Typography setting
	---------------------------------*/
	$wp_customize->add_section( 'typography', array(
		'title'    => __( 'Typography Setting', 'upright' ),
		'priority' => 39,
	) );

}

add_action( 'customize_register', 'upright_customize_register' );


/**
 * List of customizer settings
 *
 * @param array $settings
 *
 * @return array
 */
function upright_customize_items( $settings = array() ) {

	$default_color_options = array(
		'font_color'                             => '#222222',
		'link_color'                             => '#dc2834',
		'meta_color'                             => '#aaaaaa',
		'border_color'                           => '#dddddd',
		'upright_widget_title_color'             => '#222222',
		'menu_background'                        => '#dc2834',
		'menu_link'                              => '#ffffff',
		'menu_link_hover'                        => '#ff9397',
		'menu_border_color'                      => '#ff9397',
		'secondary_menu_color'                   => '#222222',
		'secondary_menu_color_hover'             => '#aaaaaa',
		'content_background_color'               => '#ffffff',
		'content_font_color'                     => '#222222',
		'content_link_color'                     => '#dc2834',
		'content_meta_color'                     => '#bbbbbb',
		'content_border_color'                   => '#dddddd',
		'content_section_title_background_color' => '#dc2834',
		'content_section_title_color'            => '#ffffff',
		'heading_font'                           => 'Nixie_One__400',
		'heading_font_style'                     => 'normal',
		'heading_font_weight'                    => '400',
		'body_font'                              => 'Raleway__100,200,300,400,500,600,700,800,900',
		'menu_font'                              => 'Raleway__100,200,300,400,500,600,700,800,900',
		'menu_font_style'                        => 'normal',
		'menu_font_weight'                       => '700',
	);


	/* Section : Featured post slider
	---------------------------------*/

	/* Enable/disable checkbox */
	$settings[] = array(
		'id'                   => 'enable_slider',
		'default'              => false,
		'label'                => __( 'Enable slider on homepage', 'upright' ),
		'section'              => 'featured_post_slider',
		'priority'             => 1,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);

	/* Slider category select */
	$settings[] = array(
		'id'                => 'slider_category',
		'default'           => '',
		'label'             => __( 'Select the category for slider', 'upright' ),
		'section'           => 'featured_post_slider',
		'priority'          => 2,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => upright_choice_taxonomy(),
	);

	/* Slider post number */
	$settings[] = array(
		'id'                => 'slider_post_number',
		'default'           => 5,
		'label'             => __( 'Select the maximum post number', 'upright' ),
		'section'           => 'featured_post_slider',
		'priority'          => 3,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10'
		),
	);

	/* Enable/disable auto slider */
	$settings[] = array(
		'id'                   => 'slider_auto',
		'default'              => false,
		'label'                => __( 'Enable auto slide', 'upright' ),
		'section'              => 'featured_post_slider',
		'priority'             => 4,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);

	/* Slider auto slide timer*/
	$settings[] = array(
		'id'                => 'slider_auto_timer',
		'default'           => 5,
		'label'             => __( 'Auto slide timer', 'upright' ),
		'section'           => 'featured_post_slider',
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'priority'          => 5,
		'choices'           => array(
			'1'  => '1 second',
			'2'  => '2 seconds',
			'3'  => '3 seconds',
			'4'  => '4 seconds',
			'5'  => '5 seconds',
			'6'  => '6 seconds',
			'7'  => '7 seconds',
			'8'  => '8 seconds',
			'9'  => '9 seconds',
			'10' => '10 seconds'
		),
	);

	/* Show/hide in second page and next */
	$settings[] = array(
		'id'                   => 'slider_hide_next',
		'default'              => false,
		'label'                => __( 'Hide slider in second page and next', 'upright' ),
		'section'              => 'featured_post_slider',
		'priority'             => 6,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);


	/* Section : Featured post carousel
	---------------------------------*/

	/* Enable/disable checkbox */
	$settings[] = array(
		'id'                   => 'enable_carousel',
		'default'              => false,
		'label'                => __( 'Enable carousel on homepage', 'upright' ),
		'section'              => 'featured_post_carousel',
		'priority'             => 1,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);

	/* Enable/disable checkbox */
	$settings[] = array(
		'id'                => 'carousel_title',
		'default'           => 'Headlines',
		'label'             => __( 'Enable carousel on homepage', 'upright' ),
		'section'           => 'featured_post_carousel',
		'priority'          => 1,
		'control'           => 'text',
		'sanitize_callback' => 'esc_html',
	);

	/* Carousel category select */
	$settings[] = array(
		'id'                => 'carousel_category',
		'default'           => '',
		'label'             => __( 'Select the category for carousel', 'upright' ),
		'section'           => 'featured_post_carousel',
		'priority'          => 2,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => upright_choice_taxonomy(),
	);

	/* Carousel post number */
	$settings[] = array(
		'id'                => 'carousel_post_number',
		'default'           => 5,
		'label'             => __( 'Select the maximum post number', 'upright' ),
		'section'           => 'featured_post_carousel',
		'priority'          => 3,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10'
		),
	);

	/* Enable/disable auto carousel */
	$settings[] = array(
		'id'                   => 'carousel_auto',
		'default'              => false,
		'label'                => __( 'Enable auto slide', 'upright' ),
		'section'              => 'featured_post_carousel',
		'priority'             => 4,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);

	/* Carousel auto slide timer*/
	$settings[] = array(
		'id'                => 'carousel_auto_timer',
		'default'           => 5,
		'label'             => __( 'Auto slide timer', 'upright' ),
		'section'           => 'featured_post_carousel',
		'priority'          => 5,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array(
			'1'  => '1 second',
			'2'  => '2 seconds',
			'3'  => '3 seconds',
			'4'  => '4 seconds',
			'5'  => '5 seconds',
			'6'  => '6 seconds',
			'7'  => '7 seconds',
			'8'  => '8 seconds',
			'9'  => '9 seconds',
			'10' => '10 seconds'
		),
	);

	/* Show/hide in second page and next */
	$settings[] = array(
		'id'                   => 'carousel_hide_next',
		'default'              => false,
		'label'                => __( 'Hide carousel in second page and next', 'upright' ),
		'section'              => 'featured_post_carousel',
		'priority'             => 6,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);


	/* Section : Post Setting
	---------------------------------*/

	$settings[] = array(
		'id'                => 'exclude_categories',
		'default'           => '',
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'label'             => __( 'Do not show posts if home with these categories', 'upright' ),
		'section'           => 'post_setting',
		'choices'           => upright_choice_taxonomy(),
	);


	/* Section : Footer setting
	---------------------------------*/

	/* Credit text on footer */
	$settings[] = array(
		'id'                => 'footer_credit',
		'default'           => '',
		'transport'         => 'postMessage',
		'control'           => 'textarea',
		'sanitize_callback' => 'esc_html',
		'label'             => __( 'Credit text on footer', 'upright' ),
		'section'           => 'footer',
	);

	/* Section : Layout Setting
	---------------------------------*/

	$settings[] = array(
		'id'                => 'layout_default',
		'default'           => 'left-sidebar',
		'label'             => __( 'General Layout', 'upright' ),
		'section'           => 'layout',
		'priority'          => 1,
		'control'           => 'radio',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array( 'right-sidebar' => 'Right Sidebar', 'left-sidebar' => 'Left Sidebar' ),
	);

	$settings[] = array(
		'id'                => 'post_layout_default',
		'default'           => 'two-column',
		'control_id'        => 'post_layout_default',
		'label'             => __( 'Post listing layout', 'upright' ),
		'section'           => 'layout',
		'priority'          => 2,
		'control'           => 'radio',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array( 'one-column' => 'One Column', 'two-column' => 'Two Column' ),
	);

	$settings[] = array(
		'id'                   => 'hide_layout_toggle',
		'default'              => false,
		'label'                => __( 'Do not show layout option', 'upright' ),
		'section'              => 'layout',
		'priority'             => 3,
		'control'              => 'checkbox',
		'sanitize_callback'    => 'upright_sanitize_checkbox',
		'sanitize_js_callback' => 'upright_sanitize_checkbox_js',
	);

	$settings[] = array(
		'id'                => 'recent_post_title',
		'default'           => 'Recent Posts',
		'label'             => __( 'Recent Posts title in homepage', 'upright' ),
		'section'           => 'layout',
		'priority'          => 4,
		'control'           => 'text',
		'sanitize_callback' => 'esc_html',
	);

	/* General colors ( the controls added to existing section 'colors')
	---------------------------------*/
	$settings[] = array(
		'id'                => 'font_color',
		'default'           => $default_color_options['font_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Font color', 'upright' ),
		'section'           => 'colors',
		'priority'          => 1,
		'apply_css'         => array(
			array(
				'selector' => 'body, .no-heading-style, #secondary .entry-title a, .popular-posts article .home-thumb:after, #secondary input[type=text], #secondary input[type=email], #secondary input[type=password], #secondary textarea',
				'property' => 'color',
			),
		),
	);

	$settings[] = array(
		'id'                => 'link_color',
		'default'           => $default_color_options['link_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Link color', 'upright' ),
		'section'           => 'colors',
		'priority'          => 2,
		'apply_css'         => array(
			array(
				'selector' => '.tabs ul.nav-tab li.tab-active a:before, #secondary .entry-title a:hover, .popular-posts article .home-thumb:hover:after',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'meta_color',
		'default'           => $default_color_options['meta_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Meta text color', 'upright' ),
		'section'           => 'colors',
		'priority'          => 3,
		'apply_css'         => array(
			array(
				'selector' => '.entry-meta, .entry-meta .comments-link a, .widget_twitter li > a, .tabs ul.nav-tab li a, #respond .required-attr, .widget_nav_menu [class^="icon-"] a:before',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'border_color',
		'default'           => $default_color_options['border_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Border color', 'upright' ),
		'section'           => 'colors',
		'priority'          => 4,
		'apply_css'         => array(
			array(
				'selector' => '.secondary-navigation > div > ul > li a, .tabs ul.nav-tab li.second_tab, .tabs ul.nav-tab li.third_tab',
				'property' => 'border-left-color',
			),
			array(
				'selector' => '.widget ul li, .tabs ul.nav-tab li a, .widget_posts article, .widget-title, .secondary-navigation > div > ul ul a',
				'property' => 'border-bottom-color',
			),
			array(
				'selector' => '#twitter_account, .tabs ul.nav-tab li a, #secondary, .secondary-navigation, .secondary-navigation > div, .site-info, .site-footer',
				'property' => 'border-top-color',
			),
			array(
				'selector' => '#secondary input[type=text], #secondary input[type=email], #secondary input[type=password], #secondary textarea',
				'property' => 'border-color',
			),
		),
	);

	$settings[] = array(
		'id'                => 'upright_widget_title_color',
		'default'           => $default_color_options['upright_widget_title_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Widget title color', 'upright' ),
		'section'           => 'colors',
		'priority'          => 4,
		'apply_css'         => array(
			array(
				'selector' => '.widget-title',
				'property' => 'color',
			)
		),
	);


	/* Section : Top Menu Colors
	---------------------------------*/

	$settings[] = array(
		'id'                => 'menu_background',
		'default'           => $default_color_options['menu_background'],
		'transport'         => 'postMessage',
		'label'             => __( 'Main menu background color', 'upright' ),
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'section'           => 'menu_colors',
		'priority'          => 1,
		'apply_css'         => array(
			array(
				'selector' => '.main-navigation, .main-navigation ul ul',
				'property' => 'background-color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'menu_link',
		'default'           => $default_color_options['menu_link'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main menu link color', 'upright' ),
		'section'           => 'menu_colors',
		'priority'          => 2,
		'apply_css'         => array(
			array(
				'selector' => '.main-navigation div ul li > a, .main-navigation #searchform #s, .main-navigation #searchform:after, .menu-toggle [class^="inline-icon-"]',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'menu_link_hover',
		'default'           => $default_color_options['menu_link_hover'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main menu link hover color', 'upright' ),
		'section'           => 'menu_colors',
		'priority'          => 3,
		'apply_css'         => array(
			array(
				'selector' => '.main-navigation div ul li > a:hover, .main-navigation > div > ul > li.current-menu-item > a',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'menu_border_color',
		'default'           => $default_color_options['menu_border_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main menu borders color', 'upright' ),
		'section'           => 'menu_colors',
		'priority'          => 4,
		'apply_css'         => array(
			array(
				'selector' => '.main-navigation > div > ul, .main-navigation #searchform, .menu-toggle a',
				'property' => 'border-left-color',
			),
			array(
				'selector' => '.main-navigation > div > ul, .main-navigation #searchform, .menu-toggle a',
				'property' => 'border-right-color',
			),
			array(
				'selector' => '.main-small-navigation ul li a',
				'property' => 'border-top-color',
			),
			array(
				'selector' => '.main-navigation div > ul ul li a',
				'property' => 'border-bottom-color',
			)
		),
	);


	/* Section : Secondary Menu Colors
	---------------------------------*/

	$settings[] = array(
		'id'                => 'secondary_menu_color',
		'default'           => $default_color_options['secondary_menu_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Secondary menu link color', 'upright' ),
		'section'           => 'secondary_menu_colors',
		'priority'          => 5,
		'apply_css'         => array(
			array(
				'selector' => '.secondary-navigation a',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'secondary_menu_color_hover',
		'default'           => $default_color_options['secondary_menu_color_hover'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Secondary menu link hover color', 'upright' ),
		'section'           => 'secondary_menu_colors',
		'priority'          => 6,
		'apply_css'         => array(
			array(
				'selector' => '.secondary-navigation a:hover',
				'property' => 'color',
			)
		),
	);


	/* Section : Main Content Colors
	---------------------------------*/

	$settings[] = array(
		'id'                => 'content_background_color',
		'default'           => $default_color_options['content_background_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main content background color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 1,
		'apply_css'         => array(
			array(
				'selector' => '#primary',
				'property' => 'background-color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'content_font_color',
		'default'           => $default_color_options['content_font_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main content font color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 2,
		'apply_css'         => array(
			array(
				'selector' => '#primary, #primary .entry-title a, #primary input[type=text], #primary input[type=email], #primary input[type=password], #primary textarea',
				'property' => 'color',
			),
			array(
				'selector' => '#primary button:hover, #primary input[type="button"]:hover, #primary input[type="reset"]:hover, #primary input[type="submit"]:hover',
				'property' => 'background-color',
			),
		),
	);

	$settings[] = array(
		'id'                => 'content_link_color',
		'default'           => $default_color_options['content_link_color'],
		'transport'         => 'postMessage',
		'label'             => __( 'Main content link color', 'upright' ),
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'section'           => 'content_colors',
		'priority'          => 3,
		'apply_css'         => array(
			array(
				'selector' => '#primary a, #primary .entry-title a:hover',
				'property' => 'color',
			),
			array(
				'selector' => '#primary button, #primary input[type="button"], #primary input[type="reset"], #primary input[type="submit"]',
				'property' => 'background-color',
			),
		),
	);

	$settings[] = array(
		'id'                => 'content_meta_color',
		'default'           => $default_color_options['content_meta_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Main content meta text color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 4,
		'apply_css'         => array(
			array(
				'selector' => '#primary .entry-meta,  #primary .entry-meta-single, .hentry footer .inline-icon-user, #primary .post-tags, #primary #breadcrumbs, #primary .entry-meta .comments-link a, #comments .commentlist li .comment-meta a',
				'property' => 'color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'content_border_color',
		'default'           => $default_color_options['content_border_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Borders color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 5,
		'apply_css'         => array(
			array(
				'selector' => '#primary #breadcrumbs, #comments .commentlist li article.comment, .single .post .related-box ul li',
				'property' => 'border-bottom-color',
			),
			array(
				'selector' => '#primary input[type=text], #primary input[type=email], #primary input[type=password], #primary textarea, .wp-caption, pre',
				'property' => 'border-color',
			),
			array(
				'selector' => '.hentry:before, .single .post > footer, .page-navigation, #comments .commentlist li article.comment',
				'property' => 'border-top-color',
			),
			array(
				'selector' => '.hentry blockquote',
				'property' => 'border-left-color',
			),
		),
	);

	$settings[] = array(
		'id'                => 'content_section_title_background_color',
		'default'           => $default_color_options['content_section_title_background_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Section title background color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 6,
		'apply_css'         => array(
			array(
				'selector' => '.layout-toggle a:before',
				'property' => 'color',
			),
			array(
				'selector' => '.section-title, #primary .section-title a, #reply-title, .section-title:after, #reply-title:after, #carousel-slider .flex-direction-nav a',
				'property' => 'background-color',
			)
		),
	);

	$settings[] = array(
		'id'                => 'content_section_title_color',
		'default'           => $default_color_options['content_section_title_color'],
		'transport'         => 'postMessage',
		'control'           => 'color',
		'sanitize_callback' => 'sanitize_hex_color',
		'label'             => __( 'Section title font color', 'upright' ),
		'section'           => 'content_colors',
		'priority'          => 6,
		'apply_css'         => array(
			array(
				'selector' => '.section-title, #primary .section-title a, #reply-title, .section-title:after, #reply-title:after, #carousel-slider .flex-direction-nav a',
				'property' => 'color',
			)
		),
	);


	/* Section : Typography setting
	---------------------------------*/

	$settings[] = array(
		'id'                => 'heading_font',
		'default'           => $default_color_options['heading_font'],
		'label'             => __( 'Heading Font', 'upright' ),
		'section'           => 'typography',
		'priority'          => 1,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => upright_choice_font(),
	);

	$settings[] = array(
		'id'                => 'heading_font_style',
		'default'           => $default_color_options['heading_font_style'],
		'label'             => __( 'Heading Font Style', 'upright' ),
		'section'           => 'typography',
		'priority'          => 2,
		'control'           => 'radio',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array( 'italic' => 'Italic', 'normal' => 'Normal' ),
		'transport'         => 'postMessage',
		'apply_css'         => array(
			array(
				'selector' => 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6',
				'property' => 'font-style',
			)
		),
	);

	$settings[] = array(
		'id'                => 'heading_font_weight',
		'default'           => $default_color_options['heading_font_weight'],
		'label'             => __( 'Heading Font Weight', 'upright' ),
		'section'           => 'typography',
		'priority'          => 3,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array(
			'100' => '100',
			'200' => '200',
			'300' => '300',
			'400' => '400 (Normal)',
			'500' => '500',
			'600' => '600',
			'700' => '700 (Bold)',
			'800' => '800',
			'900' => '900',
		),
		'transport'         => 'postMessage',
		'apply_css'         => array(
			array(
				'selector' => 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6',
				'property' => 'font-weight',
			)
		),
	);

	$settings[] = array(
		'id'                => 'body_font',
		'default'           => $default_color_options['body_font'],
		'label'             => __( 'Body font', 'upright' ),
		'section'           => 'typography',
		'priority'          => 4,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => upright_choice_font(),
	);

	$settings[] = array(
		'id'                => 'menu_font',
		'default'           => $default_color_options['menu_font'],
		'label'             => __( 'Menu Font', 'upright' ),
		'section'           => 'typography',
		'priority'          => 7,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => upright_choice_font(),
	);

	$settings[] = array(
		'id'                => 'menu_font_style',
		'default'           => $default_color_options['menu_font_style'],
		'label'             => __( 'Menu Font Style', 'upright' ),
		'section'           => 'typography',
		'priority'          => 8,
		'control'           => 'radio',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array( 'italic' => 'Italic', 'normal' => 'Normal' ),
		'transport'         => 'postMessage',
		'apply_css'         => array(
			array(
				'selector' => '.secondary-navigation > div > ul > li > a, .section-title, #reply-title, .widget-title, button, html input[type="button"], input[type="reset"], input[type="submit"]',
				'property' => 'font-style',
			),
		),
	);

	$settings[] = array(
		'id'                => 'menu_font_weight',
		'default'           => $default_color_options['menu_font_weight'],
		'label'             => __( 'Menu Font Weight', 'upright' ),
		'section'           => 'typography',
		'priority'          => 9,
		'control'           => 'select',
		'sanitize_callback' => 'upright_sanitize_choice',
		'choices'           => array(
			'100' => '100',
			'200' => '200',
			'300' => '300',
			'400' => '400 (Normal)',
			'500' => '500',
			'600' => '600',
			'700' => '700 (Bold)',
			'800' => '800',
			'900' => '900',
		),
		'transport'         => 'postMessage',
		'apply_css'         => array(
			array(
				'selector' => '.secondary-navigation > div > ul > li > a, .section-title, #reply-title, .widget-title, button, html input[type="button"], input[type="reset"], input[type="submit"]',
				'property' => 'font-weight',
			),
		),
	);


	return $settings;
}

add_filter( 'upright_customizer_settings', 'upright_customize_items' );
add_filter( 'upright_customizer_css', 'upright_customize_items' );


/**
 * Register Customize Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function upright_customizer_settings( $wp_customize ) {

	$settings = apply_filters( 'upright_customizer_settings', array() );

	$i = 1;

	foreach ( $settings as $setting ) {
		$wp_customize->add_setting( $setting['id'], array(
			'default'              => empty( $setting['default'] ) ? null : $setting['default'],
			'transport'            => empty( $setting['transport'] ) ? null : $setting['transport'],
			'capability'           => empty( $setting['capability'] ) ? 'edit_theme_options' : $setting['capability'],
			'theme_supports'       => empty( $setting['theme_supports'] ) ? null : $setting['theme_supports'],
			'sanitize_callback'    => empty( $setting['sanitize_callback'] ) ? 'sanitize_text_field' : $setting['sanitize_callback'],
			'sanitize_js_callback' => empty( $setting['sanitize_js_callback'] ) ? 'sanitize_text_field' : $setting['sanitize_js_callback'],
			'type'                 => empty( $setting['type'] ) ? null : $setting['type'],
		) );

		$setting['control_id'] = empty( $setting['control_id'] ) ? $setting['id'] : $setting['control_id'];

		if ( 'image' === $setting['control'] ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting['control_id'],
				array(
					'label'           => empty( $setting['label'] ) ? null : $setting['label'],
					'section'         => empty( $setting['section'] ) ? null : $setting['section'],
					'settings'        => $setting['id'],
					'priority'        => empty( $setting['priority'] ) ? $i : $setting['priority'],
					'active_callback' => empty( $setting['active_callback'] ) ? null : $setting['active_callback'],
					'description'     => empty( $setting['description'] ) ? null : $setting['description'],
				)
			) );
		} else if ( 'color' === $setting['control'] ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting['control_id'],
				array(
					'label'           => empty( $setting['label'] ) ? null : $setting['label'],
					'section'         => empty( $setting['section'] ) ? null : $setting['section'],
					'settings'        => $setting['id'],
					'priority'        => empty( $setting['priority'] ) ? $i : $setting['priority'],
					'active_callback' => empty( $setting['active_callback'] ) ? null : $setting['active_callback'],
					'description'     => empty( $setting['description'] ) ? null : $setting['description'],
				)
			) );
		} else {
			$wp_customize->add_control( $setting['control_id'], array(
				'settings'        => $setting['id'],
				'label'           => empty( $setting['label'] ) ? null : $setting['label'],
				'section'         => empty( $setting['section'] ) ? null : $setting['section'],
				'type'            => empty( $setting['control'] ) ? null : $setting['control'],
				'choices'         => empty( $setting['choices'] ) ? null : $setting['choices'],
				'input_attrs'     => empty( $setting['input_attrs'] ) ? null : $setting['input_attrs'],
				'priority'        => empty( $setting['priority'] ) ? $i : $setting['priority'],
				'active_callback' => empty( $setting['active_callback'] ) ? null : $setting['active_callback'],
				'description'     => empty( $setting['description'] ) ? null : $setting['description'],
			) );
		}

		$i ++;
	}
}

add_action( 'customize_register', 'upright_customizer_settings', 100, 1 );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function upright_customize_preview_js() {
	wp_enqueue_script(
		'upright-customizer-preview',
		get_template_directory_uri() . '/js/customizer-preview.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}

add_action( 'customize_preview_init', 'upright_customize_preview_js' );


/**
 * Binds JS handlers for the helper in the customizer admin.
 */
function upright_customize_controls_js() {
	wp_enqueue_script(
		'upright-customizer-controls',
		get_template_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-controls' ),
		'20141114',
		true
	);
}

add_action( 'customize_controls_enqueue_scripts', 'upright_customize_controls_js' );


/**
 * Render Style and JS for Custom CSS
 */
function upright_customizer_css() {

	$css              = '';
	$customize_js_var = array();
	$media_queries    = array();
	$settings         = apply_filters( 'upright_customizer_css', array() );

	foreach ( $settings as $setting ) {

		if ( isset( $setting['type'] ) && $setting['type'] == 'option' ) {
			$value = get_option( $setting['id'] );
		} else {
			$value = get_theme_mod( $setting['id'] );
		}

		if ( ! empty( $setting['apply_css'] ) && is_array( $setting['apply_css'] ) ) {

			foreach ( $setting['apply_css'] as $apply_css ) {
				$mq            = empty( $apply_css['media_query'] ) ? 'global' : $apply_css['media_query'];
				$selector      = empty( $apply_css['selector'] ) ? '' : $apply_css['selector'];
				$property      = empty( $apply_css['property'] ) ? '' : $apply_css['property'];
				$unit          = empty( $apply_css['unit'] ) ? '' : $apply_css['unit'];
				$value_in_text = empty( $apply_css['value_in_text'] ) ? '' : $apply_css['value_in_text'];


				if ( $value && ( $value !== $setting['default'] ) ) {
					if ( ! isset( $media_queries[ $mq ][ $selector ] ) ) {
						$media_queries[ $mq ][ $selector ] = '';
					}

					if ( isset( $apply_css['value_in_text'] ) ) {
						$media_queries[ $mq ][ $selector ] .= $property . ': ' . str_replace( '%value%', $value, $value_in_text ) . ' ;';
					} else {
						$media_queries[ $mq ][ $selector ] .= $property . ': ' . $value . $unit . ' ;';
					}
				}

				if ( $setting['transport'] == 'postMessage' ) {
					$customize_js_var[] =
						array(
							'id'            => $setting['id'],
							'default'       => isset( $setting['default'] ) ? $setting['default'] : null,
							'selector'      => $selector,
							'property'      => $property,
							'unit'          => $unit,
							'value_in_text' => $value_in_text,
							'mq'            => $mq,
						);
				}

			}

		}
	}

	foreach ( $media_queries as $mq => $selectors ) {
		if ( $mq !== 'global' ) {
			$css .= $mq . " {\n";
		}
		foreach ( $selectors as $selector => $value ) {
			$css .= $selector . " { " . $value . "}\n";
		}
		if ( $mq !== 'global' ) {
			$css .= "}\n";
		}
	}

	$_css = '
		/* Custom Colors CSS */
		%1$s
	';

	wp_add_inline_style( 'upright-style', sprintf(
		$_css,
		strip_tags( $css )
	) );


	if ( $customize_js_var && is_customize_preview() ) {

		wp_localize_script(
			'upright-customizer-preview',
			'_customizerCSS',
			$customize_js_var
		);

	}

}

add_action( 'customize_preview_init', 'upright_customizer_css' );
add_action( 'wp_enqueue_scripts', 'upright_customizer_css' );

