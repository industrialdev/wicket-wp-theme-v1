<?php
/**
 * Wicket author block
 *
 **/

namespace Wicket\Blocks\Wicket_Author;

function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes(
		array( 'class' => 'flex flex-col gap-8' )
	);

	$title   = get_field( 'author_title' );
	$authors = get_field( 'author_authors' );

	echo '<div ' . $attrs . '>';

	if ( $title ) {
		echo '<div class="flex items-center gap-5">';
		echo '<div class="text-heading-sm font-bold">' . $title . '</div>';
		echo '<span class="border-t border-light-020 inline-flex flex-auto"></span>';
		echo '</div>';
	}

	if ( $authors ) {
		echo '<div class="flex flex-col gap-8">';
		foreach ( $authors as $author ) {
			get_component( 'author', [ 
				'author'             => isset( $author['author'] ) ? $author['author'] : null,
				'hide_profile_image' => isset( $author['hide_profile_image'] ) ? $author['hide_profile_image'] : false,
				'hide_bio'           => isset( $author['hide_bio'] ) ? $author['hide_bio'] : false,
			] );
		}
		echo '</div>';
	}

	echo '</div>';
}
