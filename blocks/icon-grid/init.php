<?php
/**
 * Wicket Icon Grid block
 *
 **/

namespace Wicket\Blocks\Wicket_Icon_Grid;

/**
 * Icon Grid block registration function
 */
function init( $block = [] ) {

	$attrs = get_block_wrapper_attributes();

	$title            = get_field( 'icon_grid_title' );
	$use_fa_codes     = get_field( 'use_font-awesome_icon_codes' );
	$use_drop_shadows = get_field( 'icon_grid_drop_shadow' );
	$show_arrow       = get_field( 'show_arrow' );
	$icons            = get_field( 'icon_grid_icons' );

	echo '<div ' . $attrs . '>';
	get_component( 'icon-grid', [ 
		'title'            => $title,
		'use-fa-codes'     => $use_fa_codes,
		'use-drop-shadows' => $use_drop_shadows,
		'show-arrow-icon'  => $show_arrow,
		'icons'            => $icons,
	] );
	echo '</div>';

	
}
