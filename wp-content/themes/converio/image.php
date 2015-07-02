<?php get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<section class="main single">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article class="page">
		<h1><?php the_title(); ?></h1>
		<p><?php printf( esc_attr__( '<a href="%1$s">%2$s</a>', 'converio' ), get_permalink( $post->post_parent ), get_the_title( $post->post_parent ));?></p>
		<p><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
		<?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?>
		<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages', 'converio').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<p class="pagination">
			<span class="alignleft"><?php previous_image_link() ?></span>
			<span class="alignright"><?php next_image_link() ?></span>
		</p>
	</article>

		<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );?>
		<?php if ( is_plugin_active( 'share-this/sharethis.php' ) ) { ?>
		<div class="share-post">
			<h3><?php esc_attr_e('Share this post', 'converio')?></h3>
			<p class="share-social">
				<?php get_template_part('share-this');?>
			</p>
		</div>
		<?php } else {
			$share_links = get_theme_mod('share_links');
			if (empty($share_links)) $share_links = 2;
			if($share_links == 2) : 
				get_template_part('share');
			endif;
		} ?>

		<?php 
		related_posts($post); 
		comments_template(); 
		?>
		
	<?php endwhile; endif; ?>
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
