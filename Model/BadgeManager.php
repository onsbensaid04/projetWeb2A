<?php
require_once __DIR__ . '/../config.php';

class BadgeManager {
    private $db;
    
    public function __construct() {
        $this->db = config::getConnexion();
        
        // Vérifier si la table badges existe, sinon la créer
        $this->checkTables();
    }
    
    // Vérifier et créer les tables si nécessaire
    private function checkTables() {
        try {
            // Vérifier si la table badges existe
            $stmt = $this->db->query("SHOW TABLES LIKE 'badges'");
            $badgesTableExists = $stmt->rowCount() > 0;
            
            if (!$badgesTableExists) {
                // Créer la table badges
                $this->db->exec("
                    CREATE TABLE IF NOT EXISTS badges (
                      id INT PRIMARY KEY AUTO_INCREMENT,
                      nom VARCHAR(50) NOT NULL,
                      description TEXT,
                      image_path VARCHAR(255) NOT NULL,
                      nb_quiz_requis INT NOT NULL
                    )
                ");
                
                // Insérer les badges prédéfinis
                $this->db->exec("
                    INSERT INTO badges (nom, description, image_path, nb_quiz_requis) VALUES 
                    ('Débutant', 'A complété 2 quiz', 'badges/debutant.png', 2),
                    ('Intermédiaire', 'A complété 3 quiz', 'badges/intermediaire.png', 3),
                    ('Expert', 'A complété tous les 5 quiz', 'badges/expert.png', 5)
                ");
            }
            
            // Vérifier si la table user_badges existe
            $stmt = $this->db->query("SHOW TABLES LIKE 'user_badges'");
            $userBadgesTableExists = $stmt->rowCount() > 0;
            
            if (!$userBadgesTableExists) {
                // Créer la table user_badges
                $this->db->exec("
                    CREATE TABLE IF NOT EXISTS user_badges (
                      id INT PRIMARY KEY AUTO_INCREMENT,
                      iduser INT NOT NULL,
                      badge_id INT NOT NULL,
                      date_obtention DATETIME DEFAULT CURRENT_TIMESTAMP
                    )
                ");
            }
        } catch (PDOException $e) {
            // En cas d'erreur, on log simplement l'erreur
            error_log("Erreur lors de la vérification/création des tables: " . $e->getMessage());
        }
    }
    
    // Le reste du code reste inchangé...
    
    // Vérifie et attribue les badges après qu'un utilisateur a complété un quiz
    public function checkAndAwardBadges($userId) {
        try {
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
                    $this->checkAndAwardBadges($userId, $badge['id']);
                    $newBadges[] = $badge;
                }
            }
            
            return $newBadges;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification des badges: " . $e->getMessage());
            return [];
        }
    }
    
    // Le reste des méthodes avec des try/catch similaires...
    public function attribuerBadges($userId, $completedQuizCount) {
        // Sécurité : s'assurer que c'est bien un nombre
        if (!is_numeric($completedQuizCount)) {
            return [];
        }
    
        // Préparer la requête pour les badges éligibles
        $stmt = $this->db->prepare("
            SELECT id, nom, nb_quiz_requis 
            FROM badges 
            WHERE nb_quiz_requis <= ?
            ORDER BY nb_quiz_requis DESC
        ");
        $stmt->execute([$completedQuizCount]);
    
        $newBadges = [];
    
        // Boucle ligne par ligne (PAS fetchAll)
        while ($badge = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Vérifier si l'utilisateur a déjà ce badge
            $checkStmt = $this->db->prepare("SELECT 1 FROM user_badges WHERE user_id = ? AND badge_id = ? LIMIT 1");
            $checkStmt->execute([$userId, $badge['id']]);
            $hasBadge = $checkStmt->fetchColumn();
    
            // Si l'utilisateur ne l'a pas encore, on l'ajoute
            if (!$hasBadge) {
                $insertStmt = $this->db->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
                $insertStmt->execute([$userId, $badge['id']]);
                $newBadges[] = $badge;
            }
        }
    
        return $newBadges;
    }
    
}
?>