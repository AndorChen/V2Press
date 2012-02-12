<?php
/**
 * This file contents functionalities related to user favorites.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * Favorite a topic.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Generate the add/remove a topic to/from bookmark list link.
 *
 * @since 0.0.1
 */
function vp_bookmark() {
  if ( !is_user_logged_in() || !is_single() )
    return;
  
  $topic_id = get_the_ID();
  $user_id = wp_get_current_user()->ID;
  
  $bookmarked_num = 0;
  
  // Which users already bookmarked this topic
  $bookmarked = (array) get_post_meta( $topic_id, 'v2press_who_bookmarked_me', true );
  
  if ( '' != $bookmarked[0] ) {
    $bookmarked_num = count( $bookmarked );
  }
    
  if ( in_array( $user_id, $bookmarked ) ) {
    $action = 'remove';
    $text = __( 'Remove from Bookmark', 'v2press' );
  } else {
    $action = 'add';
    $text = __( 'Add to Bookmark', 'v2press' );
  }
  
  $output = '<div class="favorite-topic">';
  
  if ( 0 < $bookmarked_num ) {
    $output .= sprintf( _n( '%d user bookmarked', '%d users bookmarked', $bookmarked_num, 'v2press' ), $bookmarked_num );
  }
  
  $output .= '<a class="fav ' . $action . '" href="?bookmark=' . $action . '&topic=' . $topic_id . '" title="' . esc_attr($text) . '">' . $text . '</a>';
  $output .= '</div>';
  
  echo $output;
}

/**
 * Do the add/remove a topic to/from bookmark list process.
 *
 * @since 0.0.1
 */
function vp_do_bookmark() {
  $action = $_REQUEST['bookmark'];
  $topic_id = $_REQUEST['topic'];
  if ( !isset( $topic_id ) )
    $topic_id = get_the_ID();
  
  if ( !empty( $action ) && !empty( $topic_id ) ) {
  
    $user_id = wp_get_current_user()->ID;
  
    $bookmarked = (array) get_post_meta( $topic_id, 'v2press_who_bookmarked_me', true );
    if ( '' == $bookmarked[0] ) {
      $bookmarked = array();
    }
    
    $user_bookmarked = (array) get_user_meta( $user_id, 'v2press_i_bookmarked_topics', true );
    if ( '' == $user_bookmarked[0] ) {
      $user_bookmarked = array();
    }
    
    if ( 'add' == $action ) {
      if ( ! in_array( $user_id, $bookmarked ) ) {    
        $bookmarked[] = $user_id;      
        update_post_meta( $topic_id, 'v2press_who_bookmarked_me', $bookmarked );
      }
      
      if ( ! in_array( $topic_id, $user_bookmarked ) ) {
        $user_bookmarked[] = $topic_id;
        update_user_meta( $user_id, 'v2press_i_bookmarked_topics', $user_bookmarked );
      }
    } elseif ( 'remove' == $action ) {
      $bookmarked = array_diff( $bookmarked, array( $user_id ) );
      $bookmarked = array_values( $bookmarked );      
      update_post_meta( $topic_id, 'v2press_who_bookmarked_me', $bookmarked );
      
      $user_bookmarked = array_diff( $user_bookmarked, array( $topic_id ) );
      $user_bookmarked = array_values( $user_bookmarked );      
      update_user_meta( $user_id, 'v2press_i_bookmarked_topics', $user_bookmarked );
    }
    
    wp_redirect( get_permalink() );
    exit;
  }
}
add_action( 'template_redirect', 'vp_do_bookmark' );

/**
 * Retrieve the current user's bookmark list count.
 *
 * @since 0.0.1
 */
function vp_get_bookmarks_count() {
  $count = 0;
  
  $user_id = wp_get_current_user()->ID;
  $user_bookmarked = (array) get_user_meta( $user_id, 'v2press_i_bookmarked_topics', true );
  
  if ( '' != $user_bookmarked[0] ) {
    $count = count( $user_bookmarked );
  }
  
  return $count;
}
 
 
/* =============================================================================
 * Favorite a user, aka following a user.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Generate the following/unfollowing a user link.
 *
 * @since 0.0.1
 */
function vp_following() {
  if ( !is_user_logged_in() )
    return;
  
  $current_user_id = get_current_user_id();
  $user_id = get_user_by( 'login', get_query_var( 'author_name' ) )->ID;
    
  if ( $current_user_id != $user_id ) {
    $following = (array) get_user_meta( $current_user_id, 'v2press_i_following_who', true );
    // User meta not exists
    if ( '' == $following[0] )
      $floowing = array();
    
    // I already following you
    if ( in_array( $user_id, $following ) ) {
      $class = 'btn';
      $action = 'remove';
      $text = __( 'Unfollowing', 'v2press' );
    
    // I yet not following you
    } else {
      $class = 'btn gold';
      $action = 'add';
      $text = __( 'Following', 'v2press' );
    }
    
    $output = '<div class="follow-user">';
    $output .= '<a class="' . $class . ' ' . $action . '" href="?following=' . $action . '&user=' . $user_id . '" title="' . $text . '">' . $text . '</a>';
    $output .= '</div>';
    
    echo $output; 
  }
}

/**
 * Do the following/unfollowing a user process.
 *
 * @since 0.0.1
 */
function vp_do_following() {
  $action = $_REQUEST['following'];
  $user_id = $_REQUEST['user'];
  
  if ( !empty( $action ) && !empty( $user_id ) ) {
    $current_user_id = wp_get_current_user()->ID;
    
    $i_f_who = (array) get_user_meta( $current_user_id, 'v2press_i_following_who', true );
    if ( '' == $i_f_who[0] ) {
      $i_f_who = array();
    }
    $who_f_him = (array) get_user_meta( $user_id, 'v2press_who_followed_me', true );
    if ( '' == $who_f_him[0] ) {
      $who_f_him = array();
    }
    
    // Following a user
    if ( 'add' == $action ) {
      if ( !in_array( $user_id, $i_f_who ) ) {
        $i_f_who[] = $user_id;
        update_user_meta( $current_user_id, 'v2press_i_following_who', $i_f_who );
      }
      
      if ( !in_array( $current_user_id, $who_f_him ) ) {
        $who_f_him[] = $current_user_id;
        update_user_meta( $user_id, 'v2press_who_following_me', $who_f_him );
      }
    } elseif ( 'remove' == $action ) {
      $i_f_who = array_diff( $i_f_who, array( $user_id ) );
      $i_f_who = array_values( $i_f_who );
      update_user_meta( $current_user_id, 'v2press_i_following_who', $i_f_who );
      
      $who_f_him = array_diff( $who_f_him, array( $current_user_id ) );
      $who_f_him = array_values( $who_f_him );
      update_user_meta( $user_id, 'v2press_who_following_me', $who_f_him );
    } // END if $action
    
    $redirect_to = get_author_posts_url( $user_id );
    wp_redirect( $redirect_to );
    exit;
  } // END if !empty( $action )
} // END vp_do_following()
add_action( 'template_redirect', 'vp_do_following' );

/**
 * Retrieve the current user's following count.
 *
 * @since 0.0.1
 */
function vp_get_following_count() {
  $count = 0;
  
  $current_user_id = wp_get_current_user()->ID;
  $i_f_who = (array) get_user_meta( $current_user_id, 'v2press_i_following_who', true );
  // User meta exits
  if ( '' != $i_f_who[0] ) {
    $count = count( $i_f_who );
  }
  
  return $count;
}

/**
 * Retrieve a user's followers count.
 *
 * @since
 */
function vp_get_followers_count() {
  $count = 0;
  
  $user_id = get_user_by( 'login', get_query_var( 'member_name' ) )->ID;
  $who_f_me =  (array) get_user_meta( $user_id, 'v2press_who_following_me', true );
  // User meta exists
  if ( '' != $who_f_me[0] ) {
    $count = count( $who_f_me );
  }
  
  return $count;
}