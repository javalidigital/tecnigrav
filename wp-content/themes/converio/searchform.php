<form method="get" class="searchform" action="<?php echo esc_url(home_url()); ?>">
<fieldset>
	<input type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php esc_attr_e('Search', 'converio') ?>" id="s"/>
	<button type="submit" name="searchsubmit" value="<?php esc_attr_e('Search', 'converio') ?>"><?php esc_attr_e('Search', 'converio') ?></button>
</fieldset>
</form>
