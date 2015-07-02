<?php 
function converio_analytics() {
	$analytics = get_theme_mod('analytics') ? get_theme_mod('analytics') : false;
	echo converio_sanitize_text_decode($analytics);
}
add_action('wp_footer', 'converio_analytics');