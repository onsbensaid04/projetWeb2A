<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Quiz.php';

class QuizController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function afficherQuiz() {
        $sql = "SELECT * FROM quizzes";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterQuiz($quiz) {
        $sql = "INSERT INTO quizzes 
            (quiz_name, questionQ1, repQ1, rep2Q1, rep3Q1, reponse_correcteQ1,
             questionQ2, repQ2, rep2Q2, rep3Q2, reponse_correcteQ2,
             questionQ3, repQ3, rep2Q3, rep3Q3, reponse_correcteQ3, id_video) 
            VALUES 
            (:quiz_name, :questionQ1, :repQ1, :rep2Q1, :rep3Q1, :reponse_correcteQ1,
             :questionQ2, :repQ2, :rep2Q2, :rep3Q2, :reponse_correcteQ2,
             :questionQ3, :repQ3, :rep2Q3, :rep3Q3, :reponse_correcteQ3, :id_video)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':quiz_name', $quiz->getQuizName());
        $stmt->bindValue(':questionQ1', $quiz->getQuestionQ1());
        $stmt->bindValue(':repQ1', $quiz->getRepQ1());
        $stmt->bindValue(':rep2Q1', $quiz->getRep2Q1());
        $stmt->bindValue(':rep3Q1', $quiz->getRep3Q1());
        $stmt->bindValue(':reponse_correcteQ1', $quiz->getReponseCorrecteQ1());

        $stmt->bindValue(':questionQ2', $quiz->getQuestionQ2());
        $stmt->bindValue(':repQ2', $quiz->getRepQ2());
        $stmt->bindValue(':rep2Q2', $quiz->getRep2Q2());
        $stmt->bindValue(':rep3Q2', $quiz->getRep3Q2());
        $stmt->bindValue(':reponse_correcteQ2', $quiz->getReponseCorrecteQ2());

        $stmt->bindValue(':questionQ3', $quiz->getQuestionQ3());
        $stmt->bindValue(':repQ3', $quiz->getRepQ3());
        $stmt->bindValue(':rep2Q3', $quiz->getRep2Q3());
        $stmt->bindValue(':rep3Q3', $quiz->getRep3Q3());
        $stmt->bindValue(':reponse_correcteQ3', $quiz->getReponseCorrecteQ3());

        $stmt->bindValue(':id_video', $quiz->getIdVideo());

        $stmt->execute();
    }

    public function traiterQuiz($iduser, $idQuiz) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $scoreQuiz = 0;

            // Récupérer les bonnes réponses du quiz
            $sql = "SELECT reponse_correcteQ1, reponse_correcteQ2, reponse_correcteQ3 FROM quizzes WHERE idQuiz = :idQuiz";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idQuiz', $idQuiz, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) return "Quiz non trouvé.";

            if (isset($_POST['repQ1']) && $_POST['repQ1'] == $result['reponse_correcteQ1']) $scoreQuiz++;
            if (isset($_POST['repQ2']) && $_POST['repQ2'] == $result['reponse_correcteQ2']) $scoreQuiz++;
            if (isset($_POST['repQ3']) && $_POST['repQ3'] == $result['reponse_correcteQ3']) $scoreQuiz++;

            $this->enregistrerScoreQuiz($iduser, $scoreQuiz);

            return $scoreQuiz;
        }
    }

    private function enregistrerScoreQuiz($iduser, $scoreQuiz) {
        $sql = "INSERT INTO score (iduser, resultatQuiz) VALUES (:iduser, :resultatQuiz)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->bindValue(':resultatQuiz', $scoreQuiz, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
