<?php
/**
 * Template Name: Recent Topics
 *
 * This page template is only used for Recent page.
 *
 * @since 0.0.1
 */

get_header();
?>
      <div id="main">
        <section id="recent-topics-box" class="box">
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>

          <?php // Recent 50 topics order by create date ?>
          <div id="recent-topics" class="topics-list">
          <?php $recent = new WP_Query( 'posts_per_page=50' ); ?>
          <?php if ( $recent->have_posts() ) : while ( $recent->have_posts() ) : $recent->the_post(); ?>
            <?php vp_get_template_part( 'content', 'topics-list' ); ?>
          <?php endwhile; ?>
          <?php else : ?>
            <?php vp_get_template_part( 'content', 'zero' ); ?>
          <?php endif; // END if have_posts() ?>
          </div>
        </section>
      </div><!--END #main-->

      <?php get_sidebar(); ?>

<?php get_footer(); ?>
