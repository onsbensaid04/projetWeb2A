<?php
// github_login.php: Initiate GitHub OAuth2 flow for login
require_once __DIR__ . '/../Controller/utilisateur.php';
$client_id = Utilisateur::$GITHUB_CLIENT_ID;
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/github_callback.php';
$scope = 'user:email';

$auth_url = 'https://github.com/login/oauth/authorize?'
    . 'client_id=' . urlencode($client_id)
    . '&redirect_uri=' . urlencode($redirect_uri)
    . '&scope=' . urlencode($scope)
    . '&state=login'
    . '&allow_signup=true';

header('Location: ' . $auth_url);
exit;
