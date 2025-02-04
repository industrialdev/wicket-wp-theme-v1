<?php

if ( ! function_exists( 'wicket_breadcrumb' ) ) {
	function wicket_breadcrumb() {
		$theme_locations = get_nav_menu_locations();
		if ( ! isset( $theme_locations['header'] ) ) {
			return '';
		}
		global $post;
		$items = wp_get_nav_menu_items( $theme_locations['header'] );
		_wp_menu_item_classes_by_context( $items );

		$separator_icon = apply_filters( 'wicket_breadcrumb_separator_icon', 'fa-solid fa-chevron-right' );

		$separator = get_component(
			'icon',
			[ 
				'classes' => [ '' ],
				'icon'    => $separator_icon,
				'text'    => '',
			],
			false
		);
		$crumbs    = [];
		$crumbs[]  = get_component(
			'link',
			[ 
				'classes'    => [ 'flex' ],
				'default_link_style' => true,
				'url'        => get_home_url(),
				'text'       => __( 'Home', 'wicket' ),
				'icon_start' => [ 
					'icon' => 'fa-regular fa-house',
				],
				'icon_end'   => [],
			],
			false
		);
		$url       = get_permalink();
		if ( str_contains( $url, 'resource' ) ) {
			$crumbs[] = '<span class="bold">' . __( 'Resources', 'wicket' ) . '</span>';
		} elseif ( str_contains( $url, 'news' ) ) {
			$crumbs[] = '<span class="bold">' . __( 'News', 'wicket' ) . '</span>';
		} elseif ( str_contains( $url, 'event' ) ) {
			$crumbs[] = '<span class="bold">' . __( 'Events', 'wicket' ) . '</span>';
		} elseif ( is_page() || is_single() ) {
			// Standard page
			if ( $post->post_parent ) {
				// If child page, get parents
				$anc = get_post_ancestors( $post->ID );
				// Get parents in the right order
				$anc = array_reverse( $anc );
				// Parent page loop
				if ( ! isset( $parents ) )
					$parents = null;
				foreach ( $anc as $ancestor ) {
					$crumbs[] = get_component(
						'link',
						[ 
							'classes' => [ '' ],
							'default_link_style' => true,
							'url'     => get_permalink( $ancestor ),
							'text'    => get_the_title( $ancestor ),
						],
						false
					);
				}
			}
			$crumbs[] = '<strong>' . get_the_title() . '</strong>';
		} elseif ( is_search() ) {
			$crumbs[] = '<strong>' . __( 'Search Results for:', 'wicket' ) . '"<em>' . get_search_query() . '</em>"</strong>';
		}

		echo implode( $separator, $crumbs );
	}
}