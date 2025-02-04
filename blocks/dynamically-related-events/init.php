<?php
/**
 * Wicket Dynamically Related Events Block
 *
 **/

namespace Wicket\Blocks\Wicket_Dynamically_Related_Events;

function init( $block = [] ) {

	$attrs                      = get_block_wrapper_attributes( [ 
		'class' => 'alignfull',
	] );
	$title                      = get_field( 'related_events_title' );
	$hide_block_title           = get_field( 'related_events_hide_title' );
	$show_view_all              = get_field( 'related_events_show_view_all' );
	$set_custom_view_all        = get_field( 'related_events_set_custom_view_all' );
	$view_all_link              = get_field( 'related_events_view_all_link' );
	$column_count               = get_field( 'related_events_column_count' );
	$max_posts                  = get_field( 'related_events_max_posts' );
	$taxonomies                 = get_field( 'related_events_taxonomies' );
	$hide_excerpt               = get_field( 'related_events_hide_excerpt' );
	$hide_date                  = get_field( 'related_events_hide_date' );
	$hide_featured_image        = get_field( 'related_events_hide_featured_image' );
	$hide_event_category        = get_field( 'related_events_hide_event_category' );
	$hide_price                 = get_field( 'related_events_hide_price' );
	$hide_start_date_indicator  = get_field( 'related_events_hide_start_date_indicator' );
	$hide_event_format_location = get_field( 'related_events_hide_event_format_location' );
	$remove_drop_shadow         = get_field( 'related_events_remove_drop_shadow' );
	$highlight_featured_posts   = get_field( 'related_events_highlight_featured_posts' );
	$number_of_featured_posts   = get_field( 'related_events_featured_posts_number' );
	$show_cta                   = get_field( 'related_events_show_cta' );
	$cta_options                = get_field( 'related_events_cta_options' );
	$show_tags                  = get_field( 'related_events_show_tags' );
	$tag_taxonomy               = get_field( 'related_events_tag_taxonomy' );
	$cta_style                  = isset( $cta_options, $cta_options['button_style'] ) ? $cta_options['button_style'] : null;
	$cta_label                  = isset( $cta_options, $cta_options['label'] ) ? $cta_options['label'] : null;

	$current_post_id = get_the_ID();

	echo '<div ' . $attrs . '>';

	get_component( 'related-events', [ 
		'title'                      => $title,
		'hide_block_title'           => $hide_block_title,
		'show_view_all'              => $show_view_all,
		'set_custom_view_all'        => $set_custom_view_all,
		'view_all_link'              => $view_all_link,
		'column_count'               => $column_count,
		'max_posts'                  => $max_posts,
		'taxonomies'                 => $taxonomies,
		'hide_excerpt'               => $hide_excerpt,
		'hide_date'                  => $hide_date,
		'hide_featured_image'        => $hide_featured_image,
		'hide_event_category'        => $hide_event_category,
		'hide_price'                 => $hide_price,
		'hide_start_date_indicator'  => $hide_start_date_indicator,
		'hide_event_format_location' => $hide_event_format_location,
		'remove_drop_shadow'         => $remove_drop_shadow,
		'highlight_featured_posts'   => $highlight_featured_posts,
		'number_of_featured_posts'   => $number_of_featured_posts,
		'show_cta'                   => $show_cta,
		'cta_options'                => $cta_options,
		'cta_style'                  => $cta_style,
		'cta_label'                  => $cta_label,
		'current_post_id'            => $current_post_id,
		'show_tags'                  => $show_tags,
		'tag_taxonomy'               => $tag_taxonomy,
	] );
	echo '</div>';
}
