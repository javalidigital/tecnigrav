<?php 
$call_to_action = get_theme_mod('call_to_action');
if (empty($call_to_action)) $call_to_action = -1;

if($call_to_action == 1) {
$call_to_action_text = get_theme_mod('call_to_action_text');
$call_to_action_button_text = get_theme_mod('call_to_action_button_text');
$call_to_action_button_link = get_theme_mod('call_to_action_button_link');

$pattern = esc_attr(get_theme_mod('call_to_action_pattern'));
if ($pattern == 0) {
    $pattern_class = '';
} else {
    if ($pattern < 10) {
        $pattern = "0" . $pattern;
    }
    $pattern_class = "p".$pattern;
}

$color = esc_attr(get_theme_mod('call_to_action_color'));
if ($color) {
	$style = 'style="background-color: ' . $color . '"';
}
else {
	$style = '';
}

echo '<section class="hp-intro full-width-bg" '.$style.'>'; ?>
<?php	if ($pattern_class != '') {
		echo '<div class="custom-bg '.$pattern_class.'"></div>';
	}
?>
	<div class="content-container">
		<p class="slogan"><?php echo converio_sanitize_text($call_to_action_text);?></p>
		<?php $color_scheme = get_theme_mod('color_scheme') ? get_theme_mod('color_scheme') : 'green';?>
		<p class="cta"><a href="<?php echo esc_url($call_to_action_button_link);?>" class="btn large <?php echo esc_attr($color_scheme); ?>"><?php echo esc_attr($call_to_action_button_text);?></a></p>
	</div>
</section>

<?php } ?>