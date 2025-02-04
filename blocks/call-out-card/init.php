<?php
/**
 * Wicket Call Out Card block
 *
 **/

namespace Wicket\Blocks\Wicket_Call_Out_Card;

/**
 * Call Out Card block registration function
 */
function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes();

	$title       = get_field( 'call_out_card_title' );
	$description = get_field( 'call_out_card_description' );
	$links       = get_field( 'call_out_card_links' );
	$style       = get_field( 'call_out_card_style' );

	echo '<div ' . $attrs . '>';
	get_component( 'card-call-out', [ 
		'title'       => $title,
		'description' => $description,
		'links'       => $links,
		'style'       => $style,
	] );
	echo '</div>';
}
