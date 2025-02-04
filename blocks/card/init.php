<?php
/**
 * Wicket Card block
 *
 **/

namespace Wicket\Blocks\Wicket_Card;

/**
 * Card block registration function
 */
function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes();

	$title     = get_field( 'card_title' );
	$subtitle  = get_field( 'card_subtitle' );
	$excerpt   = get_field( 'card_excerpt' );
	$link      = get_field( 'card_link' );
	$cta_style = get_field( 'card_cta_style' );
	$image     = get_field( 'card_image' );

	echo '<div ' . $attrs . '>';
	get_component( 'card', [ 
		'title'     => $title,
		'subtitle'  => $subtitle,
		'excerpt'   => $excerpt,
		'link'      => $link,
		'cta_style' => $cta_style,
		'image'     => $image,
	] );
	echo '</div>';
}
