<?php
include_once("includes/config.php");
require_once __DIR__ . '/vendor/autoload.php';
session_start();
if (isset($_GET['code'])) {
    $gClient = new Google_Client();
    $gClient->setApplicationName('Login to cloudweblabs.com');
    $gClient->setClientId($google_credentials['client_id']);
    $gClient->setClientSecret($google_credentials['client_secret']);
    $gClient->setRedirectUri($google_credentials['callback_url']);
    $gClient->addScope("email");
    $gClient->addScope("profile");
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);

    $_SESSION['google_access_token'] = (string) $token['access_token'];
    header("Location:" . $google_credentials['redirect_url']);
    exit;
}
