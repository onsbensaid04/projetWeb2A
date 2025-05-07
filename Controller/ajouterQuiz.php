<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Quiz.php';

class QuizC {
    public function ajouterQuiz(Quiz $quiz) {
        $sql = "INSERT INTO quizzes 
            (quiz_name, questionQ1, option1, option2, option3, correct_option1,
             questionQ2, op1, op2, op3, correct_op2,
             questionQ3, opt1, opt2, opt3, correct_opt3, id_video, idTest)
            VALUES 
            (:quiz_name, :questionQ1, :option1, :option2, :option3, :correct_option1,
             :questionQ2, :op1, :op2, :op3, :correct_op2,
             :questionQ3, :opt1, :opt2, :opt3, :correct_opt3, :id_video, :idTest)";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);

            // Lier les valeurs des paramètres avec les attributs de l'objet Quiz
            $query->bindValue(':quiz_name', $quiz->getQuizName());
            $query->bindValue(':questionQ1', $quiz->getQuestionQ1());
            $query->bindValue(':option1', $quiz->getOption1());
            $query->bindValue(':option2', $quiz->getOption2());
            $query->bindValue(':option3', $quiz->getOption3());
            $query->bindValue(':correct_option1', $quiz->getCorrectOption1());

            $query->bindValue(':questionQ2', $quiz->getQuestionQ2());
            $query->bindValue(':op1', $quiz->getOp1());
            $query->bindValue(':op2', $quiz->getOp2());
            $query->bindValue(':op3', $quiz->getOp3());
            $query->bindValue(':correct_op2', $quiz->getCorrectOp2());

            $query->bindValue(':questionQ3', $quiz->getQuestionQ3());
            $query->bindValue(':opt1', $quiz->getOpt1());
            $query->bindValue(':opt2', $quiz->getOpt2());
            $query->bindValue(':opt3', $quiz->getOpt3());
            $query->bindValue(':correct_opt3', $quiz->getCorrectOpt3());

            $query->bindValue(':id_video', $quiz->getIdVideo());
            $query->bindValue(':idTest', $quiz->getIdTest());

            $query->execute();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function deleteQuiz($idQuiz) {
        // Requête pour supprimer un quiz
        $sql = "DELETE FROM quizzes WHERE idQuiz = :idQuiz";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        
        // Lier l'id du quiz
        $stmt->bindValue(':idQuiz', $idQuiz, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function modifierQuiz($data) {
        $sql = "UPDATE quizzes SET 
                    quiz_name = :quiz_name,
                    questionQ1 = :questionQ1,
                    option1 = :option1,
                    option2 = :option2,
                    option3 = :option3,
                    correct_option1 = :correct_option1,
                    questionQ2 = :questionQ2,
                    op1 = :op1,
                    op2 = :op2,
                    op3 = :op3,
                    correct_op2 = :correct_op2,
                    questionQ3 = :questionQ3,
                    opt1 = :opt1,
                    opt2 = :opt2,
                    opt3 = :opt3,
                    correct_opt3 = :correct_opt3,
                    idTest = :idTest
                WHERE idQuiz = :idQuiz";

        $db = config::getConnexion();
        $stmt = $db->prepare($sql);

        // Lier les données du formulaire
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
    }

    public function getQuizById($id) {
        $sql = "SELECT * FROM quizzes WHERE idQuiz = :idQuiz";
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':idQuiz', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function submitQuiz() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les réponses soumises
            $score = 0;
            if ($_POST['reponseQ1'] == 'option1') $score++; // Exemple de comparaison
            if ($_POST['reponseQ2'] == 'option2') $score++; // Exemple de comparaison
            if ($_POST['reponseQ3'] == 'option3') $score++; // Exemple de comparaison
            
            // Sauvegarder le score dans la base de données
            $this->saveScore($_SESSION['user_id'], $score);
            
            // Redirection ou affichage du score
            echo "Votre score : " . $score . "/3";
            header("Location: results.php?score=" . $score);
            exit;
        }
    }
    
    private function saveScore($user_id, $score) {
        // Enregistrer le score dans la base de données
        $db = config::getConnexion(); // Connexion à la base de données
        $sql = "INSERT INTO score (idUser, score) VALUES (:idUser, :score)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':score', $score, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>


