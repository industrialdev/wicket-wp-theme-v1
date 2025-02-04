<?php
/**
 * ACF Blocks
 *
 **/


/* Add Gutenberg Theme Support */
add_theme_support( 'wp-block-styles' );
add_theme_support( 'align-wide' );

/* Remove Core patterns */
remove_theme_support( 'core-block-patterns' );

/* Remove WooCommerce patterns
 * This does not work, support may be coming in future version
 */
// remove_theme_support( 'woocommerce-block-patterns' );

/**
 * Create Wicket pattern categories
 */
if ( function_exists( 'register_block_pattern_category' ) ) {
	register_block_pattern_category(
		'wicket',
		array(
			'label'       => __( 'Wicket', 'wicket' ),
			'description' => __( 'Custom pre-made patterns', 'wicket' ),
		)
	);
}

/**
 * Registers all ACF blocks in the parent theme.
 *
 * This function is called by the load_acf_blocks_field_group filter and
 * registers all ACF blocks in the parent theme.
 *
 * @since 1.0.0
 */
function load_acf_blocks() {
	$blocks = get_blocks();

	foreach ( $blocks as $block ) {
		// Check if Block is already registered
		$registry = WP_Block_Type_Registry::get_instance();

		if ($registry->get_registered( 'wicket/' . $block )) {
			continue;
		}

		if ( file_exists( get_template_directory() . '/blocks/' . $block . '/block.json' ) ) {
			register_block_type( get_template_directory() . '/blocks/' . $block . '/block.json' );

			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/style.css' ) ) {
				wp_register_style( 'block-' . $block, get_template_directory_uri() . '/blocks/' . $block . '/style.css', array(), filemtime( get_template_directory() . '/blocks/' . $block . '/style.css' ) );
			}

			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/init.php' ) ) {
				include_once get_template_directory() . '/blocks/' . $block . '/init.php';
			}
		}
	}
}

/**
 * Registers all ACF blocks in the child theme.
 *
 * This function works just like load_acf_blocks(), but loads blocks from the
 * child theme instead of the parent theme.
 *
 * @since 1.0.0
 * @see load_acf_blocks()
 */
function load_child_acf_blocks() {
	$blocks = get_child_blocks();

	foreach ( $blocks as $block ) {
		// Check if Block is already registered
		$registry = WP_Block_Type_Registry::get_instance();

		if ($registry->get_registered( 'wicket/' . $block )) {
			continue;
		}

		if ( file_exists( get_stylesheet_directory() . '/blocks/' . $block . '/block.json' ) ) {
			register_block_type( get_stylesheet_directory() . '/blocks/' . $block . '/block.json' );

			if ( file_exists( get_stylesheet_directory() . '/blocks/' . $block . '/style.css' ) ) {
				wp_register_style( 'block-' . $block, get_stylesheet_directory_uri() . '/blocks/' . $block . '/style.css', array(), filemtime( get_template_directory() . '/blocks/' . $block . '/style.css' ) );
			}

			if ( file_exists( get_stylesheet_directory() . '/blocks/' . $block . '/init.php' ) ) {
				include_once get_stylesheet_directory() . '/blocks/' . $block . '/init.php';
			}
		}
	}
}
add_action( 'init', 'load_child_acf_blocks', 5 );
add_action( 'init', 'load_acf_blocks', 5 );


/**
 * Load ACF field groups for blocks
 */
function load_acf_blocks_field_group( $paths ) {
	$blocks = get_blocks();
	foreach ( $blocks as $block ) {
		$paths[] = get_template_directory() . '/blocks/' . $block;
	}
	return $paths;
}
function load_child_acf_blocks_field_group( $paths ) {
	$blocks = get_child_blocks();
	foreach ( $blocks as $block ) {
		$paths[] = get_stylesheet_directory() . '/blocks/' . $block;
	}
	return $paths;
}
add_filter( 'acf/settings/load_json', 'load_acf_blocks_field_group' );
add_filter( 'acf/settings/load_json', 'load_child_acf_blocks_field_group' );

/**
 * Get ACF Blocks
 */
function get_blocks() {
	$blocks = scandir( get_template_directory() . '/blocks/' );
	$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );

	return $blocks;
}
function get_child_blocks() {
	$child_blocks = scandir( get_stylesheet_directory() . '/blocks/' );
	$child_blocks = array_values( array_diff( $child_blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );

	return $child_blocks;
}

/**
 * Adjusting Core Blocks
 */

function wicket_core_block_wrappers( $block_content, $block ) {
	if ( $block['blockName'] === 'core/paragraph' ) {
		$content = '<div class="wp-block-paragraph">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/list' ) {
		$content = '<div class="wp-block-list">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/legacy-widget' ) {
		$content = '<div class="wp-block-legacy-widget">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( str_contains( $block['blockName'], 'gravityforms' ) ) {
		$content = '<div class="wp-block-gravityforms">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	}
	return $block_content;
}

add_filter( 'render_block', 'wicket_core_block_wrappers', 10, 2 );

/**
 * Adding default blocks to posts
 */
function register_post_template() {
	$template                   = array(
		array( 'wicket/banner', [
			'data'  => [
				'banner_show_breadcrumbs' => false,
				'banner_show_post_type'   => true,
				'banner_back_link'        => '',
				'banner_show_date'        => true,
			],
			'align' => 'full',
			'lock'  => array(
				'move'   => true,
				'remove' => true,
			),
		] ),
		array( 'core/paragraph', [
			'content' => '<b>Topics:</b>',
			'style'   => [
				'spacing' => [
					'padding' => [
						'top'    => '1.5rem',
						'bottom' => '0',
					],
				] ],
		] ),
		array( 'core/post-terms', [
			'term' => 'post_tag',
		] ),
		array( 'wicket/manually-related-content' ),
		array( 'wicket/dynamically-related-content', [
			'data' => [
				'related_content_max_posts'    => 3,
				'related_content_column_count' => 3,
				'post_type'                    => 'post',
			],
		] ),
	);
	$post_type_object           = get_post_type_object( 'post' );
	$post_type_object->template = $template;
}
add_action( 'init', 'register_post_template' );

// Activate the Block editor on specific post types
function wicket_activate_block_editor_post_types( $can_edit, $post_type ) {
	if ( $post_type == 'product' || $post_type == 'tribe_events' ) {
		$can_edit = true;
	}

	return $can_edit;
}

add_filter( 'use_block_editor_for_post_type', 'wicket_activate_block_editor_post_types', 10, 2 );
