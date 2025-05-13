<?php
require_once("../../Model/Quiz.php");
require_once("../../Controller/ajouterQuiz.php");
require_once '../../config.php'; 

if (
    isset($_POST['idQuiz']) &&
    isset($_POST['quiz_name']) &&
    isset($_POST['questionQ1']) && isset($_POST['option1']) && isset($_POST['option2']) && isset($_POST['option3']) && isset($_POST['correct_option1']) &&
    isset($_POST['questionQ2']) && isset($_POST['op1']) && isset($_POST['op2']) && isset($_POST['op3']) && isset($_POST['correct_op2']) &&
    isset($_POST['questionQ3']) && isset($_POST['opt1']) && isset($_POST['opt2']) && isset($_POST['opt3']) && isset($_POST['correct_opt3'])
) {
    $data = [
        'idQuiz' => $_POST['idQuiz'],
        'quiz_name' => $_POST['quiz_name'],
        'questionQ1' => $_POST['questionQ1'],
        'option1' => $_POST['option1'],
        'option2' => $_POST['option2'],
        'option3' => $_POST['option3'],
        'correct_option1' => $_POST['correct_option1'],
        'questionQ2' => $_POST['questionQ2'],
        'op1' => $_POST['op1'],
        'op2' => $_POST['op2'],
        'op3' => $_POST['op3'],
        'correct_op2' => $_POST['correct_op2'],
        'questionQ3' => $_POST['questionQ3'],
        'opt1' => $_POST['opt1'],
        'opt2' => $_POST['opt2'],
        'opt3' => $_POST['opt3'],
        'correct_opt3' => $_POST['correct_opt3'],
        'idTest' => $_POST['idTest']


    ];

    $quizC = new QuizC();
    $quizC->modifierQuiz($data);

    // Redirection après modification
    echo "<script>window.location.href = 'afficherQuiz.php';</script>";
    exit;
} else {
    echo "Données manquantes pour la modification du quiz.";
}
