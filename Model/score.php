<?php
require_once __DIR__ . '/../config.php';

class Score {
    private $iduser;
    private $idQuiz;
    private $idTest;
    private $resultatQuiz;
    private $resultatTest;

    public function __construct($iduser, $idQuiz = null, $idTest = null, $resultatQuiz = null, $resultatTest = null) {
        $this->iduser = $iduser;
        $this->idQuiz = $idQuiz;
        $this->idTest = $idTest;
        $this->resultatQuiz = $resultatQuiz;
        $this->resultatTest = $resultatTest;
    }

    // Getters
    public function getIduser() {
        return $this->iduser;
    }

    public function getIdQuiz() {
        return $this->idQuiz;
    }

    public function getIdTest() {
        return $this->idTest;
    }
    
    public function getResultatQuiz() {
        return $this->resultatQuiz;
    }
    
    public function getResultatTest() {
        return $this->resultatTest;
    }

    // Setters
    public function setIdQuiz($idQuiz) {
        $this->idQuiz = $idQuiz;
    }

    public function setIdTest($idTest) {
        $this->idTest = $idTest;
    }
    
    public function setResultatQuiz($resultatQuiz) {
        $this->resultatQuiz = $resultatQuiz;
    }
    
    public function setResultatTest($resultatTest) {
        $this->resultatTest = $resultatTest;
    }

    // Ajouter un score dans la table
    public function ajouterScore() {
        $db = config::getConnexion();

        $sql = "INSERT INTO score (iduser, idQuiz, idTest, resultatQuiz, resultatTest) 
                VALUES (:iduser, :idQuiz, :idTest, :resultatQuiz, :resultatTest)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':iduser', $this->iduser, PDO::PARAM_INT);
        $stmt->bindValue(':idQuiz', $this->idQuiz, PDO::PARAM_INT);
        $stmt->bindValue(':idTest', $this->idTest, PDO::PARAM_INT);
        $stmt->bindValue(':resultatQuiz', $this->resultatQuiz, PDO::PARAM_INT);
        $stmt->bindValue(':resultatTest', $this->resultatTest, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Mettre à jour un score existant
    public function updateScore() {
        $db = config::getConnexion();
        
        $sql = "UPDATE score SET ";
        $params = [];
        
        if ($this->resultatQuiz !== null) {
            $sql .= "resultatQuiz = :resultatQuiz";
            $params[':resultatQuiz'] = $this->resultatQuiz;
        }
        
        if ($this->resultatTest !== null) {
            if ($this->resultatQuiz !== null) {
                $sql .= ", ";
            }
            $sql .= "resultatTest = :resultatTest";
            $params[':resultatTest'] = $this->resultatTest;
        }
        
        $sql .= " WHERE iduser = :iduser";
        
        if ($this->idQuiz !== null) {
            $sql .= " AND idQuiz = :idQuiz";
            $params[':idQuiz'] = $this->idQuiz;
        }
        
        if ($this->idTest !== null) {
            $sql .= " AND idTest = :idTest";
            $params[':idTest'] = $this->idTest;
        }
        
        $params[':iduser'] = $this->iduser;
        
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Récupérer tous les scores
    public static function getAllScores() {
        $db = config::getConnexion();
        $sql = "SELECT s.idscore, s.iduser, s.idQuiz, s.idTest, s.resultatQuiz, s.resultatTest, 
                u.prenom, u.nom, q.quiz_name, t.test_name
                FROM score s
                LEFT JOIN utilisateur u ON s.iduser = u.id
                LEFT JOIN quizzes q ON s.idQuiz = q.idQuiz
                LEFT JOIN tests t ON s.idTest = t.idTest";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les scores par utilisateur
    public static function getScoresByUser($iduser) {
        $db = config::getConnexion();
        $sql = "SELECT * FROM score WHERE iduser = :iduser";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer le nombre de quiz complétés avec succès (score de 3)
    public static function getCompletedQuizCount($iduser) {
        $db = config::getConnexion();
        $sql = "SELECT COUNT(*) FROM score WHERE iduser = :iduser AND resultatQuiz = 3";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    // Récupérer le score total (3 points par quiz réussi)
    public static function getTotalScore($iduser) {
        $db = config::getConnexion();
        $sql = "SELECT COUNT(*) * 3 FROM score WHERE iduser = :iduser AND resultatQuiz = 3";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>