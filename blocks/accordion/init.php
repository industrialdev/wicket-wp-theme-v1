<?php
/**
 * Wicket Accordion.
 *
 **/

namespace Wicket\Blocks\Wicket_Accordion;

/**
 * Accordion block registration function.
 */
function init($block = [])
{

    $attrs = get_block_wrapper_attributes();

    $items = get_field('accordion_items');
    $icon_type = get_field('icon_type');
    $accordion_type = get_field('accordion_type');
    $separate_title_body = get_field('separate_title_body');
    $heading_level = get_field('heading_level');

    echo '<div ' . $attrs . '>';
    get_component('accordion', [
        'items'                 => $items,
        'icon-type'             => $icon_type,
        'accordion-type'        => $accordion_type,
        'separate-title-body'   => $separate_title_body,
        'heading-level'         => $heading_level,
    ]);
    echo '</div>';

}
