<?php
require_once __DIR__ . '/../../../controller/offre_emploi_con.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new OffreEmploiCon();
    try {
        $controller->delete($id);
        header('Location: offre_emploi_list.php?deleted=1');
        exit();
    } catch (Exception $e) {
        $error_message = "Erreur lors de la suppression de l'offre : " . urlencode($e->getMessage());
        header('Location: offre_emploi_list.php?error=' . $error_message);
        exit();
    }
} else {
    $error_message = "Identifiant d'offre manquant ou invalide.";
    header('Location: offre_emploi_list.php?error=' . urlencode($error_message));
    exit();
}
