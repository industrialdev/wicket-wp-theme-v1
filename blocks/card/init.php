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
	$title       = get_field( 'card_title' );
	$subtitle    = get_field( 'card_subtitle' );
	$excerpt     = get_field( 'card_excerpt' );
	$link        = get_field( 'card_link' );
	$cta_style   = get_field( 'card_cta_style' );
	$image       = get_field( 'card_image' );
	$full_height = get_field( 'full_height' );

	$attrs = get_block_wrapper_attributes(
		[ 
			'class' => $full_height ? 'wp-block-wicket-card-is-full-height' : '',
		]
	);

	echo '<div ' . $attrs . '>';
	get_component( 'card', [ 
		'title'       => $title,
		'subtitle'    => $subtitle,
		'excerpt'     => $excerpt,
		'link'        => $link,
		'cta_style'   => $cta_style,
		'image'       => $image,
		'full_height' => $full_height,
	] );
	echo '</div>';
}
