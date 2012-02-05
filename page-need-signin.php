<?php
/**
 * Template Name: Need Signin
 *
 * This page template is used when the page is need signin to view.
 * For example, the user settings page.
 *
 * @since 0.0.1
 */

// Weird auth_redirect behaviour hack
if ( !is_user_logged_in() )
  auth_redirect();

get_header();
?>
    
      <div id="main">
        <section id="need-signin-page-box" class="box">          
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