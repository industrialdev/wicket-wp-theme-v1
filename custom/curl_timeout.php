<?php

// Extend CURL timeout beyond the default 10 seconds.
// This was mainly to fix the WOO webhooks that talk to tray.io

// Copied from http://fatlabmusic.com/blog/2009/08/12/how-to-fix-wp-http-error-name-lookup-timed-out/
//adjustments to wp-includes/http.php timeout values to workaround slow server responses
add_filter('http_request_args', 'bal_http_request_args', 100, 1);
function bal_http_request_args($r) //called on line 237
{
	$r['timeout'] = 20;
	return $r;
}
 
add_action('http_api_curl', 'bal_http_api_curl', 100, 1);
function bal_http_api_curl($handle) //called on line 1315
{
	curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 20 );
	curl_setopt( $handle, CURLOPT_TIMEOUT, 20 );
}