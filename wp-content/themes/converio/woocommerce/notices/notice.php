<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info msg info"><?php echo wp_kses_post( $message ); ?> <a href="#" class="hide"><?php esc_attr_e( 'hide this', 'woocommerce' ); ?></a></div>
<?php endforeach; ?>