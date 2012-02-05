<?php
/**
 * Template Name: Create New Topic
 *
 * This page template is for Create New Topic page only.
 *
 * @since 0.0.1
 */
if ( !is_user_logged_in() )
  auth_redirect();

if ( !current_user_can( 'publish_posts' ) ) {
	wp_redirect( home_url() );
	exit;
}

get_header(); ?>

      <div id="main">
        <section id="create-new-topic-box" class="box">          
          <div class="heading">
            <p class="xlarge"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <?php vp_new_topic_form(); ?>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>