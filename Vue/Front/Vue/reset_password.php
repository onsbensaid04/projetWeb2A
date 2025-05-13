<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe - Startup Academy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="form-container">
    <h2>🔑 Réinitialiser votre mot de passe</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalidtoken'): ?>
        <p class="error-global">❌ Le lien de réinitialisation est invalide ou expiré.</p>
    <?php endif; ?>

    <form action="../../../Controller/process_reset_password.php" method="POST">
        <!-- Champ caché pour envoyer le token -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

        <div class="form-group">
            <input type="password" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe" required>
        </div>

        <button type="submit">Réinitialiser le mot de passe</button>
        <p class="login-link"><a href="connexion.php">🔙 Retour à la connexion</a></p>
    </form>
</div>

</body>
</html>
