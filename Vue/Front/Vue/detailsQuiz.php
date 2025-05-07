<?php
require_once __DIR__ . '/../../../config.php';
session_start();

if (!isset($_GET['idQuiz'])) {
    die('ID du quiz manquant.');
}

$idQuiz = $_GET['idQuiz'];
$db = config::getConnexion();

// Récupérer les détails du quiz
$stmt = $db->prepare("SELECT * FROM quizzes WHERE idQuiz = ?");
$stmt->execute([$idQuiz]);
$quiz = $stmt->fetch();

if (!$quiz) {
    die('Quiz introuvable.');
}

// Définir la variable $score à 0 au départ, même si aucune réponse n'est soumise
$score = 0;
$message = '';

// Si une réponse est soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie s'il faut rediriger vers la page des badges
    if (isset($_POST['voir_badge'])) {
        header('Location: badges.php');
        exit;
    }

    // Vérifie les réponses
    $reponseQ1 = $_POST['q1'] ?? '';
    $reponseQ2 = $_POST['q2'] ?? '';
    $reponseQ3 = $_POST['q3'] ?? '';

    if ($reponseQ1 === $quiz['correct_option1']) $score++;
    if ($reponseQ2 === $quiz['correct_op2']) $score++;
    if ($reponseQ3 === $quiz['correct_opt3']) $score++;
    // Enregistrer le score dans la table score
    if (isset($_SESSION['id'])) {
        $iduser = $_SESSION['id']; // ID de l'utilisateur connecté
        
        // Vérifier si l'utilisateur a déjà un enregistrement pour ce quiz
        $checkScore = $db->prepare("SELECT * FROM score WHERE iduser = ? AND idQuiz = ?");
        $checkScore->execute([$iduser, $idQuiz]);
        
        if ($checkScore->rowCount() > 0) {
            // Si un enregistrement existe, mettre à jour le score
            $updateScore = $db->prepare("UPDATE score SET resultatQuiz = ? WHERE iduser = ? AND idQuiz = ?");
            $updateScore->execute([$score, $iduser, $idQuiz]);
        } else {
            // Sinon, créer un nouvel enregistrement avec toutes les informations
            $insertScore = $db->prepare("INSERT INTO score (iduser, idQuiz, resultatQuiz) VALUES (?, ?, ?)");
            $insertScore->execute([$iduser, $idQuiz, $score]);
        }
        
    }
    


    // Ajouter +1 point UNIQUEMENT si l'utilisateur est connecté ET a un score parfait (3/3)
    if (isset($_SESSION['id']) && $score === 3) {
        $iduser = $_SESSION['id']; 

        // Vérifier si l'utilisateur a déjà une ligne dans user_badges
        $check = $db->prepare("SELECT * FROM user_badges WHERE id = ?");
        $check->execute([$iduser]);

        if ($check->rowCount() > 0) {
            // S'il existe, on incrémente quizzes_valides de +1
            $update = $db->prepare("UPDATE user_badges SET quizzes_valides = quizzes_valides + 1 WHERE id = ?");
            $update->execute([$iduser]);
        } else {
            // Sinon, on crée une nouvelle ligne avec 1 quiz validé
            $insert = $db->prepare("INSERT INTO user_badges (id, quizzes_valides) VALUES (?, 1)");
            $insert->execute([$iduser]);
        }

        $message = "✅ Quiz validé ! +1 point ajouté à votre compte.";
    } elseif (!isset($_SESSION['id'])) {
        $message = "Erreur : utilisateur non connecté.";
    } elseif ($score < 3) {
        $message = "Vous devez obtenir un score parfait (3/3) pour valider ce quiz.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
    .header-area {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 60px;
      background-color: #830940; /* ta couleur magenta-arroxeado */
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    body {
      margin: 0;
      padding-top: 60px; /* pour ne pas cacher le contenu */
      font-family: Arial, sans-serif;
    }
  </style>

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Détails du Quiz</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<!-- Lien Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Lien Google Fonts (Raleway) -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color:rgb(247, 246, 246); /* Beige/orangé clair */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        h1 {
            color: #FF5722;
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            font-weight: bold;
            color: #444;
        }

        .form-check {
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #FF5722;
            border: none;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .btn-secondary {
            background-color: #ffa726;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #fb8c00;
        }

        .alert-success {
            background-color: #ffe0b2;
            color: #e65100;
            border: 1px solid #ff9800;
        }

        .alert-warning {
            background-color: #fff3e0;
            color: #ff5722;
            border: 1px solid #ff9800;
        }

        .alert-info {
            background-color: #ffe0b2;
            color: #e65100;
            border: 1px solid #ff9800;
        }
    </style>
</head>
    </body>
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
        <h1>Quiz - Détails</h1>

        <!-- Affichage du message et du score -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= ($score === 3) ? 'success' : 'warning' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <?php if ($score !== 0 || isset($_POST['q1'])): ?>
            <div class="alert alert-info">
                Votre score : <strong><?= $score ?>/3</strong>
            </div>
        <?php endif; ?>

        <!-- Formulaire pour répondre aux questions -->
        <form method="POST">

            <p>Question 1 : <?= htmlspecialchars($quiz['questionQ1'] ?? 'Question 1') ?></p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" value="<?= htmlspecialchars($quiz['option1'] ?? '') ?>" id="q1o1">
                <label class="form-check-label" for="q1o1"><?= htmlspecialchars($quiz['option1'] ?? 'Option 1') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" value="<?= htmlspecialchars($quiz['option2'] ?? '') ?>" id="q1o2">
                <label class="form-check-label" for="q1o2"><?= htmlspecialchars($quiz['option2'] ?? 'Option 2') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q1" value="<?= htmlspecialchars($quiz['option3'] ?? '') ?>" id="q1o3">
                <label class="form-check-label" for="q1o3"><?= htmlspecialchars($quiz['option3'] ?? 'Option 3') ?></label>
            </div>

            <p>Question 2 : <?= htmlspecialchars($quiz['questionQ2'] ?? 'Question 2') ?></p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q2" value="<?= htmlspecialchars($quiz['op1'] ?? '') ?>" id="q2o1">
                <label class="form-check-label" for="q2o1"><?= htmlspecialchars($quiz['op1'] ?? 'Option 1') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q2" value="<?= htmlspecialchars($quiz['op2'] ?? '') ?>" id="q2o2">
                <label class="form-check-label" for="q2o2"><?= htmlspecialchars($quiz['op2'] ?? 'Option 2') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q2" value="<?= htmlspecialchars($quiz['op3'] ?? '') ?>" id="q2o3">
                <label class="form-check-label" for="q2o3"><?= htmlspecialchars($quiz['op3'] ?? 'Option 3') ?></label>
            </div>

            <p>Question 3 : <?= htmlspecialchars($quiz['questionQ3'] ?? 'Question 3') ?></p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q3" value="<?= htmlspecialchars($quiz['opt1'] ?? '') ?>" id="q3o1">
                <label class="form-check-label" for="q3o1"><?= htmlspecialchars($quiz['opt1'] ?? 'Option 1') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q3" value="<?= htmlspecialchars($quiz['opt2'] ?? '') ?>" id="q3o2">
                <label class="form-check-label" for="q3o2"><?= htmlspecialchars($quiz['opt2'] ?? 'Option 2') ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="q3" value="<?= htmlspecialchars($quiz['opt3'] ?? '') ?>" id="q3o3">
                <label class="form-check-label" for="q3o3"><?= htmlspecialchars($quiz['opt3'] ?? 'Option 3') ?></label>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Soumettre</button>
                <button type="submit" name="voir_badge" class="btn btn-secondary">Voir mon badge</button>
            </div>

        </form>
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
