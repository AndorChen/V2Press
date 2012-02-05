<?php
/**
 * Template Name: Recent Topics
 *
 * This is the recent page template, please donot use for other page.
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
          
          <!--Recent 50 topics order by create date-->
          <div id="recent-topics" class="topics-list">
          <?php $recent = new WP_Query( 'posts_per_page=50' ); ?>
          <?php if ( $recent->have_posts() ) : while ( $recent->have_posts() ) : $recent->the_post(); ?>
            <?php get_template_part( 'content', 'topics-list' ); ?>
          <?php endwhile; ?>
          <?php else : ?>
          
          <?php endif; // END if have_posts() ?>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>