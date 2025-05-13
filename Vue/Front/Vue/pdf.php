<?php
require_once "../../../controller/pdfC.php";
require_once "../../../Controller/categoryC.php";
require_once "../../../controller/TestC.php";

session_start();




$categoryController = new CategoryC();
$pdfC = new PdfC();

$testC = new TestC();


$pdfs = $pdfC->afficherPdfs(); // Tous les PDFs par défaut
$categories = $categoryController->getAllCategories();

// Par défaut, on affiche tous les PDFs
$afficher = $pdfs;

// Si une recherche est effectuée
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && !empty($_POST['id_category'])) {
    $id_category = $_POST['id_category'];
    $list = $categoryController->getPdfsByCategory($id_category);

    // Si on a trouvé des PDFs dans cette catégorie
    if (!empty($list)) {
        $afficher = $list;
    } else {
        $afficher = []; // Aucune correspondance
    }
}
?>
<?php
// Récupérer l'ID de l'utilisateur depuis la session
$userId = $_SESSION['user']['id'];  // Assurez-vous que l'utilisateur est bien connecté
$pdo = config::getConnexion(); // Connexion à la base

// Requête SQL pour récupérer la progression de l'utilisateur pour chaque PDF
$query = $pdo->prepare('
    SELECT p.id_pdf, p.pages_lues, p.total_pages, p.pourcentage 
    FROM progression_lecture p
    WHERE p.id = :userId
');
$query->execute([':userId' => $userId]);

// Récupérer les résultats
$progressions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
 
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    <title>Training Studio - Free CSS Template</title>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">


    </head>


<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky background-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo"> Startup<em> Academy</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section">
                            <li class="scroll-to-section"><a href="/Vue/Front/Vue/index.php" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="offre_emploi.php">Offres d'Emploi</a></li>

                            <li class="scroll-to-section">
                                <a href="pdf.php" style="color: rgba(0,123,255,.25);">Cours</a>
                            </li>
                            <li class="scroll-to-section"><a href="/integration/Vue/Front/Front/channels.html">Forum</a></li>
                           

                            <li class="scroll-to-section">
                                <a href="#contact-us" style="color: rgba(0,123,255,.25);">Contact</a>
                            </li>
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
                                
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <section class="section" id="trainers">
    <div class="container">
    <div class="row">
    
</div>
<section class="section" id="trainers">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading">
                    <h2><em class="course-title">Course</em></h2>
                    <img src="../assets/images/line-dec.png" alt="">
                </div>
            </div>
        </div>
        <!-- Barre de recherche par titre -->
<!-- 🔍 Barre de recherche fixe -->
<div class="search-title-bar">
    <input type="text" id="searchInput" placeholder="📝 Search for a course...">
</div>


        <!-- Résultats de recherche -->
    </div>
</section>
<div class="search-form-container">
    <form method="POST" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <label for="categorySelect" style="margin: 0; font-weight: 500;">🔎 Category :</label>
        <select name="id_category" id="categorySelect" style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="">-- Choose --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id_category'] ?>" 
                    <?= (isset($_POST['id_category']) && $_POST['id_category'] == $cat['id_category']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nom_C']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Search" name="search" style="padding: 5px 15px; background-color: #cc5500; border: none; border-radius: 5px; color: white; cursor: pointer;">
    </form>
</div>


<br> </br>
<?php if (!empty($afficher)): ?> 
    <div class="row" id="searchResults">
    <?php foreach ($afficher as $pdf): ?>

            <?php
            // Vérifier si la progression existe pour ce PDF
            $progress = null;
            foreach ($progressions as $prog) {
                if ($prog['id_pdf'] == $pdf['id_pdf']) {
                    $progress = $prog; // On trouve la progression de ce PDF
                    break;
                }
            }
            $hasTest = $testC->hasTestForPdf($pdf['id_pdf']); // $testC doit être initialisé
            ?>
            <div class="col-lg-4 pdf-item">
                <div class="trainer-item">
                    <div class="pdf-thumb">
                        <img src="../uploads/cour.png" alt="" width="100%" height="180px" style="object-fit: cover; border-radius: 10px;">
                    </div>
                    <div class="down-content">
                        <h4 class="pdf-title"><?= htmlspecialchars($pdf['titre']) ?></h4>
                        <p><?= htmlspecialchars($pdf['Type']) ?></p>
                        <p><?= htmlspecialchars($pdf['description_P']) ?></p>

                       <!-- Afficher la progression de lecture si elle existe -->
                       <?php if ($progress): ?>
                            <p>Pages lues: <?= $progress['pages_lues'] ?> / <?= $progress['total_pages'] ?></p>
                            <p>Progression: <?= round($progress['pourcentage'], 2) ?>%</p>
                        <?php else: ?>
                            <p>Pas encore commencé</p>
                        <?php endif; ?>

                        <a href="voirPdf.php?id_pdf=<?= htmlspecialchars($pdf['id_pdf']) ?>&url=<?= urlencode($pdf['url']) ?>" class="btn btn-warning btn-orange-dark mt-3">Open the PDF</a>
                        <?php if ($hasTest): ?>
                    <a href="Test.php?id_pdf=<?= htmlspecialchars($pdf['id_pdf']) ?>" class="btn btn-warning btn-orange-dark mt-3">Access the test</a>
                <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php elseif (isset($_POST['search'])): ?>
    <p>Aucun PDF trouvé pour cette catégorie.</p>
<?php endif; ?>


</section>




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
<!-- ***** Footer Start ***** -->
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

    <script>
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    const pdfItems = document.querySelectorAll('.pdf-item');

    pdfItems.forEach(item => {
        const title = item.querySelector('.pdf-title').textContent.toLowerCase();
        if (title.startsWith(query) || query === '') {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
        var userId = <?php echo json_encode($userId); ?>;
        console.log("ID de l'utilisateur connecté: ", userId);
</script>

</body>

</html>