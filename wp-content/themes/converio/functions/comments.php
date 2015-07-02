<?php
function converio_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);
?>
		<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<div id="div-comment-<?php comment_ID() ?>">
		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 50 ); ?>	
			<cite class="fn"><?php echo get_comment_author_link() ?>
			<span class="sep">/</span>
			<span class="comment-meta commentmetadata"><em><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php echo get_comment_date(); esc_attr__('at', 'converio') ?> <?php echo get_comment_time() ?></a></em>
		   <span class="sep">/</span> <?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span></cite>
			
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
			<p class="awaiting-post"><?php esc_attr_e('Your comment is awaiting moderation.', 'converio') ?></p>
		<?php endif; ?>
		<div class="comment-body"><?php comment_text() ?></div>
		</div>
<?php
}

function converio_comment_form_args() {
	$commenter = wp_get_current_commenter();
	$req = get_option('require_name_email');
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$comment_notes_before = '<p class="comment-notes">' . esc_attr__( 'Your email address will not be published.', 'converio' ) . '</p>';
	$comment_notes_after = '';	
	$fields =  array(
	    'author' => '<ul><li><label for="author">'.esc_attr__( 'Name', 'converio' ).( $req ? ' <span class="required">*</span>' : '' ).'</label><input id="author" name="author"'. $aria_req . ' value="' . esc_attr( $commenter['comment_author'] ) . '"></li>',
	    'email' => '<li><label for="email">' . esc_attr__( 'Email', 'converio' ) .( $req ? ' <span class="required">*</span>' : '' ). '</label><input id="email" name="email"' . $aria_req . ' value="' . esc_attr(  $commenter['comment_author_email'] ) .'"></li>',
	    'url' => '<li><label for="url">' . esc_attr__( 'Website', 'converio' ) . '</label><input id="url" name="url"' . $aria_req . ' value="' . esc_url( $commenter['comment_author_url'] ) .'"></li></ul>'
	);

	$comment_field = '<label for="comment">' . esc_attr__( 'Comment', 'converio' ) . '</label><textarea name="comment" id="comment" rows="8" cols="50"></textarea>';
	return array('comment_notes_before' => $comment_notes_before, 'comment_notes_after' => $comment_notes_after, 'fields' => $fields, 'comment_field' => $comment_field);
}
