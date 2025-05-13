<?php
class Quiz {
    private $quiz_name;
    private $questionQ1;
    private $option1;
    private $option2;
    private $option3;
    private $correct_option1;

    private $questionQ2;
    private $op1;
    private $op2;
    private $op3;
    private $correct_op2;

    private $questionQ3;
    private $opt1;
    private $opt2;
    private $opt3;
    private $correct_opt3;

    private $id_video;
    private $idTest; // 🆕 Attribut ajouté

    public function __construct(
        $quiz_name, $questionQ1, $option1, $option2, $option3, $correct_option1,
        $questionQ2, $op1, $op2, $op3, $correct_op2,
        $questionQ3, $opt1, $opt2, $opt3, $correct_opt3,
        $id_video, $idTest // 🆕 Paramètre ajouté
    ) {
        $this->quiz_name = $quiz_name;
        $this->questionQ1 = $questionQ1;
        $this->option1 = $option1;
        $this->option2 = $option2;
        $this->option3 = $option3;
        $this->correct_option1 = $correct_option1;

        $this->questionQ2 = $questionQ2;
        $this->op1 = $op1;
        $this->op2 = $op2;
        $this->op3 = $op3;
        $this->correct_op2 = $correct_op2;

        $this->questionQ3 = $questionQ3;
        $this->opt1 = $opt1;
        $this->opt2 = $opt2;
        $this->opt3 = $opt3;
        $this->correct_opt3 = $correct_opt3;

        $this->id_video = $id_video;
        $this->idTest = $idTest; // 🆕 Initialisation
    }

    // Getters
    public function getQuizName() { return $this->quiz_name; }
    public function getQuestionQ1() { return $this->questionQ1; }
    public function getOption1() { return $this->option1; }
    public function getOption2() { return $this->option2; }
    public function getOption3() { return $this->option3; }
    public function getCorrectOption1() { return $this->correct_option1; }

    public function getQuestionQ2() { return $this->questionQ2; }
    public function getOp1() { return $this->op1; }
    public function getOp2() { return $this->op2; }
    public function getOp3() { return $this->op3; }
    public function getCorrectOp2() { return $this->correct_op2; }

    public function getQuestionQ3() { return $this->questionQ3; }
    public function getOpt1() { return $this->opt1; }
    public function getOpt2() { return $this->opt2; }
    public function getOpt3() { return $this->opt3; }
    public function getCorrectOpt3() { return $this->correct_opt3; }

    public function getIdVideo() { return $this->id_video; }
    public function getIdTest() { return $this->idTest; } // 🆕 Getter
}
?>
