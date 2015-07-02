<?php global $converio_breadcrumb_header;
$converio_breadcrumb_header = esc_attr__('Portfolio','converio');
?>
<?php get_header(); ?>
<section class="content <?php echo esc_attr($converio_sidebar_class);?> single-sidebar ">

<ul class="single-btn">
<?php
$type = get_post_type();
?>	
	<li class="all"><a href="<?php echo get_post_type_archive_link($type);?>"><?php esc_attr_e('Projects', 'converio');?></a></li>
	<?php if(get_next_post_link('%link')) { ?><li class="previous"><?php next_post_link('%link'); ?></li><?php } ?>
	<?php if(get_previous_post_link('%link')) { ?><li class="next"><?php previous_post_link('%link'); ?></li><?php } ?>
</ul>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php 
		if (has_post_thumbnail()) {
			$feat_img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'project-thumbnail');
			$feat_img = $feat_img[0];
		}
		$project_url = get_post_meta($post->ID, 'project_meta_url', true);
		$project_video = get_post_meta($post->ID, 'project_meta_video', true);
		$project_testimonial = get_post_meta($post->ID, 'project_meta_testimonial', true);
		$project_testimonial_author = get_post_meta($post->ID, 'project_meta_testimonial_author', true);
		$project_testimonial_company = get_post_meta($post->ID, 'project_meta_testimonial_company', true);
		$project_testimonial_job = get_post_meta($post->ID, 'project_meta_testimonial_job', true);
		$project_team = get_post_meta($post->ID, 'project_meta_team', true);
		
		$cats = wp_get_post_terms($post->ID, 'project-categories', array());
		$cat_list = array();
		foreach($cats as $c) {
			$cat_list[] = esc_attr($c->name);
		}
		$cat_list = implode(", ", $cat_list);

		$skill_list = array();
		
		$skills = wp_get_post_terms($post->ID, 'project-skills', array()); 
		
		foreach ($skills as $s) {
			$skill_list[] = '<li><i class="fa fa-check"></i>'.esc_attr($s->name).'</li>';
		}		
		
		$skill_list = implode("", $skill_list);
	?>

	<section class="main single">
		<article>
			
		<h2 class="entry-title"><?php the_title(); ?></h2>
		<?php if($project_video): ?>
			<div class="video"><?php echo converio_sanitize_text_decode($project_video); ?></div>
		<?php else :
			if(has_post_thumbnail()) : ?>
			<p><img src="<?php echo esc_url($feat_img);?>" alt="<?php the_title(); ?>"></p>
			<?php endif; ?>
		<?php endif; ?>	
			<?php the_content(); ?>
			
			<?php //converio_related_projects($post); ?>
		</article>
	</section>

<?php endwhile; endif; ?>
		<aside>
			<section class="text">
				<h3><?php esc_attr_e('Project summary', 'converio'); ?></h3>
				<?php the_excerpt(); ?>
				<?php if($project_url): ?><a class="btn light light-gray" href="<?php echo esc_url($project_url); ?>" target="_blank"><?php esc_attr_e('View Project', 'converio'); ?></a><?php endif; ?>
			</section>
			<?php if ($project_team) : ?>
			<section class="project-team col">
				<h3><?php esc_attr_e('Project Team', 'converio'); ?></h3>
				<?php echo do_shortcode($project_team); ?>
			</section>
			<?php endif; ?>
			<?php if ($skill_list) : ?>
			<section class="skills">
				<h3><?php esc_attr_e('Skills', 'converio'); ?></h3>
				<ul class="custom">
					<?php echo $skill_list; ?>
				</ul> 
			</section>
			<?php endif; ?>
			<?php if ($project_testimonial) : ?>
			<section>
				<h3><?php esc_attr_e('Testimonial', 'converio'); ?></h3>
				<div class="testimonial with-avatar">
					<p class="muted">&ldquo;<?php echo esc_attr($project_testimonial); ?>&rdquo;</p>
					<p><span class="name"><?php echo esc_attr($project_testimonial_author); ?></span> <span><?php echo esc_attr($project_testimonial_job); ?><?php if($project_testimonial_job !='' & $project_testimonial_company != '') : ?> / <?php endif; ?><?php echo esc_attr($project_testimonial_company); ?></span></p>
				</div>
			</section>
			<?php endif; ?>
			<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );?>
			<?php if ( is_plugin_active( 'share-this/sharethis.php' ) ) { ?>
			<section>
				<h3><?php esc_attr_e('Share this work', 'converio'); ?></h3>
				<p class="share-social">
				<em class="arrow"></em>
				<?php get_template_part('share-this');?>
				</p>
			</section>
   			<?php } else {
   				$share_links = get_theme_mod('share_links');
   				if (empty($share_links)) $share_links = 2;
   				if($share_links == 2) : ?>
				<section>
					<h3><?php esc_attr_e('Share this work', 'converio'); ?></h3>
					<?php get_template_part('share');?>
				</section>
   				<?php endif;
   			} ?>
		</aside>
		<div class="clear"></div>
</section>
<?php get_template_part('call-to-action');?>
<?php get_footer(); ?>