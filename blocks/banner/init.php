<?php
/**
 * Wicket Banner block
 *
 **/

namespace Wicket\Blocks\Wicket_Banner;

/**
 * Banner block registration function
 */
function init( $block = [] ) {
	$attrs = get_block_wrapper_attributes();

	$title                    = get_field( 'banner_title' ) ?: get_the_title();
	$intro                    = get_field( 'banner_intro' );
	$show_breadcrumbs         = get_field( 'banner_show_breadcrumbs' );
	$show_post_type           = get_field( 'banner_show_post_type' );
	$show_share               = get_field( 'banner_show_share' );
	$show_date                = get_field( 'banner_show_date' );
	$member_only              = is_member_only( get_the_ID() );
	$text_alignment           = get_field( 'banner_text_alignment' );
	$image                    = get_field( 'banner_image' );
	$custom_image             = get_field( 'banner_custom_image' );
	$call_to_action           = get_field( 'banner_call_to_action' );
	$background_style         = get_field( 'banner_background_style' );
	$background_image         = get_field( 'banner_background_image' );
	$back_link                = get_field( 'banner_back_link' );
	$download_file            = get_field( 'banner_download_file' );
	$download_button_style    = get_field( 'banner_download_button_button_style' );
	$download_button_label    = get_field( 'banner_download_button_label' );
	$helper_link              = get_field( 'banner_helper_link' );
	$helper_link_button_style = get_field( 'banner_helper_link_button_button_style' );

	echo '<div ' . $attrs . '>';
	get_component( 'banner', [ 
		'title'                    => $title,
		'intro'                    => $intro,
		'show_breadcrumbs'         => $show_breadcrumbs,
		'show_post_type'           => $show_post_type,
		'show_share'               => $show_share,
		'show_date'                => $show_date,
		'member_only'              => $member_only,
		'text_alignment'           => $text_alignment,
		'image'                    => $image,
		'custom_image'             => $custom_image,
		'call_to_action'           => $call_to_action,
		'background_style'         => $background_style,
		'background_image'         => $background_image,
		'back_link'                => $back_link,
		'download_file'            => $download_file,
		'download_button_style'    => $download_button_style,
		'download_button_label'    => $download_button_label,
		'helper_link'              => $helper_link,
		'helper_link_button_style' => $helper_link_button_style,
	] );
	echo '</div>';
}
