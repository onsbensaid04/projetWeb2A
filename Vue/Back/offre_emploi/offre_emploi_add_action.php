<?php
require_once __DIR__ . '/../../../controller/offre_emploi_con.php';
require_once __DIR__ . '/../../../model/offre_emploi.php';

if (
    isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['entreprise']) &&
    isset($_POST['lieu']) && isset($_POST['salaire']) && isset($_POST['date_limit'])
) {
    if (
        !empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['entreprise']) &&
        !empty($_POST['lieu']) && !empty($_POST['salaire']) && !empty($_POST['date_limit'])
    ) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $entreprise = $_POST['entreprise'];
        $lieu = $_POST['lieu'];
        $salaire = $_POST['salaire'];
        $date_creation = date('Y-m-d');
        $date_limit = $_POST['date_limit'];

        $offre = new OffreEmploi(null, $titre, $description, $entreprise, $lieu, $salaire, $date_creation, $date_limit);
        $controller = new OffreEmploiCon();
        try {
            $controller->add($offre);
            header('Location: offre_emploi_list.php?success=1');
            exit();
        } catch (Exception $e) {
            $error_message = "Erreur lors de l'ajout de l'offre : " . urlencode($e->getMessage());
            header('Location: offre_emploi_list.php?error=' . $error_message);
            exit();
        }
    } else {
        $error_message = "Tous les champs sont obligatoires.";
        header('Location: offre_emploi_list.php?error=' . urlencode($error_message));
        exit();
    }
} else {
    $error_message = "Formulaire incomplet.";
    header('Location: offre_emploi_list.php?error=' . urlencode($error_message));
    exit();
}
?>
