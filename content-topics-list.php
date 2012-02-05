<?php 
/**
 * This file is a code snippet for use in the topics list as one topic.
 *
 * @since 0.0.1
 */
?>
<div id="topic-<?php the_ID(); ?>" <?php post_class('inner'); ?>>
  <?php vp_the_author_avatar_link(); ?>
  <?php if ( !'0' == get_comments_number() ) { ?>            
  <div class="f-right">
    <?php comments_popup_link( '0', '1', '%', '', '0' ); ?>
  </div>
  <?php } // END if not vp_get_comment_count ?>
  <h2 class="topic-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  <div class="topic-meta">
    <span class="node-link"><?php the_category( ', ' ); ?></span>
    &bull;
    <?php vp_the_author_profile_link(); ?>
    &bull;
    <?php vp_time_ago(); ?>
  </div>
</div>