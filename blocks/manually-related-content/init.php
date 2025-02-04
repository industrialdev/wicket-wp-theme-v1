<?php
/**
 * Wicket Manually Related Content Block
 *
 **/

namespace Wicket\Blocks\Wicket_Manually_Related_Content;

/**
 * Initialize the Manually Related Content block.
 *
 * @param array $block The block settings and attributes.
 *
 * @return void
 */
function init( $block = [] ) {

	$attrs                    = get_block_wrapper_attributes();
	$title                    = get_field( 'manually_related_content_title' );
	$posts                    = get_field( 'manually_related_content_posts' );
	$layout_style             = get_field( 'manually_related_content_layout_style' );
	$column_count             = get_field( 'manually_related_content_column_count' );
	$buttons_equal_width      = get_field( 'manually_related_content_make_buttons_same_width' );
	$rounded_corners          = get_field( 'manually_related_content_rounded_corners' );
	$posts_wrapper_classes    = [ 
		'grid',
		'gap-3',
		'grid-cols-1',
		'items-start',
		'px-4',
		'xl:px-0',
	];
	$placeholder_styles       = '';
	$button_same_width_params = '';
	if ( is_admin() ) {
		$placeholder_styles = 'style="min-height: 40px;border: 1px solid var(--wp--preset--color--light);"';
	}

	if ( ! $posts ) {
		$output = '<div ' . $placeholder_styles . '>';
		if ( is_admin() ) {
			$output .= "<p>" . __( 'Use the Block controls on the right to add manually related content.', 'wicket' ) . "</p>";
		}
		$output .= '</div>';
		echo $output;
		return;
	}

	if ( $layout_style === 'card' ) {
		$posts_wrapper_classes[] = 'lg:grid-cols-' . $column_count;
	}

	if ( $layout_style === 'list' ) {
		$posts_wrapper_classes[] = 'lg:grid-cols-1';
	}

	echo '<div ' . $attrs . ' ' . $placeholder_styles . '>';

	if ( is_admin() && empty( $posts ) && empty( $title ) ) {
		echo "<p>" . __( 'Use the Block controls on the right to add manually related content.', 'wicket' ) . "</p>";
	}

	if ( $title ) {
		echo '<div class="text-heading-sm font-bold mb-3 px-4 xl:px-0">' . $title . '</div>';
	}

	if ( $buttons_equal_width ) {
		$button_same_width_params = '
			x-data="{ widestButtonWidth: 100, maybeWiderButton(newWidth) { if( this.widestButtonWidth < newWidth ) { this.widestButtonWidth = newWidth; } } }"
			x-init=" 
				let allCards = $el.querySelectorAll(\'.man-related-content-card\');
				for (const card of allCards) {
					let button = card.querySelector(\'a.button\');
					if( button != null ) {
						maybeWiderButton(button.offsetWidth);
					}
				}
			"
		';
	}

	echo '
	<div 
		class="' . implode( ' ', $posts_wrapper_classes ) . '"
		' . $button_same_width_params . '
	>';
	foreach ( $posts as $post ) {
		$content_type = $post['content_type'];
		$link         = $post['link'];
		$document     = $post['document'];
		$title_text   = ! empty( $post['display_text'] ) ? $post['display_text'] : '';
		if ( empty( $title_text ) && isset( $post['link']['title'] ) ) {
			$title_text = $post['link']['title'];
		}
		$body_text          = $post['body_text']; //
		$cta_label_override = $post['cta_label_override']; //
		$icon_type          = $post['icon_type']; //
		$icon_img           = $post['icon'];

		get_component( 'card-related', [ 
			'classes'            => [ 'man-related-content-card' ],
			'content_type'       => $content_type,
			'link'               => $link,
			'document'           => $document,
			'display_text'       => $title_text,
			'icon_type'          => $icon_type,
			'icon'               => $icon_img,
			'layout_style'       => $layout_style,
			'body_text'          => $body_text,
			'cta_label_override' => $cta_label_override,
			'rounded_corners'    => $rounded_corners,
		] );
	}
	echo '</div>';
	echo '</div>';
}
