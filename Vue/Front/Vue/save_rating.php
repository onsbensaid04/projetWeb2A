<?php
require_once(__DIR__ . "/../../../config.php");

session_start();

if (!isset($_SESSION['user']['id'])) {
    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    header('Location: login.php');
    exit;
}

// Récupère les données envoyées via le formulaire
if (!isset($_POST['note'], $_POST['id_video'])) {
    echo "Données manquantes.";
    exit;
}

$id_utilisateur = $_SESSION['user']['id'];  // Utilisateur connecté
$id_video = (int) $_POST['id_video'];  // ID de la vidéo
$rating = (int) $_POST['note'];  // Note donnée par l'utilisateur
$commentaire = $_POST['commentaire'] ?? null;  // Le commentaire de l'utilisateur

// Connexion à la base de données
$pdo = config::getConnexion();

// Vérifie si l'utilisateur a déjà noté cette vidéo
$stmt = $pdo->prepare("SELECT note FROM video_rating WHERE id_utilisateur = :id_utilisateur AND id_video = :id_video ORDER BY date_avis DESC LIMIT 1");
$stmt->execute([
    'id_utilisateur' => $id_utilisateur,
    'id_video' => $id_video
]);

$existing = $stmt->fetch(PDO::FETCH_ASSOC);  // Si une ligne existe, $existing contiendra la note existante

// Si la note existe déjà, on la met à jour, sinon on insère une nouvelle note
if ($existing) {
    // Si la note a changé, on met à jour la note pour toutes les entrées existantes
    if ($existing['note'] != $rating) {
        // Met à jour la note dans toutes les lignes de la table pour cette vidéo et cet utilisateur
        $stmt = $pdo->prepare("UPDATE video_rating SET note = :note WHERE id_utilisateur = :id_utilisateur AND id_video = :id_video");
        $stmt->execute([
            'note' => $rating,
            'id_utilisateur' => $id_utilisateur,
            'id_video' => $id_video
        ]);
    }

    // Ajouter un nouveau commentaire avec la même note
    $stmt = $pdo->prepare("INSERT INTO video_rating (id_utilisateur, id_video, note, commentaire) VALUES (:id_utilisateur, :id_video, :note, :commentaire)");
    $stmt->execute([
        'id_utilisateur' => $id_utilisateur,
        'id_video' => $id_video,
        'note' => $rating,
        'commentaire' => $commentaire
    ]);
    echo "Votre commentaire a été ajouté avec succès.";
} else {
    // Première fois que l'utilisateur note la vidéo : on insère la première note et commentaire
    $stmt = $pdo->prepare("INSERT INTO video_rating (id_utilisateur, id_video, note, commentaire) VALUES (:id_utilisateur, :id_video, :note, :commentaire)");
    $stmt->execute([
        'id_utilisateur' => $id_utilisateur,
        'id_video' => $id_video,
        'note' => $rating,
        'commentaire' => $commentaire
    ]);
    echo "Votre note et commentaire ont été enregistrés avec succès.";
}

// Redirection après l'enregistrement des données
header('Location: voir_video.php?id_video=' . $id_video);  // Redirige vers la page de la vidéo
exit;
?>
