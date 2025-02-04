<?php
/**
 * Block: Tickets
 * Items
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/tickets/items.php
 *
 * See more documentation about our views templating system.
 *
 * @link    https://evnt.is/1amp Help article for RSVP & Ticket template files.
 *
 * @since   5.0.3
 *
 * @version 5.0.3
 *
 * @var Tribe__Tickets__Editor__Template   $this                        [Global] Template object.
 * @var int                                $post_id                     [Global] The current Post ID to which tickets are attached.
 * @var Tribe__Tickets__Tickets            $provider                    [Global] The tickets provider class.
 * @var string                             $provider_id                 [Global] The tickets provider class name.
 * @var Tribe__Tickets__Ticket_Object[]    $tickets                     [Global] List of tickets.
 * @var array                              $cart_classes                [Global] CSS classes.
 * @var Tribe__Tickets__Ticket_Object[]    $tickets_on_sale             [Global] List of tickets on sale.
 * @var bool                               $has_tickets_on_sale         [Global] True if the event has any tickets on sale.
 * @var bool                               $is_sale_past                [Global] True if tickets' sale dates are all in the past.
 * @var bool                               $is_sale_future              [Global] True if no ticket sale dates have started yet.
 * @var Tribe__Tickets__Commerce__Currency $currency                    [Global] Tribe Currency object.
 * @var Tribe__Tickets__Tickets_Handler    $handler                     [Global] Tribe Tickets Handler object.
 * @var Tribe__Tickets__Privacy            $privacy                     [Global] Tribe Tickets Privacy object.
 * @var int                                $threshold                   [Global] The count at which "number of tickets left" message appears.
 * @var bool                               $show_original_price_on_sale [Global] Show original price on sale.
 * @var null|bool                          $is_mini                     [Global] If in "mini cart" context.
 * @var null|bool                          $is_modal                    [Global] Whether the modal is enabled.
 * @var string                             $submit_button_name          [Global] The button name for the tickets block.
 * @var string                             $cart_url                    [Global] Link to Cart (could be empty).
 * @var string                             $checkout_url                [Global] Link to Checkout (could be empty).
 */

if ( empty( $tickets_on_sale ) ) {
	return;
}

foreach ( $tickets_on_sale as $key => $ticket ) {
	$available_count = $ticket->available();

	/**
	 * Allows hiding of "unlimited" to be toggled on/off conditionally.
	 *
	 * @since 4.11.1
	 * @since 5.0.3 Added $ticket parameter.
	 *
	 * @var bool                          $show_unlimited  Whether to show the "unlimited" text.
	 * @var int                           $available_count The quantity of Available tickets based on the Attendees number.
	 * @var Tribe__Tickets__Ticket_Object $ticket          The ticket object.
	 */
	$show_unlimited = apply_filters( 'tribe_tickets_block_show_unlimited_availability', true, $available_count, $ticket );

	$has_shared_cap = $handler->has_shared_capacity( $ticket );

  // test whether to display this specific ticket based on the wppcp settings on the ticket product, same way we protect pages
	$visibility = get_post_meta($ticket->ID, '_wppcp_post_page_visibility', true);
	$visibility_roles = get_post_meta($ticket->ID, '_wppcp_post_page_roles', true);
  $can_see_this = false;
  $message = '';

  if ($visibility == '' || $visibility == 'none' || $visibility == 'all' || $visibility == 'guest') {
    $can_see_this = true;
  }

  if ($visibility == 'member' && !is_user_logged_in()) {
    $message = 'You must be logged in to purchase this ticket';
  }

  if ($visibility == 'role') {
    $message = 'You do not have the correct permissions to see this ticket';
    $user = wp_get_current_user();
    foreach ($visibility_roles as $role) {
      if(in_array($role, $user->roles)){
        $can_see_this = true;
        $message = '';
      }
    }
  }

  // check to see if the global ticket setting "Require users to log in before they purchase tickets" is checked
  // from plugins/event-tickets/src/Tickets/Commerce/Module.php:308
  $requirements = (array) tribe_get_option( 'ticket-authentication-requirements', array() );
  $requirements = in_array( 'event-tickets_all', $requirements, true );

  // override above stuff if the global ticket setting is checked
  if($requirements){
    $message = '';
    $can_see_this = true;
  }

  // check to see if there is a discount being added by the 'Role Based Pricing for WooCommerce' plugin
  // This is using a helper function found here: plugins/role-based-pricing-for-woocommerce/includes/af-product-pricing-functions.php
  if (function_exists('addify_csp_get_role_based_price')) {
     $new_price = addify_csp_get_role_based_price($ticket->ID);
     $ticket->price = $new_price != false ? $new_price : $ticket->price;
  }

  if ($can_see_this) {
    $this->template(
      'v2/tickets/item',
      [
        'ticket'              => $ticket,
        'key'                 => $key,
        'data_available'      => 0 === $handler->get_ticket_max_purchase( $ticket->ID ) ? 'false' : 'true',
        'has_shared_cap'      => $has_shared_cap,
        'data_has_shared_cap' => $has_shared_cap ? 'true' : 'false',
        'currency_symbol'     => $currency->get_currency_symbol( $ticket->ID, true ),
        'show_unlimited'      => (bool) $show_unlimited,
        'available_count'     => $available_count,
        'is_unlimited'        => - 1 === $available_count,
        'max_at_a_time'       => $handler->get_ticket_max_purchase( $ticket->ID ),
      ]
    );
  }
  // if there's a message, display it. This would be if the logic above is being utilized and that global setting isn't being used
  if($message && !$requirements){
    echo "<p>$message</p>";
  }
}



