<?php 
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'converio_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'converio_wrapper_end', 10);

function converio_wrapper_start() {
  echo '<section class="main single">';
}

function converio_wrapper_end() {
  echo '</section>';
}


//image gallery thumbs on product page
add_image_size('shop_thumbnail_gallery', 120, 134, true);


//check if woocommerce plugin is activated
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) {
			return true;
		}
		else {
			return false;
		}
	}
}


//showing cart header menu item
function converio_cart_menu_item() {
if(is_woocommerce_activated()){ 
?>

<li class="border"><a class="cart collapsed" data-target=".shopping-bag" href="javascript:;">
	<span class="cart-icon"><?php esc_attr_e('Cart','converio');?></span>
	
	<?php
	global $woocommerce;
	$my_cart_count = $woocommerce->cart->cart_contents_count;

		echo '<span class="cart-number-box';
		if ($my_cart_count > 0) {
	 		echo ' active';
		}
		echo '">';echo sprintf( _n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes' ), $woocommerce->cart->cart_contents_count );
		echo '</span>';
	?>
	</a></li>
<?php }
}

function converio_cart_shopping_bag(){
	if(is_woocommerce_activated()){
		?> 
		<div class="shopping-bag">
		<?php
			if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
				the_widget( 'WC_Widget_Cart', 'title=' );
			} else {
				the_widget( 'WooCommerce_Widget_Cart', 'title=' );
			} ?>
		</div>
<?php
	}
}

add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment' ) ) {
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
	?>

	<?php
	echo '<span class="cart-number-box';
	$my_cart_count = $woocommerce->cart->cart_contents_count;
	if ($my_cart_count > 0) {
	 echo ' active';
	}	
	echo '">';
	?>
	<?php
		echo sprintf( _n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes' ), $woocommerce->cart->cart_contents_count );
	?></span>


	<?php

		$fragments['span.cart-number-box'] = ob_get_clean();

		return $fragments;
	} // End woocommerce_header_add_to_cart_fragment()
}

//Removing Breadcrumbs in WooCommerce
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

//Disable the default stylesheet - Woocommerce
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// Display 12 products per page.
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );


/**
* WooCommerce Extra Feature
* --------------------------
*
* Change number of related products on product page
* Set your own value for 'posts_per_page'
*
*/
function converio_woo_related_products_limit() {
global $product;
$args = array(
'post_type' => 'product',
'no_found_rows' => 1,
'posts_per_page' => 9,
'ignore_sticky_posts' => 1,
'orderby' => @$orderby,
'post__in' => @$related,
'post__not_in' => array($product->id)
);
return $args;
}
add_filter( 'woocommerce_related_products_args', 'converio_woo_related_products_limit' );


//Remove prettyPhoto lightbox
add_action( 'wp_enqueue_scripts', 'converio_remove_woo_lightbox', 99 );
function converio_remove_woo_lightbox() {
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
        wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
        wp_dequeue_script( 'prettyPhoto' );
        wp_dequeue_script( 'prettyPhoto-init' );
}


/*
 * Hook in on activation
 *
 */
add_action( 'init', 'converio_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function converio_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '560',	// px
		'height'	=> '627',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '560',	// px
		'height'	=> '626',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '60',	// px
		'height'	=> '60',	// px
		'crop'		=> 1 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}