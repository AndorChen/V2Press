<form method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="visuallyhidden"><?php _e( 'Search:', 'v2press' ); ?></label>
	<input type="search" name="s" id="s" placeholder="<?php _e( 'Search....', 'v2press' ); ?>" />
	<input type="image" name="submit" id="search-submit" src="<?php echo get_bloginfo('template_url') ?>/images/btn-search-go.gif" />
</form>