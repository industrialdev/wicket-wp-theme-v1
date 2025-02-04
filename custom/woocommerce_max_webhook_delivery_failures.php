<?php

// Raise up the amount of retries the webhooks have before they disable themselves. 
// According to the docs, https://woocommerce.com/document/webhooks/#creating-webhooks, 
// Webhooks are disabled after 5 retries by default if the delivery URL returns an unsuccessful status such as 404 or 5xx. Successful responses are 2xx, 301 or 302.
// https://wordpress.stackexchange.com/questions/214868/woocommerce-webhook-disabled-on-its-own
function overrule_webhook_disable_limit( $number ) {
  return 999999999999; //very high number hopefully you'll never reach.
}
add_filter( 'woocommerce_max_webhook_delivery_failures', 'overrule_webhook_disable_limit' );