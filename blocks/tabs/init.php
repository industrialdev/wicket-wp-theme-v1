<?php
/**
 * Wicket Tabs
 *
 **/

namespace Wicket\Blocks\Wicket_Tabs;

/**
 * Tab block registration function
 */
function init( $block = [] ) {

	$attrs = get_block_wrapper_attributes();
	$items = get_field( 'tab_items' );

	echo '<div ' . $attrs . '>';
	get_component( 'tabs', [ 
		'items' => $items,
	] );
	echo '</div>';


}
