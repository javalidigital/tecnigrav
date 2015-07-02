<?php get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<?php 
$hide_title = get_post_meta(get_the_id(), 'hide_title', true);

$sidebar_position = get_post_meta($converio_thisPageId, 'sidebar_position', true);
if($sidebar_position == 3) $sidebar_position = $converio_sidebar_pos_global;

//if sidebar is set to "don't show"
if($sidebar_position == 2) {
	if (have_posts()) : 
		while (have_posts()) : the_post();
			if(!$hide_title) : ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php endif;
			the_content();
			wp_link_pages(array('before' => '<p class="pages"><strong>'.esc_attr__('Pages', 'converio').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));
			comments_template();
		endwhile; 
	endif;
} else {
?>
<section class="main single">
	<?php if (have_posts()) : while (have_posts()) : 
		the_post();
	?>
		<article class="page">
			<?php if(!$hide_title) : ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php endif; ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<p class="pages"><strong>'.esc_attr__('Pages', 'converio').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</article>

		<?php comments_template(); ?>
	<?php endwhile; endif; ?>
</section>
<?php 
if($sidebar_position != 2) {
	$sidebar = get_post_meta(get_the_id(), 'custom_sidebar', true) ? get_post_meta(get_the_id(), 'custom_sidebar', true) : "default";
	if($sidebar != 'no') {
		if($sidebar && $sidebar != "default") get_sidebar("custom");
		else get_sidebar();	
	}
}
?>
<?php } ?>
 </section>
<?php get_footer(); ?>