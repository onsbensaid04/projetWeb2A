<?php
require_once __DIR__ . '/../config.php';

class BadgeManager {
    private $db;
    
    public function __construct() {
        $this->db = config::getConnexion();
    }
    
    // Vérifie et attribue les badges après qu'un utilisateur a complété un quiz
    public function checkAndAwardBadges($userId) {
        // Compter le nombre de quiz complétés par l'utilisateur (avec score de 3)
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM score 
            WHERE iduser = ? AND resultatquiz = 3
        ");
        $stmt->execute([$userId]);
        $completedQuizCount = $stmt->fetchColumn();
        
        // Récupérer les badges que l'utilisateur possède déjà
        $stmt = $this->db->prepare("
            SELECT b.id, b.nom, b.nb_quiz_requis
            FROM badges b
            JOIN user_badges ub ON b.id = ub.badge_id
            WHERE ub.iduser = ?
        ");
        $stmt->execute([$userId]);
        $existingBadges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $existingBadgeIds = array_column($existingBadges, 'id');
        
        // Récupérer tous les badges disponibles
        $stmt = $this->db->prepare("
            SELECT id, nom, nb_quiz_requis 
            FROM badges 
            WHERE nb_quiz_requis <= ?
            ORDER BY nb_quiz_requis DESC
        ");
        $stmt->execute([$completedQuizCount]);
        $eligibleBadges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $newBadges = [];
        
        // Attribuer les badges non possédés
        foreach ($eligibleBadges as $badge) {
            if (!in_array($badge['id'], $existingBadgeIds)) {
                $this->awardBadge($userId, $badge['id']);
                $newBadges[] = $badge;
            }
        }
        
        return $newBadges;
    }
    
    // Attribue un badge à un utilisateur
    private function awardBadge($userId, $badgeId) {
        $stmt = $this->db->prepare("
            INSERT INTO user_badges (iduser, badge_id) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $badgeId]);
    }
    
    // Récupère tous les badges d'un utilisateur
    public function getUserBadges($userId) {
        $stmt = $this->db->prepare("
            SELECT b.id, b.nom, b.description, b.image_path, b.nb_quiz_requis, ub.date_obtention 
            FROM badges b
            JOIN user_badges ub ON b.id = ub.badge_id
            WHERE ub.iduser = ?
            ORDER BY b.nb_quiz_requis DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupère le badge de plus haut niveau d'un utilisateur
    public function getHighestBadge($userId) {
        $stmt = $this->db->prepare("
            SELECT b.* 
            FROM badges b
            JOIN user_badges ub ON b.id = ub.badge_id
            WHERE ub.iduser = ?
            ORDER BY b.nb_quiz_requis DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupère le nombre total de quiz réussis par un utilisateur
    public function getCompletedQuizCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM score 
            WHERE iduser = ? AND resultatquiz = 3
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
?>