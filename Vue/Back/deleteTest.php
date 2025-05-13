<?php
require_once "../../Controller/TestC.php";  // Inclure le contrôleur TestC

// Vérifie si l'idTest est passé en paramètre GET
if (isset($_GET["idTest"])) {
    $testController = new TestC();  // Créer une instance du contrôleur TestC

    // Appeler la méthode pour supprimer le test
    $testController->deleteTest($_GET["idTest"]);

    // Afficher un message de succès et rediriger vers la page d'affichage des tests
    echo "<script>alert('Suppression réussie !');</script>";
    echo "<script>window.location='afficherTest.php';</script>";

    exit();
} else {
    echo "Identifiant du test manquant.";
    exit();
}
?>
