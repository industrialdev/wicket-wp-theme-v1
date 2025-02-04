<?php
/**
 * Wicket Search Form Block
 *
 **/

namespace Wicket\Blocks\Wicket_Search_Form;

/**
 * Initialize the Wicket Search Form block.
 */
function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes();

	$placeholder = get_field( 'search_form_placeholder' );

	echo '<div ' . $attrs . '>';
	get_component( 'search-form', [ 
		'placeholder' => $placeholder,
	] );
	echo '</div>';
}
