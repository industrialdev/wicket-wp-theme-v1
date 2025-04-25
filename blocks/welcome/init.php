<?php
/**
 * Wicket Welcome block
 *
 **/

namespace Wicket\Blocks\Wicket_Welcome;

/**
 * Admin Welcome
 */
function site( $block = [] ) {
	echo '<h2 class="wicket-welcome-title">' .
		sprintf(
			/* translators: %s: username */
			esc_html__('Welcome %s', 'wicket-wp-theme'),
			esc_html(wp_get_current_user()->user_login)
		) .
	'</h2>';
}
