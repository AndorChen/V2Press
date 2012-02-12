<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8" />
  <title><?php vp_title(); ?></title>
    
  <link rel="icon" href="<?php bloginfo( 'template_url' ); ?>/images/favicon.ico" />
  <link rel="shortcut icon" href="<?php bloginfo( 'template_url' ); ?>/images/favicon.ico" />
  <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>?ver=0.0.1" media="all" />
  
  <?php vp_feed_link(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    
  <!--[if lt IE 9]>
  <script src="<?php echo get_bloginfo( 'template_url' ); ?>/js/html5.js"></script>
  <![endif]-->
    
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header id="header" role="banner">
    <div class="container">
      <hgroup id="site-info">
        <h1 id="logo"><a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <?php if ( get_bloginfo( 'description' ) ) : ?>
        <h2 id="description" class="visuallyhidden"><?php bloginfo( 'description' ); ?></h2>
        <?php endif; // END if description ?>
      </hgroup>
      
      <nav id="menu" role="navigation">
      <?php
      if ( !is_user_logged_in() ) :
      ?>
        <ul id="logged-out-menu" class="menu">
          <li<?php if ( is_front_page() ) echo ' class="current_page_item"' ?>><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'menu', 'v2press' ); ?>"><?php _ex( 'Home', 'menu', 'v2press' ); ?></a></li>
          <li<?php if ( is_page( 'signin' ) ) echo ' class="current_page_item"' ?>><?php vp_page_link( array( 'slug' => 'signin', 'text' => __( 'Signin', 'v2press' ), 'rel' => 'nofollow' ) ); ?></li>
          <li<?php if ( is_page( 'signup' ) ) echo ' class="current_page_item"' ?>><?php vp_page_link( array( 'slug' => 'signup', 'text' => __( 'Signup', 'v2press' ), 'rel' => 'nofollow' ) ); ?></li>
        </ul>
      <?php else : // User logged in ?>
        <ul id="logged-in-menu" class="menu">
          <li<?php if ( is_front_page() ) echo ' class="current_page_item"' ?>><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'menu', 'v2press' ); ?>"><?php _ex( 'Home', 'menu', 'v2press' ); ?></a></li>
          <li<?php if ( is_page( 'member' ) ) echo ' class="current_page_item"' ?>><?php vp_user_profile_link(); ?></li>
          <li<?php if ( is_page( 'settings' ) ) echo ' class="current_page_item"' ?>><?php vp_page_link( array( 'slug' => 'settings', 'text' => __( 'Settings', 'v2press' ), 'rel' => 'nofollow' ) ); ?></li>
          <?php if ( current_user_can( 'administrator' ) ) : ?>
          <li><a href="<?php echo admin_url(); ?>" title="<?php _ex( 'Admin', 'menu', 'v2press' ); ?>"><?php _ex( 'Admin', 'menu', 'v2press' ); ?></a></li>
          <?php endif; ?>
          <li><a href="<?php echo wp_logout_url( vp_current_url() ); ?>" title="<?php _ex( 'Signout', 'menu', 'v2press' ); ?>"><?php _ex( 'Signout', 'menu', 'v2press' ); ?></a></li>
        </ul>
      <?php endif; // END if is_user_logged_in ?>
      </nav>
      
      <?php get_search_form(); ?>
      
    </div>
  </header><!--END #header-->
  
  <div id="content" class="clearfix">
    <div class="container">