<?php get_header(); ?>

        <div id="main">
            <section id="normal-page-box" class="box">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="heading">
                        <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
                    </div>
                    <div class="inner topic-content">
                        <?php the_content(); ?>
                        <p class="xsmall snow"><?php printf( __( 'Last Revised %s', 'v2press' ), get_the_modified_time( 'n/j Y' ) ); ?></p>
                    </div>
                </article>
                <?php
                    endwhile; else:
                    vp_get_template_part( 'content', 'zero' );
                    endif;
                ?>
            </section>
        </div><!--END #main-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
