<?php
/**
 * This file contents seo functionalities.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * Noindex some predefined Pages for SEO.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Display the noindex meta in head.
 *
 * @since 0.0.1
 */
function vp_noindex_page() {
  echo '<meta name="robots" content="noindex" />' . "\n";
}

/**
 * Do not index some predefined Pages.
 *
 * @since 0.0.1
 */
function vp_noindex_predefined_pages() {
  $pages = vp_predefined_pages();
  foreach ( $pages as $page ) {
    if ( is_page( $page ) ) {
      return vp_noindex_page();
    } 
  }
}
add_action( 'wp_head', 'vp_noindex_predefined_pages' );

/**
 * Add trailing slash to everthing except single topic page url.
 *
 * @since 0.0.1
 */
function vp_add_trailingslash( $url, $type ) {
	if ( 'single' === $type ) {
		return $url;
	} else {
		return trailingslashit( $url );
	}
}
add_filter( 'user_trailingslashit', 'vp_add_trailingslash', 10, 2 );

/**
 * Redirect some archive pages to home.
 *
 * @since 0.0.1
 */
function vp_archive_redirect() {
  global $wp_query;
  if ( $wp_query->is_date ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
}
add_action( 'wp', 'vp_archive_redirect' );


/**
 * Not follow the comments popup link.
 *
 * @since 0.0.1
 */
function vp_echo_nofollow() {
  echo ' rel="nofollow"';
}
add_filter( 'comments_popup_link_attributes', 'vp_echo_nofollow' );


/**
 * Add description meta data to head
 *
 * @since 0.0.1
 */
function vp_description() {
  
  if ( is_home() ) {
    $desc = get_bloginfo( 'description' );
  } elseif ( is_singular() ) {
    global $post;
    $content = $post->post_content;
    $content = preg_replace('/\s+/',' ', $content );
    $content = strip_tags( $content );
    $content = stripslashes( $content );
    $content = esc_attr( $content );
    $content = strip_shortcodes( $content );
    $excerpt = substr( $content, 0, 140 );
    
    $desc = $excerpt;
  }
  
  if ( empty( $desc ) )
    return;
  
  echo '<meta name="description" content="' . $desc . '" />' . "\n";
}
add_action( 'wp_head', 'vp_description' );

/**
 * Add Google Webmaster verification code to head if needed.
 *
 * @since 0.0.2
 */
function vp_verify_google_webmaster() {
	$verify = vp_get_theme_option( 'google-webmaster-verify' );
	
	if ( empty( $verify ) )
		return;
	
	echo $verify;
}
add_action( 'wp_head', 'vp_verify_google_webmaster' );
