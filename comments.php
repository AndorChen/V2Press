<?php
/**
 * The template for displaying Comments.
 *
 * @since 0.0.1
 */
?>

<?php if ( post_password_required() ) : ?>
<section class="box">
	<p class="inner center fade xlarge"><?php _e( 'This post is password protected.', 'v2press' ); ?></p>
</section>
<?php
	return;
endif;
?>

<?php if ( have_comments() ) : ?>
<section id="comments" class="box">
	<div class="heading">
		<h2 id="comments-title" class="xsmall fade"><?php
				printf( _n( '%d reply', '%d replies', get_comments_number(), 'v2press' ),
					number_format_i18n( get_comments_number() ) );
			?></h2>
	</div>

	<ol class="comments-list">
			<?php wp_list_comments( array( 'callback' => 'vp_comments_list' ) ); ?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-page-navi">
		<?php paginate_comments_links( array( 'prev_next' => false ) ); ?>
	</nav>
	<?php endif; ?>
</section>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<section id="comments-closed" class="box">
	<p class="inner center xlarge fade"><?php _e( 'Reply closed.', 'v2press' ); ?></p>
</section>
<?php endif; ?>

<?php vp_comment_form(); ?>

<?php if ( !is_user_logged_in() ) : ?>
<section class="box">
  <p class="inner xlarge fade center"><?php printf( __( 'You need %1$ssignin%2$s to add replies.', 'v2press' ), '<a href="' . vp_get_page_url_by_slug( 'signin' ) . '">', '</a>' ); ?></p>
</section>
<?php endif; ?>