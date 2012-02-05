<?php
/**
 * This file contents page related functionalities.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * Post related custom template tags.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Retrieve the current url.
 *
 * @since 0.0.1
 * @return string The current url.
 */
function vp_current_url() {
  $url = 'http';
  
  if ( isset( $_SERVER['HTTPS'] ) ) {
  	if ( 'on' == $_SERVER['HTTPS'] )
  	  $url .= 's';
  }
  
  $url .= "://";
  
  if ( '80' != $_SERVER['SERVER_PORT'] ) {
  	$url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
  } else {
  	$url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  }
  
  return $url;
}
 
/**
 * Retrieve a page's url by its slug.
 *
 * @since 0.0.1
 *
 * @param string $slug The page's slug.
 * @param string $query Optional. The url query parameter. Default is no query.
 * @return string The page's url.
 */
function vp_get_page_url_by_slug( $slug, $query = '' ) {
  global $wpdb;
  $id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $slug, 'page' ) );
  
  $url = '';
  
  if ( $id ) {
    $url = get_page_link( $id );
  } else {
    $url .= home_url() . '/404/';
  }
  
  if ( !empty( $query ) ) {
    $url .= '?' . $query;
  }
  
  return $url;
}

/**
 * Retrieve a link to a page by the page's slug.
 *
 * @since 0.0.1
 *
 * @param string $slug The page slug.
 * @param string $link_text Optional. The link text. If empty, the page title.
 * @param string $class Optional. The link class. Default is none.
 * @param string $rel Optional. If there is a rel. Default none.
 * @param bool $display Optional. If display or return the output. Default is display it.
 * @return string The link to the page.
 */
function vp_page_link( $args = array() ) {
  global $wpdb;
  
  $defaults = array(
    'slug' => null,
    'text' => '',
    'class' => '',
    'rel' => '',
    'display' => true
  );
  
  $args = wp_parse_args( $args, $defaults );
  extract( $args );
  
  $url = vp_get_page_url_by_slug( $slug );
  
  if ( empty( $text ) ) {
    $post_title = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $slug, 'page' ) );
    if ( $post_title ) {
      $text = $post_title;
    } else {
      $text = __( 'Not Found', 'v2press' );
    }
  }
  
  $output = '<a';
  
  if ( !empty( $class ) ) {
    $class = esc_attr( $class );
    $output .= ' class=' . $class;
  }
  
  if ( !empty( $rel ) ) {
    $output .= ' rel=' . $rel;
  }
  
 $output .= ' href="' . $url . '" title="' . esc_attr( $text ) . '">' . $text . '</a>';
 
  if ( $display ) {
    echo $output;
  } else {
    return $output;
  }
}


/* =============================================================================
 * Setup needed pages.
 *
 * @since 0.0.1
 ============================================================================ */
/**
 * How to create page.
 * Create the specified page, return the page id, then update the associated page id option.
 *
 * @since 0.0.1
 *
 * @param string $slug The slug for page.
 * @param string $option
 * @param string $page_title The title for page.
 * @param string $page_content The content for page.
 * @param string $page_template The template for page. Default is the default template.
 * @param int $post_parent The parent of the page.
 */
function vp_create_page( $slug, $option, $page_title = '', $page_content = '', $post_parent = 0, $page_template = '' ) {
	global $wpdb;
	 
	$option_value = get_option( $option ); 
	 
	// Page id already exists, terminate!
	if ( $option_value > 0 ) {
		if ( get_post( $option_value ) ) {
			return;
		}
	}

  // Page slug already exists, terminate!	
	$page_found = $wpdb->get_var( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' LIMIT 1;" );
	if ( $page_found ) {
		if ( !$option_value ) {
		  update_option( $option, $page_found );
		}		
		return;
	}
	
	$page_data = array(
                  'post_status' => 'publish',
                  'post_type' => 'page',
                  'post_author' => 1,
                  'post_name' => $slug,
                  'post_title' => $page_title,
                  'post_content' => $page_content,
                  'post_parent' => $post_parent,
                  'comment_status' => 'closed'
                );
                
  $page_id = wp_insert_post( $page_data );
  
  if ( '' != $page_template ) {
    update_post_meta( $page_id, '_wp_page_template', $page_template );
  }
  
  update_option( $option, $page_id );
}

/**
 * Create the pages for V2Press.
 *
 * @since 0.0.1
 */
function vp_init_pages() {
  // Signup page
  vp_create_page( esc_sql( 'signup' ), 'v2press_signup_page_id', 'Signup', '[vp-signup-form]', 'page-need-signout.php' );
  
  // Signin page
  vp_create_page( esc_sql( 'signin' ), 'v2press_signin_page_id', 'Signin', '[vp-signin-form]', 0, 'page-need-signout.php' );
  
  // Forgot password page
  vp_create_page( esc_sql( 'forgot' ), 'v2press_forgot_page_id', 'Forgot Password', '[vp-forgot-password-form]', 0, 'page-need-signout.php' );
  
  // Reset password page
  vp_create_page( esc_sql( 'reset' ), 'v2press_reset_page_id', 'Reset Password', '[vp-reset-password-form]', 0, 'page-need-signout.php' );
  
  // User settings page
  vp_create_page( esc_sql( 'settings' ), 'v2press_settings_page_id', 'Settings', '', 0, 'page-settings.php' );
  
  // Bookmarks page
  vp_create_page( esc_sql( 'bookmarks' ), 'v2press_bookmark_page_id', 'Bookmarks', '', 0, 'page-bookmarks.php' );
  
  // Following page
  vp_create_page( esc_sql( 'following' ), 'v2press_following_page_id', 'Following', '', 0, 'page-following.php' );
  
  // Create new topic page
  vp_create_page( esc_sql( 'new' ), 'v2press_new_topic_page_id', 'Create New Topic', '', 0, 'page-new-topic.php' );
  
  // Recent topics page
  vp_create_page( esc_sql( 'recent' ), 'v2press_recent_page_id', 'Recent', '', 0, 'page-recent.php' );
  
  // All nodes list page
  vp_create_page( esc_sql( 'planes' ), 'v2press_planes_page_id', 'All nodes', '', 0, 'page-planes.php' );
}