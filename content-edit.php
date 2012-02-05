<?php
/**
 * This file is for edit topic page when view a single topic page with 'edit=true' query.
 *
 * @since 0.0.1
 */

$topic_id = get_query_var( 'p' );

// Topic not exists
if ( !$topic_id ) {
  wp_redirect( home_url() );
  exit;
}

// User not logged in
if ( !is_user_logged_in() ) {
  auth_redirect();
}

// You cannot edit this topic
if ( !current_user_can( 'edit_post', $topic_id ) ) {
  wp_redirect( home_url() );
  exit;
}

get_header(); ?>
      <div id="main">
        <section id="edit-topic-box" class="box">          
          <div class="heading">
            <p class="xlarge"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <?php vp_edit_topic_form(); ?>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>