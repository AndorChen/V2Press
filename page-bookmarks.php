<?php
/**
 * Template Name: Bookmarks
 *
 * This page template is for Bookmarks page only.
 *
 * @since 0.0.1
 */
if ( !is_user_logged_in() )
  auth_redirect();

$user = wp_get_current_user();
$bookmarked = (array) get_user_meta( $user->ID, 'v2press_i_bookmarked_topics', true );
if ( '' == $bookmarked[0] ) {
  $bookmarked = array();
}
$count = count( $bookmarked );

get_header(); ?>
    
      <div id="main">
        <section id="bookmarks-page-box" class="box">          
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
          <?php if ( 0 < $count ) :
            $bm_query = new WP_Query( array( 'post__in' => $bookmarked ) );
            if ( $bm_query->have_posts() ) :
            $i = 0;
          ?>
            <ul class="zebra-topics-list">
              <?php while ( $bm_query->have_posts() ) : $bm_query->the_post(); $i++; ?>
              <li id="topic-<?php the_ID(); ?>"<?php if ( 0 == $i%2) echo ' class="alt"'; ?>><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
              <?php endwhile; ?>
            </ul>
            <?php endif; // END if $bm_query->have_posts() ?>
          <?php else: ?>
            <h2 class="xlarge fade center"><?php _e( 'You have not bookmarke any topics yet.', 'v2press' ); ?></h2>
          <?php endif; // END if 0 < $count ?>
          </div>
          <div class="footing">
            <p class="xsmall fade"><?php printf( _n( '%d bookmark in total.', '%d bookmarks in total.', $count, 'v2press' ), $count); ?></p>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>