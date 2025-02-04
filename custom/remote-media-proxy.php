<?php

// No direct access
if (!defined('ABSPATH')) {
    exit;
}

/*
 * Wicket_Remote_Media_Proxy class.
 *
 * Configuration.
 * Adjust the constant WICKET_REMOTE_MEDIA_URL to your desired remote site.
 * Example:
 *
 * define( 'WICKET_REMOTE_MEDIA_URL', 'https://wordpress-baseline-sandbox.ind.ninja' );
 *
 * Use the proper URL for the desired remote site.
 */

class Wicket_Remote_Media_Proxy
{
    /**
     * Instance of this class.
     *
     * @var Wicket_Remote_Media_Proxy
     */
    protected static $_instance = null;

    /**
     * The home URL of the site to use for images/media. Default: ''.
     *
     * @var string
     */
    protected $home_url = '';

    /**
     * The URL of the remote site to use for images/media. Default: ''.
     *
     * @var string
     */
    protected $remote_media_url = '';

    /**
     * The WordPress uploads directory path. Default: ''.
     *
     * @var string
     */
    protected $wp_upload_base_dir = '';

    /**
     * The WordPress uploads directory URL. Default: ''.
     *
     * @var string
     */
    protected $wp_upload_base_url = '';

    /**
     * Initialize the plugin by setting localization and loading public scripts,
     * styles, filters, and hooks.
     */
    private function __construct()
    {
        // Get the filtered remote media URL
        $this->remote_media_url = trailingslashit(apply_filters('wicket_remote_media_proxy_url', ''));

        // Exit early if remote media URL is empty
        if (empty($this->remote_media_url)) {
            return;
        }

        // Make sure the home URL contains a trailing slash for consistent find/replace actions.
        $this->home_url = trailingslashit(get_home_url());

        // Store the WordPress uploads directory path & URL for image/media replacements.
        $wp_upload_dir = wp_upload_dir();
        $this->wp_upload_base_dir = $wp_upload_dir['basedir'];
        $this->wp_upload_base_url = $wp_upload_dir['baseurl'];

        // Register hooks.
        $this->register_hooks();
    }

    /**
     * Return an instance of this class.
     *
     * @since 1.1.0
     *
     * @return Wicket_Remote_Media_Proxy    A single instance of this class.
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Register any required hooks.
     *
     * @return void
     */
    private function register_hooks()
    {
        // Check for remote media URL and add hooks to proxy media library images.
        if (!empty($this->remote_media_url)) {
            // Add filters only if constant is configured.
            add_filter('wp_get_attachment_image_src', [$this, 'filter_wp_get_attachment_image_src']);
            add_filter('wp_calculate_image_srcset', [$this, 'filter_wp_calculate_image_srcset']);
            add_filter('wp_get_attachment_url', [$this, 'filter_wp_get_attachment_url']);
        }
    }

    /**
     * Check for a local media file and if it doesn't exist replace the home URL with the remote URL.
     *
     * @param string $image_url The URL of the image/media file.
     *
     * @return string
     */
    private function replace_url($image_url)
    {
        $absolute_path = str_replace($this->wp_upload_base_url, $this->wp_upload_base_dir, $image_url);

        if (file_exists($absolute_path)) {
            return $image_url;
        }

        $image_url = str_replace($this->home_url, $this->remote_media_url, $image_url);

        return $image_url;
    }

    /**
     * Proxy requests for images to a remote URL.
     *
     * @link https://developer.wordpress.org/reference/hooks/wp_get_attachment_image_src/
     *
     * @param array|bool $image Array of image data, or boolean false if no image is available.
     *
     * @return array|bool
     */
    public function filter_wp_get_attachment_image_src($image = [])
    {
        if (!is_array($image) || empty($image)) {
            return $image;
        }

        $image[0] = $this->replace_url($image[0]);

        return $image;
    }

    /**
     * Proxy srcset requests to a remote URL.
     *
     * @link https://developer.wordpress.org/reference/hooks/wp_calculate_image_srcset/
     *
     * @param array $sources One or more arrays of source data to include in the 'srcset'.
     *
     * @return array
     */
    public function filter_wp_calculate_image_srcset($sources = [])
    {
        if (is_array($sources) && !is_admin()) {
            foreach ($sources as $key => $val) {
                $val['url'] = $this->replace_url($val['url']);
                $sources[$key] = $val;
            }
        }

        return $sources;
    }

    /**
     * Proxy the attachement URL to a remote site.
     *
     * @link https://developer.wordpress.org/reference/hooks/wp_get_attachment_url/
     *
     * @param string $url URL for the given attachment.
     *
     * @return string
     */
    public function filter_wp_get_attachment_url($url = '')
    {
        if (empty($url)) {
            return $url;
        }

        return $this->replace_url($url);
    }
}

// Run the class on WP init
add_action('init', function () {
    // Only run on localhost/docker
    if (
        in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost'])
        || str_contains($_SERVER['REMOTE_ADDR'], '192.')
        || str_contains($_SERVER['REMOTE_ADDR'], '172.')
    ) {
        $WicketRemoteMediaProxy = Wicket_Remote_Media_Proxy::get_instance();
    }
});
