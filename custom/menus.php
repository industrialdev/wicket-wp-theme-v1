<?php

// Takes in a WP Menu array (typically creatd by wp_get_nav_menu_items()) and returns
// a structured associative array of the menu items.
// Currently supports up to 3 levels of menu items.
function wicket_generate_structured_menu( $wp_nav_items ) {
	$nav_items_structured = [];
	// Initial pass to add all parent items
	foreach(  $wp_nav_items as  $wp_nav_item ) {
				if(  $wp_nav_item->menu_item_parent == 0 ) {
							$nav_items_structured[ $wp_nav_item->ID ] = (array) $wp_nav_item;
							$nav_items_structured[ $wp_nav_item->ID ]['child_count'] = 0;
							$nav_items_structured[ $wp_nav_item->ID ]['grand_child_count'] = 0;
				}
	}
	// Second pass to add all 2nd tier child items
	foreach(  $wp_nav_items as  $wp_nav_item ) {
				if(  $wp_nav_item->menu_item_parent != 0 ) {
							// Add this as a child element if its parent item is present
							if( isset( $nav_items_structured[ $wp_nav_item->menu_item_parent] ) ) {
										$nav_items_structured[ $wp_nav_item->menu_item_parent]['children'][ $wp_nav_item->ID] = (array) $wp_nav_item;
										// Update child count of the parent item
										$current_child_count = 0;
										if( isset( $nav_items_structured[ $wp_nav_item->menu_item_parent]['child_count'] ) ) {
											$current_child_count = $nav_items_structured[ $wp_nav_item->menu_item_parent]['child_count'];
										}
										$current_child_count++;
										$nav_items_structured[ $wp_nav_item->menu_item_parent]['child_count'] = $current_child_count;

										// Set initial value for the child count of this item, if not already set
										$current_child_count = 0;
										if( isset( $nav_items_structured[ $wp_nav_item->menu_item_parent]['children'][ $wp_nav_item->ID]['child_count'] ) ) {
											$current_child_count = $nav_items_structured[ $wp_nav_item->menu_item_parent]['children'][ $wp_nav_item->ID]['child_count'];
										}
										$nav_items_structured[ $wp_nav_item->menu_item_parent]['children'][ $wp_nav_item->ID]['child_count'] = $current_child_count;
							
								}
				}
	}
	// Third pass to add all 3rd tier child items
	foreach(  $wp_nav_items as  $wp_nav_item ) {
				// Traverse the child items of each parent array in the structured array
				foreach( $nav_items_structured as $nav_structured_item ) {
							if( isset( $nav_structured_item['children'] ) ) {
										foreach( $nav_structured_item['children'] as $second_tier_child_item ) {
													// $second_tier_child_item is array->parent_item->child_item
													if(  $wp_nav_item->menu_item_parent == $second_tier_child_item['ID'] ) {
																$nav_items_structured[ $nav_structured_item['ID'] ]['children'][ $second_tier_child_item['ID'] ]['children'][ $wp_nav_item->ID] = (array) $wp_nav_item;

																// Update child count of the parent items
																$current_grand_child_count = 0;
																$current_child_count = 0;
																if( isset( $nav_items_structured[ $nav_structured_item['ID'] ]['grand_child_count'] ) ) {
																	$current_grand_child_count = $nav_items_structured[ $nav_structured_item['ID'] ]['grand_child_count'];
																}
																if( isset( $nav_items_structured[ $nav_structured_item['ID'] ]['children'][ $second_tier_child_item['ID'] ]['child_count'] ) ) {
																	$current_child_count = $nav_items_structured[ $nav_structured_item['ID'] ]['children'][ $second_tier_child_item['ID'] ]['child_count'];
																}
																$current_grand_child_count++;
																$current_child_count++;

																$nav_items_structured[ $nav_structured_item['ID'] ]['grand_child_count'] = $current_grand_child_count;
																$nav_items_structured[ $nav_structured_item['ID'] ]['children'][ $second_tier_child_item['ID'] ]['child_count'] = $current_child_count;

													}
										}
							}
				}
	}

	return $nav_items_structured;
}

// Takes in a post_id and finds all ancestor/parent pages, and
// then finds all child pages. Returns the family tree in an array and notes what level 
// the current post_id is located in.
function wicket_get_all_parent_and_child_pages( $post_id, $post_type = 'page' ) {
	$ancestry_array = [ 
		'ancestors_top_to_curr' => []
	];

	// =====================
	// Get the ancestors
	// =====================
	$post_ancestors = get_post_ancestors($post_id);
	$post_ancestors = array_reverse( $post_ancestors );
	$ancestry_array['ancestors_top_to_curr'] = $post_ancestors;
	$ancestry_array['ancestors_top_to_curr'][] = $post_id;

	// ================================================
	// Build the family tree
	// ================================================
	$topmost_parent_id = 0;
	$tree_depth = 0;
	$curr_page_location_from_top = [];


	if( isset( $ancestry_array['ancestors_top_to_curr'][0] ) ) {
		$topmost_parent_id = $ancestry_array['ancestors_top_to_curr'][0];
		$tree_depth = 1;
		if( $post_id == $topmost_parent_id ) {
			$curr_page_location_from_top = [$topmost_parent_id];
		}
	}

	// Get second-teir list of children
	$children_ids_to_add = [];

	$post_children = get_children( [
		'post_status' => 'published',
		'post_type'   => $post_type,
		'post_parent' => $topmost_parent_id
	], 'ARRAY_N' );
	foreach( $post_children as $child_id => $child_data ) {
		$children_ids_to_add[$child_id] = [];

		if( $post_id == $child_id ) {
			$curr_page_location_from_top = [ $topmost_parent_id, $child_id ];
		}
	}

	// Check each sibling down 5 levels

	// 1 Down
	foreach( $children_ids_to_add as $sibling_id => $sibling_data ) {
		if( $tree_depth < 2 ) {
			$tree_depth = 2;
		}
		$sibling_children = get_children( [
			'post_status' => 'published',
			'post_type'   => $post_type,
			'post_parent' => $sibling_id
		], 'ARRAY_N' );
		$sibling_children_to_add = [];
		foreach( $sibling_children as $sibling_child_id => $sibling_child_data ) {
			$sibling_children_to_add[$sibling_child_id] = [];

			if( $post_id == $sibling_child_id ) {
				$curr_page_location_from_top = [ $topmost_parent_id, $sibling_id, $sibling_child_id ];
			}
		}
		$children_ids_to_add[$sibling_id] = $sibling_children_to_add;

		// 2 Down
		foreach( $children_ids_to_add[$sibling_id] as $sibling_2_id => $sibling_2_data ) {
			if( $tree_depth < 3 ) {
				$tree_depth = 3;
			}
			$sibling_2_children = get_children( [
				'post_status' => 'published',
				'post_type'   => $post_type,
				'post_parent' => $sibling_2_id
			], 'ARRAY_N' );
			$sibling_children_to_add = [];
			foreach( $sibling_2_children as $sibling_child_id => $sibling_child_data ) {
				$sibling_children_to_add[$sibling_child_id] = [];

				if( $post_id == $sibling_child_id ) {
					$curr_page_location_from_top = [ $topmost_parent_id, $sibling_id, $sibling_2_id, $sibling_child_id ];
				}
			}
			$children_ids_to_add[$sibling_id][$sibling_2_id] = $sibling_children_to_add;

			// 3 Down
			foreach( $children_ids_to_add[$sibling_id][$sibling_2_id] as $sibling_3_id => $sibling_3_data ) {
				if( $tree_depth < 4 ) {
					$tree_depth = 4;
				}
				$sibling_3_children = get_children( [
					'post_status' => 'published',
					'post_type'   => $post_type,
					'post_parent' => $sibling_3_id
				], 'ARRAY_N' );
				$sibling_children_to_add = [];
				foreach( $sibling_3_children as $sibling_child_id => $sibling_child_data ) {
					$sibling_children_to_add[$sibling_child_id] = [];

					if( $post_id == $sibling_child_id ) {
						$curr_page_location_from_top = [ $topmost_parent_id, $sibling_id, $sibling_2_id, $sibling_3_id, $sibling_child_id ];
					}
				}
				$children_ids_to_add[$sibling_id][$sibling_2_id][$sibling_3_id] = $sibling_children_to_add;

				// 4 Down
				foreach( $children_ids_to_add[$sibling_id][$sibling_2_id][$sibling_3_id] as $sibling_4_id => $sibling_4_data ) {
					if( $tree_depth < 5 ) {
						$tree_depth = 5;
					}
					$sibling_4_children = get_children( [
						'post_status' => 'published',
						'post_type'   => $post_type,
						'post_parent' => $sibling_4_id
					], 'ARRAY_N' );
					$sibling_children_to_add = [];
					foreach( $sibling_4_children as $sibling_child_id => $sibling_child_data ) {
						$sibling_children_to_add[$sibling_child_id] = [];

						if( $post_id == $sibling_child_id ) {
							$curr_page_location_from_top = [ $topmost_parent_id, $sibling_id, $sibling_2_id, $sibling_3_id, $sibling_4_id, $sibling_child_id ];
						}
					}
					$children_ids_to_add[$sibling_id][$sibling_2_id][$sibling_3_id][$sibling_4_id] = $sibling_children_to_add;

					// 5 Down
					foreach( $children_ids_to_add[$sibling_id][$sibling_2_id][$sibling_3_id][$sibling_4_id] as $sibling_5_id => $sibling_5_data ) {
						if( $tree_depth < 6 ) {
							$tree_depth = 6;
						}
						$sibling_5_children = get_children( [
							'post_status' => 'published',
							'post_type'   => $post_type,
							'post_parent' => $sibling_5_id
						], 'ARRAY_N' );
						$sibling_children_to_add = [];
						foreach( $sibling_5_children as $sibling_child_id => $sibling_child_data ) {
							$sibling_children_to_add[$sibling_child_id] = [];

							if( $post_id == $sibling_child_id ) {
								$curr_page_location_from_top = [ $topmost_parent_id, $sibling_id, $sibling_2_id, $sibling_3_id, $sibling_4_id, $sibling_5_id, $sibling_child_id ];
							}
						}
						$children_ids_to_add[$sibling_id][$sibling_2_id][$sibling_3_id][$sibling_4_id][$sibling_5_id] = $sibling_children_to_add;
					} // End 5 down
				} // End 4 down
			} // End 3 down
		} // End 2 down
	} // End 1 down

	$ancestry_array['family_tree'][$topmost_parent_id] = $children_ids_to_add;

	// ================================================
	// Note the current page's pos. in the hierarchy
	// ================================================
	$ancestry_array['curr_page_level_from_zero'] = count( $curr_page_location_from_top ) - 1;
	$ancestry_array['curr_page_location_from_top'] = $curr_page_location_from_top;
	$ancestry_array['num_levels'] = $tree_depth;

	

	return $ancestry_array;
}