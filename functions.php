<?php

define( 'VP_VERSION', '0.0.3.alpha' );
define( 'VP_HOME_URL', 'http://v2press.com' );
define( 'VP_INC_PATH', get_stylesheet_directory() . '/inc' );
define( 'VP_LIBS_PATH', get_stylesheet_directory() . '/libs' );

/* Load extra files */
$incs = array(
            'vp-page.php',
            'vp-category.php',
            'vp-comment.php',
            'vp-hooks.php',
            'vp-init.php',
            'vp-post.php',
            'vp-general.php',
            'vp-user.php',
            'vp-favorite.php',
            'vp-member.php',
            'vp-notification.php',
            'vp-seo.php',
            'vp-options.php',
            'vp-pointers.php'
        );
foreach( $incs as $inc )
    include( VP_INC_PATH . '/' . $inc );
