<?php
/**
 * Node topics list template.
 *
 * @since 0.0.1
 */

get_header(); ?>

        <div id="main">
            <section id="node-topics-box" class="box">
                <div class="heading clearfix">
                    <p class="xlarge"><?php vp_breadcrumb(); ?></p>
                    <?php if( vp_get_category_meta( 'logo' ) ) { ?>
                    <img class="node-logo" src="<?php echo vp_get_category_meta( 'logo' ); ?>" width="72" height="72" />
                    <?php } ?>
                    <?php if( category_description() ) { ?>
                    <div class="node-description"><?php echo category_description(); ?></div>
                    <?php } ?>
                    <p class="create-new-topic-link"><?php vp_new_topic_link(); ?></p>
                </div>

                <div id="node-topics" class="topics-list">
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
                    <?php vp_page_navi(); ?>
                </div>
            </section>
        </div><!--END #main-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
