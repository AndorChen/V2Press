<?php
/**
 * This file is single topic page.
 *
 * @since 0.0.1
 */

get_header(); ?>

      <div id="main">
        <section id="single-topic-box" class="box">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <article id="topic-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="heading">
              <p class="xlarge"><?php vp_breadcrumb(); ?></p>
              <h1 class="topic-title"><?php the_title(); ?></h1>
              <?php vp_the_author_avatar_link( 72 ); ?>
              <p><span class="topic-meta"><?php printf( __( 'By %1$s at %2$s', 'v2press' ), vp_get_the_author_profile_link(), vp_time_ago( false ) ); ?></span> <?php vp_edit_topic_links(); ?></p>
            </div>
            <div class="inner topic-content">
              <?php the_content(); ?>
              <?php vp_bookmark(); ?>
            </div>
          </article>
        <?php endwhile; ?>
        <?php else : // No topics yet ?>
          <?php get_template_part( 'content', 'zero' ); ?>
        <?php endif; // END if have_posts() ?>
        </section>

        <?php comments_template(); ?>
      </div><!--END #main-->
      <?php get_sidebar(); ?>

<?php get_footer(); ?>
