<?php
/**
 * Template Name: Following
 *
 * This page template is for Following page only.
 *
 * @since 0.0.1
 */

if ( !is_user_logged_in() )
  auth_redirect();

$user_id = get_current_user_id();
$following = get_user_meta( $user_id, 'v2press_i_following_who', true );
if ( !is_array( $following ) )
  $following = array();
$count = count( $following );

get_header(); ?>
    
      <div id="main">
        <section id="following-page-box" class="box">          
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
          <?php if ( 0 < $count ) :
            foreach ( $following as $f ) {
              vp_user_avatar_link( 48, $f );
            }
            else: ?>
            <h2 class="xlarge fade center"><?php _e( 'You have not following any users yet.', 'v2press' ); ?></h2>
          <?php endif; // END if 0 < $count ?>
          </div>
          <div class="footing">
            <p class="xsmall fade"><?php printf( __( '%d following in total.', 'v2press' ), $count ); ?></p>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>