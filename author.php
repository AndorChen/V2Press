<?php
/**
 * Buildin author page template, for V2Press used for '/member/Andor'.
 *
 * @since 0.0.1
 */

$user = get_user_by( 'slug', get_query_var( 'author_name' ) );
get_header();
?>    
      <div id="main">
        <section id="member-info" class="box">          
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <h1 class="member-name xxlarge"><?php echo $user->user_login; ?><?php if ( $user->ID == get_current_user_id() ) { ?>  <span class="snow lighter xsmall"><?php _e( '(This is you!)', 'v2press' ); ?></span><?php } ?></h1>
            <?php vp_user_avatar_link( 72, $user->ID ); ?>
            <p class="fade xsmall"><?php printf( __( 'Member %s, created at %s.', 'v2press' ), $user->ID, date_i18n( 'Y-n-j g:i a', strtotime( $user->user_registered ) ) ); ?></p>
            <?php vp_following(); ?>
            <?php vp_member_info_list(); ?>
            <?php
              if ( !empty( $user->description ) )
                echo '<div class="member-info-desc">' . $user->description . '</div>';
            ?>           
          </div>
        </section>
        
        <section id="member-recent-topics" class="box">
          <div class="heading">
            <h2 class="xsmall fade lighter"><?php _e( 'Member Recent Topics', 'v2press' ); ?></h2>
          </div>
          <div class="inner">
          <?php
            $member_recent_topics = new WP_Query( 'author=' . $user->ID . 'posts_per_page=10' );
            if ( $member_recent_topics->have_posts() ) :
            $i = 0;
          ?>
            <ul class="zebra-topics-list">
            <?php
              while ( $member_recent_topics->have_posts() ) : $member_recent_topics->the_post(); $i++;
            ?>
              <li id="topic-<?php the_ID(); ?>"<?php if ( 0 == $i%2 ) echo ' class="alt"'; ?>><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <h2 class="xxlarge snow center"><?php _e( 'No topics created by this member.', 'v2press' ); ?></h2>
          <?php endif; ?>
          </div>
          <div class="footing">
            <p class="fade xsmall"><?php printf( __( 'Created %s topics in total.', 'v2press' ), count_user_posts( $user->ID ) ); ?></p>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>