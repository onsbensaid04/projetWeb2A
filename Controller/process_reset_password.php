<?php
session_start();
require_once __DIR__ . '/../config.php';
$pdo = Config::getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifier que le code a été validé et que l'email est en session
    if (!isset($_SESSION['code_verified']) || !$_SESSION['code_verified'] || !isset($_SESSION['reset_email'])) {
        header('Location: ../Vue/Front/Vue/verify_code.php?error=notverified');
        exit;
    }

    // 1. Récupérer le nouveau mot de passe
    $nouveauMotDePasse = htmlspecialchars(trim($_POST['nouveau_mot_de_passe']));
    $email = $_SESSION['reset_email'];

    // 2. Rechercher l'utilisateur avec cet email
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Email invalide (ne devrait pas arriver)
        header('Location: ../Vue/Front/Vue/reset_password.php?error=invalidemail');
        exit;
    }

    // 3. Hasher le nouveau mot de passe
    $motDePasseCrypte = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);

    // 4. Mettre à jour le mot de passe
    $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE id = ?");
    $stmt->execute([$motDePasseCrypte, $user['id']]);

    // 5. Nettoyer la session
    unset($_SESSION['reset_code'], $_SESSION['reset_code_expiry'], $_SESSION['reset_email'], $_SESSION['code_verified']);

    // 6. Rediriger vers la connexion avec un message de succès
    header('Location: ../Vue/Front/Vue/connexion.php?success=passwordreset');
    exit;
}
?>
