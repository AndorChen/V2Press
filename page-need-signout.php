<?php
/**
 * Template Name: Need Signout
 *
 * This page template is used when the page is need signout to view.
 * For example, the signup page, signin page.
 *
 * @since 0.0.1
 */

// If signed in, redirect to home
if ( is_user_logged_in() ) {
  wp_redirect( home_url() );
  exit;
}

get_header();
?>
      <div id="main">
        <section id="need-signout-page-box" class="box">          
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <?php the_content(); ?>
          </div>
        <?php endwhile; ?>
        <?php endif; // END if have_posts() ?>          
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>