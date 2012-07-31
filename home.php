<?php get_header(); ?>

        <div id="main">
            <section id="home-topics-box" class="box">
                <div id="welome" class="heading clearfix">
                    <p class="f-left"><?php printf( __( 'Welcome to <strong>%s</strong>', 'v2press' ), get_bloginfo( 'name' ) ); ?></p>
                    <?php if ( get_bloginfo( 'description' ) ) : ?>
                    <p class="f-right fade"><?php bloginfo( 'description' ); ?></p>
                    <?php endif; ?>
                </div>

                <?php // Latest topics order by last comment date ?>
                <div id="latest-topics" class="topics-list">
                <?php
                    if ( have_posts() ) :
                        while ( have_posts() ) :
                            the_post();
                            vp_get_template_part( 'content', 'topics-list' );
                        endwhile;
                    else :
                        vp_get_template_part( 'content', 'zero' );
                    endif;
                ?>
                </div>

                <div class="footing no-border">
                    <p class="xsmall"><?php vp_page_link( array( 'slug' => 'recent', 'text' => __( '&raquo; More Recent Topics', 'v2press' ) ) ); ?></p>
                </div>
            </section>

            <section id="home-node-navi" class="box">
                <div class="heading clearfix">
                    <p class="f-left fade"><?php _e( 'Node Navigation', 'v2press' ); ?></p>
                    <p class="f-right"><?php vp_page_link( array( 'slug' => 'planes', 'text' => __( 'View All Nodes', 'v2press' ) ) ); ?></p>
                </div>

                <?php vp_node_navi(); ?>
            </section>
        </div><!--END #main-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
