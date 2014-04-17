<?php
include_once "mapmyfitness-php-sdk/MMF.php";
include_once("mapmyfitness-php-sdk/MMF_OAuth.php");

// Generate absolute URL for callback action
$callback_url = 'http://127.0.0.1/~bradday/mmf_php_sdk_tutorial/step_2.php';

try {
    // We need the Authorize URL to adk for the User's permission to access their MMF account
    $authorize_url = MMF_OAuth::getAuthorizeURL($callback_url);
    header( 'Location: ' . $authorize_url ) ;
} catch(Exception $e) {
    echo "Request for Authorization failed. Double check your API Key and Secret.";
}

?>