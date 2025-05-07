<?php
require_once "../../Controller/ajouterQuiz.php";

$quizController = new QuizC();



$quizController->deleteQuiz($_GET["idQuiz"]);
echo "<script>alert('suppression avec succès');</script>";
echo "<script>window.location='afficherQuiz.php';</script>";
exit();
//header('Location:read.php');
?>