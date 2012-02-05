<?php
/**
 * Template Name: Planes
 * This page template is only used for all nodes display page.
 *
 * @since 0.0.1
 */
get_header(); ?>
    
      <div id="main">
        <section id="nodes-count-box" class="box">          
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <p class="xxlarge center"><?php printf( _n( '%d node now and growing.', '%d nodes now and growing.', 'v2press', wp_count_terms( 'category' ) ), wp_count_terms( 'category' ) ); ?></p>
          </div>        
        </section>
        
        <section id="all-nodes-box" class="box">
          <div class="inner">
            <ul class="nodes-list">
              <?php wp_list_categories( array( 'orderby' => 'ID', 'use_desc_for_title' => 0, 'hierarchical' =>0, 'title_li' => '', 'hide_empty' => 0 ) ); ?>
            </ul>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>