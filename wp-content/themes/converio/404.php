<?php get_header(); ?>
<div class="root404">
<section class="content custom-bg-color">
	<div class="custom-bg p01"></div>
	<section class="e404 content-container">
		<article>
			<p><?php esc_attr_e("Oops, page not found", 'converio'); ?></p>
			<p><?php esc_attr_e("Sorry, the page you are looking for is not here. Use the search field below to find something else or go back to", 'converio'); ?>  <a href="<?php echo esc_url(home_url()); ?>"><?php esc_attr_e('Homepage', 'converio'); ?></a> <?php esc_attr_e("to start from scratch.", 'converio'); ?></p>
		</article>
		<article>
			<div class="search">
			<?php get_search_form(); ?>
			</div>
		</article>
	</section>
</section>
</div>
<?php get_footer(); ?>

