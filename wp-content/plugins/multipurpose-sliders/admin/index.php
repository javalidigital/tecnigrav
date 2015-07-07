<?php
include_once ABSPATH.'wp-admin/includes/meta-boxes.php';
$iworks_sliders->update();
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e('Silders Configuration', 'iworks-sliders') ?></h2>
    <form method="post" action="options.php" id="iworks_sliders_admin_index">
<?php
$option_name = basename( __FILE__, '.php');
$iworks_sliders_options->settings_fields( $option_name );
$iworks_sliders_options->build_options( $option_name );
?>
    </form>
</div>

