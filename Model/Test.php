<?php

class Test {
    private $test_name;
    private $questionT1;
    private $reponseT1;
    private $reponse_correcteT1;

    private $questionT2;
    private $reponseT2;
    private $reponse_correcteT2;

    private $questionT3;
    private $reponseT3;
    private $reponse_correcteT3;

    private $idTest;
    private $id_pdf; // 🔹 Ajout de l'attribut id_pdf

    // Constructeur
    public function __construct(
        $test_name, $questionT1, $reponseT1, $reponse_correcteT1,
        $questionT2, $reponseT2, $reponse_correcteT2,
        $questionT3, $reponseT3, $reponse_correcteT3,
        $idTest, $id_pdf // 🔹 Ajout de id_pdf ici
    ) {
        $this->test_name = $test_name;

        $this->questionT1 = $questionT1;
        $this->reponseT1 = $reponseT1;
        $this->reponse_correcteT1 = $reponse_correcteT1;

        $this->questionT2 = $questionT2;
        $this->reponseT2 = $reponseT2;
        $this->reponse_correcteT2 = $reponse_correcteT2;

        $this->questionT3 = $questionT3;
        $this->reponseT3 = $reponseT3;
        $this->reponse_correcteT3 = $reponse_correcteT3;

        $this->idTest = $idTest;
        $this->id_pdf = $id_pdf; // 🔹 Initialisation de id_pdf
    }

    // Getters
    public function getTestName() { return $this->test_name; }

    public function getQuestionT1() { return $this->questionT1; }
    public function getReponseT1() { return $this->reponseT1; }
    public function getReponseCorrecteT1() { return $this->reponse_correcteT1; }

    public function getQuestionT2() { return $this->questionT2; }
    public function getReponseT2() { return $this->reponseT2; }
    public function getReponseCorrecteT2() { return $this->reponse_correcteT2; }

    public function getQuestionT3() { return $this->questionT3; }
    public function getReponseT3() { return $this->reponseT3; }
    public function getReponseCorrecteT3() { return $this->reponse_correcteT3; }

    public function getIdTest() { return $this->idTest; }

    public function getIdPdf() { return $this->id_pdf; } // 🔹 Getter pour id_pdf

    // Setters
    public function setTestName($test_name) { $this->test_name = $test_name; }

    public function setQuestionT1($questionT1) { $this->questionT1 = $questionT1; }
    public function setReponseT1($reponseT1) { $this->reponseT1 = $reponseT1; }
    public function setReponseCorrecteT1($reponse_correcteT1) { $this->reponse_correcteT1 = $reponse_correcteT1; }

    public function setQuestionT2($questionT2) { $this->questionT2 = $questionT2; }
    public function setReponseT2($reponseT2) { $this->reponseT2 = $reponseT2; }
    public function setReponseCorrecteT2($reponse_correcteT2) { $this->reponse_correcteT2 = $reponse_correcteT2; }

    public function setQuestionT3($questionT3) { $this->questionT3 = $questionT3; }
    public function setReponseT3($reponseT3) { $this->reponseT3 = $reponseT3; }
    public function setReponseCorrecteT3($reponse_correcteT3) { $this->reponse_correcteT3 = $reponse_correcteT3; }

    public function setIdTest($idTest) { $this->idTest = $idTest; }

    public function setIdPdf($id_pdf) { $this->id_pdf = $id_pdf; } // 🔹 Setter pour id_pdf

    // Méthode pour obtenir le score total
    public function getScore() {
        $score = 0;
        if ($this->reponseT1 === $this->reponse_correcteT1) $score++;
        if ($this->reponseT2 === $this->reponse_correcteT2) $score++;
        if ($this->reponseT3 === $this->reponse_correcteT3) $score++;
        return $score;
    }

    // Méthode pour obtenir le nombre de questions
    public function getNombreQuestions() {
        return 3;
    }

    // Méthode pour afficher les résultats détaillés
    public function getResultats() {
        return [
            [
                'question' => $this->questionT1,
                'votre_reponse' => $this->reponseT1,
                'bonne_reponse' => $this->reponse_correcteT1,
                'correct' => $this->reponseT1 === $this->reponse_correcteT1
            ],
            [
                'question' => $this->questionT2,
                'votre_reponse' => $this->reponseT2,
                'bonne_reponse' => $this->reponse_correcteT2,
                'correct' => $this->reponseT2 === $this->reponse_correcteT2
            ],
            [
                'question' => $this->questionT3,
                'votre_reponse' => $this->reponseT3,
                'bonne_reponse' => $this->reponse_correcteT3,
                'correct' => $this->reponseT3 === $this->reponse_correcteT3
            ]
        ];
    }
}

?>
