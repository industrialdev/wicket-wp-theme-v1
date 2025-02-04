<?php
/**
 * Wicket Sidebar/Contextual Nav
 *
 **/

namespace Wicket\Blocks\Wicket_SidebarContextualNav;

/**
 * Accordion block registration function
 */
function init( $block = [] ) {

	$attrs                 = get_block_wrapper_attributes();
	$icon_type             = get_field( 'icon_type' );

	echo '<div ' . $attrs . '>';
	get_component( 'sidebar-contextual-nav', [
		'icon-type'     => $icon_type,
	] );
	echo '</div>';
  
}
