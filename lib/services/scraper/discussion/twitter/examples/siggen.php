<?php

/**
 * A script which will generate an OAuth signature for checking with other
 * libraries/scripts.
 *
 * This example is intended to be run from the command line. To use it:
 *
 * Instructions:
 * 1) If you don't have one already, create a Twitter application on
 *      http://dev.twitter.com/apps
 * 2) Note your the consumer key and consumer secret for the application
 * 3) In a terminal or server type:
 *      php /path/to/here/siggen.php
 *
 * @author themattharris
 *
 * Known Issues:
 *   * Parameters are not yet supported. To add parameters see the note
 *     in the code
 */

require '../tmhOAuth.php';
require '../tmhUtilities.php';

function welcome() {
  echo <<<EOM
tmhOAuth PHP Signature Generator.
This script generates an OAuth signature from adhoc values.
No requests are made to the Twitter API.

EOM;
}

welcome();
$consumer_key    = tmhUtilities::read_input(PHP_EOL . 'Consumer Key' . PHP_EOL);
$consumer_secret = tmhUtilities::read_input(PHP_EOL . 'Consumer Secret' . PHP_EOL);
$user_token      = tmhUtilities::read_input(PHP_EOL . 'User Token' . PHP_EOL . '(this can be left blank for checking request_token calls)');
$user_secret     = tmhUtilities::read_input(PHP_EOL . 'User Secret' . PHP_EOL . '(this can be left blank for checking request_token calls)');
$timestamp       = tmhUtilities::read_input(PHP_EOL . 'Timestamp' . PHP_EOL . '(leave blank to have this autogenerated)' . PHP_EOL);
$nonce           = tmhUtilities::read_input(PHP_EOL . 'Nonce' . PHP_EOL . '(leave blank to have this autogenerated)' . PHP_EOL);
$url             = tmhUtilities::read_input(PHP_EOL . 'URL' . PHP_EOL . '(e.g. https://api.twitter.com/1/account/verify_credentials.json)' . PHP_EOL);
$action          = tmhUtilities::read_input(PHP_EOL . 'HTTP Action' . PHP_EOL . '(leave blank for GET)' . PHP_EOL);

$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => $consumer_key,
  'consumer_secret' => $consumer_secret,
  'user_token'      => $user_token,
  'user_secret'     => $user_secret,
  'prevent_request' => true,
));

if (strlen($nonce) > 0) :
  $tmhOAuth->config['force_nonce'] = true;
  $tmhOAuth->config['nonce'] = $nonce;
endif;

if (strlen($timestamp) > 0) :
  $tmhOAuth->config['force_timestamp'] = true;
  $tmhOAuth->config['timestamp'] = $timestamp;
endif;

$action = strlen($action) > 0 ? strtoupper($action) : 'GET';

// default request
$tmhOAuth->request($action, $url);

// if you want to use paramters you'll need to do something like this:
/*

$tmhOAuth->request($action, $url, array(
  'param' => 'value',
));

*/

echo PHP_EOL;
echo 'Base String:' . $tmhOAuth->base_string;
echo PHP_EOL;
echo 'Signing Key:' . $tmhOAuth->signing_key;
echo PHP_EOL;
echo 'Auth Header:' . $tmhOAuth->auth_header;
echo PHP_EOL;
?>