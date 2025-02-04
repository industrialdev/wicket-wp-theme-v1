<?php
/**
 * Wicket Featured posts block
 *
 **/

namespace Wicket\Blocks\Wicket_Featured_Posts;

/**
 * Featured posts block registration function
 */
function init( $block = [] ) {

	$attrs               = get_block_wrapper_attributes();
	$title               = get_field( 'featured_posts_title' );
	$posts               = get_field( 'featured_posts_posts' );
	$hide_excerpt        = get_field( 'featured_posts_hide_excerpt' );
	$hide_date           = get_field( 'featured_posts_hide_date' );
	$hide_featured_image = get_field( 'featured_posts_hide_featured_image' );
	$hide_content_type   = get_field( 'featured_posts_hide_content_type' );
	$style               = get_field( 'featured_posts_style' );
	$column_count        = get_field( 'featured_posts_column_count' );
	$placeholder_styles  = '';
	if ( is_admin() ) {
		$placeholder_styles = 'style="min-height: 40px;border: 1px solid var(--wp--preset--color--light);"';
	}

	if ( ! $posts ) {
		$output = '<div ' . $placeholder_styles . '>';
		if ( is_admin() ) {
			$output .= "<p>" . __( 'Use the Block controls in edit mode or on the right to add featured posts.', 'wicket' ) . "</p>";
		}
		$output .= '</div>';
		echo $output;
		return;
	}

	echo '<div ' . $attrs . ' ' . $placeholder_styles . '>';
	if ( is_admin() && empty( $posts ) ) {
		echo "<p>" . __( 'Use the Block controls in edit mode or on the right to add featured posts.', 'wicket' ) . "</p>";
	}
	get_component( 'featured-posts', [ 
		'title'               => $title,
		'posts'               => $posts,
		'hide_excerpt'        => $hide_excerpt,
		'hide_date'           => $hide_date,
		'hide_featured_image' => $hide_featured_image,
		'hide_content_type'   => $hide_content_type,
		'style'               => $style,
		'column_count'        => $column_count,
	] );
	echo '</div>';
}
