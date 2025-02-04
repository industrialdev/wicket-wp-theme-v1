<?php
/**
 * Wicket Contact Card block
 *
 **/

namespace Wicket\Blocks\Wicket_Contact_Card;

/**
 * Contact Card block registration function
 */
function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes();

	$title       = get_field( 'contact_card_title' );
	$description = get_field( 'contact_card_description' );
	$email       = get_field( 'contact_card_email' );
	$phone       = get_field( 'contact_card_phone' );
	$style       = get_field( 'contact_card_style' );

	echo '<div ' . $attrs . '>';
	get_component( 'card-contact', [ 
		'title'       => $title,
		'description' => $description,
		'email'       => $email,
		'phone'       => $phone,
		'style'       => $style,
	] );
	echo '</div>';
}
