<?php
if ( ! function_exists( 'wicket_paginate_links' ) ) {

	function wicket_paginate_links( $args = array() ) {

		global $wp_query;

		$current_page = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$total_pages  = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;

		// default args
		$args         = wp_parse_args(
			$args,
			array(
				'total'     => $total_pages,
				'current'   => $current_page,
				'type'      => 'array',
				'next_text' => __('Next', 'wicket') . ' <i class="fa fa-arrow-right ml-2" aria-hidden="true"></i>',
				'prev_text' => '<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> ' . __('Previous', 'wicket'),
			)
		);
		$total_pages  = (int) $args['total'];
		$current_page = (int) $args['current'];
		$orig_type    = $args['type'];

		$page_links = paginate_links( $args );

		if ( ! $page_links ) {
			return false;
		}

		// Loop through each page link.
		foreach ( $page_links as $key => $page_link ) {
			$page_links[ $key ] = str_replace( 'page-numbers', 'button button--ghost', $page_link );
			$page_links[ $key ] = str_replace( 'button button--ghost current', 'button button--primary', $page_links[ $key ] );
		}

		$r = "";
		switch ( $orig_type ) {
			case 'array':
				$r = join( "\n", $page_links );
			default:
				$r = join( "\n", $page_links );
				break;
		}
		return $r;
	}
}

if ( ! function_exists( 'wicket_pagination' ) ) {

	function wicket_pagination( $args = array() ) {

		return '<nav class="flex justify-center gap-2" aria-label="' . __( 'Pagination', 'wicket' ) . '">' . wicket_paginate_links( $args ) . '</nav>';

	}
}

if ( ! function_exists( 'the_wicket_pagination' ) ) {

	function the_wicket_pagination( $args = array() ) {

		echo wicket_pagination( $args );

	}
}
