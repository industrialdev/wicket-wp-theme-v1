<?php
/**
 * Wicket Post list block
 *
 **/

namespace Wicket\Blocks\Wicket_Post_List;

/**
 * Post list block registration function
 */
function init( $block = [] ) {

	$attrs               = get_block_wrapper_attributes();
	$posts               = get_field( 'post_list_posts' );
	$hide_excerpt        = get_field( 'post_list_hide_excerpt' );
	$hide_date           = get_field( 'post_list_hide_date' );
	$hide_featured_image = get_field( 'post_list_hide_featured_image' );
	$hide_tags           = get_field( 'post_list_hide_tags' );
	$placeholder_styles  = '';
	if ( is_admin() ) {
		$placeholder_styles = 'style="min-height: 40px;border: 1px solid var(--wp--preset--color--light);"';
	}

	if ( ! $posts ) {
		$output = '<div ' . $placeholder_styles . '>';
		if ( is_admin() ) {
			$output .= "<p>" . __( 'Use the Block controls in edit mode or on the right to add posts.', 'wicket' ) . "</p>";
		}
		$output .= '</div>';
		echo $output;
		return;
	}

	echo '<div ' . $attrs . ' ' . $placeholder_styles . '>';
	if ( is_admin() && empty( $posts ) ) {
		echo "<p>" . __( 'Use the Block controls in edit mode or on the right to add posts.', 'wicket' ) . "</p>";
	}
	foreach ( $posts as $post ) {
		$post_id        = $post->ID;
		$post_type      = get_post_type( $post_id );
		$title          = get_the_title( $post_id );
		$excerpt        = get_the_excerpt( $post_id );
		$date           = get_the_date( 'F j, Y', $post_id );
		$featured_image = get_post_thumbnail_id( $post_id );
		$permalink      = get_the_permalink( $post_id );
		$member_only    = is_member_only( $post_id );
		$topics         = get_the_terms( $post_id, 'listing-type' );

		$card_params = [ 
			'classes'        => [ 'mb-6' ],
			'content_type'   => $post_type,
			'title'          => $title,
			'excerpt'        => ! $hide_excerpt ? $excerpt : '',
			'date'           => ! $hide_date ? $date : '',
			'featured_image' => ! $hide_featured_image ? $featured_image : '',
			'link'           => [ 
				'url'    => $permalink,
				'text'   => 'Read more',
				'title'  => 'Read more',
				'target' => '_self',
			],
			'member_only'    => $member_only,
			'topics'         => ( ! $hide_tags && $topics ) ? $topics : '',
		];

		if ( component_exists( 'card-' . $post_type ) ) {
			get_component( 'card-' . $post_type, $card_params );
		} else {
			get_component( 'card', $card_params );
		}

	}
	echo '</div>';
}
