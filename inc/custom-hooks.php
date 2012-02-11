<?php
/**
 * This file contents hooks modified by V2Press.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * For Markdown.
 *
 * @since 0.0.1
 ============================================================================ */

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'convert_smilies' );
remove_filter( 'the_content_rss', 'wpautop' );
remove_filter( 'content_save_pre',  'balanceTags', 50 );
remove_filter( 'excerpt_save_pre',  'balanceTags', 50 );
add_filter( 'the_content',  	  'balanceTags', 50 );
add_filter( 'get_the_excerpt', 'balanceTags', 9 );

require_once( VP_LIBS_PATH . '/markdown-extra.php' );
remove_filter( 'comment_text', 'wpautop', 30 );
remove_filter( 'comment_text', 'make_clickable', 9 );
remove_filter( 'comment_text', 'convert_smilies', 20 );
add_filter( 'pre_comment_content', 'Markdown', 6 );
add_filter( 'get_comment_text',    'Markdown', 6 );
add_filter( 'get_comment_excerpt', 'Markdown', 6 );

/* =============================================================================
 * Do not need these in head.
 *
 * @since 0.0.1
 ============================================================================ */
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );