<!-- Vue/motdepasse_oublie.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - Startup Academy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Mot de passe oublié</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'emailnotfound'): ?>
        <p class="error-global">❌ Aucun compte trouvé pour cette adresse email.</p>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'emailsent'): ?>
        <p class="success-global">📬 Un lien de réinitialisation vous a été envoyé par email.</p>
    <?php endif; ?>

    <form method="POST" action="../../../Controller/send_reset_link.php">
        <div class="form-group">
            <input type="email" name="email" placeholder="Votre adresse email">
        </div>
        <button type="submit">Envoyer le lien de réinitialisation</button>
        <p class="login-link"><a href="connexion.php">🔙 Retour à la connexion</a></p>
    </form>
</div>

</body>
</html>
