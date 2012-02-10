<?php

define( 'VP_VERSION', '0.0.2' );
define( 'VP_INCLUDE_PATH', get_stylesheet_directory() . '/inc' );
define( 'VP_LIBS_PATH', get_stylesheet_directory() . '/libs' );

/**
 * Load extra functions in.
 *
 */
$includes = array(
  'custom-hooks',
  'custom-options',
  'custom-template-tags',
  'custom-category',
  'member',
  'favorite',
  'custom-page',
  'custom-comment',
  'custom-post',
  'custom-user',
  'theme-options',
  'seo',
  'notifications'
  );
foreach ( $includes as $include ) {
  include( VP_INCLUDE_PATH . '/' . $include . '.php' );
}

/**
 * V2Press activation.
 * Make sure these codes will only excute once.
 *
 * @since 0.0.1
 */
function vp_active() {
  vp_init_options();
  vp_init_pages();
}
vp_activation_hook( 'vp_active' );

/**
 * Run when V2Press first activation or when we need update.
 *
 * @since 0.0.1
 */
function vp_activation_hook( $function ) {
  $installed = get_option( 'v2press_installed' );
  $version = get_option( 'v2press_version' );

  if ( !$installed || ( VP_VERSION != $version ) ) {
    call_user_func( $function );
    update_option( $installed, '1' );
    update_option( 'v2press_version', VP_VERSION );
  }
}

/**
 * V2Press setup
 * Setup many things after this theme is setupped.
 *
 * @since 0.0.1
 */
function vp_theme_setup() {
  // i18n
  load_theme_textdomain( 'v2press', get_template_directory() . '/languages' );

  // Register navigation menus
  register_nav_menu( 'footer', __( 'Footer Navigation', 'v2press' ) );
}
add_action( 'after_setup_theme', 'vp_theme_setup' );

/**
 * Add JavaScript files to footer
 *
 * @since 0.0.1
 */
function vp_enqueue_scripts(){
  wp_enqueue_script( 'html5placeholder', get_bloginfo('template_url').'/js/jquery.html5.placeholder.js', array('jquery'), '1.0.1', true );
}
add_action('wp_enqueue_scripts', 'vp_enqueue_scripts');

/**
 * Disable the toolbar at frontend
 *
 * @since 0.0.1
 */
if ( !is_admin() )
  show_admin_bar(false);

/**
 * Prevent none administrator user from admin area.
 *
 * @since 0.0.1
 */
function vp_prevent_admin_access() {
  if ( strpos( strtolower( $_SERVER['REQUEST_URI'] ), '/wp-admin' ) !== false
       && !current_user_can( 'administrator' ) ) {
    wp_redirect( home_url() );
  }
}
add_action( 'init', 'vp_prevent_admin_access', 0 );

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