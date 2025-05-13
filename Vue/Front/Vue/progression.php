<?php
require_once(__DIR__ . "/../../../config.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        throw new Exception("Données JSON invalides");
    }    

    // Utilisez l'ID de l'utilisateur directement depuis la session
    $userId = $_SESSION['user']['id']; // Récupérer l'ID utilisateur de la session

    if (isset($data['id_pdf'], $data['pages_lues'], $data['total_pages'], $data['pourcentage'])) {
        $currentPdfId = $data['id_pdf'];
        $pagesRead = $data['pages_lues'];
        $totalPages = $data['total_pages'];
        $progress = $data['pourcentage'];

        try {
            $pdo = config::getConnexion(); // Connexion à la base

            // Vérifier si une progression existe déjà
            $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM progression_lecture WHERE id = :id AND id_pdf = :id_pdf');
            $checkStmt->execute([
                ':id' => $userId, // Utilisation de l'ID de la session
                ':id_pdf' => $currentPdfId
            ]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                // Déjà existant -> Mise à jour
                $updateStmt = $pdo->prepare('
                    UPDATE progression_lecture 
                    SET pages_lues = :pages_lues, 
                        total_pages = :total_pages, 
                        pourcentage = :pourcentage, 
                        date_maj = NOW()
                    WHERE id = :id AND id_pdf = :id_pdf
                ');
                $updateStmt->execute([
                    ':pages_lues' => $pagesRead,
                    ':total_pages' => $totalPages,
                    ':pourcentage' => $progress,
                    ':id' => $userId, // Utilisation de l'ID de la session
                    ':id_pdf' => $currentPdfId
                ]);
            } else {
                // Pas existant -> Insertion
                $insertStmt = $pdo->prepare('
                    INSERT INTO progression_lecture (id, id_pdf, pages_lues, total_pages, pourcentage, date_maj)
                    VALUES (:id, :id_pdf, :pages_lues, :total_pages, :pourcentage, NOW())
                ');
                $insertStmt->execute([
                    ':id' => $userId, // Utilisation de l'ID de la session
                    ':id_pdf' => $currentPdfId,
                    ':pages_lues' => $pagesRead,
                    ':total_pages' => $totalPages,
                    ':pourcentage' => $progress
                ]);
            }

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
}
?>
