<?php
session_start();

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_code = isset($_POST['reset_code']) ? trim($_POST['reset_code']) : '';
    $session_code = isset($_SESSION['reset_code']) ? $_SESSION['reset_code'] : null;
    $expiry = isset($_SESSION['reset_code_expiry']) ? $_SESSION['reset_code_expiry'] : 0;
    $now = time();

    if (!$session_code || !$expiry || $now > $expiry) {
        $error = 'Le code a expiré. Veuillez refaire une demande de réinitialisation.';
    } elseif ($input_code != $session_code) {
        $error = 'Code invalide. Veuillez réessayer.';
    } else {
        // Code correct, autoriser la réinitialisation du mot de passe
        $_SESSION['code_verified'] = true;
        header('Location: reset_password.php');
        exit;
    }
}
?>
<!-- Vue/verify_code.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification du code - Startup Academy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Vérification du code</h2>
    <p>Veuillez entrer le code à 6 chiffres que vous avez reçu par email.</p>

    <?php if (isset($_GET['resent']) && $_GET['resent'] === 'success'): ?>
        <p class="success-global">📬 Le code a été renvoyé à votre adresse email.</p>
    <?php elseif (isset($_GET['resent']) && $_GET['resent'] === 'error'): ?>
        <p class="error-global">❌ Erreur lors de l'envoi du code. Veuillez réessayer.</p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error-global">❌ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" style="margin-bottom: 10px;">
        <div class="form-group">
            <input type="text" name="reset_code" maxlength="6" pattern="\d{6}" required placeholder="Code à 6 chiffres">
        </div>
        <button type="submit">Vérifier</button>
    </form>
    <form method="post" action="../../../Controller/resend_code.php" style="margin-bottom: 10px;">
        <button type="submit" style="background:#6c757d;">Renvoyer le code</button>
    </form>
    <p class="login-link"><a href="connexion.php">🔙 Retour à la connexion</a></p>
</div>

</body>
</html>
