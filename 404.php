<?php
/**
 * HTTP 404 Not Found page template.
 *
 * @since 0.0.2
 */

get_header(); ?>
        <div id="main">
            <section id="four0four-page-box" class="box">
                <article id="page-404">
                    <div class="heading">
                        <p class="xsmall fade"><?php vp_breadcrumb(); ?></p>
                    </div>
                    <div class="inner topic-content">
                        <h2 class="fade center xlarge"><?php _e( 'Sorry, not found what you were looking for.', 'v2press' ); ?></h2>
                    </div>
                </article>
            </section>
        </div><!--END #main-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
