<?php
// Ajout des en-têtes CORS pour autoriser les requêtes cross-origin
header("Access-Control-Allow-Origin: *");  // Permet toutes les origines, tu peux aussi spécifier un domaine particulier si tu veux
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../../../controller/pdfC.php";

session_start();
$db=config::getConnexion();
// Simuler un utilisateur connecté (à remplacer par votre vrai login)
$_SESSION['id'] = 1; // Exemple: utilisateur avec ID 1

$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!isset($_GET['id_pdf'])) {

    echo "❌ PDF non trouvé.";
    exit;
}

// Récupérer l'ID
$id_pdf = (int)$_GET['id_pdf'];

// Instancier le contrôleur et récupérer le PDF
$pdfC = new PdfC();
$pdf = $pdfC->getPdfById($id_pdf);
if (!$pdf) {
    echo "PDF introuvable.";
    exit;
}

// Récupérer l'URL du PDF
$pdf_url = $pdf['url'];  // Assure-toi que tu récupères correctement l'URL du PDF

// Vérifier l'URL et l'afficher (pour débogage)

// Vérifier si l'URL du PDF est valide

// Récupérer les vidéos associées à ce PDF

$pdo = config::getConnexion();
$stmt = $pdo->prepare("SELECT * FROM video WHERE id_pdf = :id_pdf");
$stmt->execute(['id_pdf' => $id_pdf]);
$videos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($video['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
<!-- 📌 Utilisation cohérente de la version 2.14.305 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    
    <title>Training Studio - Free CSS Template</title>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">
</head>
<body class="bg-light">
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
                                <a href="index.html" class="active" style="color: rgba(0,123,255,.25) ;">Home</a>
                            </li>
                            
                            <li class="scroll-to-section">
                                <a href="pdf.php" style="color: rgba(0,123,255,.25);">Cours</a>
                            </li>
                           
                          

                            <li class="scroll-to-section">
                                <a href="#contact-us" style="color: rgba(0,123,255,.25);">Contact</a>
                            </li>
                            <li class="main-button">
                                <a href="#" >Sign Up</a>
                            </li>
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

<!-- Ton header ici -->
<br>  </br>
<br>  </br>
<br>  </br>

<section class="section" id="pdf-view">
    <div class="container">
        <!-- Titre principal -->
        <div class="row">
        <div class="col-lg-12 text-center">
    <h2 class="pdf-title"><?= htmlspecialchars($pdf['titre']) ?> <small class="pdf-type"><?= htmlspecialchars($pdf['Type']) ?></small></h2>
    <hr class="my-4" />
</div>

        </div>

        <div>
        <div id="pdf-container" style="overflow: auto; height: 100vh;">
    <canvas id="pdf-canvas"></canvas>
    
    <p id="page-status" style="margin-bottom: 10px; font-weight: bold; font-family: Arial;"></p>

</div>

<!-- Barre de progression -->
<div style="width: 100%; background-color: #eee; height: 10px; margin-top: 10px;">
    
<progress id="progress-bar" value="0" max="100" style="width: 100%; height: 20px;"></progress>
</div>

<!-- Boutons de navigation -->
<style>
    .nav-button {
        background-color: #001f3f; /* Bleu nuit */
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
    }

    .nav-button:hover {
        background-color: #ff851b; /* Orange foncé */
        transform: scale(1.05);
    }
</style>

<div style="margin-top: 20px; text-align: center;">
    <button id="prev-button" class="nav-button">⬅️ Page Précédente</button>
    <button id="next-button" class="nav-button">Page Suivante ➡️</button>
    <button id="save-progress" class="nav-button">💾 Enregistrer ma progression</button>
</div>

        

        <hr class="my-5" />

        <!-- Section Vidéos -->
        <div class="row">
            <div class="col-lg-12 text-center">
                <h3 class="mb-4">Vidéos Associées</h3>
            </div>
        </div>

        <div class="row">
            <?php foreach ($videos as $video): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <!-- Carte vidéo -->
                    <div class="card shadow-sm" style="border-radius: 10px;">
                    <div class="pdf-thumb" style="display: flex; justify-content: center; align-items: center; height: 220px;">
                            <!-- Image de la vidéo centrée -->
                            <img src="../uploads/video.png" alt="Vidéo" class="card-img-top" 
                                 style="object-fit: cover; width: 70%; height: 100%; border-radius: 10px;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($video['titre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($video['description']) ?></p>
                            <p><strong>Duration:</strong> <?= htmlspecialchars($video['duree']) ?> minutes</p>
                            <p><strong>Date_added:</strong> <?= htmlspecialchars($video['date_ajout']) ?></p>

                            <!-- Bouton pour voir la vidéo -->
                            <a href="voir_video.php?id_video=<?= htmlspecialchars($video['id_video']) ?>" class="btn btn-primary mt-3">
                            Watch the video
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton retour -->
        <div class="row mt-4">
            <div class="col-lg-12 text-center">
                <a href="pdf.php" class="btn btn-outline-primary">⬅ Back to the PDF list</a>
            </div>
        </div>
    </div>
</section>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';

    let currentPage = 1;
    let pdfDoc = null;

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function loadNewPdf(pdfUrl) {
        const fullUrl = pdfUrl + '?t=' + Date.now(); // Empêche le cache
        pdfjsLib.getDocument(fullUrl).promise.then(function (pdf) {
            pdfDoc = pdf;

            const savedPage = parseInt(localStorage.getItem('pdf-current-page'));
            if (savedPage && savedPage >= 1 && savedPage <= pdfDoc.numPages) {
                currentPage = savedPage;
            } else {
                currentPage = 1;
            }

            renderPage(currentPage);
            updateProgressBar();
        }).catch(function (error) {
            console.error("❌ Erreur de chargement du PDF :", error);
        });
    }

    function saveProgress() {
        const userId = <?php echo json_encode($userId); ?>;
        const pdfId = getQueryParam('id_pdf');
        const pagesLues = currentPage;
        const totalPages = pdfDoc.numPages;
        const pourcentage = Math.round((pagesLues / totalPages) * 100);

        if (!userId || !pdfId) return;

        const postData = {
            id: userId,
            id_pdf: pdfId,
            pages_lues: pagesLues,
            total_pages: totalPages,
            pourcentage: pourcentage
        };

        fetch('progression.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(postData)
        }).catch(error => {
            console.error("Erreur réseau (sauvegarde auto):", error);
        });
    }

    function renderPage(pageNum) {
        pdfDoc.getPage(pageNum).then(function (page) {
            const scale = 1.5;
            const viewport = page.getViewport({ scale });
            const canvas = document.getElementById('pdf-canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);

            localStorage.setItem('pdf-current-page', pageNum);
            updateProgressBar();
            saveProgress(); // ✅ Sauvegarde automatique ici
        });
    }

    function updateProgressBar() {
        if (pdfDoc) {
            const percentage = (currentPage / pdfDoc.numPages) * 100;
            document.getElementById('progress-bar').value = percentage;
        }
    }

    document.getElementById('next-button').addEventListener('click', function () {
        if (currentPage < pdfDoc.numPages) {
            currentPage++;
            renderPage(currentPage);
        }
    });

    document.getElementById('prev-button').addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            renderPage(currentPage);
        }
    });

    // (Optionnel) Bouton "Manuel" de sauvegarde
    document.getElementById('save-progress').addEventListener('click', function () {
        saveProgress();
        alert("📌 Progression enregistrée manuellement !");
    });

    const urlParam = getQueryParam("url");
    if (urlParam) {
        loadNewPdf(decodeURIComponent(urlParam));
    }
});
</script>

</body>
</html>
