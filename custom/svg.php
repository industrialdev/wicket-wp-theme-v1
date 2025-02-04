<?php

function wicket_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'wicket_mime_types' );


function wicket_svg_enqueue_scripts( $hook ) {
	wp_enqueue_style( 'wicket-svg-style', get_theme_file_uri( '/assets/styles/svg.css' ) );
	wp_enqueue_script( 'wicket-svg-script', get_theme_file_uri( '/assets/scripts/libraries/svg.js' ), array('jquery') );
	wp_localize_script( 'wicket-svg-script', 'script_vars',
		array( 'AJAXurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'admin_enqueue_scripts', 'wicket_svg_enqueue_scripts' );


function wicket_get_attachment_url_media_library() {

	$url          = '';
	$attachmentID = isset( $_REQUEST['attachmentID'] ) ? $_REQUEST['attachmentID'] : '';
	if ( $attachmentID ) {
		$url = wp_get_attachment_url( $attachmentID );
	}

	echo $url;

	die();
}

add_action( 'wp_ajax_svg_get_attachment_url', 'wicket_get_attachment_url_media_library' );