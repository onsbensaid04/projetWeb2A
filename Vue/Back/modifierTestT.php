<?php
require_once("../../Model/Test.php");
require_once("../../Controller/TestC.php");
require_once '../../config.php';

if (
    isset($_POST['idTest']) &&
    isset($_POST['test_name']) &&
    isset($_POST['questionT1']) && isset($_POST['reponseT1']) && isset($_POST['reponse_correcteT1']) &&
    isset($_POST['questionT2']) && isset($_POST['repT2']) && isset($_POST['rep_correcteT2']) &&
    isset($_POST['questionT3']) && isset($_POST['reponT3']) && isset($_POST['repon_correcteT3'])
) {
    $data = [
        'idTest' => $_POST['idTest'],
        'test_name' => $_POST['test_name'],
        'questionT1' => $_POST['questionT1'],
        'reponseT1' => $_POST['reponseT1'],
        'reponse_correcteT1' => $_POST['reponse_correcteT1'],
        'questionT2' => $_POST['questionT2'],
        'repT2' => $_POST['repT2'],
        'rep_correcteT2' => $_POST['rep_correcteT2'],
        'questionT3' => $_POST['questionT3'],
        'reponT3' => $_POST['reponT3'],
        'repon_correcteT3' => $_POST['repon_correcteT3']
    ];

    $testC = new TestC();
    $testC->modifierTest($data);

    // Redirection
    echo "<script>window.location.href = 'afficherTest.php';</script>";
    exit;
} else {
    echo "Données manquantes pour la modification du test.";
}
