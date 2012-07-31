<?php get_header(); ?>

        <div id="main">
            <section id="home-topics-box" class="box">
                <div class="heading">
                    <p class="xlarge"><?php vp_breadcrumb(); ?></p>
                </div>

                <div class="topics-list">
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

                <div class="footing">
                    <?php vp_page_navi(); ?>
                </div>
            </section>
        </div><!--END #main-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
