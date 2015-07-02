<?php get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<section class="main postlist postlist-blog no-comment">
		<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : 
			while(have_posts()) : the_post(); ?>
			<!-- Start: Post -->
			<?php 
			$classes = '';
			if (is_sticky(get_the_id())) $classes = "sticky";
			?>
			<article <?php post_class($classes); ?>>
				<div class="post-right">

				<?php 
				$video_iframe = get_post_meta(get_the_id(), 'single_meta_video_iframe', true);
				$audio_iframe = get_post_meta(get_the_id(), 'single_meta_audio_iframe', true);
				$quote_content = get_post_meta(get_the_id(), 'single_meta_quote_content', true);
				$quote_author = get_post_meta(get_the_id(), 'single_meta_quote_author', true);
				?>

				
				<?php if($quote_content == '' ) { ?>
					<h2 class="post-headline"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-meta"><?php the_time(get_option('date_format')) ?> <span>/</span> <?php esc_attr_e( 'by', 'converio' );?> <?php the_author(); ?> <span>/</span> <?php the_category(", "); ?><?php if ( comments_open() ) : ?> <span>/</span> <?php comments_popup_link('0 comments', '1 comment', '% comments', ''); ?><?php endif; ?></p>

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
					
								<?php global $more; $more = 0; the_excerpt(); ?>
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

		<div class='wp-pagenavi'>
			<?php 
			global $wp_query;
			$big = 999999999;
			echo paginate_links(array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),  
	    		'format' => '/page/%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages,
				'prev_text' => esc_attr__('', 'converio'),
				'next_text' => esc_attr__('', 'converio'),
			));
			?> 
		</div>
	<?php else : ?>
		<h1><?php esc_attr_e( 'Your search did not match any documents', 'converio' ); ?></h1>
		<p><?php esc_attr_e( 'Suggestions:', 'converio' ); ?></p>
		<ul>
			<li><?php esc_attr_e( 'Make sure all words are spelled correctly.', 'converio' ); ?></li>
			<li><?php esc_attr_e( 'Try different keywords.', 'converio' ); ?></li>
			<li><?php esc_attr_e( 'Try more general keywords.', 'converio' ); ?></li>
		</ul>
	<?php endif; ?>
</section>
<?php 
$sidebar_position = $converio_sidebar_pos_global;
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
