<?php
session_start();
require_once __DIR__ . '/../Controller/utilisateurBack.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../Vue/Front/Vue/connexion.php');
    exit;
}

$utilisateur = new UtilisateurBack();

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'bloquer') {
        $utilisateur->bloquerUtilisateur($id);
    } elseif ($action == 'debloquer') {
        $utilisateur->debloquerUtilisateur($id);
    }

    header('Location: ../Vue/Back/dashboard.php');
    exit;
} else {
    header('Location: ../Vue/Back/dashboard.php');
    exit;
}
?>
