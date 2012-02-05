<?php
/**
 * Template Name: User Settings
 *
 * This page template is sort of a child template of Need Signin, and only for user settings page.
 *
 * @since 0.0.1
 */

// Weird auth_redirect behaviour hack
if ( !is_user_logged_in() )
  auth_redirect();

get_header();
?>
    
      <div id="main">
        <section id="settings-box" class="box">          
          <div class="heading">
            <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
          </div>
          <div class="inner">
            <?php vp_settings_form(); ?>
          </div>          
        </section>
        
        <section id="avatar-box" class="box">
          <div class="heading">
            <p class="xsmall fade"><?php _e( 'Avatar', 'v2press' ); ?></p>
          </div>
          <div class="inner">
            <p class="push-right"><?php echo get_avatar( wp_get_current_user()->ID, 72, get_stylesheet_directory_uri() . '/images/default-avatar-72.png'); ?></p>
            <p class="field-helper"><?php _e( 'Your avatar is hosted by Gravatar', 'v2press' ); ?></p>
            <p class="push-right"><a class="btn" href="https://gravatar.com/emails"><?php _e( 'Change Avatar', 'v2press' ); ?></a></p>
          </div>
        </section>
        
        <section id="change-password-box" class="box">
          <div class="heading">
            <p class="xsmall fade"><?php _e( 'Change Password', 'v2press' ); ?></p>
          </div>
          <div class="inner">
            <?php vp_change_password_form(); ?>
          </div>
        </section>
      </div><!--END #main-->
      
      <?php get_sidebar(); ?>

<?php get_footer(); ?>