<?php 
	global $wp_query;
	$big = 999999999;
?>
<?php if($wp_query->max_num_pages > 1) { ?>
<div class='wp-pagenavi'>
<?php
	echo paginate_links(array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),  
	    'format' => '/page/%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'prev_text' => esc_attr__('', 'converio'),
		'next_text' => esc_attr__('', 'converio'),
	));
?> 
</div>
<?php } ?>