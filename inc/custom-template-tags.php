<?php
/**
 * This file contents common custom template tags.
 * And some other files may also content custom template tags.
 *
 * @since 0.0.1
 */

/**
 * Custom the wp_title()
 *
 * @since 0.0.1
 */
function vp_title() {
  global $page, $paged;
  $output = '';

  if ( 0 < vp_unread_notifications_count() )
    $output .= '(' . vp_unread_notifications_count() . ') ';

  $name = get_bloginfo( 'name' );
  $description = get_bloginfo( 'description' );

  $sep = ' &#155; ';
  $normal_title = wp_title( $sep, false, 'left' );

  $output .= $name ;

  if ( $description && ( is_home() || is_front_page() ) ) {
    $output .= ' - ' . $description;
  } elseif ( is_page( 'member' ) ) {
    $output .= $sep . esc_attr( get_query_var( 'member_name' ) );
  } else {
    $output .= $normal_title;
  }

  // Add a page number if necessary
  if ( $paged >= 2 || $page >= 2 )
  	$output .= $sep . __( 'Page', 'v2press' ). max( $paged, $page );

  echo $output;
}

/**
 * Filter the body_class().
 * Add 'single-category-{$slug}' when view single topic page.
 * You can use this class to add a background image to the page for a specific category.
 *
 * @since 0.0.1
 */
function vp_filter_body_class( $classes ) {
  if ( is_single() ) {
    $post_id = get_the_ID();
    $categories = get_the_category( $post_id );

    foreach ( $categories as $cat ) {
      $classes[] = 'category-' . $cat->slug;
    }
  }
  return $classes;
}
add_filter( 'body_class', 'vp_filter_body_class' );

/**
 * Simple breadcrumb navigation.
 *
 * Just display breadcrumb at page, single and category page.
 *
 * @since 0.0.1
 *
 * @param string $sep The seperator between two navigation items.
 * @return string Just the breadcrumb navigation links, no wrapper.
 */
function vp_breadcrumb( $sep = ' &#155; ') {
  $output = '<a href="' . home_url(). '">' . get_bloginfo( 'name' ) . '</a>' . $sep;

  if ( is_page() && !is_page( 'new' ) ) {
    $output .= get_the_title();
  } elseif ( is_page( 'new' ) ) {
    if ( !empty($_GET['node'] ) ) {
      $node_id = (int) $_GET['node'];
      $node = get_term_by( 'id', $node_id, 'category' );

      if ( !$node ) {
        wp_redirect( home_url() );
        exit;
      }

      $node_name = $node->name;
      $node_link = get_category_link( $node );

      $output .= '<a href="' . $node_link . '">' .$node_name . '</a>' . $sep;
    }

    $output .= __( 'Create New Topic', 'v2press' );
  } elseif ( is_single() && !vp_is_edit() ) {
    $output .= get_the_category_list( ', ' );
  } elseif ( vp_is_edit() ) {
    $output .= sprintf( __( 'Edit "%s"', 'v2press' ), get_the_title() );
  } elseif ( is_category() ) {
    $output .= single_cat_title( '', false );
  } elseif ( is_author() ) {
    $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
    $output .= $curauth->user_login;
  } elseif ( is_search() ) {
    $output .= sprintf( __( 'Search results for "%s"', 'v2press' ), get_search_query() );
  } else if ( is_404() ) {
    $output .= __( 'Not Found', 'v2press' );
  }

  echo $output;
}

/**
 * Archives page navigation link.
 *
 * @since 0.0.1
 */
function vp_page_navi() {
  global $paged, $wp_query;

  if ( !$paged )
    $paged = 1;

  $total_pages = $wp_query->max_num_pages;
  $total_posts = $wp_query->found_posts;

  if ( 0 == $total_pages )
    $paged = 0;

  $output = '<div class="pages-navi">' . "\n";
  $output .= '<span class="fade">' . $paged . '/' . $total_pages . '</span> <span class="snow">- ';
  $output .= sprintf( _n( '%d topic', '%d topics', 'v2press', $total_posts ), $total_posts );
  $output .= "</span>\n" . '<div class="prev-page f-left">' . get_previous_posts_link( __( '&laquo; Previous', 'v2press' ) ) . "</div>\n";
  $output .= '<div class="next-page f-right">' . get_next_posts_link( __( 'Next &raquo;', 'v2press' ) ) . "</div>\n";
  $output .= '</div>';

  echo $output;
}

/**
 * Display the feed url in head.
 *
 * You can setting feed url with feedburner or else, otherwise the WordPress default one will used.
 *
 * @since 0.0.2
 */
function vp_feed_link() {
	$feed = vp_get_theme_option( 'feed_url' );

	if ( !$feed )
		$feed = get_feed_link();
		
	echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo( 'name' ) . ' Latest Topic Feed" href="' . $feed . '" />';
}
