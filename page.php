<?php get_header(); ?>
    
      <div id="main">
        <section id="normal-page-box" class="box">          
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="heading">
              <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
            </div>
            <div class="inner topic-content">
              <?php the_content(); ?>
              <p class="xsmall snow"><?php printf( __( 'Last Revised %s', 'v2press' ), get_the_modified_time( 'n/j Y' ) ); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
        <?php else : // No topics yet ?>
          <?php get_template_part( 'content', 'zero' ); ?>
        <?php endif; // END if have_posts() ?>          
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>