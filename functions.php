<?php
// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wicket Includes
 */
$wicket_includes = [
	'config.php',
	'remote-media-proxy.php',
	'constants.php',
	'acf.php',
	'blocks.php',
	'menus.php',
	'pagination.php',
	'widgets.php',
	'dates.php',
	'remove_wp_toolbar_except_admins.php',
	'curl_timeout.php',
	'private_content_redirect.php',
	'woocommerce.php',
	'switch_to_user_wicket_sync.php',
	'helpers.php',
	'woocommerce_max_webhook_delivery_failures.php',
	'wpml_translator_override.php',
	'breadcrumbs.php',
	'post-types.php',
	'taxonomies.php',
	'redirections.php',
	'search.php',
	'api-rate-limiting.php',
	'remove-tec-attendee-field-uniqueness.php',
];

if ( is_array( $wicket_includes ) ) {

	foreach ( $wicket_includes as $wicket_inc ) {
		$file_inc = get_template_directory() . '/custom/' . $wicket_inc;

		if ( ! file_exists( $file_inc ) ) {
			continue;
		}
		require_once $file_inc;
	}
}

/**
 * DO NOT ADD ANYTHING BELOW THIS COMMENT
 * DO NOT ADD ANYTHING BELOW THIS COMMENT
 * DO NOT ADD ANYTHING BELOW THIS COMMENT
 *
 * Be thoughtful and considerate of your fellow developers, please.
 * This will make it easier for everyone, including you, to maintain this work in the future.
 *
 * Feel free to use existing files or create new files or subfolders on /custom/, to add functionality to the theme.
 * Don't forget to add your new files, into the $wicket_includes array above.
 *
 * Thanks!
 */
