<?php
session_start();

require_once __DIR__ . '/../config.php'; // Connexion DB
$pdo = Config::getConnexion();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

$your_mail = "abidimohamed123456789123@gmail.com";
$your_password = "nxpq inqo nroo afxi";

$email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';
if (!$email) {
    header('Location: ../Vue/Front/Vue/motdepasse_oublie.php?error=emailnotfound');
    exit;
}

// Générer un nouveau code à 6 chiffres
$reset_code = random_int(100000, 999999);
$code_expiry = time() + 600; // 10 minutes
$_SESSION['reset_code'] = $reset_code;
$_SESSION['reset_code_expiry'] = $code_expiry;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $your_mail;
    $mail->Password = $your_password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($your_mail, 'Startup Academy');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Réinitialisation de votre mot de passe';
    $mail->Body = "
        Bonjour,<br><br>
        Vous avez demandé à réinitialiser votre mot de passe.<br><br>
        Voici votre code de réinitialisation : <b>$reset_code</b><br><br>
        Ce code est valable pendant 10 minutes.<br><br>
        Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.
    ";
    $mail->AltBody = "Votre code de réinitialisation : $reset_code (valable 10 minutes)";

    $mail->send();
    header('Location: ../Vue/Front/Vue/verify_code.php?resent=success');
    exit;

} catch (Exception $e) {
    error_log("Erreur mail : " . $mail->ErrorInfo);
    header('Location: ../Vue/Front/Vue/verify_code.php?resent=error');
    exit;
}
