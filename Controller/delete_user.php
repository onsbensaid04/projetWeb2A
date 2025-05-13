<?php
session_start();
require_once __DIR__ . '/../Controller/utilisateurBack.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../Vue/Front/Vue/connexion.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $utilisateur = new UtilisateurBack();
    $utilisateur->supprimerUtilisateur($id);

    header('Location: ../Vue/Back/dashboard.php?deleted=1');
    exit;
} else {
    header('Location: ../Vue/Back/dashboard.php');
    exit;
}
?>
