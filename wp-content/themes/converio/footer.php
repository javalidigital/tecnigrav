<div class="clear"></div>

	<?php 
	$disable_top_link = get_theme_mod('disable_top_link');
	if(!$disable_top_link): ?>
		<a href="#top" class="go-top"><?php esc_attr_e('top', 'converio');?></a>
	<?php endif; ?>


	<?php
	$fbg_color = get_theme_mod('footer_bg_color');
    if ($fbg_color) $style = 'style="background-color: ' . esc_attr($fbg_color) . '"';
    else $style = '';
	?>

	<footer <?php if($style) echo $style; ?> >
		<?php if(is_active_sidebar('footer-widget-area')) : ?>
		<section class="widgets columns">
			<?php 
			$column_count = get_theme_mod('column_count');
			if (empty($column_count)) $column_count = 4;
			$col_class = 'col' . $column_count;
			?>
			<?php
			if (!dynamic_sidebar('footer-widget-area') ) : ?><?php endif; ?>		
		</section>
		<?php endif; ?>	
		<section class="bottom">
			
			<?php $show_footer_content = get_theme_mod('show_footer_content');
			if($show_footer_content) {
				$footer_content = get_theme_mod('footer_content');
				echo converio_sanitize_text_decode($footer_content);
			} else { ?>
				<p><?php esc_attr_e('2013-2014', 'converio');?> <?php if( is_front_page() & !is_paged() ) { ?><a href="<?php echo esc_url('http://thememotive.com/converio/');?>"><?php esc_attr_e('Converio', 'converio');?></a><?php } else { esc_attr_e('Converio', 'converio'); } ?> <?php esc_attr_e('WordPress Theme by', 'converio');?> <a href="<?php echo esc_url('http://thememotive.com/'); ?>"><?php esc_attr_e('ThemeMotive.', 'converio');?></a><?php esc_attr_e(' | All rights reserved.', 'converio');?></p>
			<?php } ?>
			<nav class="social social-light social-colored">
				<ul>
					<?php $social_links = converio_get_social_links(); 
					foreach($social_links as $link) : ?>
					<li><a href="<?php echo esc_url($link->url); ?>" class="<?php echo esc_attr($link->class) ?>" title="<?php echo esc_attr($link->name) ?>" target="_blank"><?php echo esc_attr($link->name) ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</section>
	</footer>
</div>

<?php wp_footer(); ?>

<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/ie.js"></script>
<![endif]-->
</body>
</html>