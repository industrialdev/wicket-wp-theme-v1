<?php
/**
 * Wicket Dynamically Related Content Block
 *
 **/

namespace Wicket\Blocks\Wicket_Dynamically_Related_Content;

/**
 * Featured posts block registration function
 */
function init( $block = [] ) {

	$attrs                    = get_block_wrapper_attributes( [ 
		'class' => 'alignfull',
	] );
	$title                    = get_field( 'related_content_title' );
	$hide_block_title         = get_field( 'related_content_hide_title' );
	$column_count             = get_field( 'related_content_column_count' );
	$max_posts                = get_field( 'related_content_max_posts' );
	$post_type                = get_field( 'related_content_post_type' );
	$taxonomies               = get_field( 'related_content_taxonomies' );
	$highlight_featured_posts = get_field( 'related_content_highlight_featured_posts' );
	$number_of_featured_posts = get_field( 'related_content_featured_posts_number' );
	$hide_excerpt             = get_field( 'related_content_hide_excerpt' );
	$hide_date                = get_field( 'related_content_hide_date' );
	$hide_featured_image      = get_field( 'related_content_hide_featured_image' );
	$hide_content_type        = get_field( 'related_content_hide_content_type' );
	$show_cta                 = get_field( 'related_content_show_cta' );
	$show_view_all            = get_field( 'related_content_show_view_all' );
	$set_custom_view_all      = get_field( 'related_content_set_custom_view_all' );
	$view_all_link            = get_field( 'related_content_view_all_link' );
	$cta_options              = get_field( 'related_content_cta_options' );
	$cta_style                = isset( $cta_options, $cta_options['button_style'] ) ? $cta_options['button_style'] : null;
	$cta_label                = isset( $cta_options, $cta_options['label'] ) ? $cta_options['label'] : null;

	$current_post_id = get_the_ID();

	echo '<div ' . $attrs . '>';

	get_component( 'related-posts', [ 
		'title'                    => $title,
		'hide_block_title'         => $hide_block_title,
		'column_count'             => $column_count,
		'max_posts'                => $max_posts,
		'post_type'                => $post_type,
		'taxonomies'               => $taxonomies,
		'highlight_featured_posts' => $highlight_featured_posts,
		'number_of_featured_posts' => $number_of_featured_posts,
		'hide_excerpt'             => $hide_excerpt,
		'hide_date'                => $hide_date,
		'hide_featured_image'      => $hide_featured_image,
		'hide_content_type'        => $hide_content_type,
		'show_cta'                 => $show_cta,
		'show_view_all'            => $show_view_all,
		'set_custom_view_all'      => $set_custom_view_all,
		'view_all_link'            => $view_all_link,
		'cta_style'                => $cta_style,
		'cta_label'                => $cta_label,
		'current_post_id'          => $current_post_id,
	] );
	echo '</div>';
}
