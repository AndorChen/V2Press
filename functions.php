<?php

define( 'VP_VERSION', '0.0.1' );
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
  'seo'
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
  update_option( 'v2press_version', VP_VERSION );
}
vp_activation_hook( 'vp_active' );

function vp_activation_hook( $function ) {
  $option_name = 'v2press_installed';
  if ( !get_option( $option_name ) ) {
    call_user_func( $function );
    update_option( $option_name, '1' );
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
