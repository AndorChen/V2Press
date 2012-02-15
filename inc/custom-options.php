<?php
/**
 * This file will initialize some WordPress buildin options to fit V2Press.
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