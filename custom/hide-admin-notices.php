<?php
/**
 * Plugin Name: Hide WP Admin Notifications
 * Description: Hides all WordPress admin dashboard notifications with an option to show them temporarily. Visit the settings page to temporarily show the notices.
 * Version: 0.2
 * Author: Yoda Of WP
 * Requires at least: 5.2
 * Tested up to: 6.7
 * Requires PHP: 7.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @link https://wordpress.org/plugins/hide-wp-admin-notifications/
 */

// Prevent direct access
defined('ABSPATH') || die('No direct script access allowed!');

// Only if WP_ENV environment = development
if (!defined('WP_DEBUG') || !WP_DEBUG) {
  return;
}

define('HWAN_PLUGIN_VERSION', '0.2');

// Load plugin textdomain for translations
function hwan_load_plugin_textdomain()
{
  load_plugin_textdomain('hide-wp-admin-notifications', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'hwan_load_plugin_textdomain');

// Ensure option exists for seamless version updates
function hwan_ensure_option_exists()
{
  if (false === get_option('hwan_show_dashboard_notices')) {
    add_option('hwan_show_dashboard_notices', 'no');
  }
}
register_activation_hook(__FILE__, 'hwan_ensure_option_exists');

// Maintain existing setting during updates
function hwan_update_check()
{
  if (get_option('hwan_plugin_version') !== HWAN_PLUGIN_VERSION) {
    update_option('hwan_plugin_version', HWAN_PLUGIN_VERSION);
    hwan_ensure_option_exists();
  }
}
add_action('plugins_loaded', 'hwan_update_check');

// Remove admin notices based on the setting
function hwan_remove_admin_notices()
{
  $show_notices = get_option('hwan_show_dashboard_notices', 'no');
  if ('yes' !== $show_notices) {
    global $wp_filter;

    // Remove user admin notices
    if (is_user_admin()) {
      if (isset($wp_filter['user_admin_notices'])) {
        unset($wp_filter['user_admin_notices']);
      }
    }
    // Remove admin notices
    if (isset($wp_filter['admin_notices'])) {
      unset($wp_filter['admin_notices']);
    }

    // Remove all admin notices
    if (isset($wp_filter['all_admin_notices'])) {
      unset($wp_filter['all_admin_notices']);
    }
  }
}
add_action('admin_print_scripts', 'hwan_remove_admin_notices');

// Add plugin settings page
function hwan_admin_menu()
{
  add_options_page(
    'Hide WP Admin Notifications',
    'Hide WP Admin Notifications',
    'manage_options',
    'hide-wp-admin-notifications',
    'hwan_options_page'
  );
}
add_action('admin_menu', 'hwan_admin_menu');

// Register settings and fields
function hwan_settings_init()
{
  register_setting('hwan_settings', 'hwan_show_dashboard_notices', 'hwan_sanitize_checkbox');

  add_settings_section(
    'hwan_settings_section',
    '',
    null,
    'hwan_settings'
  );

  add_settings_field(
    'hwan_show_dashboard_notices',
    'Show Dashboard Notices',
    'hwan_show_dashboard_notices_cb',
    'hwan_settings',
    'hwan_settings_section'
  );
}
add_action('admin_init', 'hwan_settings_init');

// Sanitize checkbox value
function hwan_sanitize_checkbox($value)
{
  return ('yes' === $value) ? 'yes' : 'no';
}

// Show dashboard notices setting field
function hwan_show_dashboard_notices_cb()
{
  $show_notices = get_option('hwan_show_dashboard_notices', 'no');
?>
  <label>
    <input type="checkbox" name="hwan_show_dashboard_notices" value="yes" <?php checked($show_notices, 'yes'); ?> />
    <span class="checkmark"></span>
    <span>Check this box to temporarily show all dashboard notices.</span>
  </label>
<?php
}

// Display the plugin settings page
function hwan_options_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'hide-wp-admin-notifications'));
  }
?>
  <div class="hwan-settings-container">
    <h1>Hide WP Admin Notifications</h1>
    <div class="hwan-message">
      <p>Periodically displaying dashboard notices is essential, as they may provide vital information regarding your site's security, updates, and other significant announcements.</p>
    </div>
    <form method="post" action="options.php">
      <?php
      settings_fields('hwan_settings');
      do_settings_sections('hwan_settings');
      submit_button();
      ?>
    </form>
  </div>
<?php
}

// Add settings link to the plugin action links
function hwan_add_settings_link($links)
{
  $settings_link = '<a href="options-general.php?page=hide-wp-admin-notifications">' . __('Settings', 'hide-wp-admin-notifications') . '</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'hwan_add_settings_link');

// Enqueue admin styles for the plugin settings page
function hwan_enqueue_admin_styles($hook)
{
  if ('settings_page_hide-wp-admin-notifications' !== $hook) {
    return;
  }

  wp_enqueue_style('hwan-admin-styles', plugins_url('admin.css', __FILE__), array(), HWAN_PLUGIN_VERSION);
}
add_action('admin_enqueue_scripts', 'hwan_enqueue_admin_styles');
