<?php
/**
 * This file contents functionalities related to comments.
 *
 * @since 0.0.1
 */

/**
 * Comments count without pingback/trackback.
 *
 * @since 0.0.1
 *
 * @param int $count The filter need this.
 * @return int The comments number.
 */
function vp_filter_comments_count( $count ) {
	global $id;

	$comments = get_approved_comments( $id );
	$comment_count = 0;

	foreach ( $comments as $comment ) {
		if ( $comment->comment_type == "" ) {
			$comment_count++;
		}
	}

	return $comment_count;
}
add_filter( 'get_comments_number', 'vp_filter_comments_count', 0 );

/**
 * Custom the comments list.
 *
 * @since 0.0.1
 *
 * @for wp_list_comments() callback in comments.php
 *
 */
function vp_comments_list( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
			break;

		default :
	?>
	<li <?php comment_class( 'inner' ); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php vp_user_avatar_link( 48, $comment->user_id ); ?>
					<span class="fn"><?php vp_user_profile_link( $comment->user_id ); ?></span>
					<?php if ( is_user_logged_in() && comments_open() ) { vp_comment_reply_link(); } ?>
					<?php printf( '<time class="comment-datetime" pubdate datetime="%1$s">%2$s</time>',
								get_comment_time('c'), get_comment_time('Y-n-j @ g:i a') );
					?>

					<?php edit_comment_link( 'edit', '<span class="edit-link">- ', '</span>' ); ?>
				</div>
			</footer>

			<div class="comment-content">
			  <?php if ( $comment->comment_approved == '0' ) : ?>
			  	<p class="comment-awaiting-moderation"><?php _e( 'Your reply is awaiting moderation.', 'v2press' ); ?></p>
			  <?php endif; ?>
			  <?php comment_text(); ?>
			</div>

		</article>
	</li>
	<?php
			break;
	endswitch;
}

/**
 * Display the comment reply link.
 *
 * @since 0.0.1
 */
function vp_comment_reply_link( $args = array(), $comment = null ) {
  $defaults = array(
    'before' => '<div class="reply-to">',
    'after' => '</div>',
    'reply_text' => __( 'Reply', 'v2press' )
  );

  $args = wp_parse_args( $args, $defaults );
  extract( $args, EXTR_SKIP );

  $comment = get_comment($comment);

  $link = '<img class="comment-reply-link" src="' . get_stylesheet_directory_uri() . '/images/reply.png" onclick="replyTo(' . $comment->comment_ID . ');return false;" />';

  echo $link;
}

/**
 * Filter comment text to display @mention link.
 *
 * @since 0.0.2
 */
function vp_mention_link( $content ) {
  $content = preg_replace_callback('/(@)([a-zA-z0-9_\-]+)/', '_vp_mention_link_cb', $content );
  return $content;
}
add_filter( 'comment_text', 'vp_mention_link', 999 );

/**
 * Return the mentioned user link.
 *
 * @since 0.0.2
 * @for vp_mention_link()
 */
function _vp_mention_link_cb( $matches ) {
  $user_login = $matches[2];
  $user = get_user_by( 'login', $user_login );
  if ( !$user )
    return;

  $url = get_author_posts_url( $user->ID );
  $link = '<a href="' . $url . '">@' . $user_login . '</a>';

  return $link;
}

/**
 * Outputs a complete commenting form for use within a template.
 * This is a custom one of the WordPress buildin.
 *
 * @since 0.0.1
 *
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function vp_comment_form( $post_id = null ) {
  if ( !is_user_logged_in() )
    return;

	global $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	if ( comments_open() ) : ?>
		<section id="respond" class="box">
		  <div class="heading">
			  <h2 id="reply-title" class="xsmall fade"><?php _e( 'Add a reply', 'v2press' ); ?></h2>
			</div>
			<div class="inner">
				<form id="respond-form" action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post">
					<fieldset>
					  <p>
					    <textarea id="comment" class="form-field required" name="comment" cols="45" rows="8" aria-required="true"></textarea>
					  </p>
					  <p class="form-submit">
							<input name="submit" type="submit" class="btn" value="<?php _e( 'Add Reply', 'v2press' ); ?>" />
							<?php comment_id_fields( $post_id ); ?>
						</p>
						<?php do_action( 'comment_form', $post_id ); ?>
					</fieldset>
				</form>
			</div>
			<div class="footing">
			  <p class="xsmall fade"><?php _e( 'Please avoid posting pointless replies, it\'s always great to save everyone\'s precious time.', 'v2press' ); ?></p>
			</div>
		</section>
		<?php endif; ?>
	<?php
}
