<?php
require_once __DIR__ . '/../../../config.php';
session_start();

if (!isset($_SESSION['id'])) {
    die('Erreur : utilisateur non connecté.');
}

$iduser = $_SESSION['id']; // ici aussi, session correcte
$db = config::getConnexion();

// Chercher combien de quiz validés
$stmt = $db->prepare("SELECT quizzes_valides FROM user_badges WHERE id = ?");
$stmt->execute([$iduser]);
$data = $stmt->fetch();
$quizzes_valides = $data['quizzes_valides'] ?? 0;

// Déterminer badge actuel
if ($quizzes_valides >= 5) {
    $badge = "Expert";
} elseif ($quizzes_valides >= 3) {
    $badge = "Intermédiaire";
} elseif ($quizzes_valides >= 2) {
    $badge = "Débutant";
} else {
    $badge = "Aucun badge pour l'instant";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Badges</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .badge-card {
            border: 2px solid #007bff;
            border-radius: 15px;
            padding: 30px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .badge-card img {
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="badge-card">
        <h1 class="mb-4">🏅 Mes Badges</h1>
        <p><strong>Quiz validés :</strong> <?= $quizzes_valides ?></p>
        <p><strong>Badge actuel :</strong> <?= $badge ?></p>

        <div class="d-flex justify-content-center flex-wrap mt-4">
            <?php if ($quizzes_valides >= 2): ?>
                <div class="m-2">
                    <img src="../assets/images/deb.jpg" alt="Badge Débutant" width="150">
                    <p>Débutant</p>
                </div>
            <?php endif; ?>

            <?php if ($quizzes_valides >= 3): ?>
                <div class="m-2">
                    <img src="../assets/images/inter.jpg" alt="Badge Intermédiaire" width="150">
                    <p>Intermédiaire</p>
                </div>
            <?php endif; ?>

            <?php if ($quizzes_valides >= 5): ?>
                <div class="m-2">
                    <img src="../assets/images/expert.jpg" alt="Badge Expert" width="150">
                    <p>Expert</p>
                </div>
            <?php endif; ?>
        </div>

        <a href="quiz.php" class="btn btn-primary mt-4">Retour aux quiz</a>
    </div>
</div>

<script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>