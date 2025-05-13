<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Controller/TestC.php';

$testC = new TestC();

if (isset($_GET['id_pdf'])) {
    $id_pdf = $_GET['id_pdf'];
    // Récupérer uniquement les tests liés à ce id_pdf
    $tests = $testC->getTestByPdf($id_pdf);
} else {
    echo "<p><em>Aucun cours sélectionné.</em></p>";
    exit;
}

$score = $_GET['score'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Startup Academy - Tests</title>

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">

    <style>
        body {
            font-family: 'Raleway', 'Poppins', sans-serif;
            background-color: #fff3e0;
            color: #FF5722;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            color: #ffa726;
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
            color: hsla(38, 87.40%, 49.80%, 0.90);
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(45deg, rgba(241, 220, 26, 0.94), rgb(245, 150, 9));
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
            background: linear-gradient(45deg, rgba(241, 220, 26, 0.94), rgb(245, 150, 9));
        }
    </style>
</head>

<body>

<!-- ***** Header Start ***** -->
<header class="header-area header-sticky background-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <a href="index.html" class="logo">Startup<em> Academy</em></a>
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="/Vue/Front/Vue/index.php" class="active">Home</a></li>
                        <li class="has-sub">
                            <li class="scroll-to-section"><a href="offre_emploi.php">Offres d'Emploi</a></li>
                            <li class="scroll-to-section">
                                <a href="pdf.php" style="color: rgba(0,123,255,.25);">Cours</a>
                            </li>
                            <li class="scroll-to-section"><a href="/integration/Vue/Front/Front/channels.html">Forum</a></li>

                      
                        <li class="scroll-to-section"><a href="#contact-us">Contact</a></li>
                         <?php if (!isset($_SESSION['user'])): ?>
                                <li class="main-button"><a href="./connexion.php">Sign In</a></li>
                            <?php else: ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="display: flex; align-items: center;">
                                        <i class="fa fa-user-circle" style="font-size: 1.5em; margin-right: 5px;"></i>
                                        <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <span
                                            class="dropdown-item-text"><strong><?php echo htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']); ?></strong></span>
                                        <span class="dropdown-item-text">Role:
                                            <?php echo htmlspecialchars($_SESSION['user']['role']); ?></span>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="mon_profil.php"><i
                                                    class="fa fa-user" style="margin-right: 5px;"></i>Mon Profil</a>
                                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                            <a class="dropdown-item" href="./../../Back/dashboard.php"><i
                                                    class="fa fa-tachometer" style="margin-right: 5px;"></i>Dashboard</a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="./logout.php"><i
                                                class="fa fa-sign-out" style="margin-right: 5px;"></i>Logout</a>
                                    </div>
                                </li>
                            <?php endif; ?>
                    </ul>
                    <a class='menu-trigger'><span>Menu</span></a>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header End ***** -->

<!-- Score Alert -->
<?php if ($score !== null): ?>
    <div class="container mt-4">
        <div class="alert alert-success text-center">
            ✅ Votre score : <strong><?= $score ?>/3</strong>
        </div>
    </div>
<?php endif; ?>

<!-- Tests Section -->
<div class="container my-5">
    <div class="section-title">
        <h2>Découvrez nos Tests</h2>
    </div>

    <div class="row">
        <?php foreach ($tests as $testC): ?>
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="card m-3 p-3 w-100">
                    <h4><?= htmlspecialchars($testC['test_name']) ?></h4>
                    <a href="detailsTest.php?idTest=<?= $testC['idTest'] ?>" class="btn btn-primary mt-auto">Découvrez Plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
 <!-- ***** Contact Us Area Starts ***** -->
    <section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                      <iframe src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div class="contact-form">
                        <form id="contact" action="" method="post">
                          <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                              <fieldset>
                                <input name="subject" type="text" id="subject" placeholder="Subject">
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Message" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Us Area Ends ***** -->
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
