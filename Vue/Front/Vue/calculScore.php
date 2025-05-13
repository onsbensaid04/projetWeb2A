<?php
session_start();

require_once __DIR__ . '/../../../Controller/afficherQuiz.php';
require_once __DIR__ . '/../../../Model/score.php';

$idUser = $_SESSION['id'];
$scoreC = new Score($idUser); // ✅ tu passes bien l'ID utilisateur

// Récupère tous les résultats de l'utilisateur
$scores = $scoreC->getScoresByUser($idUser);

$quizReussis = 0;

foreach ($scores as $score) {
    // Vérifie si le quiz est validé (3 bonnes réponses)
    if ($score['resultatquiz'] == 3) {
        $quizReussis++;
    }
}

// Calcul du score total
$scoreTotal = $quizReussis * 3;

// Déterminer le badge
if ($quizReussis == 5) {
    $badge = "Expert";
} elseif ($quizReussis >= 3) {
    $badge = "Intermédiaire";
} elseif ($quizReussis >= 2) {
    $badge = "Débutant";
} else {
    $badge = "Aucun badge";
}

// Redirection vers la page de badge avec paramètres
header("Location: badges.php?score=$scoreTotal&badge=$badge");
exit;
?>
