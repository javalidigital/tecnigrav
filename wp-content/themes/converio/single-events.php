<?php
if ( !class_exists('IworksEvents') ) {
    die(__('Please install MotiveEvents plugin!','converio'));
}

get_header(); ?>

<!-- motive_events: start -->
<?php
global $iworks_events;
while ( have_posts() ) : the_post();
        $date = intval($iworks_events->get_post_meta(get_the_ID(),'date'));
?>
<section class="content <?php echo esc_attr($converio_sidebar_class); ?>">
<section class="main single">
    <article>

        <!-- links to all, previous, next -->
        <ul class="single-btn">
			<li><a class="all" href="<?php echo site_url();?>/events/"><?php echo $iworks_events->get_option('all_name'); ?></a></li>
            <?php echo $iworks_events->get_previous_post_link(get_the_ID()); ?>
            <?php echo $iworks_events->get_next_post_link(get_the_ID()); ?>
        </ul>
		
        <!-- date -->
        <p class="event-day-number"><?php echo esc_attr(date('d', $date )); ?></p>
        <p class="event-day-month"><?php echo esc_attr__(date('F, Y', $date )); ?> <span><?php echo esc_attr__(date('l', $date )); ?></span></p>
        <p class="event-hour"><?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'hour')); ?></p>

        <!-- category -->
        <p class="event-category"><?php echo esc_attr($iworks_events->get_event_category());?></p>

        <!-- title -->
        <h2 class="entry-title"><?php the_title(); ?></h2>

        <!-- place -->
        <p class="event-place"><?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'place')); ?></p>

        <!-- address -->
        <p class="event-address"><?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'city')); ?>, <?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'address')); ?></p>

        <!-- featured image -->
<?php
if ( has_post_thumbnail() ) {
    echo '<p>';
    the_post_thumbnail();
	echo '</p>'; 
}
?>

         <!-- short description -->
         <?php 
		 	echo '<div class="bold-text">';
			 
			the_excerpt();
			 
			echo '</div>';
		 ?>
         <!-- the rest of the post -->
         <?php the_content(); ?>
         </article>

		<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );?>
		<?php if ( is_plugin_active( 'share-this/sharethis.php' ) ) { ?>
		<div class="share-post">
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

        <?php echo $iworks_events->upcoming_events(); ?>

        </section>
<!-- end of wp_query -->
<?php endwhile; ?>
<!-- motive_events: end -->

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
