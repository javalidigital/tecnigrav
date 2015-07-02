<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="wrap">

	<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
	<ul>
		<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :

			$_product = $cart_item['data'];

			// Only display if allowed
			if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 )
				continue;

			// Get price
			$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

			$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
			?>

			<li>
						
				<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
					
					<?php echo $_product->get_image( array( 50, 50 ) ); ?>

					<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>

				<?php echo $woocommerce->cart->get_item_data( $cart_item ); ?>

				<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
				</a>
			</li>

		<?php endforeach; ?>
	</ul><!-- end product list -->
	<?php else : ?>

		<p class="empty-item"><?php esc_attr_e( 'There are no items in your cart.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
	<div class="meta">
	<p class="total"><?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>: <strong><?php echo $woocommerce->cart->get_cart_subtotal(); ?></strong></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="btn-list">
		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="cart-button"><?php esc_attr_e( 'View Cart', 'woocommerce' ); ?></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="cart-button"><?php esc_attr_e( 'Checkout', 'woocommerce' ); ?></a>
	</p>
	</div>


<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
