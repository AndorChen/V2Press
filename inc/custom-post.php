<?php
/**
 * This file contents functionalities related to post.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * Post related custom template tags.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Display the post author's avatar link to profile page, must use within the loop.
 *
 * @since 0.0.1
 */
function vp_the_author_profile_link() {
  echo vp_get_the_author_profile_link();
}
/**
 * Retrieve the post author's avatar link to profile page, must use within the loop.
 *
 * @since 0.0.2
 *
 * @use vp_user_profile_link()
 */
function vp_get_the_author_profile_link() {
  return vp_get_user_profile_link( get_the_author_meta( 'id' ) );
}

/**
 * Display the post author's avatar link to profile page, must use within the loop.
 *
 * @since 0.0.1
 *
 * @use vp_user_avatar_link()
 */
function vp_the_author_avatar_link( $size = 48 ) {
  return vp_user_avatar_link( $size, get_the_author_meta( 'id' ) );
}

/**
 * Display or retrieve the Twitter like time ago text for post.
 *
 * @sice 0.0.1
 *
 * @param bool $display If display or return the output. Default is display.
 * @use BUild in human_time_diff()
 */
function vp_time_ago( $display = true ) {
  $output = sprintf( __( '%s ago', 'v2press' ), human_time_diff( get_the_time( 'U' ), current_time('timestamp') ) );
  if ( $display ) {
    echo $output;
  } else {
    return $output;
  }
}


/* =============================================================================
 * Query posts order by last comment date in home.php.
 *
 * @since 0.0.1
 ============================================================================ */
function vp_posts_fields( $query ) {
  global $wpdb;
  return( $query .", IF( $wpdb->comments.comment_date, MAX( $wpdb->comments.comment_date ), $wpdb->posts.post_date ) AS last_comment_date " );
}

function vp_posts_join( $query ) {
  global $wpdb;
  return( $query ." LEFT JOIN $wpdb->comments ON $wpdb->comments.comment_post_id = $wpdb->posts.ID " );
}

function vp_posts_groupby( $query ) {
  global $wpdb;
  return( " $wpdb->posts.ID ". $query );
}

function vp_posts_orderby( $query ) {
  return( ' last_comment_date DESC, '. $query );
}

function vp_pre_get_posts( $query ) {
  if( is_home() || is_front_page() ){
    add_filter( 'posts_fields', 'vp_posts_fields', 7 );
    add_filter( 'posts_join', 'vp_posts_join', 7 );
    add_filter( 'posts_groupby', 'vp_posts_groupby', 7 );
    add_filter( 'posts_orderby', 'vp_posts_orderby', 7 );
  }
}
add_action( 'pre_get_posts', 'vp_pre_get_posts' );

/* =============================================================================
 * Create new topic.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Display the create new topic link on category page.
 *
 * @since 0.0.1
 *
 * @param string $text Optional. The link text. Default is 'Create New Topic'.
 * @param string $before Optional. The text before link. Default is none.
 * @param string $after Optional. The text after link. Default is none.
 * @return string The final link.
 */
function vp_new_topic_link( $text = '', $before = '', $after = '') {
  if ( !is_user_logged_in() )
    return;

  global $wp_query;
  $node_id = $wp_query->get_queried_object_id();

  if ( is_category() ) {
    $url = vp_get_page_url_by_slug( 'new', 'node=' . $node_id );
  } else {
    $url = vp_get_page_url_by_slug( 'new' );
  }

  if ( '' == $text ) {
    $text = __( 'Create New Topic', 'v2press' );
  }

  $link = $before . '<a rel="nofollow" class="btn" href="' . $url . '">' . $text . '</a>' . $after;

  echo $link;
}

/**
 * The create new topic form.
 *
 * @since 0.0.1
 */
function vp_new_topic_form() {
  ob_start();

  vp_error_messages();
?>
<form id="vp-new-topic" action="" method="post">
  <fieldset>
  <?php
    if ( empty( $_GET['node'] ) ) {
  ?>
    <p>
      <?php wp_dropdown_categories( array( 'show_option_all' => __( 'Select Node', 'v2press' ) ) ); ?>
    </p>
  <?php
    }
  ?>
    <p>
      <input type="text" name="topic_title" placeholder="<?php _e( 'Topic title here', 'v2press' ); ?>" id="topic_title" class="form-field wider" value="" />
    </p>
    <p>
      <textarea name="topic_content_filtered" id="topic_content_filtered" class="form-field wider" rows="10" cols="20" ></textarea>
    </p>
    <p>
      <input type="hidden" name="action" value="new_topic" />
      <input type="hidden" name="new_topic_nonce" value="<?php echo wp_create_nonce( 'new-topic-nonce' ); ?>" />
      <input type="submit" name="submit" class="btn" value="<?php _e( 'Create Topic', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  echo ob_get_clean();
}

/**
 * Do the create new topic form process.
 *
 * @since 0.0.1
 */
function vp_do_create_new_topic() {
  if ( empty( $_POST['action'] ) || ( 'new_topic' != $_POST['action'] ) )
    return;

  if ( isset( $_POST['topic_title'] ) && wp_verify_nonce( $_POST['new_topic_nonce'], 'new-topic-nonce' ) ) {
    if ( !empty( $_GET['node'] ) ) {
      $node_id = (int) $_GET['node'];
    } else {
      $node_id = $_POST['cat'];
    }
    $title = $_POST['topic_title'];
    $content_filtered = $_POST['topic_content_filtered'];
    $user_id = get_current_user_id();

    // Node empty
    if ( empty( $node_id ) || ( 0 == $node_id ) ) {
      vp_errors()->add( 'node_empty', __( 'Please select a node', 'v2press' ) );
    }

    // Node id invalid
    if ( !empty( $node_id ) ) {
      $node = get_term_by( 'id', $node_id, 'category');
      if ( !$node ) {
        vp_errors()->add( 'node_invalid', 'Please do not cheating me.', 'v2press' );
      }
    }

    // Title empty
    if ( empty( $title ) ) {
      vp_errors()->add( 'topic_title_empty', __( 'Please enter the topic title', 'v2press' ) );
    }

    // Content empty
    if ( empty( $content_filtered ) ) {
      vp_errors()->add( 'topic_content_empty', __( 'Please write something useful', 'v2press' ) );
    }

    $errors = vp_errors()->get_error_messages();
    if( empty( $errors ) ) {
      $data = array(
        'post_author' => $user_id,
        'post_category' => (array) $node_id,
        'post_title' => $title,
        'post_content_filtered' => $content_filtered,
        'post_status' => 'publish'
      );
      require_once( VP_LIBS_PATH . '/markdown-extra.php');
      $data['post_content'] = Markdown( $content_filtered );

      $topic = wp_insert_post( $data );

      if ( $topic ) {
        $topic_url = get_permalink( $topic );
        wp_redirect( $topic_url );
        exit;
      }
    }
  }
}
add_action( 'template_redirect', 'vp_do_create_new_topic' );

/* =============================================================================
 * Edit topic.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * The topic edit links.
 *
 * @since 0.0.1
 */
function vp_edit_topic_links() {
  $topic_id = get_the_ID();

  if ( !is_user_logged_in() || !current_user_can( 'edit_post', $topic_id ) )
    return;

  $pub_time = get_the_time( 'U' );
  $time_diff = abs( current_time( 'timestamp' ) - $pub_time );

  // Cannot edit topic 15mins after created
  if ( 900 < $time_diff )
    return;

  $edit_url = add_query_arg( 'edit', 'true', get_permalink() );
  $link = '<span class="topic-edit">';
  $link .= '<a rel="nofollow" href="' . $edit_url . '">' . __( 'edit', 'v2press' ) . '</a>';
  $link .= '</span>';

  echo $link;
}

/**
 * If the current page the edit topic page.
 *
 * @since 0.0.1
 */
function vp_is_edit() {
  $q = $_GET['edit'];
  if ( is_single() && $q && 'true' == $q ) {
    return true;
  }

  return false;
}

/**
 * The edit topic form.
 *
 * @since 0.0.1
 */
function vp_edit_topic_form() {
  $pub_time = get_the_time( 'U' );
  $time_diff = abs( current_time( 'timestamp' ) - $pub_time );
  // Cannot edit topic 15mins after created
  if ( 900 < $time_diff ) {
    echo '<p>' . __( 'You cannot edt this topic 15 minites after created.', 'v2press' ) . '</p>';
    return;
  }

  ob_start();
  $topic_id = get_query_var( 'p' );
  $topic = get_post( $topic_id );

  vp_error_messages();
?>
<form id="vp-edit-topic" action="" method="post">
  <fieldset>
    <p>
      <input type="text" name="topic_title" id="topic_title" class="form-field wider" value="<?php echo $topic->post_title ?>" />
    </p>
    <p>
      <textarea name="topic_content_filtered" id="topic_content_filtered" class="form-field wider" rows="10" cols="20" ><?php echo $topic->post_content_filtered; ?></textarea>
    </p>
    <p>
      <input type="hidden" name="action" value="edit_topic" />
      <input type="hidden" name="edit_topic_nonce" value="<?php echo wp_create_nonce( 'edit-topic-nonce' ); ?>" />
      <input type="submit" name="submit" class="btn" value="<?php _e( 'Submit Changes', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  echo ob_get_clean();
}

/**
 * Do the edit topic form process.
 *
 * @since 0.0.1
 */
function vp_do_edit_topic() {
  if ( empty( $_POST['action'] ) || ( 'edit_topic' != $_POST['action'] ) )
    return;

  if ( isset( $_POST['topic_title'] ) && wp_verify_nonce( $_POST['edit_topic_nonce'], 'edit-topic-nonce' ) ) {
    $topic_id = get_query_var( 'p' );
    $title = $_POST['topic_title'];
    $content_filtered = $_POST['topic_content_filtered'];

    // Title empty
    if ( empty( $title ) ) {
      vp_errors()->add( 'topic_title_empty', __( 'Please enter the topic title', 'v2press' ) );
    }

    // Content empty
    if ( empty( $content_filtered ) ) {
      vp_errors()->add( 'topic_content_empty', __( 'Please write something useful', 'v2press' ) );
    }

    $errors = vp_errors()->get_error_messages();
    if( empty( $errors ) ) {
      $data = array(
        'ID' => $topic_id,
        'post_title' => $title,
        'post_content_filtered' => $content_filtered,
      );

      require_once( VP_LIBS_PATH . '/markdown-extra.php' );
      $data['post_content'] = Markdown( $content_filtered );

      $topic = wp_update_post( $data );

      if ( $topic ) {
        $topic_url = get_permalink( $topic );
        wp_redirect( $topic_url );
        exit;
      }
    }
  }
}
add_action( 'template_redirect', 'vp_do_edit_topic' );
