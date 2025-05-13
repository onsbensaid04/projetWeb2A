<?php
// check_ban.php: Redirect banned users to banned.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['user'])) {
    require_once __DIR__ . '/../../../Controller/utilisateur.php';
    $utilisateurModelB = new Utilisateur();
    $user = $utilisateurModelB->getUtilisateurByEmail($_SESSION['user']['email']);
    if ($user && isset($user['statut']) && $user['statut'] === 'bloque') {
        header('Location: '. Utilisateur::$BAN_DIR);
        exit;
    }
}
