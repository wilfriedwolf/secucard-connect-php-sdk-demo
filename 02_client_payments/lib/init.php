<?php

define('TWIG_VIEWS_PATH', __DIR__ . "/../views");

require_once(__DIR__ . "/../../shared/php/init.php");

/*
 * CONFIG
 */
$config_sdk = array(
    'client_id' => 'overwrite with cookie when set',
    'client_secret' => 'overwrite with cookie when set',
    'debug' => true,
);


/*
 * Prepare secucard connect SDK
 */

// Get credentials from cookie if avail
$credentials = $app->getCookie('secucard-connect-demo');

if ($credentials) {

    $credentials = json_decode($credentials);

    if (isset($credentials->client_id) && !empty($credentials->client_id)) {
        $config_sdk['client_id'] = $credentials->client_id;
        $config_sdk['client_secret'] = $credentials->client_secret;
    }

    if (isset($credentials->server_host) && !empty($credentials->server_host)) {
        if (strpos($credentials->server_host, 'https://') !== false) {
            $config_sdk['base_url'] = $credentials->server_host;
        } else {
            $config_sdk['base_url'] = "https://" . $credentials->server_host;
        }
    }
}

// create client
$secucard = new secucard\Client($config_sdk, $logger);