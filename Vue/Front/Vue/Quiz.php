<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Controller/TestC.php';
session_start();

$_SESSION['id'] = 2;
$db = config::getConnexion();

$id = isset($_GET['idTest']) ? intval($_GET['idTest']) : null;

if (!$id) {
    echo "Aucun test sélectionné.";
    exit;
}

// Récupérer les quiz liés à ce test
$stmt = $db->prepare("SELECT * FROM quizzes WHERE idTest = :idTest");
$stmt->execute(['idTest' => $id]);
$listeQuiz = $stmt->fetchAll(); // 🔧 ici on utilise $listeQuiz directement

// Pour afficher un nom de test si besoin
$testC = new TestC();
$test = $testC->getPdfById($id);

// Récupérer un éventuel score
$score = isset($_GET['score']) ? intval($_GET['score']) : null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">
    <!-- Lien Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Lien Google Fonts (Raleway) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Raleway', 'Poppins', sans-serif;
            background-color: #fff3e0; /* Fond très clair */
            color: #FF5722; /* Violet doux */
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            color: #ffa726; /* Violet foncé */
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 15px;
            background: #fff;
            padding: 25px;
            box-shadow: 0 6px 15px hsla(38, 87.40%, 49.80%, 0.90);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(230, 200, 65, 0.88);
        }

        .card h4 {
            color:hsla(38, 87.40%, 49.80%, 0.90); /* Violet moyen */
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(45deg,rgba(241, 220, 26, 0.94),rgb(245, 150, 9));
            border: none;
            color: #fff;
            padding: 12px 25px;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            transition: background 0.4s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg,rgba(241, 220, 26, 0.94),rgb(245, 150, 9));
        }

        .alert-success {
            background: linear-gradient(rgba(241, 220, 26, 0.94),rgb(245, 150, 9));
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
        }
    </style>
</head>

<body>
<header class="header-area header-sticky background-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <a href="index.html" class="logo">Startup<em> Academy</em></a>
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="index.html">Home</a></li>
                        <li class="scroll-to-section"><a href="classes.html">Classes</a></li>
                        <li class="scroll-to-section"><a href="schedules.html">Schedules</a></li>
                        <li class="has-sub">
                            <a href="javascript:void(0)">Cours</a>
                            <ul class="sub-menu">
                                <li><a href="video.html">Videos</a></li>
                                <li><a href="pdf.html">PDF</a></li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a href="javascript:void(0)">Exams</a>
                            <ul class="sub-menu">
                                <li><a href="test.html">Test</a></li>
                                <li><a href="quiz.html">Quiz</a></li>
                            </ul>
                        </li>
                        <li class="scroll-to-section"><a href="#contact-us">Contact</a></li>
                        <li class="main-button"><a href="#">Sign Up</a></li>
                    </ul>
                    <a class='menu-trigger'><span>Menu</span></a>
                </nav>
            </div>
        </div>
    </div>
</header>
<br></br>
<br></br>
<br></br>
    <div class="container my-5">
        <!-- Affichage du score -->
        <?php if (isset($score)): ?>
            <div class="alert alert-success">
                ✅ Bravo ! Votre score : <strong><?= htmlspecialchars($score) ?>/3</strong>
            </div>
        <?php endif; ?>

        <!-- Titre -->
        <div class="section-title">
            <h2>Découvrez nos Quiz</h2>
        </div>

        <!-- Cartes Quiz -->
        <div class="row">
            <?php foreach ($listeQuiz as $quiz): ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="card m-3 p-3 w-100">
                        <h4><?= htmlspecialchars($quiz['quiz_name']) ?></h4>
                        <a href="detailsQuiz.php?idQuiz=<?= $quiz['idQuiz'] ?>" class="btn btn-primary mt-auto">Découvrez Plus</a>
                        

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2020 Training Studio
                    
                    - Designed by <a rel="nofollow" href="https://templatemo.com" class="tm-text-link" target="_parent">TemplateMo</a></p>
                    
                    <!-- You shall support us a little via PayPal to info@templatemo.com -->
                    
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
