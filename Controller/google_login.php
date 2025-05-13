<?php
// google_login.php: Initiate Google OAuth2 flow

// Replace with your actual Google client ID and redirect URI
require_once __DIR__ . '/../Controller/utilisateur.php';
$client_id = Utilisateur::$GOOGLE_CLIENT_ID;
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/google_callback.php';
$scope = 'email profile';

$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?'
    . 'response_type=code'
    . '&client_id=' . urlencode($client_id)
    . '&redirect_uri=' . urlencode($redirect_uri)
    . '&scope=' . urlencode($scope)
    . '&access_type=online'
    . '&prompt=select_account';

header('Location: ' . $auth_url);
exit;
