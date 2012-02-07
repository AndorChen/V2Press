<?php
/**
 * This file contents functionalities related to user.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * User related template tags.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Display the user's profile text link
 *
 * @since 0.0.1
 *
 * @param int $user_id Optional. The user's id. If false, the current user's id.
 * @return string The user's profile text link, if no $user_id the current user's.
 */
function vp_user_profile_link( $user_id = false ) {
  echo vp_get_user_profile_link( $user_id );
}

/**
 * Retrieve the user's profile text link
 *
 * @since 0.0.2
 *
 * @use vp_get_user_profile_url()
 * @param bool|int $user_id Optional. The user's id. If false, the current user's id.
 * @param bool $display Optional. If display the link or return it. Default is display.
 * @return string The user's profile text link, if no $user_id the current user's.
 */
function vp_get_user_profile_link( $user_id = false) {
  if ( ! $user_id ) {
    $user_id = get_current_user_id();
  } else {
    $user_id = (int) $user_id;
  }

  $profile_url = get_author_posts_url( $user_id );
  $user_login = get_userdata( $user_id )->user_login;

  $output = '<a href="' . $profile_url . '">' . $user_login . '</a>';

  return $output;
}

/**
 * Display the user's profile avatar link.
 *
 * @since 0.0.1
 */
function vp_user_avatar_link( $size = 48, $user_id = false) {
  echo vp_get_user_avatar_link( $size, $user_id );
}

/**
 * Retrieve the user's profile avatar link.
 *
 * @since 0.0.2
 *
 * @use vp_get_user_profile_url()
 * @param int $user_id The user's id.
 * @return string The user's profile avatar link, if no $user_id the current user's.
 */
function vp_get_user_avatar_link( $size = 48, $user_id = false) {
  if ( ! $user_id ) {
    $user_id = wp_get_current_user()->id;
  } else {
    $user_id = (int) $user_id;
  }

  $profile_url = get_author_posts_url( $user_id );

  $default_avatar = get_template_directory_uri() . '/images/default-avatar-' . $size . '.png';
  $avatar = get_avatar( $user_id, $size, $default_avatar );

  return '<a href="' . $profile_url . '">' . $avatar . '</a>';
}
