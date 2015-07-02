<?php
/*
* Template name: Blog - masonry sidebar
*/
get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<?php 
if (have_posts()) :
	while (have_posts()) : the_post(); ?>
	<?php  wp_reset_postdata();
	endwhile; ?>

	<section class="main postlist postlist-blog">
		<div class="columns masonry animation-enabled">
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
				<?php 
				$classes = 'item col col2';
				if (is_sticky(get_the_id())) $classes .= " sticky";
				?>
				<article <?php post_class($classes); ?>><div>
	
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
				<p class="post-mb10"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail-masonry'); ?></a></p>
			<?php } ?>
					
					<div class="post-detail">
						<?php if($quote_content == '' ) { ?>
						<h2 class="post-headline"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<p class="post-meta"><?php the_time(get_option('date_format')) ?> <span>/</span> <?php esc_attr_e( 'by', 'converio' );?> <?php the_author(); ?></p>
						<p class="text-about"><?php echo converio_custom_excerpt(30, $post); ?></p>
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
			<?php  wp_reset_query(); ?>
		<?php endif; ?>
		
		</div>
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
		
	</section>
	
	
<?php else : ?>
	<?php get_template_part('no-content'); ?>
<?php endif; ?>
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
