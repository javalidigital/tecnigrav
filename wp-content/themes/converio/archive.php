<?php get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<?php
$archive_layout = get_theme_mod('archive_layout') ? get_theme_mod('archive_layout') : 0;
get_template_part('archive-layout-' . $archive_layout);?>

<?php
// if the layout isn't full width, show the sidebar
if(!in_array($archive_layout, array(3))) {
	$sidebar_position = $converio_sidebar_pos_global;
	if($sidebar_position != 2) {
		$sidebar = get_post_meta(get_the_id(), 'custom_sidebar', true) ? get_post_meta(get_the_id(), 'custom_sidebar', true) : "default";
		if($sidebar != 'no') {
			if($sidebar && $sidebar != "default") get_sidebar("custom");
			else get_sidebar();	
		}
	}
}
?>
</section>
<?php get_template_part('call-to-action');?>
<?php get_footer(); ?>
