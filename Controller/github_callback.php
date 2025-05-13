<?php
// github_callback.php: Handle GitHub OAuth2 callback for login and signup
require_once __DIR__ . '/../Controller/utilisateur.php';
require_once __DIR__ . '/../config.php';

$client_id = Utilisateur::$GITHUB_CLIENT_ID;
$client_secret = Utilisateur::$GITHUB_CLIENT_SECRET;
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/github_callback.php';

if (!isset($_GET['code'])) {
    // Default to login page if state is missing
    $isSignup = (isset($_GET['state']) && $_GET['state'] === 'signup');
    $redirect = $isSignup ? '../Vue/Front/Vue/inscription.php?error=github_signup_msg&msg=missing_code' : '../Vue/Front/Vue/connexion.php?error=github_msg&msg=missing_code';
    header('Location: ' . $redirect);
    exit;
}

$code = $_GET['code'];

// Exchange code for access token
$token_url = 'https://github.com/login/oauth/access_token';
$post_fields = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $code,
    'redirect_uri' => $redirect_uri,
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$token_response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($token_response, true);

if (!isset($token_data['access_token'])) {
    $isSignup = (isset($_GET['state']) && $_GET['state'] === 'signup');
    $redirect = $isSignup ? '../Vue/Front/Vue/inscription.php?error=github_signup_msg&msg=token_error' : '../Vue/Front/Vue/connexion.php?error=github_msg&msg=token_error';
    header('Location: ' . $redirect);
    exit;
}

$access_token = $token_data['access_token'];

// Fetch user email
$userinfo_url = 'https://api.github.com/user/emails';
$ch = curl_init($userinfo_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: token ' . $access_token,
    'User-Agent: StartupAcademyApp'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userinfo_response = curl_exec($ch);
curl_close($ch);

$user_emails = json_decode($userinfo_response, true);
$email = '';
if (is_array($user_emails)) {
    foreach ($user_emails as $e) {
        if (isset($e['primary']) && $e['primary'] && isset($e['email'])) {
            $email = $e['email'];
            break;
        }
    }
}
if (!$email && isset($user_emails[0]['email'])) {
    $email = $user_emails[0]['email'];
}

session_start();
$utilisateurModel = new Utilisateur();
if ($email) {
    $isSignup = (isset($_GET['state']) && $_GET['state'] === 'signup');
    $utilisateur = $utilisateurModel->getUtilisateurByEmail($email);
    if ($isSignup) {
        // SIGNUP FLOW
        if ($utilisateur) {
            header('Location: ../Vue/Front/Vue/inscription.php?error=github_signup_msg&msg=exists');
            exit;
        }
        // Fetch GitHub user's profile for name
        $profile_url = 'https://api.github.com/user';
        $ch = curl_init($profile_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $access_token,
            'User-Agent: StartupAcademyApp'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $profile_response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($profile_response, true);
        $prenom = $profile['name'] ?? '';
        $nom = '';
        $telephone = '';
        $genre = '';
        $role = 'etudiant'; // Default role
        $mot_de_passe = bin2hex(random_bytes(16)); // Random password, not used
        $utilisateurModel->ajouterUtilisateur($prenom, $nom, $email, $telephone, $genre, $mot_de_passe, $role);
        // Fetch newly created user
        $utilisateur = $utilisateurModel->getUtilisateurByEmail($email);
        // Log in
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
        // LOGIN FLOW
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
            header('Location: ../Vue/Front/Vue/connexion.php?error=github_msg&msg=user_not_found');
            exit;
        }
    }
} else {
    $isSignup = (isset($_GET['state']) && $_GET['state'] === 'signup');
    $redirect = $isSignup ? '../Vue/Front/Vue/inscription.php?error=github_signup_msg&msg=userinfo_error' : '../Vue/Front/Vue/connexion.php?error=github_msg&msg=userinfo_error';
    header('Location: ' . $redirect);
    exit;
}
