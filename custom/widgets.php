<?php

// Register widget areas
function wicket_widgets_init() {
	register_sidebar( array(
		'id'          => 'sidebar-widgets',
		'name'        => 'Sidebar Widgets',
		'description' => __( 'Sidebar widget area.', 'wicket' ),
	) );
}
add_action( 'widgets_init', 'wicket_widgets_init' );