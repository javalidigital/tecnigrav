<?php
function converio_related_projects($project) {
	$cats = array();
	$skills = array();

	$c = wp_get_object_terms($project->ID, 'project-categories');
	foreach($c as $k => $v) {
		$cats[] = $v->term_id;
	}

	$s = wp_get_object_terms($project->ID, 'project-categories');
	foreach($s as $k => $v) {
		$skills[] = $v->term_id;
	}

	$options = array(
		'posts_per_page' => 5,
		'post_type' => 'project',
		'post_status' => 'publish',
		'tax_query' => array(
		'relation' => 'OR',
			array(
				'taxonomy' => 'project-categories',
				'field' => 'id',
				'terms' => $cats,
				'include_children' => false,
				'meta_key' => '_thumbnail_id'
			),
			array(
				'taxonomy' => 'project-skills',
				'field' => 'id',
				'terms' => $skills,
				'include_children' => false,
				'meta_key' => '_thumbnail_id'
			)
		)
	);
	$related = new WP_Query($options);
	if($related->have_posts()) : ?>
		<div class="page-portfolio"><section class="columns portfolio">
			<h3><?php esc_attr_e('Related projects', 'converio'); ?></h3>
					<?php while($related->have_posts()) : $related->the_post(); 
					if($project->ID == get_the_id()) continue;
					?><article class="col col4"><div>
					<?php if(has_post_thumbnail()) : 
					$th_file = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_id()), 'large');
					?>
					<div class="img"><a href=""><?php the_post_thumbnail('thumbnail-related', array()); ?></a>
						<div>
							<ul>
								<?php 
								$project_image_link_hidden = get_post_meta(get_the_id(), 'project_image_link_hidden', true);
								if (!$project_image_link_hidden) : ?>
								<li><a href="<?php echo esc_url($th_file[0]); ?>" class="action view"><?php esc_attr_e('View', 'converio'); ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div></article><?php endwhile; ?>
		</section></div>
	<?php endif;
	 wp_reset_postdata();
}

function related_posts($post) {
	$cids = wp_get_post_categories($post->ID);

	$options = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'category__in' => $cids
	);
	$related = new WP_Query($options);
	if($related->have_posts()) : ?>


					<?php 
					$count_related_posts=0;
					$output = ''; 
					?>
					<?php while($related->have_posts()) : $related->the_post(); 
					if($post->ID == get_the_id()) continue;
					?>
						<?php if ($count_related_posts < 3) : ?>
							<?php if(has_post_thumbnail() ) { 
								$output .= '<article class="col col3">';
									$output .= '<a href="';
									$output .= get_permalink();
									$output .= '"><div class="img">';
									$output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail-related', array());
									$output .= ' </div></a>';
									$output .= '<h3><a href="';
									$output .= get_permalink();
									$output .= '">';
									$output .= get_the_title();
									$output .= '</a></h3>';
								$output .= '</article>';
								$count_related_posts += 1; ?>
							<?php } else { ?>
									<?php $excerpt = get_the_excerpt();
									if ($excerpt <> '') { 
										$output .= '<article class="col col3">';
											$output .= '<h3><a href="';
											$output .= get_permalink();
											$output .= '">';
											$output .= get_the_title();
											$output .= '</a></h3>';
											$output .= converio_custom_excerpt(30, $post);
										$output .= '</article>';
										$count_related_posts += 1;
									} ?>
								<?php } ?>
						<?php endif; ?>
					<?php endwhile; ?>
					
					<?php if($output <> '') { ?>
						<section class="related">
							<h2><?php esc_attr_e('Related posts', 'converio'); ?></h2>
							<div class="columns">
								<?php echo $output;?>
							</div>
						</section>
					<?php } ?>
	<?php endif;
	 wp_reset_postdata();
}