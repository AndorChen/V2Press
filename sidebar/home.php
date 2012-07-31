<div id="hottest-nodes" class="box">
  <div class="heading">
    <h3 class="aside-title"><?php _e( 'Hottest Nodes', 'v2press' ); ?></h3>
  </div>
  <div class="inner">
    <ul class="nodes-list">
      <?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'use_desc_for_title' => 0, 'hierarchical' =>0, 'title_li' => '', 'number' => 25 ) ); ?>
    </ul>
  </div>
</div>

<div id="recent-nodes" class="box">
  <div class="heading">
    <h3 class="aside-title"><?php _e( 'Recent Add Nodes', 'v2press' ); ?></h3>
  </div>
  <div class="inner">
    <ul class="nodes-list">
      <?php wp_list_categories( array( 'orderby' => 'ID', 'order' => 'DESC', 'hide_empty' => 0, 'use_desc_for_title' => 0, 'hierarchical' =>0, 'title_li' => '', 'number' => 10) ); ?>
    </ul>
  </div>
</div>

<div id="stats-box" class="box">
  <div class="heading">
    <h3 class="aside-title"><?php _e( 'Community Stats', 'v2press' ); ?></h3>
  </div>
  <div class="inner">
    <dl class="stats-list">
      <dt><?php _e( 'Total Members', 'v2press' ); ?></dt>
      <dd><?php $user_count = count_users(); echo $user_count['total_users']; ?></dd>
      <dt><?php _e( 'Total Topics', 'v2press' ); ?></dt>
      <dd><?php echo wp_count_posts( 'post' )->publish; ?></dd>
      <dt><?php _e( 'Total Replies', 'v2press' ); ?></dt>
      <dd><?php echo wp_count_comments()->total_comments; ?></dd>
    </dl>
  </div>
</div>