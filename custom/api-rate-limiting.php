<?php 

// -----------------------------------------------------------------------------------
// Rate-Limit Incoming WordPress API Calls (for both WooCommerce Api and WP Rest API)
// Stolen from here: https://wp-tutorials.tech/optimise-wordpress/rate-limit-wordpress-api-calls/
// -----------------------------------------------------------------------------------

/**
 * How many seconds should there be between successive API calls from
 * rate-limited clients.
 */
const WPTARL_SECONDS_BETWEEN_GUEST_API_CALLS = 1;
/**
 * A list of client IP addresses that should be rate-limited. If you want to
 * rate-limit all API calls then set this to an empty array.
 */
const WPTARL_RATE_LIMITED_IPS = []; // << CHANGE THIS
/**
 * IP addresses that should never be rate-limited (localhost).
 */
const WPTARL_NEVER_RATE_LIMITED_IPS = array(
   '127.0.0.1',
   '::1',
   '52.40.200.248',
   '52.39.10.61',
   '52.26.59.155',
   '108.129.66.83',
   '54.73.116.155',
   '63.33.233.130',
   '3.105.252.6',
   '54.79.191.214',
   '3.24.148.13'
);
/**
 * Check multiple $_SERVER elements to find the remote client's IP address.
 *
 * @return null|string Remote client IP address
 */
function wptarl_client_ip() {
  global $wptarl_client_ip;
  if (is_null($wptarl_client_ip)) {
    $server_vars = array(
      'HTTP_CLIENT_IP',
      'HTTP_X_FORWARDED_FOR',
      'REMOTE_ADDR',
    );
    foreach ($server_vars as $server_var) {
      if (!array_key_exists($server_var, $_SERVER)) {
        // The server variable isn't set - do nothing.
      } elseif (empty($wptarl_client_ip = filter_var($_SERVER[$server_var], FILTER_VALIDATE_IP))) {
       // The IP address is not valid - do nothing.
      } else {
        // We've got a valid IP address in the global variable $wptarl_client_ip,
        // so we can "break" out of the foreach(...) loop here.
      break;
      }
    }
    // Make sure we don't leave something like an empty string or "false"
    // in $wptarl_client_ip
    if (empty($wptarl_client_ip)) {
      $wptarl_client_ip = null;
    }
  }
   return $wptarl_client_ip;
}

/**
 * When the WordPress REST API is initialised, check to see if the request
 * should be blocked, or allowed to execute normally.
 */
function wptarl_rest_api_init(WP_REST_Server $wp_rest_server) {
  $request_uri = $_SERVER['REQUEST_URI'];
  $is_client_rate_limited = false;
  $transient_key = null;

  if( str_contains( $request_uri, 'wicket' ) || $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin' ) {
    // We don't want to rate-limit our own custom endpoints, so we'll do
    // nothing here.
  } elseif (empty($client_ip = wptarl_client_ip())) {
    // Determine if the client that's making the API requests is
    // subject to rate-limiting.

    // We don't know the client's IP address so we probably don't want to do
    // anything here.
  } elseif (!empty(WPTARL_NEVER_RATE_LIMITED_IPS) && in_array($client_ip, WPTARL_NEVER_RATE_LIMITED_IPS)) {
    // Never rate-limit IP addresses in the WPTARL_NEVER_RATE_LIMITED_IPS array.
  } else {
    $transient_key = 'wptarl_' . $client_ip;
    $rate_limited_ips = apply_filters('wptarl_rate_limited_ips', WPTARL_RATE_LIMITED_IPS);
    if (!empty($rate_limited_ips)) {
      $is_client_rate_limited = in_array($client_ip, $rate_limited_ips);
    } else {
      $is_client_rate_limited = true;
    }
      $is_client_rate_limited = (bool)apply_filters('wptarl_is_client_rate_limited', $is_client_rate_limited);
  }

  if (!$is_client_rate_limited) {
    // The client is not rate-limited - do nothing
  } elseif (empty($transient_key)) {
    // If we couldn't figure out the transient key - do nothing
  } elseif (empty(get_transient($transient_key))) {
    // This client IP does not have a transient record, so it has not made
    // an API call recently - let the API call execute normally.
    // Create a transient record that will expire in a few seconds time.
    $seconds_between_api_calls = intval(apply_filters('wptarl_seconds_between_api_calls', WPTARL_SECONDS_BETWEEN_GUEST_API_CALLS, $client_ip));
    if ($seconds_between_api_calls > 0) {
      // We only need the transient record to exist, we don't actually
      // care what the value of the record is, so we've set it to '1'
      set_transient(
        $transient_key,
        '1',
        $seconds_between_api_calls
      );
    }
  } else {
    // A JSON message to send back to the client.
    $response = array(
      'clientIp' => $client_ip,
      'message' => 'Slow down your API calls',
    );
    // HTTP Response 429 => "Too many requests"
    wp_send_json(
      $response,
      429
    );
  }
}
add_action('rest_api_init', 'wptarl_rest_api_init', 10, 1);