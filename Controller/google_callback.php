<?php
// google_callback.php: Handle Google OAuth2 callback

// Replace with your actual Google client ID and secret
require_once __DIR__ . '/../Controller/utilisateur.php';
$client_id = Utilisateur::$GOOGLE_CLIENT_ID;
$client_secret = Utilisateur::$GOOGLE_CLIENT_SECRET;
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/google_callback.php';

if (!isset($_GET['code'])) {
    header('Location: ../Vue/Front/Vue/connexion.php?error=google_msg&msg=missing_code');
    exit;
}

$code = $_GET['code'];

// Exchange code for access token
$token_url = 'https://oauth2.googleapis.com/token';
$post_fields = [
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code',
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$token_response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($token_response, true);

if (!isset($token_data['access_token'])) {
    header('Location: ../Vue/Front/Vue/connexion.php?error=google_msg&msg=token_error');
    exit;
}

$access_token = $token_data['access_token'];

// Fetch user info
$userinfo_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
$ch = curl_init($userinfo_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userinfo_response = curl_exec($ch);
curl_close($ch);

$userinfo = json_decode($userinfo_response, true);

if (isset($userinfo['email'])) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../Controller/utilisateur.php';
    session_start();
    $utilisateurModel = new Utilisateur();
    $utilisateur = $utilisateurModel->getUtilisateurByEmail($userinfo['email']);
    if ($utilisateur) {
        $_SESSION['user'] = [
            'id' => $utilisateur['id'],
            'prenom' => $utilisateur['prenom'],
            'nom' => $utilisateur['nom'],
            'email' => $utilisateur['email'],
            'telephone' => $utilisateur['telephone'],
            'role' => $utilisateur['role'],
        ];
        header('Location: ./../Vue/Front/Vue/index.php');
        exit;
    } else {
        header('Location: ../Vue/Front/Vue/connexion.php?error=google_msg&msg=user_not_found');
        exit;
    }
} else {
    header('Location: ../Vue/Front/Vue/connexion.php?error=google_msg&msg=userinfo_error');
    exit;
}
