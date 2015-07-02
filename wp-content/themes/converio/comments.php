<?php
	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
	
	if ( post_password_required() )
		return;
?>
<?php if ( comments_open() ) : ?>
<div id="comments">
	<?php if ($comments) : ?>
		<section class="comments">
			<h2><?php printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'converio'), number_format_i18n( get_comments_number() ));?></h2>
			<ul class="commentlist">
				<?php wp_list_comments('callback=converio_comment'); ?>
			</ul>		
			<p><?php paginate_comments_links(); ?></p>			
		</section>
	<?php endif; ?>
	<section class="comment-form" id="respond">
		
		<?php comment_form(converio_comment_form_args()); ?>
		
	</section>
</div>
<?php endif; ?>