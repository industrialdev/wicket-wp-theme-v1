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

	echo '<h2 class="wicket-welcome-title">Welcome '.wp_get_current_user()->user_login.'</h2>';
}
