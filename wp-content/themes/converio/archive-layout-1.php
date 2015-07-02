<section class="main postlist postlist-blog">
	<?php 
		if(have_posts()) :
			while(have_posts()) : the_post(); ?>
				<!-- Start: Post -->
				<article <?php post_class(); ?>>
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
					
						<?php the_excerpt(); ?>
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

		<?php get_template_part('pager'); ?>

		<?php else : ?>
			<h2 class="center"><?php esc_attr_e( 'Not found', 'converio' ); ?></h2>
			<p class="center"><?php esc_attr_e( 'Sorry, but you are looking for something that isn\'t here.', 'converio' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
</section>