<?php

define( 'VP_INCLUDE_PATH', get_stylesheet_directory() . '/inc' );
define( 'VP_LIBS_PATH', get_stylesheet_directory() . '/libs' );

/**
 * Load extra functions in.
 *
 */
$includes = glob( VP_INCLUDE_PATH . '/*.php' );
// In case you don't need to include a file in /inc,
// just enter the file name without extension
$excludes = array();
if ( is_array( $includes ) && 0 < count( $includes ) ) {
  foreach ( $includes as $include ) {
    $info = pathinfo( $include );
    $name = basename( $include, '.' . $info['extension'] );
    
    if ( !in_array( $name, $excludes ) )
      include( $include );
  }
}

/**
 * V2Press activation.
 * Make sure these codes will only excute once on activation.
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
    update_option( 'v2press_installed', '1' );
    update_option( 'v2press_version', VP_VERSION );
  }
}

/**
 * Setup many things after V2Press is setupped.
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
 * Add file modification time to the style.css.
 *
 * @since 0.0.2
 */
function vp_filter_stylesheet_uri( $stylesheet_uri ) {
  $style = get_stylesheet_directory() . '/style.css';
  $mtime = filemtime( $style );
  $stylesheet_uri .= '?ver=' . $mtime;
  return $stylesheet_uri;
}
add_filter( 'stylesheet_uri', 'vp_filter_stylesheet_uri' );

/**
 * Enqueue JavaScript files to footer.
 *
 * @since 0.0.1
 */
function vp_enqueue_scripts(){
  wp_enqueue_script( 'html5placeholder', get_bloginfo('template_url').'/js/jquery.html5.placeholder.js', array('jquery'), '1.0.1', true );
  wp_enqueue_script( 'facebox', get_bloginfo( 'template_url' ) . '/js/facebox.js', array( 'jquery' ), '1.3', true );
  wp_enqueue_script( 'global', get_bloginfo( 'template_url' ) . '/js/global.js', array( 'jquery' ), '0.0.1', true );

  // Localize script
  wp_localize_script( 'global', 'globalL10n', array(
    'replyConfirm' => __( 'One reply a time please! Replace the previous one?', 'v2press' ),
    'stylesheetURI' => get_stylesheet_directory_uri(),
    'newTopicURL' => vp_get_page_url_by_slug( 'new' )
  ) );
}
add_action('wp_enqueue_scripts', 'vp_enqueue_scripts');

/**
 * Disable the toolbar at frontend.
 *
 * @since 0.0.1
 */
if ( defined( 'WP_DEBUG' ) && true != WP_DEBUG && !is_admin() )
  show_admin_bar( false );

/**
 * Prevent none administrator user from access admin area.
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
