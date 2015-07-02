<?php

function converio_contact_options() {
    add_theme_page(
        esc_attr__('Contact page settings', 'converio'), 
        esc_attr__('Contact page', 'converio'), 
        'administrator', 
        'converio_contact_settings', 
        'converio_contact_settings_page'
    );
}
add_action('admin_menu', 'converio_contact_options');


function converio_register_contact_settings() {
    $options = array (
        'contact_email', 
        'contact_msg_success', 
        'contact_msg_err_no_name', 
        'contact_msg_err_no_email', 
        'contact_msg_err_inv_email', 
        'contact_msg_err_no_subject', 
        'contact_msg_err_no_message', 
        'contact_msg_err', 
        'contact_subjects');
	foreach ($options as $opt) register_setting( 'converio-contact-settings', $opt);
}
add_action( 'admin_init', 'converio_register_contact_settings' );

function converio_contact_settings_page() { 
?>
<div class="wrap">
<h1><?php esc_attr_e('Contact form settings', 'converio'); ?></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'converio-contact-settings' ); ?>
    <?php do_settings_sections( 'converio-contact-settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('E-mail address', 'converio'); ?></th>
        <td><input type="text" name="contact_email" value="<?php echo esc_attr(get_option('contact_email')); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Email subjects (one per line)', 'converio'); ?></th>
        <td><textarea rows="5" cols="60" name="contact_subjects" class="widefat"><?php echo esc_attr(get_option('contact_subjects')); ?></textarea></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Success message', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_success" value="<?php echo esc_attr(get_option('contact_msg_success')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Error: no name given', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err_no_name" value="<?php echo esc_attr(get_option('contact_msg_err_no_name')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Error: no e-mail', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err_no_email" value="<?php echo esc_attr(get_option('contact_msg_err_no_email')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Error: e-mail invalid', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err_inv_email" value="<?php echo esc_attr(get_option('contact_msg_err_inv_email')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Error: no subject', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err_no_subject" value="<?php echo esc_attr(get_option('contact_msg_err_no_subject')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Error: no message', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err_no_message" value="<?php echo esc_attr(get_option('contact_msg_err_no_message')); ?>" class="widefat" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php esc_attr_e('Other error', 'converio'); ?></th>
        <td><input type="text" name="contact_msg_err" value="<?php echo esc_attr(get_option('contact_msg_err')); ?>" class="widefat" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>