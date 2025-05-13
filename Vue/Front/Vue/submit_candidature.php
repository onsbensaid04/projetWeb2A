<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../controller/candidature_con.php';
require_once __DIR__ . '/../../../model/candidature.php';

session_start();
// $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
$user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 1;

if (
    isset($_POST['offre_id']) && intval($_POST['offre_id']) > 0 &&
    isset($_POST['lettre_motivation'])
) {
    $offre_id = intval($_POST['offre_id']);
    $lettre_motivation = trim($_POST['lettre_motivation']);
    $candidatureCon = new CandidatureCon();
    $existing = $candidatureCon->getByUserAndOffre($user_id, $offre_id);
    if (!$existing) {
        $now = date('Y-m-d');
        $candidature = new Candidature(
            null,
            $user_id,
            $offre_id,
            $lettre_motivation,
            $now,
            'en attente'
        );
        $candidatureCon->add($candidature);
        header('Location: offre_emploi.php?success=1');
        exit;
    } else {
        header('Location: offre_emploi.php?already=1');
        exit;
    }
} else {
    header('Location: offre_emploi.php?error=1');
    exit;
}
