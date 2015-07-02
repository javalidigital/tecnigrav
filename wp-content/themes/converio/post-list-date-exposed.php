<?php
/*
* Template name: Blog - date exposed
*/
get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<?php 
if (have_posts()) :
	while (have_posts()) : the_post(); ?>
	<?php  wp_reset_postdata();
	endwhile; ?>

	<section class="main postlist postlist-blog">
	    <?php
	    if ( get_query_var('paged') ) {
	    	$paged = get_query_var('paged');
	    } elseif ( get_query_var('page') ) {
	    	$paged = get_query_var('page');
	    } else {
	    	$paged = 1;
	    }
	    ?>
		<?php 
		$options = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'paged' => $paged
			);
		$postlist = new WP_Query($options);
		if($postlist->have_posts()) :
			while($postlist->have_posts()): $postlist->the_post(); ?>
			<?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("showposts=1&paged=$page");?>
				<!-- Start: Post -->
				<?php 
				$classes = '';
				if (is_sticky(get_the_id())) $classes = "sticky";
				?>
				<article <?php post_class($classes); ?>>
					<p class="post-date-and-comments">
						<span class="post-date">
						<b><?php the_time('j') ?></b>
						<small><?php the_time('m') ?></small>
						</span>
						<span class="post-comments">
							<?php
							  comments_popup_link( '0', '1', '%', 'comments-link', '');
							 ?>
						</span>
					</p>
					<div class="post-right">


				<?php 
				$video_iframe = get_post_meta(get_the_id(), 'single_meta_video_iframe', true);
				$audio_iframe = get_post_meta(get_the_id(), 'single_meta_audio_iframe', true);
				$quote_content = get_post_meta(get_the_id(), 'single_meta_quote_content', true);
				$quote_author = get_post_meta(get_the_id(), 'single_meta_quote_author', true);
				?>

			<?php if($quote_content == '' ) { ?>
			<h2 class="post-headline"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-meta"><?php esc_attr_e( 'Posted by', 'converio' );?> <?php the_author(); ?> <span>/</span> <?php the_category(", "); ?>
			</p>
			<?php
			if( has_post_thumbnail() && ( converio_ext_get_featured_image_id( 'featured-image-2', get_post_type() ) || converio_ext_get_featured_image_id('featured-image-3', get_post_type() ) ) ) {
	?>
				<section class="slider3">
					<div class="slider">
						<article><?php the_post_thumbnail('thumbnail-large');?></article>
						<?php if (converio_ext_get_featured_image_id('featured-image-2', get_post_type())) { ?>
						<article><?php converio_ext_the_featured_image('featured-image-2', get_post_type(), 'thumbnail-large')?></article>
						<?php } ?>
						<?php if (converio_ext_get_featured_image_id('featured-image-3', get_post_type())) { ?>
						<article><?php converio_ext_the_featured_image('featured-image-3', get_post_type(), 'thumbnail-large')?></article>
						<?php } ?>
					</div>
				</section>	  
						<?php		
					}
			
					elseif($video_iframe) { 
						echo '<div class="video post-mb10">'.converio_sanitize_text_decode($video_iframe).'</div>';
					}
					elseif($audio_iframe) {
						echo converio_sanitize_text_decode($audio_iframe);
					}
					elseif(has_post_thumbnail()) { ?>
						<div class="img large"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail-large'); ?></a></div>
						<?php } ?>				
					
						<?php global $more; $more = 0;the_excerpt(); ?>
						<p class="more more-detail"><a href="<?php the_permalink() ?>"><?php esc_attr_e( 'Read more', 'converio' );?></a></p>
				
			
						<?php } else {
								//quote
								echo '<blockquote class="quote-typography">';
								echo '<p><a href="';
								echo the_permalink(); 
								echo '">'.esc_attr($quote_content).'</a></p>';
								echo '<small>&mdash; '.esc_attr($quote_author).'</small>';
								echo '</blockquote>';
					
							} ?>
					</div>
				</article>
				<!-- End: Post -->
			<?php endwhile; ?>
		   			
		<?php endif; ?>
		<?php  wp_reset_query(); ?>
		<div class='wp-pagenavi'>
			<?php 
			global $wp_query;
			$big = 999999999;
			echo paginate_links(array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),  
	    		'format' => '/page/%#%',
				'current' => max( 1, $paged ),
				'total' => $postlist->max_num_pages,
				'prev_text' => esc_attr__('', 'converio'),
				'next_text' => esc_attr__('', 'converio'),
			));
		 	?> 
		</div>

<?php else : ?>
	<?php get_template_part('no-content'); ?>
<?php endif; ?>

</section>
<?php 
$sidebar_position = get_post_meta($converio_thisPageId, 'sidebar_position', true);
if($sidebar_position == 3) $sidebar_position = $converio_sidebar_pos_global;
if($sidebar_position != 2) {
	$sidebar = get_post_meta(get_the_id(), 'custom_sidebar', true) ? get_post_meta(get_the_id(), 'custom_sidebar', true) : "default";
	if($sidebar != 'no') {
		if($sidebar && $sidebar != "default") get_sidebar("custom");
		else get_sidebar();	
	}
}
?>
</section>
<?php get_template_part('call-to-action');?>
<?php get_footer(); ?>
