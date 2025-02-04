<?php
/**
 * Wicket Listing Block
 *
 **/

namespace Wicket\Blocks\Wicket_Listing;

/**
 * Listing block registration function
 */
function init( $block = [] ) {

	$attrs = get_block_wrapper_attributes( [ 
		'class' => 'block-wicket-listing alignfull',
	] );

	$post_type                 = $block['post_type'] ?? get_field( 'listing_post_type' );
	$default_order_by          = get_field( 'listing_sort_by' ) ?? 'date-desc';
	$news_types                = $block['news_types'] ?? get_field( 'listing_news_type' );
	$resource_types            = $block['resource_types'] ?? get_field( 'listing_resource_type' );
	$topics_types              = $block['topics_types'] ?? get_field( 'listing_topic' );
	$event_categories          = $block['event_categories'] ?? get_field( 'listing_event_categories' );
	$product_categories        = $block['product_categories'] ?? get_field( 'listing_product_categories' );
	$additional_taxonomies     = $block['additional_taxonomies'] ?? get_field( 'listing_additional_taxonomies' );
	$posts_per_page            = $block['posts_per_page'] ?? get_field( 'listing_posts_per_page' );
	$listing_layout            = get_field( 'listing_layout' ) ?? 'list';
	$taxonomy_filters          = $block['taxonomy_filters'] ?? get_field( 'listing_taxonomy_filters' );
	$additional_filters        = $block['additional_taxonomy_filters'] ?? get_field( 'listing_additional_taxonomy_filters' );
	$exclude_from_results      = $block['exclude_from_results'] ?? get_field( 'listing_exclude_from_results' );
	$hide_search               = $block['hide_search'] ?? get_field( 'listing_hide_search' );
	$hide_date_filter          = $block['hide_search'] ?? get_field( 'listing_hide_date_filter' );
	$hide_type_taxonomy        = $block['hide_type_taxonomy'] ?? get_field( 'listing_hide_type_taxonomy' );
	$hide_featured_image       = $block['hide_featured_image'] ?? get_field( 'listing_hide_featured_image' );
	$hide_excerpt              = $block['hide_excerpt'] ?? get_field( 'listing_hide_excerpt' );
	$hide_attachment           = $block['hide_attachment'] ?? get_field( 'listing_hide_attachment' );
	$hide_helper_link          = $block['hide_helper_link'] ?? get_field( 'listing_hide_helper_link' );
	$hide_document_format_icon = $block['hide_document_format_icon'] ?? get_field( 'listing_hide_document_format_icon' );
	$listing_download_label    = get_field( 'listing_download_label' ) ?? __( 'Download', 'wicket' );
	$listing_link_label        = get_field( 'listing_link_label' ) ?? __( 'View Page', 'wicket' );
	$date_format               = apply_filters( 'wicket_general_date_format', 'F j, Y' );
	$pre_filter_categories     = [];

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	switch ( $default_order_by ) {
		case 'alpha-asc':
			$orderby = 'title';
			$order = 'ASC';
			break;
		case 'alpha-desc':
			$orderby = 'title';
			$order = 'DESC';
			break;
		case 'date-asc':
			$orderby = 'date';
			$order = 'ASC';
			break;
		case 'date-desc':
			$orderby = 'date';
			$order = 'DESC';
			break;
	}

	/* Pre-filter the taxonomy filters based on the post type */
	switch ( $post_type ) {
		case 'tribe_events':
			$pre_filter_categories = $event_categories;
			break;
		case 'product':
			$pre_filter_categories = $product_categories;
			break;
	}

	$keyword        = '';
	$excluded_posts = [];

	if ( ! empty( $exclude_from_results ) ) {
		$excluded_posts = array_map( function ($post) {
			return $post->ID;
		}, $exclude_from_results );
	}

	/* Get sort by from query string */
	if ( isset( $_GET['sort-by'] ) ) {
		if ( $_GET['sort-by'] == 'date-desc' ) {
			$orderby = 'date';
			$order   = 'DESC';
		}
		if ( $_GET['sort-by'] == 'date-asc' ) {
			$orderby = 'date';
			$order   = 'ASC';
		}
		if ( $_GET['sort-by'] == 'alpha-asc' ) {
			$orderby = 'title';
			$order   = 'ASC';
		}
		if ( $_GET['sort-by'] == 'alpha-desc' ) {
			$orderby = 'title';
			$order   = 'DESC';
		}
	}

	/* Get keyword from search form */
	if ( isset( $_GET['keyword'] ) ) {
		$keyword = $_GET['keyword'];
	}

	$tax_query = [ 
		'relation' => 'OR',
	];

	/* Add news type taxonomy to tax query if it is set */
	if ( ! empty( $news_types ) && ! isset( $_GET['news_type'] ) ) {
		$terms = [];
		foreach ( $news_types as $term ) {
			array_push( $terms, $term->slug );
		}

		$taxonomy_args = [ 
			'taxonomy' => 'news_type',
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];
		array_push( $tax_query, $taxonomy_args );
	}

	/* Add resource type taxonomy to tax query if it is set */
	if ( ! empty( $resource_types ) && ! isset( $_GET['resource_type'] ) ) {
		$terms = [];
		foreach ( $resource_types as $term ) {
			array_push( $terms, $term->slug );
		}

		$taxonomy_args = [ 
			'taxonomy' => 'resource_type',
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];
		array_push( $tax_query, $taxonomy_args );
	}

	/* Add topic taxonomy to tax query if it is set */
	if ( ! empty( $topics_types ) && ! isset( $_GET['topics'] ) ) {
		$terms = [];
		foreach ( $topics_types as $term ) {
			array_push( $terms, $term->slug );
		}

		$taxonomy_args = [ 
			'taxonomy' => 'topics',
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];
		array_push( $tax_query, $taxonomy_args );
	}

	/* Add tribe_events_cat taxonomy to tax query if it is set */
	if ( ! empty( $event_categories ) && ! isset( $_GET['tribe_events_cat'] ) ) {
		$terms = [];
		foreach ( $event_categories as $term ) {
			array_push( $terms, $term->slug );
		}

		$taxonomy_args = [ 
			'taxonomy' => 'tribe_events_cat',
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];

		array_push( $tax_query, $taxonomy_args );
	}

	/* Add product_cat taxonomy to tax query if it is set */
	if ( ! empty( $product_categories ) && ! isset( $_GET['product_cat'] ) ) {
		$terms = [];
		foreach ( $product_categories as $term ) {
			array_push( $terms, $term->slug );
		}

		$taxonomy_args = [ 
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];

		array_push( $tax_query, $taxonomy_args );
	}

	/* Add additional taxonomies to tax query if they are set */
	if ( ! empty( $additional_taxonomies ) ) {
		$taxonomies = $additional_taxonomies['taxonomy_terms'];
		$terms      = [];
		foreach ( $taxonomies as $taxonomy ) {
			$term    = get_term( $taxonomy );
			$terms[] = $term->slug;
		}

		$taxonomy_args = [ 
			'taxonomy' => $term->taxonomy,
			'field'    => 'slug',
			'operator' => 'IN',
			'terms'    => $terms,
		];

		array_push( $tax_query, $taxonomy_args );
	}

	/* Add WooCommerce product attributes to filters if post type is product */
	if ( class_exists( 'WooCommerce' ) && $post_type == 'product' ) {
		$attributes = wc_get_attribute_taxonomies();
		foreach ( $attributes as $attribute ) {
			$taxonomy_filters[] = [ 
				'slug' => 'pa_' . $attribute->attribute_name,
				'name' => $attribute->attribute_label,
			];
		}
	}


	/* Add additional taxonomy filters to tax query if they are set */
	if ( is_array( $additional_filters ) ) {
		foreach ( $additional_filters as $filter ) {
			if ( isset( $_GET[ $filter['taxonomy'] ] ) ) {
				$taxonomy_args = [ 
					'taxonomy' => $filter['taxonomy'],
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $_GET[ $filter['taxonomy'] ],
				];
				array_push( $tax_query, $taxonomy_args );
			}
		}

		// Change 'taxonmy' key to 'slug' in $additional_filters array
		$additional_filters = array_map( function ($filter) {
			$filter['slug'] = $filter['taxonomy'];
			unset( $filter['taxonomy'] );
			return $filter;
		}, $additional_filters );

		// Merge $additional_filters with $taxonomy_filters
		$taxonomy_filters = array_merge( $taxonomy_filters, $additional_filters );
	}

	$has_filters = false;

	/* Add custom taxonomy filters to tax query if they are set */
	if ( is_array( $taxonomy_filters ) ) {
		foreach ( $taxonomy_filters as $taxonomy ) {
			if ( isset( $_GET[ $taxonomy['slug'] ] ) ) {
				$taxonomy_args = [ 
					'taxonomy' => $taxonomy['slug'],
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $_GET[ $taxonomy['slug'] ],
				];

				$has_filters = true;
			}
		}

		if ( $has_filters ) {
			$tax_query = [ $taxonomy_args ];
		}
	}

	$default_search_form_bg_color       = '';
	$default_listing_container_bg_color = '';

	if ( ! defined( 'WICKET_WP_THEME_V2' ) ) {
		$default_search_form_bg_color       = 'bg-dark-010';
		$default_listing_container_bg_color = 'bg-light-010';
	}

	$search_form_bg_color       = apply_filters( 'wicket_listing_search_form_bg_color', $default_search_form_bg_color );
	$listing_container_bg_color = apply_filters( 'wicket_listing_container_bg_color', $default_listing_container_bg_color );

	/* Set listing layout to grid if post type is products */
	if ( $post_type == 'product' ) {
		$listing_layout = 'grid';
	}

	/* Add custom filter for adding extra values to $tax_query */
	$tax_query = apply_filters( 'wicket_listing_block_before_query', $tax_query );

	?>

	<form action="" <?php echo $attrs ?> class="block-wicket-listing">

		<?php if ( ! $hide_search ) : ?>
			<div
				class="block-wicket-listing__search-form <?php echo $search_form_bg_color ?> <?php echo defined( 'WICKET_WP_THEME_V2' ) ? '' : 'px-4 py-5 lg:px-0' ?>">
				<div class="container">
					<?php get_component( 'search-form', [ 
						'button_reversed' => defined( 'WICKET_WP_THEME_V2' ) ? true : false,
					] ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		echo '<div class="block-wicket-listing__container ' . $listing_container_bg_color . ' overflow-x-hidden">';
		if ( is_admin() && ! $post_type ) {
			echo "<p>" . __( 'Use the Block controls in edit mode or on the right to configure listing.', 'wicket' ) . "</p>";
		} ?>

		<div class="container">
			<div class="flex flex-col lg:flex-row gap-4">
				<?php if ( ! empty( $taxonomy_filters ) ) : ?>
					<div
						class="block-wicket-listing__filters basis-1/4 bg-white relative after:content-[''] after:absolute after:top-0 after:bottom-0 after:right-full after:bg-white after:w-[30vw] before:block lg:before:hidden before:content-[''] before:absolute before:top-0 before:bottom-0 before:left-full before:bg-white before:w-[30vw]">
						<?php
						get_component( 'filter-form', [ 
							'taxonomies'            => $taxonomy_filters,
							'hide_date_filter'      => $hide_date_filter,
							'pre_filter_categories' => $pre_filter_categories,
						] ); ?>
					</div>
				<?php endif; ?>

				<div
					class="block-wicket-listing__entries <?php echo ! empty( $taxonomy_filters ) ? 'basis-3/4' : 'basis-full' ?> pt-4 lg:pt-10">
					<?php
					$args = [ 
						'post_type'      => $post_type,
						'posts_per_page' => $posts_per_page,
						'paged'          => $paged,
						'orderby'        => $orderby,
						'order'          => $order,
						's'              => $keyword,
						'tax_query'      => $tax_query,
					];

					/* Add start date and end date to tax query if they are set */
					if ( isset( $_GET['start_date'] ) && isset( $_GET['end_date'] ) ) {
						$start_date = $_GET['start_date'];
						$end_date   = $_GET['end_date'];

						$args['date_query'] = [ 
							'after'     => $start_date,
							'before'    => $end_date,
							'inclusive' => true,
						];
					}

					$args['meta_query'] = [ 
						[ 
							'relation' => 'OR',
							[ 
								'key'     => 'hide_on_listings',
								'value'   => '0',
								'compare' => '=',
							],
							[ 
								'key'     => 'hide_on_listings',
								'compare' => 'NOT EXISTS',
							],
						],
					];

					$query       = new \WP_Query( $args );
					$posts       = $query->posts;
					$total_posts = $query->found_posts;
					?>

					<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-7 px-4 lg:px-0">
						<div class="<?php echo defined( 'WICKET_WP_THEME_V2' ) ? 'block-wicket-listing__total' : 'font-bold' ?>">
							<?php
							if ( $total_posts === 0 ) {
								echo '0' . __( ' Results', 'wicket' );
							} else {
								echo sprintf(
									__( '%1$d-%2$d of %3$d Results', 'wicket' ),
									$paged,
									count( $posts ),
									$total_posts
								);
							}
							?>
						</div>
						<div
							class="<?php echo defined( 'WICKET_WP_THEME_V2' ) ? 'block-wicket-listing__sort-by' : 'flex items-center gap-3' ?>">
							<label for="sort-by">
								<?php echo __( 'Sort by', 'wicket' ); ?>
							</label>
							<select name="sort-by" id="sort-by" class="min-w-[260px]" onchange="this.form.submit()">
								<?php
								$date_desc_label  = __( 'Date (newest-oldest)', 'industrial' );
								$date_asc_label   = __( 'Date (oldest-newest)', 'industrial' );
								$alpha_asc_label  = __( 'Alphabetical (a-z)', 'industrial' );
								$alpha_desc_label = __( 'Alphabetical (z-a)', 'industrial' );
								if ( isset( $_GET['sort-by'] ) ) : ?>
									<option value="date-desc" <?php if ( $_GET['sort-by'] == 'date-desc' ) : ?>selected<?php endif; ?>>
										<?php echo $date_desc_label; ?>
									</option>
									<option value="date-asc" <?php if ( $_GET['sort-by'] == 'date-asc' ) : ?>selected<?php endif; ?>>
										<?php echo $date_asc_label; ?>
									</option>
									<option value="alpha-asc" <?php if ( $_GET['sort-by'] == 'alpha-asc' ) : ?>selected<?php endif; ?>>
										<?php echo $alpha_asc_label; ?>
									</option>
									<option value="alpha-desc" <?php if ( $_GET['sort-by'] == 'alpha-desc' ) : ?>selected<?php endif; ?>>
										<?php echo $alpha_desc_label; ?>
									</option>
									<?php
								else : ?>
									<option value="date-desc" <?php if ( $default_order_by == 'date-desc' ) : ?>selected<?php endif; ?>>
										<?php echo $date_desc_label; ?>
									</option>
									<option value="date-asc" <?php if ( $default_order_by == 'date-asc' ) : ?>selected<?php endif; ?>>
										<?php echo $date_asc_label; ?>
									</option>
									<option value="alpha-asc" <?php if ( $default_order_by == 'alpha-asc' ) : ?>selected<?php endif; ?>>
										<?php echo $alpha_asc_label; ?>
									</option>
									<option value="alpha-desc" <?php if ( $default_order_by == 'alpha-desc' ) : ?>selected<?php endif; ?>>
										<?php echo $alpha_desc_label; ?>
									</option>
									<?php
								endif; ?>
							</select>
						</div>
					</div>

					<?php
					if ( $query->have_posts() ) : ?>
						<div class="pb-24 px-4 lg:px-0">
							<?php

							if ( $listing_layout === 'grid' ) {
								echo '<div class="grid gap-10 grid-cols-1 lg:gap-4 lg:grid-cols-3 mb-6">';
							}

							$item_number = 1;

							while ( $query->have_posts() ) :
								$query->the_post();
								$post_id = get_the_ID();

								do_action( 'wicket_listing_block_before_card', $post_id );

								// If post is excluded from results, skip it
								if ( in_array( $post_id, $excluded_posts ) ) {
									$query->the_post();
									continue;
								}

								$title                   = get_the_title( $post_id );
								$excerpt                 = get_the_excerpt( $post_id );
								$date                    = get_the_date( $date_format, $post_id );
								$featured_image          = get_post_thumbnail_id( $post_id );
								$permalink               = get_the_permalink( $post_id );
								$member_only             = is_member_only( $post_id );
								$related_topic_type      = get_related_topic_type( get_post_type( $post_id ) );
								$topics                  = get_the_terms( $post_id, $related_topic_type );
								$document_attachment     = get_field_from_block( $post_id, 'wicket/banner', 'banner_download_file' );
								$document_attachment_url = wp_get_attachment_url( $document_attachment );
								$helper_link             = get_field_from_block( $post_id, 'wicket/banner', 'banner_helper_link' );


								if ( $post_type == 'tribe_events' ) {
									$date = tribe_get_start_date( $post_id, false, $date_format );
								}

								if ( $listing_layout === 'grid' ) {
									$grid_card_params = [ 
										'classes'      => defined( 'WICKET_WP_THEME_V2' ) ? [] : [ 'p-4' ],
										'post_type'    => $post_type,
										'post_id'      => $post_id,
										'content_type' => ! $hide_type_taxonomy ? get_related_content_type_term( $post_id ) : '',
										'title'        => $title,
										'excerpt'      => ! $hide_excerpt ? $excerpt : '',
										'date'         => $date,
										'image'        => ( ! $hide_featured_image && $featured_image ) ? [ 
											'id' => $featured_image,
										] : '',
										'link'         => $permalink,
										'member_only'  => $member_only,
										'topics'       => $topics,
									];

									get_component( $post_type == 'product' ? 'card-product' : 'card-featured', $grid_card_params );

								} else {
									$listing_card_params = [ 
										'classes'                   => [ 'mb-6', "item-number-{$item_number}" ],
										'post_type'                 => $post_type,
										'content_type'              => ! $hide_type_taxonomy ? get_related_content_type_term( $post_id ) : '',
										'title'                     => $title,
										'excerpt'                   => ! $hide_excerpt ? $excerpt : '',
										'date'                      => $date,
										'featured_image'            => ! $hide_featured_image ? $featured_image : '',
										'link'                      => [ 
											'url'    => $permalink,
											'text'   => 'Read more',
											'target' => '_self',
										],
										'member_only'               => $member_only,
										'topics'                    => $topics,
										'document'                  => ! $hide_attachment ? $document_attachment_url : '',
										'download_label'            => $listing_download_label == '' ? __( 'Download', 'wicket' ) : $listing_download_label,
										'link_label'                => $listing_link_label,
										'helper_link'               => ! $hide_helper_link ? $helper_link : '',
										'hide_document_format_icon' => $hide_document_format_icon,
									];

									$listing_card_params = apply_filters( 'wicket_listing_block_card_params', $listing_card_params, $post_id );

									get_component( 'card-listing', $listing_card_params );
								}

								$item_number++;
								do_action( 'wicket_listing_block_after_card', $post_id );


							endwhile;

							if ( $listing_layout === 'grid' ) {
								echo '</div>';
							}

							the_wicket_pagination( [ 
								'total' => $query->max_num_pages,
							] );
							?>
						</div>
					<?php else : ?>
						<div class="p-10">
							<h2
								class="<?php echo defined( 'WICKET_WP_THEME_V2' ) ? 'block-wicket-listing__no-results' : 'text-center font-bold text-heading-md mb-6' ?>">
								<?php echo __( 'No results found.', 'wicket' ) ?>
							</h2>
							<div class="text-center">
								<?php echo apply_filters( 'wicket_listing_block_no_results_message', __( 'Try adjusting your search or filter to find what you are looking for.', 'wicket' ) ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php echo '</div>';
		echo '</form>';


}
