<?php
/**
 * V2Press theme options.
 *
 * @since 0.0.3
 */

if ( ! class_exists( 'NHP_Options' ) )
	require_once( VP_LIBS_PATH . '/nhp-options/options.php' );

/*
 * The V2Press theme options.
 *
 * @since 0.0.3
 */
function vp_theme_options(){
	$args = array();

	// Set it to dev mode to view the class settings/info in the form
	$args['dev_mode'] = false;

	// Google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
	//$args['google_api_key'] = '***';

	//Setup custom links in the footer for share icons
	$args['share_icons']['twitter'] = array(
											'link' => 'https://twitter.com/v2press',
											'title' => 'Folow V2Press on Twitter',
											'img' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png'
											);

	// Choose to disable the import/export feature
	//$args['show_import_export'] = false;

	// The option name
	$args['opt_name'] = 'v2press_theme_options';

	// The menu title for options page
	$args['menu_title'] = __( 'V2Press Options', 'v2press' );

	// The Page Title for options page
	$args['page_title'] = __( 'V2Press Options', 'v2press' );

	// The page slug for options page (wp-admin/themes.php?page=***)
	$args['page_slug'] = 'v2press_options';

	// Add the option page as a top-level menu
	$args['page_type'] = 'menu';

	// Not allowed to show the submenus
	$args['allow_sub_menu'] = false;

	// Menu location
	$args['page_position'] = 61;

	// The help tabs
	$args['help_tabs'][] = array(
								'id' => 'vp-opts-1',
								'title' => __( 'V2Press Support', 'v2press' ),
								'content' => __( '<p>If you have any question about the V2Press theme, please visit <a href="http://v2press.com/support">V2Press Support Forum</a> and create an top, the author or someone else would help you.</p>', 'v2press' )
								);

	// The footer text
	$args['footer_credit'] = sprintf( __( 'Thanks for using %1$sV2Press%2$s to create your site.', 'v2press' ), '<a href="' . VP_HOME_URL . '" target="_blank">', '</a>' );

	$args['last_tab'] = 'general';

	// The sections are displayed as vertical tabs
	// They appear as the order you defined theme
	$sections = array();

	// General Tab
	$sections['general'] = array(
						'icon' => NHP_OPTIONS_URL . 'img/glyphicons/glyphicons_232_cloud.png',
						'title' => __( 'General', 'v2press' ),
						'desc' => __( 'Config the general settings.', 'v2press' ),
						'fields' => array(
							array(
								'id' => 'vp-feed-url', //must be unique
								'type' => 'text', //builtin fields include:
										  //text|textarea|editor|checkbox|multi_checkbox|radio|radio_img|button_set|select|multi_select|color|date|divide|info|upload
								'title' => __( 'Feed URL', 'v2press' ),
								'desc' => __( 'Enter your site\'s feed url if you don\'t use the WordPress builtin one. For example, the feedburner one.', 'v2press' ),
								'validate' => 'url', //builtin validation includes: email|html|html_custom|no_html|js|numeric|url
								),
							array(
								'id' => 'vp-topic-lock-time',
								'type' => 'text',
								'title' => __( 'Topic Lock Time', 'v2press' ),
								'desc' => __( 'The time in sections before the topic author can edit his topic after created.', 'v2press'),
								'validate' => 'numeric',
								'std' => '900',
								'class' => 'small-text'
								),
							array(
								'id' => 'vp-sticky-area-title',
								'type' => 'text',
								'title' => __( 'Sticky Area Title', 'v2press' ),
								'desc' => __( 'Set the sticky topics area title.', 'v2press' ),
								'std' => __( 'Sticky Topics', 'v2press' )
								),
							array(
								'id' => 'vp-sections',
								'type' => 'textarea',
								'title' => __( 'Section Names', 'v2press' ),
								'desc' => __( 'Enter the Section names with commas(,) separated. The order matters.', 'v2press'),
								'validate' => 'no_html'
								),

					array(
						'id' => '4',
						'type' => 'text',
						'title' => __('Text Option - Numeric Validated', 'nhp-opts'),
						'sub_desc' => __('This must be numeric.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'comma_numeric',
						'type' => 'text',
						'title' => __('Text Option - Comma Numeric Validated', 'nhp-opts'),
						'sub_desc' => __('This must be a comma seperated string of numerical values.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'comma_numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'no_special_chars',
						'type' => 'text',
						'title' => __('Text Option - No Special Chars Validated', 'nhp-opts'),
						'sub_desc' => __('This must be a alpha numeric only.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'no_special_chars',
						'std' => '0'
						),
					array(
						'id' => 'str_replace',
						'type' => 'text',
						'title' => __('Text Option - Str Replace Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'str_replace',
						'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
						'std' => '0'
						),
					array(
						'id' => 'preg_replace',
						'type' => 'text',
						'title' => __('Text Option - Preg Replace Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'preg_replace',
						'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
						'std' => '0'
						),
					array(
						'id' => 'custom_validate',
						'type' => 'text',
						'title' => __('Text Option - Custom Callback Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate_callback' => 'validate_callback_function',
						'std' => '0'
						),
					array(
						'id' => '5',
						'type' => 'textarea',
						'title' => __('Textarea Option - No HTML Validated', 'nhp-opts'),
						'sub_desc' => __('All HTML will be stripped', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'no_html',
						'std' => 'No HTML is allowed in here.'
						),
					array(
						'id' => '6',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated', 'nhp-opts'),
						'sub_desc' => __('HTML Allowed (wp_kses)', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
						'std' => 'HTML is allowed in here.'
						),
					array(
						'id' => '7',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated Custom', 'nhp-opts'),
						'sub_desc' => __('Custom HTML Allowed (wp_kses)', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'html_custom',
						'std' => 'Some HTML is allowed in here.',
						'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
						),
					array(
						'id' => '8',
						'type' => 'textarea',
						'title' => __('Textarea Option - JS Validated', 'nhp-opts'),
						'sub_desc' => __('JS will be escaped', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'js'
						),
					array(
						'id' => '9',
						'type' => 'editor',
						'title' => __('Editor Option', 'nhp-opts'),
						'sub_desc' => __('Can also use the validation methods if required', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => 'OOOOOOhhhh, rich editing.'
						)
					,
					array(
						'id' => 'editor2',
						'type' => 'editor',
						'title' => __('Editor Option 2', 'nhp-opts'),
						'sub_desc' => __('Can also use the validation methods if required', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => 'OOOOOOhhhh, rich editing2.'
						)
					)
				);
$sections['radio'] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_150_check.png',
				'title' => __('Radio/Checkbox Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '10',
						'type' => 'checkbox',
						'title' => __('Checkbox Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => '1'// 1 = on | 0 = off
						),
					array(
						'id' => '11',
						'type' => 'multi_checkbox',
						'title' => __('Multi Checkbox Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
						'std' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also dont need to specify opts that are 0.
						),
					array(
						'id' => '12',
						'type' => 'radio',
						'title' => __('Radio Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
					array(
						'id' => '13',
						'type' => 'radio_img',
						'title' => __('Radio Image Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array(
										'1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
										'2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
										'3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
										'4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						),
					array(
						'id' => 'radio_img',
						'type' => 'radio_img',
						'title' => __('Radio Image Option For Layout', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This uses some of the built in images, you can use them for layout options.', 'nhp-opts'),
						'options' => array(
										'1' => array('title' => '1 Column', 'img' => NHP_OPTIONS_URL.'img/1col.png'),
										'2' => array('title' => '2 Column Left', 'img' => NHP_OPTIONS_URL.'img/2cl.png'),
										'3' => array('title' => '2 Column Right', 'img' => NHP_OPTIONS_URL.'img/2cr.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						)
					)
				);
$sections['select'] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_157_show_lines.png',
				'title' => __('Select Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '14',
						'type' => 'select',
						'title' => __('Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for select options
						'std' => '2'
						),
					array(
						'id' => '15',
						'type' => 'multi_select',
						'title' => __('Multi Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => array('2','3')
						)
					)
				);
$sections['custom'] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
				'title' => __('Custom Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '16',
						'type' => 'color',
						'title' => __('Color Option', 'nhp-opts'),
						'sub_desc' => __('Only color validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => '#FFFFFF'
						),
					array(
						'id' => 'color_gradient',
						'type' => 'color_gradient',
						'title' => __('Color Gradient Option', 'nhp-opts'),
						'sub_desc' => __('Only color validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => array('from' => '#000000', 'to' => '#FFFFFF')
						),
					array(
						'id' => '17',
						'type' => 'date',
						'title' => __('Date Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '18',
						'type' => 'button_set',
						'title' => __('Button Set Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
					array(
						'id' => '19',
						'type' => 'upload',
						'title' => __('Upload Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => 'pages_select',
						'type' => 'pages_select',
						'title' => __('Pages Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites pages.', 'nhp-opts'),
						'args' => array()//uses get_pages
						),
					array(
						'id' => 'pages_multi_select',
						'type' => 'pages_multi_select',
						'title' => __('Pages Multiple Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites pages.', 'nhp-opts'),
						'args' => array('number' => '5')//uses get_pages
						),
					array(
						'id' => 'posts_select',
						'type' => 'posts_select',
						'title' => __('Posts Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites posts.', 'nhp-opts'),
						'args' => array('numberposts' => '10')//uses get_posts
						),
					array(
						'id' => 'posts_multi_select',
						'type' => 'posts_multi_select',
						'title' => __('Posts Multiple Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites posts.', 'nhp-opts'),
						'args' => array('numberposts' => '10')//uses get_posts
						),
					array(
						'id' => 'tags_select',
						'type' => 'tags_select',
						'title' => __('Tags Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites tags.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_tags
						),
					array(
						'id' => 'tags_multi_select',
						'type' => 'tags_multi_select',
						'title' => __('Tags Multiple Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites tags.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_tags
						),
					array(
						'id' => 'cats_select',
						'type' => 'cats_select',
						'title' => __('Cats Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites cats.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_categories
						),
					array(
						'id' => 'cats_multi_select',
						'type' => 'cats_multi_select',
						'title' => __('Cats Multiple Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites cats.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_categories
						),
					array(
						'id' => 'menu_select',
						'type' => 'menu_select',
						'title' => __('Menu Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites menus.', 'nhp-opts'),
						//'args' => array()//uses wp_get_nav_menus
						),
					array(
						'id' => 'select_hide_below',
						'type' => 'select_hide_below',
						'title' => __('Select Hide Below Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field requires certain options to be checked before the below field will be shown.', 'nhp-opts'),
						'options' => array(
									'1' => array('name' => 'Opt 1 field below allowed', 'allow' => 'true'),
									'2' => array('name' => 'Opt 2 field below hidden', 'allow' => 'false'),
									'3' => array('name' => 'Opt 3 field below allowed', 'allow' => 'true')
									),//Must provide key => value(array) pairs for select options
						'std' => '2'
						),
					array(
						'id' => 'menu_location_select',
						'type' => 'menu_location_select',
						'title' => __('Menu Location Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the themes menu locations.', 'nhp-opts')
						),
					array(
						'id' => 'checkbox_hide_below',
						'type' => 'checkbox_hide_below',
						'title' => __('Checkbox to hide below', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a checkbox which will allow the user to use the next setting.', 'nhp-opts'),
						),
						array(
						'id' => 'post_type_select',
						'type' => 'post_type_select',
						'title' => __('Post Type Select Option', 'nhp-opts'),
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all registered post types.', 'nhp-opts'),
						//'args' => array()//uses get_post_types
						),
					array(
						'id' => 'custom_callback',
						//'type' => 'nothing',//doesnt need to be called for callback fields
						'title' => __('Custom Field Callback', 'nhp-opts'),
						'sub_desc' => __('This is a completely unique field type', 'nhp-opts'),
						'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'nhp-opts'),
						'callback' => 'my_custom_field'
						),
					array(
						'id' => 'google_webfonts',
						'type' => 'google_webfonts',//doesnt need to be called for callback fields
						'title' => __('Google Webfonts', 'nhp-opts'),
						'sub_desc' => __('This is a completely unique field type', 'nhp-opts'),
						'desc' => __('This is a simple implementation of the developer API for Google webfonts. Preview selection will be coming in future releases.', 'nhp-opts')
						)
					)
				);

$sections['non_value'] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_093_crop.png',
				'title' => __('Non Value Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '20',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'),
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '21',
						'type' => 'divide'
						),
					array(
						'id' => '22',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'),
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '23',
						'type' => 'info',
						'desc' => __('<p class="description">This is the info field, if you want to break sections up.</p>', 'nhp-opts')
						),
					array(
						'id' => '24',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'),
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						)
					)
				);


	$tabs = array();

	if (function_exists('wp_get_theme')){
		$theme_data = wp_get_theme();
		$theme_uri = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	}else{
		$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
		$theme_uri = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}

	$theme_info = '<div class="nhp-opts-section-desc">';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'nhp-opts').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'nhp-opts').$author.'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'nhp-opts').$version.'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-description">'.$description.'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'nhp-opts').implode(', ', $tags).'</p>';
	$theme_info .= '</div>';



	$tabs['theme_info'] = array(
					'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_195_circle_info.png',
					'title' => __('Theme Information', 'nhp-opts'),
					'content' => $theme_info
					);

	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
		$tabs['theme_docs'] = array(
						'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'nhp-opts'),
						'content' => nl2br(file_get_contents(trailingslashit(get_stylesheet_directory()).'README.html'))
						);
	}//if

	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

}//function
add_action( 'init', 'vp_theme_options', 0 );

/*
 *
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 *
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){

	$error = false;
	$value =  'just testing';
	/*
	do your validation

	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/

	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;

}//function

/**
 * Retrieve the theme options.
 *
 * @since 0.0.2
 */
function vp_get_theme_option( $name ) {
	$options = get_option( 'v2press_theme_options' );
	if( isset( $options[$name] ) )
		return $options[$name];
	else
		return false;
}
