<?php
require_once "config.php"; // Assure-toi que le chemin est correct

try {
    $db = config::getConnexion();
    echo "✅ Connexion réussie à la base de données !";
} catch (Exception $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}
?>
