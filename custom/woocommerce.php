<?php

// Remove Product Permalink on Order Table after checkout
add_filter( 'woocommerce_order_item_permalink', '__return_false' );

// Remove product link in cart item list
add_filter( 'woocommerce_cart_item_permalink', '__return_false' );

// when redirected to a checkout disable the "X added to cart, continue shopping?" message
add_filter( 'wc_add_to_cart_message_html', '__return_false' );

/**
 * Remove built-in react since it's messing with the wicket widgets
 */
function wicket_remove_scripts() {
	global $wp_scripts;

	if ( ! ( is_cart() || is_checkout() ) ) {
		wp_dequeue_script( 'react' );
		wp_deregister_script( 'react' );
		wp_dequeue_script( 'react-dom' );
		wp_deregister_script( 'react-dom' );
		// wp_dequeue_script( 'wc-add-to-cart' );
		// 	wp_deregister_script( 'wc-add-to-cart' );
	}
}
add_action( 'wp_enqueue_scripts', 'wicket_remove_scripts', 100 );

/**
 * Fires after a team has been created.
 * This action hook is similar to `wc_memberships_for_teams_team_saved`
 * but doesn't fire when teams are manually created from admin.
 * found in wp-content\plugins\woocommerce-memberships-for-teams\src\Teams_Handler.php:274
 *
 * @param Team $team_post_data
 * @return void
 */
function alter_teams_data( $team_post_data ) {
	// we can't update the field value before the post exists, so we do it right after
	$order = $team_post_data->get_order();
	if ( $order ) {
		$user_id             = $order->get_user_id();
		$chosen_organization = get_user_meta( $user_id, 'org_id', true );
	}

	if ( $chosen_organization ) {
		update_field( 'wicket_organization', $chosen_organization, $team_post_data->get_id() );
	}

	$name = $team_post_data->get_name();
	// don't make changes if the team already has a name!
	if ( $name == 'Team' ) {
		$company = get_user_meta( $user_id, 'org_name', true );

		// use the company name if possible, and fallback to team ID if not
		$team_name = $company ?: sprintf( __( 'Team %s', 'woocommerce-memberships-for-teams' ), $company );
		wp_update_post( array(
			'ID'         => $team_post_data->get_id(),
			'post_title' => $team_name,
		) );

		// make sure correct meta is in the order line items so "renew now" button works in the account center and other things
		if ( $order ) {
			foreach ( $order->get_items() as $item ) {
				wc_update_order_item_meta( $item->get_id(), '_wc_memberships_for_teams_team_id', $team_post_data->get_id() );
				wc_update_order_item_meta( $item->get_id(), 'team_name', $team_name );
			}
			// update the subscription item meta as well
			$subscriptions = wcs_get_subscriptions_for_order( $order->get_id() );
			foreach ( $subscriptions as $subscription ) {
				$subscription = wcs_get_subscription( $subscription->get_id() );
				foreach ( $subscription->get_items() as $item ) {
					// used this method for orders, seems to work for subscriptions as well since the 2 are so similar
					wc_update_order_item_meta( $item->get_id(), '_wc_memberships_for_teams_team_id', $team_post_data->get_id() );
					wc_update_order_item_meta( $item->get_id(), 'team_name', $team_name );
				}
			}
		}
	}
}
add_action( 'wc_memberships_for_teams_team_created', 'alter_teams_data' );

// // enable taxonomy fields for woocommerce with gutenberg on
// function enable_taxonomy_rest( $args ) {
//   $args['show_in_rest'] = true;
//   return $args;
// }

// add_filter( 'woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest' );
// add_filter( 'woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest' );

/**
 * Add multiple products to cart via an ?add-to-cart parameter URL
 * Example: https://www.example.com/cart/?add-to-cart=12345,43453
 * Example 2: https://www.example.com/cart/?add-to-cart=12345&quantity=3
 * Credit: https://www.webroomtech.com/woocommerce-add-multiple-products-to-cart-via-url/
 */
function webroom_add_multiple_products_to_cart( $url = false ) {
	// Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
	if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
		return;
	}

	// Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
	remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

	$product_ids = explode( ',', $_REQUEST['add-to-cart'] );
	$count       = count( $product_ids );
	$number      = 0;

	foreach ( $product_ids as $id_and_quantity ) {
		// Check for quantities defined in curie notation (<product_id>:<product_quantity>)

		$id_and_quantity = explode( ':', $id_and_quantity );
		$product_id      = $id_and_quantity[0];

		$_REQUEST['quantity'] = ! empty( $id_and_quantity[1] ) ? absint( $id_and_quantity[1] ) : 1;

		if ( ++$number === $count ) {
			// Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
			$_REQUEST['add-to-cart'] = $product_id;

			return WC_Form_Handler::add_to_cart_action( $url );
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$was_added_to_cart = false;
		$adding_to_cart    = wc_get_product( $product_id );

		if ( ! $adding_to_cart ) {
			continue;
		}

		$add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );

		// Variable product handling
		if ( 'variable' === $add_to_cart_handler ) {
			woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_variable', $product_id );

			// Grouped Products
		} elseif ( 'grouped' === $add_to_cart_handler ) {
			woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_grouped', $product_id );

			// Custom Handler
		} elseif ( has_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler ) ) {
			do_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler, $url );

			// Simple Products
		} else {
			woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_simple', $product_id );
		}
	}
}

// Fire before the WC_Form_Handler::add_to_cart_action callback.
add_action( 'wp_loaded', 'webroom_add_multiple_products_to_cart', 15 );


/**
 * Invoke class private method
 *
 * @since   0.1.0
 *
 * @param   string $class_name
 * @param   string $methodName
 *
 * @return  mixed
 */
function woo_hack_invoke_private_method( $class_name, $methodName ) {
	if ( version_compare( phpversion(), '5.3', '<' ) ) {
		throw new Exception( 'PHP version does not support ReflectionClass::setAccessible()', __LINE__ );
	}

	$args = func_get_args();
	unset( $args[0], $args[1] );
	$reflection = new ReflectionClass( $class_name );
	$method     = $reflection->getMethod( $methodName );
	$method->setAccessible( true );

	//$args = array_merge( array( $class_name ), $args );
	$args = array_merge( array( $reflection ), $args );
	return call_user_func_array( array( $method, 'invoke' ), $args );
}
// End: Add multiple products to cart via ?add-to-cart link

add_filter( 'woocommerce_loop_add_to_cart_args', 'filter_woocommerce_loop_add_to_cart_args', 10, 2 );
function filter_woocommerce_loop_add_to_cart_args( $args, $product ) {

	if ( ! is_user_logged_in() ) {
		$args['class'] .= ' button--disabled';
	}
	return $args;
}

add_filter( 'woocommerce_product_add_to_cart_text', 'filter_woocommerce_product_add_to_cart_text' );
function filter_woocommerce_product_add_to_cart_text( $text ) {
	global $product;

	if ( ! is_user_logged_in() ) {
		$text = __( 'Add to cart', 'woocommerce' );
	}
	return $text;
}

add_filter( 'body_class', 'add_woocommerce_block_theme_classes' );
function add_woocommerce_block_theme_classes( $classes ) {
	if ( is_woocommerce() ) {
		$classes[] = 'woocommerce-block-theme-has-button-styles';
		$classes[] = 'woocommerce-uses-block-theme';
	}
	return $classes;
}