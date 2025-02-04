<?php

// Redirect CPT archives to their respective pages
function redirect_cpt_archives() {
	// Case: News
	if ( is_post_type_archive( POST_TYPE_NEWS ) ) {
		wp_redirect( home_url( 'news' ), 301 );
		exit();
	}

	// Case: Resources
	if ( is_post_type_archive( POST_TYPE_RESOURCES ) ) {
		wp_redirect( home_url( 'resources' ), 301 );
		exit();
	}
}
add_action( 'template_redirect', 'redirect_cpt_archives' );