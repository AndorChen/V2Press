<aside id="sidebar">
  <div id="user-panel" class="box">
    <?php if ( is_user_logged_in() ) : ?>
    <div class="inner">
      <?php vp_user_avatar_link(); ?>
      <strong><?php vp_user_profile_link(); ?></strong>
      <div id="favorites">
        <ul>
          <li><a rel="nofollow" href="<?php echo vp_get_page_url_by_slug( 'bookmarks' ); ?>"><?php printf( _n( '<span>%d</span> Bookmark', '<span>%d</span> Bookmarks', vp_get_bookmarks_count(), 'v2press'), vp_get_bookmarks_count() ); ?></a></li>
          <li class="last"><a rel="nofollow" href="<?php echo vp_get_page_url_by_slug( 'following' ); ?>"><?php printf( __( '<span>%d</span> Following', 'v2press' ), vp_get_following_count() ); ?></a></li>
        </ul>
      </div>
    </div>
    <div class="footing">
      <p class="xsmall fade"><a href="<?php echo vp_get_page_url_by_slug( 'notifications' ); ?>"><?php printf( _n( '%d notification', '%d notifications', vp_unread_notifications_count(), 'v2press' ), vp_unread_notifications_count() ); ?></a></p>
    </div>
    <?php else : ?>
    <div class="heading">
      <p><strong><?php bloginfo( 'name' ); ?></strong></p>
      <?php if ( get_bloginfo( 'description' ) ) : ?>
      <p class="xsmall fade"><?php bloginfo( 'description' ); ?></p>
      <?php endif; ?>
    </div>
    <div class="inner">
      <p class="center"><?php vp_page_link( array( 'slug' => 'signup', 'text' => __( 'Signup', 'v2press'), 'class' => 'btn' ) ); ?></p>
      <p class="fade center"><?php printf( __( 'Already signup? %s', 'v2press' ), vp_page_link( array ( 'slug' =>'signin', 'text' => __( 'Signin', 'v2press' ), 'display' => false ) ) ); ?></p>
    </div>
    <?php endif; // END if is_user_logged_in() ?>
  </div>

  <?php
    if ( is_home() ) {
      get_template_part( 'sidebar', 'home' );
    } elseif ( ( is_single() && !vp_is_edit() ) || is_category() ) {
      get_template_part( 'sidebar', 'category' );
    } elseif ( is_page( 'new' ) || vp_is_edit() ) {
      get_template_part( 'sidebar', 'new' );
    }
  ?>

</aside><!--END #sidebar-->
