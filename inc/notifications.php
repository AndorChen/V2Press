<?php
/**
 * This file contents functionalities related to notifications.
 *
 * @since 0.0.1
 */

/**
 * Display the notifications list.
 *
 * @since 0.0.2
 */
function vp_notifications_list() {
  $user_id = get_current_user_id();
  $notifications = (array) get_user_meta( $user_id, 'v2press_notifications', true );
  if ( '' == $notifications[0] )
    $notifications = array();

  $notifications = array_reverse( $notifications );

  $output = '';
  if ( empty( $notifications ) ) {
    $output .= '<p class="xlarge center fade">' . __( 'No notifications yet.', 'v2press' ) . '</p>';
  } else {
    $output .= '<ul class="notifications-list">';
    foreach ( $notifications as $cmt_id ) {
      $comment = get_comment( $cmt_id );
      $comment_date = $comment->comment_date;
      $comment_content = $comment->comment_content;

      $user_id = $comment->user_id;
      $user_link = vp_get_user_profile_link( $user_id, false );
      $user_avatar_link = vp_get_user_avatar_link( 24, $user_id );

      $topic_id = $comment->comment_post_ID;
      $topic_title = get_the_title( $topic_id );
      $topic_permalink = get_permalink( $topic_id );
      $topic_link = '<a href="' . $topic_permalink . '" rel="bookmark">' . esc_attr( $topic_title ) . '</a>';

      $output .= '<li class="notification-item">' . $user_avatar_link;
      $output .= sprintf( __( '%1$s mentioned you when reply %2$s at %3$s', 'v2press'), $user_link, $topic_link, $comment_date );
      $output .= '<div class="notification-content">' . $comment_content . '</div>';
      $output .= '<a class="notification-delete" href="?delete=' . $cmt_id . '">' . _x( 'delete', 'notification', 'v2press' ) . '</a></li>';
    }
    $output .= '</ul>';
  }

  echo $output;
}

/**
 * Push a notification to user after a reply is posted.
 *
 * @since 0.0.1
 */
function vp_push_notify( $comment_ID ) {
  $comment = get_comment( $comment_ID );
  $parent_id = $comment->comment_parent;
  if ( '0' == $parent_id )
    return;

  $parent = get_comment( $parent_id );
  $parent_user_id = $parent->user_id;
  if ( '0' == $parent_user_id )
    return;

  // if the reply is post by youself, don't push notify
  if ( $comment->user_id == $parent_user_id )
    return;

  $notifications = (array) get_user_meta( $parent_user_id, 'v2press_notifications', true );
  if ( '' == $notifications[0] )
    $notifications = array();

  $notifications[] = $comment_ID;
  update_user_meta( $parent_user_id, 'v2press_notifications', $notifications );

  $count = (int) get_user_meta( $parent_user_id, 'v2press_notifications_unread', true );
  $count++;
  update_user_meta( $parent_user_id, 'v2press_notifications_unread', $count );
}
add_action( 'comment_post', 'vp_push_notify' );

/**
 * Process the delete a notification.
 *
 * @since 0.0.2
 */
function vp_delete_notification() {
  if ( !is_page( 'notifications' ) || !isset( $_GET['delete'] ) )
    return;

  $id = $_GET['delete'];
  $notifications = get_user_meta( get_current_user_id(), 'v2press_notifications', true );
  if ( !in_array( $id, $notifications ) )
    return;

  $diff = array_diff( $notifications, array( $id ) );

  $update = update_user_meta( get_current_user_id(), 'v2press_notifications', $diff );
  if ( $update ) {
    wp_redirect( vp_get_page_url_by_slug( 'notifications' ) );
    exit;
  }

}
add_action( 'template_redirect', 'vp_delete_notification' );

/**
 * Retrieve the unread notifications count.
 *
 * @since 0.0.2
 */
function vp_unread_notifications_count() {
  $count = (int) get_user_meta( get_current_user_id(), 'v2press_notifications_unread', true );
  return $count;
}

function vp_unread_notifications() {
  $count = vp_unread_notifications_count();
  
  $text = '';
  if ( $count > 0 )
    $text .= '<span class="new-notifications">';

  $text .= sprintf( _n( '%d notification', '%d notifications', $count, 'v2press' ), $count );
  
  if ( $count > 0 )
    $text .= '</span>';

  echo $text;
}

/**
 * Clear the unread notifications count.
 *
 * @since 0.0.2
 */
function vp_clear_unread_notifications_count() {
  if ( is_page( 'notifications' ) )
    update_user_meta( get_current_user_id(), 'v2press_notifications_unread', '0' );
}
add_action( 'template_redirect', 'vp_clear_unread_notifications_count' );
