<?php

/**
 * Constants.
 */

// No direct access
defined('ABSPATH') || exit;

// Defaults
define('WICKET_THEME_PATH', get_template_directory());
define('WICKET_THEME_URL', get_template_directory_uri());
define('WICKET_CHILD_THEME_PATH', get_stylesheet_directory());
define('WICKET_CHILD_THEME_URL', get_stylesheet_directory_uri());
define('WICKET_UPLOADS_PATH', wp_get_upload_dir()['basedir'] . '/wicket-theme/');
define('WICKET_UPLOADS_URL', wp_get_upload_dir()['baseurl'] . '/wicket-theme/');
define('WICKET_THEME_PREFIX', 'wicket_theme_');

// Define Wicket theme and theme version
define('WICKET_THEME', true);
define('WICKET_THEME_VERSION', wp_get_theme()->get('Version'));

// Post type keys
define('POST_TYPE_NEWS', 'news');
define('POST_TYPE_RESOURCES', 'resources');
