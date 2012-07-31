<?php

/**
 * The V2Press theme options class.
 *
 * @since 0.0.1
 */
class V2Press_Options {
	const OPTION_NAME = 'v2press_theme_options';
	const PAGE_NAME = 'v2press-options';

	private $sections;
	private $checkboxes;
	private $settings;

	public function __construct() {

		$this->checkboxes = array(); // This will keep track of the checkbox options for the validate_settings function.
		$this->settings = array();
		$this->get_settings();

		// Define the sections
		$this->sections['general']      = __( 'General Settings', 'v2press' );
		$this->sections['webmaster']   = __( 'Webmaster Tools Settings', 'v2press' );

		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		if ( ! get_option( 'v2press_theme_options' ) )
			$this->initialize_settings();
	}

	/**
	 * Add options page
	 *
	 * @since 0.0.2
	 */
	public function add_pages() {

		$admin_page = add_theme_page( __( 'V2Press Options', 'v2press' ), __( 'V2Press Options', 'v2press' ), 'administrator', self::PAGE_NAME, array( &$this, 'display_page' ) );

		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
	}

	/**
	 * Create settings field
	 *
	 * @since 0.0.2
	 */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' ),
			'desc'    => __( 'This is a default description.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;

		add_settings_field( $id, $title, array( &$this, 'display_setting' ), self::PAGE_NAME, $section, $field_args );
	}

	/**
	 * Display options page
	 *
	 * @since 0.0.2
	 */
	public function display_page() {

		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'V2Press Options', 'v2press' ) . '</h2>';

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.', 'v2press' ) . '</p></div>';

		echo '<form action="options.php" method="post">';

		settings_fields( self::OPTION_NAME );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';

		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul>';

		do_settings_sections( self::PAGE_NAME );

		echo '</div>
		<p class="submit"><input name="submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>

	</form>';

	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';

			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";

			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});

			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});

			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});

			$(".wrap h3, .wrap table").show();

			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});

			// Browser compatibility
			if ($.browser.mozilla)
			         $("form").attr("autocomplete", "off");
		});
	</script>
</div>';
	}

	/**
	 * Description for section
	 *
	 * @since 0.0.2
	 */
	public function section_description() {
		// We don't need section description
	}

	/**
	 * HTML output for text field
	 *
	 * @since 0.0.2
	 */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( self::OPTION_NAME );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;

		switch ( $type ) {
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;

			case 'checkbox':
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . self::OPTION_NAME . '[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="' . self::OPTION_NAME . '[' . $id . ']">';

				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="' . self::OPTION_NAME . '[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="' . self::OPTION_NAME . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="40">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . self::OPTION_NAME . '[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::OPTION_NAME . '[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';

		 		break;
		}
	}

	/**
	 * Settings and defaults
	 *
	 * @since 0.0.2
	 */
	public function get_settings() {

		/* General Settings */
		$this->settings['feed_url'] = array(
			'title'   => __( 'Feed URL', 'v2press' ),
			'desc'    => __( 'Enter the alternate feed url.', 'v2press' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['sections'] = array(
			'title'   => __( 'Section Names', 'v2press' ),
			'desc'    => __( 'Please enter section names, delimited with commas(,).', 'v2press' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'general'
		);

		$this->settings['recaptcha'] = array(
			'section' => 'general',
			'title'   => '',
			'desc'    => 'reCAPTCHA',
			'type'    => 'heading'
		);

		$this->settings['recaptcha_publickey'] = array(
			'title'   => __( 'reCAPTCHA Public Key', 'v2press' ),
			'desc'    => __( 'Please enter reCAPTCHA public key.', 'v2press' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['recaptcha_privatekey'] = array(
			'title'   => __( 'reCAPTCHA Private Key', 'v2press' ),
			'desc'    => __( 'Please enter reCAPTCHA private key.', 'v2press' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		/* Google Webmaster Settings */
		$this->settings['google-webmaster-verify'] = array(
			'title'   => __( 'Google Webmaster Verfify', 'v2press' ),
			'desc'    => __( 'Enter the meta tag value gives in the Webmaster Verify Ownership step.', 'v2press' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'webmaster'
		);

		/* Other type of fields, for reference later */
		/*
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' ),
			'desc'    => __( 'This is a description for the checkbox.' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);

		$this->settings['example_radio'] = array(
			'section' => 'general',
			'title'   => __( 'Example Radio' ),
			'desc'    => __( 'This is a description for the radio buttons.' ),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Choice 1',
				'choice2' => 'Choice 2',
				'choice3' => 'Choice 3'
			)
		);

		$this->settings['example_select'] = array(
			'section' => 'general',
			'title'   => __( 'Example Select' ),
			'desc'    => __( 'This is a description for the drop-down.' ),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Other Choice 1',
				'choice2' => 'Other Choice 2',
				'choice3' => 'Other Choice 3'
			)
		);*/
	}

	/**
	 * Initialize settings to their default values
	 *
	 * @since 0.0.2
	 */
	public function initialize_settings() {
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}

		update_option( self::OPTION_NAME, $default_settings );

	}

	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {

		register_setting( self::OPTION_NAME, self::OPTION_NAME, array ( &$this, 'validate_settings' ) );

		foreach ( $this->sections as $slug => $title ) {
			add_settings_section( $slug, $title, array( &$this, 'section_description' ), self::PAGE_NAME );
		}

		$this->get_settings();

		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
	}

	/**
	* jQuery Tabs
	*
	* @since 0.0.2
	*/
	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );
	}

	/**
	* Styling for the theme options page
	*
	* @since 0.0.2
	*/
	public function styles() {
?>
<style type="text/css">
.ui-tabs-nav {
	border-bottom: 1px solid #ccc;
	height: 27px;
	margin: 20px 0;
	padding: 0;
}
.ui-tabs-nav li {
	display: block;
	float: left;
	margin: 0;
}
.ui-tabs-nav li a {
	padding: 4px 20px 6px;
	font-weight: bold;
}
.ui-tabs-nav li a {
	border-style: solid;
	border-color: #ccc #ccc #f9f9f9;
	border-width: 1px 1px 0;
	color: #c1c1c1;
	text-shadow: rgba(255, 255, 255, 1) 0 1px 0;
	display: inline-block;
	padding: 4px 14px 6px;
	text-decoration: none;
	margin: 0 6px -1px 0;
	-moz-border-radius: 5px 5px 0 0;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-khtml-border-top-left-radius: 5px;
	-khtml-border-top-right-radius: 5px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
}
.ui-tabs-nav li.ui-tabs-selected a,
.ui-tabs-nav li.ui-state-active a {
	border-width: 1px;
	color: #464646;
}
.ui-tabs-panel {
	clear: both;
}
.ui-tabs-panel h3 {
	font: italic normal normal 20px Georgia, "Times New Roman", "Bitstream Charter", Times, serif;
	margin: 0;
	padding: 0 0 5px;
	line-height: 35px;
	text-shadow: 0 1px 0 #fff;
}
.ui-tabs-panel h4 {
	font-size: 15px;
	font-weight: bold;
	margin: 1em 0;
}
.wrap h3, .wrap table {
	display: none;
}
</style>
<?php
	}

	/**
	* Validate settings
	*
	* @since 0.0.2
	*/
	public function validate_settings( $input ) {
		$options = get_option( self::OPTION_NAME );

		foreach ( $this->checkboxes as $id ) {
			if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
				unset( $options[$id] );
		}

		return $input;
	}
}

new V2Press_Options();

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
