	<section class="main postlist postlist-blog">
		<div class="columns masonry animation-enabled">
			
	<?php
		if(have_posts()) :
			while(have_posts()) : the_post(); ?>			
				<article class="post item col col2"><div>
	
			<?php 
			$video_iframe = get_post_meta(get_the_id(), 'single_meta_video_iframe', true);
			$audio_iframe = get_post_meta(get_the_id(), 'single_meta_audio_iframe', true);
			$quote_content = get_post_meta(get_the_id(), 'single_meta_quote_content', true);
			$quote_author = get_post_meta(get_the_id(), 'single_meta_quote_author', true);			
			
			
			if( has_post_thumbnail() && ( converio_ext_get_featured_image_id( 'featured-image-2', get_post_type() ) || converio_ext_get_featured_image_id('featured-image-3', get_post_type() ) ) ) {
?>
					<section class="slider3">
						<div class="slider">
							<article><?php the_post_thumbnail('thumbnail-medium');?></article>
							<?php if (converio_ext_get_featured_image_id('featured-image-2', get_post_type())) { ?>
							<article><?php converio_ext_the_featured_image('featured-image-2', get_post_type(), 'thumbnail-medium')?></article>
							<?php } ?>
							<?php if (converio_ext_get_featured_image_id('featured-image-3', get_post_type())) { ?>
							<article><?php converio_ext_the_featured_image('featured-image-3', get_post_type(), 'thumbnail-medium')?></article>
							<?php } ?>
						</div>
					</section>	  
			<?php		
			}
			
			elseif($video_iframe) { 
				echo '<div class="video post-mb10">'.converio_sanitize_text_decode($video_iframe).'</div>';
			}
			elseif($audio_iframe) {
				echo '<div class="add-music">'.converio_sanitize_text_decode($audio_iframe).'</div>';
			}
			elseif($quote_content) {
				echo '<blockquote class="quote-typography">';
				echo '<p><a href="';
				echo the_permalink(); 
				echo '">'.esc_attr($quote_content).'</a></p>';
				echo '<small>&mdash; '.esc_attr($quote_author).'</small>';
				echo '</blockquote>';
			}
			elseif(has_post_thumbnail()) { ?>
				<p class="post-mb10"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail-medium'); ?></a></p>
			<?php } ?>
					
					<div class="post-detail">
						<?php if($quote_content == '' ) { ?>
						<h2 class="post-headline"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<p class="post-meta"><?php the_time(get_option('date_format')) ?> <span>/</span> <?php esc_attr_e( 'by', 'converio' );?> <?php the_author(); ?></p>
						<p class="text-about"><?php echo get_the_excerpt(); ?></p>
						<?php } ?>
						<?php /* ShareThis */
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );?>
						<?php if ( is_plugin_active( 'share-this/sharethis.php' ) ) { ?>
						<div class="info-post share-this">
							<div class="share">
								<a href="#"><?php esc_attr_e('Share', 'converio');?></a>
								<p class="share-social">
								<em class="arrow"></em>
								<?php get_template_part('share-this');?>
								</p>
							</div>
							<?php if ( comments_open() ) : ?><span>/</span><?php comments_popup_link('0 comments', '1 comment', '% comments', 'comment-link'); ?><?php endif; ?>
						</div>
						<?php } else {
							/* Default sharing icons */
			   				$share_links = get_theme_mod('share_links');
			   				if (empty($share_links)) $share_links = 2;
			   				if($share_links == 2) : ?>		
							<div class="info-post">
								<div class="share">
									<a href="#"><?php esc_attr_e('Share', 'converio');?></a>
									<div class="share-social">
										<em class="arrow"></em>
										<?php get_template_part('share');?>
									</div>
								</div>
								<?php if ( comments_open() ) : ?><span>/</span><?php comments_popup_link('0 comments', '1 comment', '% comments', 'comment-link'); ?><?php endif; ?>
							</div>
							<?php /* comments */
							else : ?>
								<div class="info-post">
									<?php if ( comments_open() ) : ?><?php comments_popup_link('0 comments', '1 comment', '% comments', 'comment-link'); ?><?php endif; ?>
								</div>
							<?php endif; ?>
							<?php } ?>	
					</div>
				</div></article>
			<?php endwhile; ?>
		</div>

		<?php get_template_part('pager'); ?>
		
	</section>
	
	<?php else : ?>
		<h2 class="center"><?php esc_attr_e( 'Not found', 'converio' ); ?></h2>
		<p class="center"><?php esc_attr_e( 'Sorry, but you are looking for something that isn\'t here.', 'converio' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>