<?php

// No direct access
defined('ABSPATH') || exit;

/**
 * For use with wp private content plus plugin
 * Appends the page to the url on redirect for where
 * the user was trying to go
 *
 * Filters applied:
 * - wp-private-content-plus\classes\class-wppcp-private-posts-pages.php:183 (single post)
 * - wp-private-content-plus global post restriction
 *
 * The main header login button then looks at this param and uses it if present
 */
function wicket_private_content_redirect($url)
{
    if (empty($url)) {
        return $url;
    }

    $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';

    $url .= '?referrer=' . $_SERVER['REQUEST_URI'];


    if ($lang == 'fr') {
        return '/fr' . $url;
    }

    return $url;
}
add_filter('wppcp_single_post_restriction_redirect', 'wicket_private_content_redirect', 10, 3);
add_filter('wppcp_global_post_restriction_redirect', 'wicket_private_content_redirect', 10, 3);
