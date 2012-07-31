<?php
/**
 * Use the WordPress Pointer to create a feature touring.
 *
 * @since 0.0.3
 */

class VP_Pointers {

	/**
   	 * Class constructor.
     */
 	function __construct() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );
		add_action( 'wp_ajax_vp_set_ignore', array( &$this, 'vp_set_ignore' ) );
  	}

	/**
	 * Enqueue styles and scripts needed for the pointers.
	 */
	function enqueue() {
		$options = get_option( 'v2press_theme_options' );

		if ( ! isset( $options['ignore_tour'] ) || ! $options['ignore_tour'] ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
			add_action( 'admin_print_footer_scripts', array( &$this, 'intro_tour' ), 99 );
			add_action( 'admin_head', array( &$this, 'admin_head' ) );
		}
  	}

	/**
	 * Load the introduction tour
	 */
	function intro_tour() {
		global $pagenow, $current_user;

		$adminpages = array(
	  		'general'         => array(
		'content'  => "<h3>" . __( "Title &amp; Description settings", 'wordpress-seo' ) . "</h3>"
		  . "<p>" . __( "This is were you set the templates for your titles and descriptions of all the different types of pages on your blog, be it your homepage, posts & pages (under post types), category or tag archives (under taxonomy archives), or even custom post type archives and custom posts: all of that is done from here.", 'wordpress-seo' ) . "</p>"
		  . "<p><strong>" . __( "Templates", 'wordpress-seo' ) . "</strong><br/>"
		  . __( "The templates are built using variables, the help tab for all the different variables available to you to use in these.", 'wordpress-seo' ) . "</p>"
		  . "<p><strong>" . __( "Sitewide settings", 'wordpress-seo' ) . "</strong><br/>"
		  . __( "You can also set some sidewide settings here to add specific meta tags or to remove some unneeded cruft.", 'wordpress-seo' ) . "</p>",
		'button2'  => __( 'Next', 'wordpress-seo' ),
		'function' => 'window.location="' . admin_url( 'admin.php?page=v2press_options&tab=radio' ) . '";'
	  ),
	  'radio'         => array(
		'content'  => "<h3>" . __( "Social settings", 'wordpress-seo' ) . "</h3>"
		  . "<p><strong>" . __( 'Facebook OpenGraph', 'wordpress-seo' ) . '</strong><br/>'
		  . __( "On this page you can enable the OpenGraph functionality from this plugin, as well as assign a Facebook user or Application to be the admin of your site, so you can view the Facebook insights.", 'wordpress-seo' ) . "</p>"
		  . '<p>' . sprintf( __( 'Read more about %1$sFacebook OpenGraph%2$s.', 'wordpress-seo' ), '<a target="_blank" href="http://yoast.com/facebook-open-graph-protocol/#utm_source=wpadmin&utm_medium=wpseo_tour&utm_term=link&utm_campaign=wpseoplugin">', '</a>' ) . "</p>"
		  . "<p><strong>" . __( 'Twitter Cards', 'wordpress-seo' ) . '</strong><br/>'
		  . sprintf( __( 'This functionality is currently in beta, but it allows for %1$sTwitter Cards%2$s.', 'wordpress-seo' ), '<a target="_blank" href="http://yoast.com/twitter-cards/#utm_source=wpadmin&utm_medium=wpseo_tour&utm_term=link&utm_campaign=wpseoplugin">', '</a>' ) . "</p>",
		'button2'  => __( 'Next', 'wordpress-seo' ),
		'function' => 'window.location="' . admin_url( 'admin.php?page=v2press_options&tab=select' ) . '";'
	  ),
	  'select'            => array(
		'content'  => '<h3>' . __( 'XML Sitemaps', 'wordpress-seo' ) . '</h3><p>' . __( 'This plugin adds an XML sitemap to your site. It\'s automatically updated when you publish a new post, page or custom post and Google and Bing will be automatically notified.', 'wordpress-seo' ) . '</p><p>' . __( 'Be sure to check whether post types or taxonomies are showing that search engines shouldn\'t be indexing, if so, check the box before them to hide them from the XML sitemaps.', 'wordpress-seo' ) . '</p>',
		'button2'  => __( 'Next', 'wordpress-seo' ),
		'function' => 'window.location="' . admin_url( 'admin.php?page=v2press_options&tab=custom' ) . '";'
	  ),
	  'custom'     => array(
		'content'  => '<h3>' . __( 'Permalink Settings', 'wordpress-seo' ) . '</h3><p>' . __( 'All of the options here are for advanced users only, if you don\'t know whether you should check any, don\'t touch them.', 'wordpress-seo' ) . '</p>',
		'button2'  => __( 'Next', 'wordpress-seo' ),
		'function' => 'window.location="' . admin_url( 'admin.php?page=v2press_options&tab=non_value' ) . '";'
	  ),
	  'non_value' => array(
		'content'  => '<h3>' . __( 'Breadcrumbs Settings', 'wordpress-seo' ) . '</h3><p>' . sprintf( __( 'If your theme supports my breadcrumbs, as all Genesis and WooThemes themes as well as a couple of other ones do, you can change the settings for those here. If you want to modify your theme to support them, %sfollow these instructions%s.', 'wordpress-seo' ), '<a target="_blank" href="http://yoast.com/wordpress/breadcrumbs/#utm_source=wpadmin&utm_medium=wpseo_tour&utm_term=link&utm_campaign=wpseoplugin">', '</a>' ) . '</p>',
		'button2'  => __( 'Next', 'wordpress-seo' ),
		'function' => 'window.location="' . admin_url( 'admin.php?page=v2press_options&tab=theme_info' ) . '";'
	  ),
	  'theme_info'            => array(
		'content'  => '<h3>' . __( 'RSS Settings', 'wordpress-seo' ) . '</h3><p>' . __( 'This incredibly powerful function allows you to add content to the beginning and end of your posts in your RSS feed. This helps you gain links from people who steal your content!', 'wordpress-seo' ) . '</p>',
	  	'button2' => '',
	  	'function' => ''
	  )
	);

		$tab = '';

		if ( isset( $_GET['tab'] ) )
		  $tab = $_GET['tab'];

		$function = '';
		$button2  = '';
		$opt_arr  = array();

		$selector = "#{$tab}_section_group_li";

		if ( 'admin.php' != $pagenow || ! array_key_exists( $tab, $adminpages ) ) {
			$selector = 'a.toplevel_page_v2press_options';
			$content = '<h3>' . __( 'Congratulations!', 'wordpress-seo' ) . '</h3>';
			$content .= '<p>' . __( 'You\'ve just installed WordPress SEO by Yoast! Click "Start Tour" to view a quick introduction of this plugins core functionality.', 'wordpress-seo' ) . '</p>';
			$opt_arr = array(
				'content'  => $content,
				'position' => array( 'edge' => 'top', 'align' => 'top' )
		  	);
		  	$button2  = __( "Start Tour", 'wordpress-seo' );
		  	$function = 'document.location="' . admin_url( 'admin.php?page=v2press_options&tab=general' ) . '";';
		} else {
			if ( '' != $tab && in_array( $tab, array_keys( $adminpages ) ) ) {
				$opt_arr  = array(
					'content'      => $adminpages[$tab]['content'],
					'position'     => array( 'edge' => 'left', 'align' => 'center' ),
					'pointerWidth' => 400
				);
				$button2  = $adminpages[$tab]['button2'];
				$function = $adminpages[$tab]['function'];
			}
		}

		$this->print_scripts( $selector, $opt_arr, __( 'Close', 'v2press' ), $button2, $function, $tab );
	}

	/**
	 * Load a tiny bit of CSS in the head
	 */
	function admin_head() {
	?>
	<style type="text/css" media="screen">
	#pointer-primary {
		margin: 0 5px 0 0;
	}
	</style>
	<script>
		function vp_setIgnore( option, hide, nonce ) {
			jQuery.post(ajaxurl, {
				action: 'vp_set_ignore',
				option: option,
				_wpnonce: nonce
			}, function(data) {
				if (data) {
					jQuery('#'+hide).hide();
				}
			} );
		}
	</script>
	<?php
	}

	/**
	 * Prints the pointer script.
	 *
	 * @param string $selector The CSS selector the pointer is attached to.
	 * @param array $options The options for the pointer.
	 * @param string $button1 Text for button 1
	 * @param string|bool $button2 Text for button 2 (or false to not show it, defaults to false)
	 * @param string $button2_function The JavaScript function to attach to button 2
	 */
	function print_scripts( $selector, $options, $button1, $button2 = false, $button2_function = '', $tab = '' ) {
	?>
	<script type="text/javascript">
		//<![CDATA[
		(function ($) {
			var vp_pointer_options = <?php echo json_encode( $options ); ?>, setup;

			vp_pointer_options = $.extend(vp_pointer_options, {
				buttons: function (event, t) {
					button = jQuery('<a id="pointer-close" class="button-secondary">' + '<?php echo $button1; ?>' + '</a>');
					button.bind('click.pointer', function () {
						t.element.pointer('close');
		  			});
		  			return button;
				},
				close:function () {
				}
	  		});

	  		setup = function () {
				$('<?php echo $selector; ?>').pointer(vp_pointer_options).pointer('open');
				<?php if ( $button2 ) { ?>
		  		$('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo $button2; ?>' + '</a>');
				$('#pointer-primary').click(function () {
					<?php echo $button2_function; ?>
					$('#<?php echo $tab; ?>_section_group_li_a').click();
		  		});
		  		<?php } ?>
		  		$('#pointer-close').click(function () {
					vp_setIgnore("tour", "wp-pointer-0", "<?php echo wp_create_nonce( 'vp-ignore' ); ?>");
				});
	  		};

			if (vp_pointer_options.position && vp_pointer_options.position.defer_loading)
				$(window).bind('load.wp-pointers', setup);
	  		else
				$(document).ready(setup);
		})(jQuery);
		//]]>
	</script>
	<?php
	}

	/**
	 * Handle hide-pointer AJAX call.
	 */
	function vp_set_ignore() {
		if ( ! current_user_can( 'manage_options' ) )
			die( '-1' );

		check_ajax_referer( 'vp-ignore' );

		$options = get_option( 'v2press_theme_options' );
		$options[ 'ignore_' . $_POST[ 'option' ] ] = 'ignore';

		update_option( 'v2press_theme_options', $options );

		die( '1' );
	}

}

new VP_Pointers;
