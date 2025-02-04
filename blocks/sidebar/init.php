<?php
/**
 * Wicket sidebar block
 *
 **/

namespace Wicket\Blocks\Wicket_Sidebar;

function init( $block = [] ) {

	if ( is_admin() ) {
		// Get admin link for widget area
		$widgets_url = admin_url( 'widgets.php' );

		echo '<div class="block-sidebar">';
		echo '<p class="block-sidebar__message">This block displays the sidebar widgets. To edit the sidebar widgets, please visit the <a href="' . $widgets_url . '" target="_blank">Widgets page</a>.</p>';
		echo '</div>';
		return;
	}

	if ( function_exists( 'dynamic_sidebar' ) ) {
		echo '<ul>';
		dynamic_sidebar( 'sidebar-widgets' );
		echo '</ul>';
	}
}
