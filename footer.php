    </div>
  </div><!--END #content-->
  
  <footer id="footer">
    <div class="container">
      <?php if ( has_nav_menu( 'footer' ) ) { ?>
      <nav id="footer-navi">
        <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1) ); ?>   
      </nav>
      <?php } ?>
      
      <div class="colophon inner">
        <?php if ( get_bloginfo( 'description' ) ) : ?>
        <p><?php bloginfo( 'description' ); ?>. <?php printf( __( 'An %1$sAndor Chen%2$s creation.', 'v2press' ), '<a href="http://about.ac/" title="' . esc_attr__( 'Andor\'s personal site', 'v2press' ) . '">', '</a>' ); ?></p>
        <?php endif; ?>
        <p><?php _e( 'Powered by WordPress and love.', 'v2press' ); ?></p>
      </div>
      
      <div class="copyright inner">
        <p>&copy;2012 <?php bloginfo( 'name' ); ?> <?php _e( 'All rights reserved.', 'v2press' ); ?></p>
      </div>
    </div>
  </footer><!--END #footer-->
  
  <?php wp_footer(); ?>
</body>
</html>