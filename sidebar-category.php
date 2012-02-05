<?php
if ( is_single() ) {
  $post_id = get_the_ID();
  $cats = get_the_category( $post_id );
  
  // A topic belongs to only one node
  $cat_id = $cats[0]->term_id;
  
} elseif ( is_category() ) {
  global $wp_query;
  $cat_id = $wp_query->get_queried_object_id();
}

$cat_meta = get_option( "v2press_category_{$cat_id}" );
$desc = $cat_meta['vp_category_description_sidebar'];

if ( empty( $desc ) )
  return;
?>
<div id="node-desc-sidebar" class="box">
  <div class="inner">
    <?php echo $desc; ?>
  </div>
</div>