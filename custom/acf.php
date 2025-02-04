<?php
/**
 * ACF Customizations
 **/

function wicket_acf_init() {
	// Check function exists.
	if ( function_exists( 'acf_add_options_page' ) ) {
		$parent = acf_add_options_page( array(
			'page_title' => __( 'Options', 'wicket' ),
			'redirect'   => true,
			'position'   => '75',
		) );

		acf_add_options_page( array(
			'page_title'  => __( 'Global Settings', 'wicket' ),
			'menu_title'  => __( 'Global', 'wicket' ),
			'parent_slug' => $parent['menu_slug'],
		) );
		acf_add_options_page( array(
			'page_title'  => __( 'Header Settings', 'wicket' ),
			'menu_title'  => __( 'Header', 'wicket' ),
			'parent_slug' => $parent['menu_slug'],
		) );
		acf_add_options_page( array(
			'page_title'  => __( 'Footer Settings', 'wicket' ),
			'menu_title'  => __( 'Footer', 'wicket' ),
			'parent_slug' => $parent['menu_slug'],
		) );
		acf_add_options_page( array(
			'page_title'  => __( 'Global Search Settings', 'wicket' ),
			'menu_title'  => __( 'Global Search', 'wicket' ),
			'parent_slug' => $parent['menu_slug'],
		) );
	}
}
add_action( 'acf/init', 'wicket_acf_init' );

// Disable Options Page UI functionality
add_filter( 'acf/settings/enable_options_pages_ui', '__return_false' );

// Don't output empty message on blocks
add_filter( 'acf/blocks/no_fields_assigned_message', '__return_empty_string' );

// Resets row index starting number to 0
add_filter( 'acf/settings/row_index_offset', '__return_zero' );

function wicket_acf_prepare_copyright_field( $field ) {
	$field['prepend'] = '© ' . date( 'Y' );
	if ( $field['value'] === '' ) {
		$field['value'] = get_bloginfo( 'name' );
	}
	return $field;
}
add_filter( 'acf/prepare_field/key=field_651ad74e60f09', 'wicket_acf_prepare_copyright_field' );

function wicket_acf_load_footer_menu_field_choices( $field ) {
	$field['choices'] = array();
	$menus            = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

	if ( is_array( $menus ) ) {
		$field['choices'][0] = __( '-- Select Menu --', 'wicket' );
		foreach ( $menus as $menu ) {
			$field['choices'][ $menu->term_id ] = $menu->name;
		}
	}
	return $field;
}
add_filter( 'acf/load_field/key=field_65203a1fff220', 'wicket_acf_load_footer_menu_field_choices' );

// Add ACF field for selecting post types
function wicket_acf_load_post_types_field_choices( $field ) {
	$field['choices'] = array();
	$post_types       = get_post_types( array( 'public' => true ), 'objects' );

	if ( is_array( $post_types ) ) {
		$field['choices'][0] = __( '-- Select Post Type --', 'wicket' );
		foreach ( $post_types as $post_type ) {
			$field['choices'][ $post_type->name ] = $post_type->label;
		}
	}
	return $field;
}
add_filter( 'acf/load_field/key=field_65c2245631b3e', 'wicket_acf_load_post_types_field_choices' );
add_filter( 'acf/load_field/key=field_6602a59e4176f', 'wicket_acf_load_post_types_field_choices' );

// Add ACF field for selecting taxonomies
function wicket_acf_load_taxonomies_field_choices( $field ) {
	$field['choices'] = array();
	$taxonomies       = get_taxonomies( array( 'public' => true ), 'objects' );

	if ( is_array( $taxonomies ) ) {
		$field['choices'][0] = __( '-- Select Taxonomy --', 'wicket' );
		foreach ( $taxonomies as $taxonomy ) {
			$field['choices'][ $taxonomy->name ] = $taxonomy->label;
		}
	}
	return $field;
}
add_filter( 'acf/load_field/key=field_6602a771ef4a9', 'wicket_acf_load_taxonomies_field_choices' );
add_filter( 'acf/load_field/key=field_67727460a5358', 'wicket_acf_load_taxonomies_field_choices' );

// Add ACF field for selecting taxonomy terms
function wicket_acf_load_taxonomy_terms_field_choices( $field ) {
	$field['choices'] = array();
	$taxonomies       = get_taxonomies( array( 'public' => true ), 'objects' );

	if ( is_array( $taxonomies ) ) {
		$field['choices']['0'] = __( '-- Select Taxonomy Term --', 'wicket' );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					$field['choices'][ $term->term_id ] = $taxonomy->label . ' -> ' . $term->name;
				}
			}
		}
	}
	return $field;
}
add_filter( 'acf/load_field/key=field_660be4668327f', 'wicket_acf_load_taxonomy_terms_field_choices' );


// Add image preview to ACF featured image field
function wicket_acf_prepare_featured_image_field( $field ) {
	$post_id           = get_the_ID();
	$featured_image_id = get_post_thumbnail_id( $post_id );
	if ( $featured_image_id ) {
		$featured_image_url = wp_get_attachment_image_url( $featured_image_id, 'thumbnail' );

		$image_classes = [ 'banner__image-preview', 'mt-4', 'w-10' ];
		if ( $field['value'] === 'featured-image' ) {
			$image_classes[] = 'block';
		} else {
			$image_classes[] = 'hidden';
		}

		echo '<img class="' . implode( ' ', $image_classes ) . '" src="' . $featured_image_url . '" alt="Featured Image">';
	}
	// Add script to remove the "Add Image" button
	?>
	<script>		const imageFieldWrapper = document.getElementsByClassName('banner__image-field'); const imageField = imageFieldWrapper[0].querySelector('select'); const imagePreview = document.querySelector('.banner__image-preview'); imageField.addEventListener('change', function () { imagePreview.style.display = this.value === 'featured-image' ? 'block' : 'none'; });</script>
	<?php
}
add_filter( 'acf/render_field/key=field_65aa70754a630', 'wicket_acf_prepare_featured_image_field' );