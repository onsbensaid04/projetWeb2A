<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Controller/utilisateur.php';

$utilisateurModel = new Utilisateur();

if (isset($_GET['action'])) {

    // ============================
    // ✅ PARTIE INSCRIPTION
    // ============================
    if ($_GET['action'] == 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        // 1. ➤ Vérification CAPTCHA (très haut)
        if (empty($_POST['g-recaptcha-response'])) {
            header('Location: ../Vue/Front/Vue/inscription.php?error=captcha');
            exit;
        }

        $recaptcha = $_POST['g-recaptcha-response'];
        $secretKey = '6LeqbSArAAAAAOXT-QUwqBAw-sWnEq_97S8ZfOfd'; 
                // Appel à l'API Google pour vérifier le token
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptcha}");
        $responseData = json_decode($verifyResponse);

        // 2. ➤ Si CAPTCHA invalide ➔ on bloque
        if (!$responseData->success) {
            header('Location: ../Vue/Front/Vue/inscription.php?error=captcha');
            exit;
        }

        // 3. ➤ Récupération des données après CAPTCHA
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $genre = htmlspecialchars($_POST['genre']);
        $role = htmlspecialchars($_POST['role']);
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

        // 4. ➤ Vérification de l’email déjà inscrit
        if ($utilisateurModel->emailExiste($email)) {
            header('Location: ../Vue/Front/Vue/inscription.php?error=exists');
            exit;
        }

        // 5. ➤ Enregistrement utilisateur
        $utilisateurModel->ajouterUtilisateur($prenom, $nom, $email, $telephone, $genre, $mot_de_passe, $role);
        header('Location: ../Vue/Front/Vue/connexion.php');
        exit;
    }

    // ============================
    // ✅ PARTIE CONNEXION
    // ============================
    if ($_GET['action'] == 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = htmlspecialchars($_POST['email']);
        $mot_de_passe = $_POST['mot_de_passe'];

        $utilisateur = $utilisateurModel->getUtilisateurByEmail($email);

        if (!$utilisateur) {
            header('Location: ../Vue/Front/Vue/connexion.php?error=email');
            exit;
        }

        if (!password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            header('Location: ../Vue/Front/Vue/connexion.php?error=password');
            exit;
        }

        // Connexion réussie : on stocke l'utilisateur dans la session
        $_SESSION['user'] = [
            'id' => $utilisateur['id'],
            'prenom' => $utilisateur['prenom'],
            'nom' => $utilisateur['nom'],
            'email' => $utilisateur['email'],
            'telephone' => $utilisateur['telephone'],
            'role' => $utilisateur['role'],
        ];

        // ➤ Redirection selon rôle
        header('Location: ../Vue/Front/Vue/index.php');
        exit;
    }
}
?>
