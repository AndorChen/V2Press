<?php
/**
 * Template Name: Notifications
 *
 * This page template is used for Page Notifications.
 *
 * @since 0.0.2
 */

if ( !is_user_logged_in() )
  auth_redirect();

get_header();
?>

      <div id="main">
        <section id="notifications-page-box" class="box">
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <?php vp_notifications_list(); ?>
          </div>
        </section>
      </div><!--END #main-->

      <?php get_sidebar(); ?>

<?php get_footer(); ?>

