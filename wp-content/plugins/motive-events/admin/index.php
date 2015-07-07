<?php
$show_metabox_help = apply_filters('iworks_events_show_metabox_help', true);
if ( $show_metabox_help ) {
    include_once ABSPATH.'wp-admin/includes/meta-boxes.php';
}
$iworks_events->update();
?>
<div class="wrap">
    <h2><?php _e('Events Settings', 'iworks_events') ?></h2>
    <?php settings_errors(); ?>
    <form method="post" action="options.php" id="iworks_events_admin_index">
<?php if ( $show_metabox_help ) { ?>
        <div class="postbox-container" style="width:75%">
<?php } ?>
<?php

$option_name = basename( __FILE__, '.php');
$iworks_events_options->settings_fields( $option_name );
$iworks_events_options->build_options( $option_name );

?>
<?php if ( $show_metabox_help ) { ?>
        </div>
        <div class="postbox-container" style="width:23%;margin-left:2%">
            <div class="metabox-holder">
                <div id="help" class="postbox">
                    <h3 class="hndle"><?php _e( 'Need Assistance?', 'iworks_events' ); ?></h3>
                    <div class="inside">
                        <p><?php _e( 'Problems? The links bellow can be very helpful to you', 'iworks_events' ); ?></p>
                        <ul>
                            <li><a href="mailto:<?php echo antispambot('marcin@iworks.pl'); ?>"><?php printf( __( 'Mail to me: %s', 'iworks_events' ), antispambot( 'marcin@iworks.pl' ) ); ?></a></li>
                        </ul>
                        <hr />
                        <p class="description"><?php _e('Created by: ', 'iworks_events' ); ?> <a href="http://iworks.pl/"><span>iWorks.pl</span></a></p>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>
    </form>
</div>
