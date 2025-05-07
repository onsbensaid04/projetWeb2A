<?php
require_once __DIR__ . '/../../../config.php';
session_start(); // Ajout de session_start() pour accéder à $_SESSION

if (!isset($_GET['idTest'])) {
    die('ID du test manquant.');
}

$idTest = $_GET['idTest']; // Renommé $id en $idTest pour plus de clarté
$db = config::getConnexion();

$stmt = $db->prepare("SELECT * FROM tests WHERE idTest = ?");
$stmt->execute([$idTest]);
$test = $stmt->fetch();

if (!$test) {
    die('Test introuvable.');
}

function nettoyer($str) {
    return strtolower(trim($str));
}

$score = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    $rep1 = isset($_POST['reponseT1']) ? nettoyer($_POST['reponseT1']) : '';
    $rep2 = isset($_POST['reponseT2']) ? nettoyer($_POST['reponseT2']) : '';
    $rep3 = isset($_POST['reponseT3']) ? nettoyer($_POST['reponseT3']) : '';

    if ($rep1 === nettoyer($test['reponse_correcteT1'])) $score++;
    if ($rep2 === nettoyer($test['rep_correcteT2'])) $score++;
    if ($rep3 === nettoyer($test['repon_correcteT3'])) $score++;
    
    // Enregistrer le score dans la table score
    if (isset($_SESSION['id'])) {
        $iduser = $_SESSION['id']; // ID de l'utilisateur connecté
        
        // Vérifier si l'utilisateur a déjà un enregistrement pour ce test
        $checkScore = $db->prepare("SELECT * FROM score WHERE iduser = ? AND idTest = ?");
        $checkScore->execute([$iduser, $idTest]);
        
        if ($checkScore->rowCount() > 0) {
            // Si un enregistrement existe, mettre à jour le score du test
            $updateScore = $db->prepare("UPDATE score SET resultatTest = ? WHERE iduser = ? AND idTest = ?");
            $updateScore->execute([$score, $iduser, $idTest]);
            $message = "✅ Score mis à jour dans votre profil.";
        } else {
            // Sinon, créer un nouvel enregistrement avec toutes les informations
            $insertScore = $db->prepare("INSERT INTO score (iduser, idTest, resultatTest) VALUES (?, ?, ?)");
            $insertScore->execute([$iduser, $idTest, $score]);
            $message = "✅ Score enregistré dans votre profil.";
        }
    } else {
        $message = "⚠️ Utilisateur non connecté. Votre score ne sera pas enregistré.";
    }
}
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
    <title><?= htmlspecialchars($test['test_name']) ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff3e0; /* Beige/orangé clair */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .test-container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            margin-top: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            color: #FF5722;
            text-align: center;
            margin-bottom: 30px;
        }

        .question {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            background-color: #FF5722;
            border: none;
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .btn-secondary {
            background-color: #ffa726;
            border: none;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #fb8c00;
        }

        .alert-success {
            background-color: #ffe0b2;
            color: #e65100;
            border: 1px solid #ff9800;
        }

        .alert-danger {
            background-color: #ffcdd2;
            color: #c62828;
            border: 1px solid #e53935;
        }

        .alert-info {
            background-color: #ffe0b2;
            color: #e65100;
            border: 1px solid #ff9800;
        }

        a {
            color: #FF5722;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
                       
						<ul class="submenu">
						<li><a href="ajouterVideo.php">Add video</a></li>
                                <li><a href="afficherVideo.php">Video List</a></li>
                                <li><a href="ajouter_pdf.php">Add PDF</a></li>
								<li><a href="Aafficherpdf.php">PDF List</a></li>
						</ul>
					</li>
                    <li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-library"></span><span class="mtext">Exams</span>
						</a>
						<ul class="submenu">
							<li><a href="ajouterQuiz.php">addquiz</a></li>
							<li><a href="afficherQuiz.php">Quiz List</a></li>
							<li><a href="ajouterTest.php">Add Test</a></li>
						<li><a href="afficherTest.php">Test List</a></li>
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

<div class="container">
    <div class="test-container">
        <h2><?= htmlspecialchars($test['test_name']) ?></h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <?php if ($score !== null): ?>
            <div class="alert alert-success">✅ Votre score : <strong><?= $score ?>/3</strong></div>
            
            <?php if ($score >= 2): ?>
                <div class="alert alert-info mt-2">
                    ✅ Score suffisant ! <a href="Quiz.php?idTest=<?= $test['idTest'] ?>">Voir les quiz</a>

                    
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    ❌ Vous devez avoir au moins 2/3 pour accéder au quiz.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST">
            <!-- Question 1 -->
            <div class="question">
                <h5><?= htmlspecialchars($test['questionT1']) ?></h5>
                <input type="text" name="reponseT1" class="form-control" required>
            </div>

            <!-- Question 2 -->
            <div class="question">
                <h5><?= htmlspecialchars($test['questionT2']) ?></h5>
                <input type="text" name="reponseT2" class="form-control" required>
            </div>

            <!-- Question 3 -->
            <div class="question">
                <h5><?= htmlspecialchars($test['questionT3']) ?></h5>
                <input type="text" name="reponseT3" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">✅ Valider mes réponses</button>
        </form>

        <div class="text-center">
            <a href="Test.php">← Retour aux tests</a>
        </div>
    </div>
</div>
<br></br>
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
<script src="../assets/js/jquery-2.1.0.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
