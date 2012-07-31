<?php
/**
 * The V2Press bootstrap.
 *
 * @since 0.0.3
 */

/**
 * V2Press activation.
 *
 * Make sure these codes will only excute once on activation.
 *
 * @since 0.0.1
 */
function vp_active() {
    vp_init_options();
    vp_create_pages();
}
vp_activation_hook( 'vp_active' );

/**
 * Set the options need by V2Press.
 *
 * @since 0.0.1
 */
function vp_init_options() {
    // General
    update_option( 'users_can_register', '1' );
    update_option( 'default_role', 'author' );

    // Writing
    update_option( 'use_smiles', '0' );

    // Reading
    update_option( 'posts_per_page', '15' );
    update_option( 'posts_per_rss', '15' );

    // Discussion
    update_option( 'default_pingback_flag', '0' );
    update_option( 'comment_registration', '1' );
    update_option( 'thread_comments', '0' );
    update_option( 'page_comments', '1' );
    update_option( 'comments_notify', '0' );
    update_option( 'comment_whitelist', '0' );

    // Permalink
    update_option( 'permalink_structure', '/t/%post_id%' );
    update_option( 'category_base', 'go' );
    global $wp_rewrite;
    $wp_rewrite->flush_rules();

    // Administrator
    update_user_meta( '1', 'rich_editing', 'false' );
}

/**
 * Run when V2Press first activation or when we need update.
 *
 * @since 0.0.1
 */
function vp_activation_hook( $function ) {
    $installed = get_option( 'v2press_installed' );
    $version = get_option( 'v2press_version' );

    if ( ! $installed || ( VP_VERSION != $version ) ) {
        call_user_func( $function );
        update_option( 'v2press_installed', '1' );
        update_option( 'v2press_version', VP_VERSION );
    }
}

/**
 * When switch to another theme delete the 'v2press_installed' option.
 *
 * @since 0.0.3
 */
function vp_switch_theme( $new_name, $new_theme ) {
    if ( 'V2Press' == $new_name )
        return;

    delete_option( 'v2press_installed' );
}
add_action( 'switch_theme', 'vp_switch_theme', 10, 2 );

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
       && !current_user_can( 'administrator' ) )
        wp_redirect( home_url() );
}
add_action( 'init', 'vp_prevent_admin_access', 0 );
