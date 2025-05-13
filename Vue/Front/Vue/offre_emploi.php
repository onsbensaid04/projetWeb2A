<!DOCTYPE html>
<html lang="en">

<head>
    <?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 1;

require_once __DIR__ . '/../../../controller/offre_emploi_con.php';
require_once __DIR__ . '/../../../controller/candidature_con.php';
require_once __DIR__ . '/../../../model/candidature.php';

$controller = new OffreEmploiCon();
$offres = $controller->getAll();
$candidatureCon = new CandidatureCon();
?>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <title>Training Studio - Free CSS Template</title>
    <!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/templatemo-training-studio.css">

    <style>
        .offre-list {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
            margin-top: 24px;
        }

        .offre-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 28px 38px;
            min-width: 500px;
            max-width: 700px;
            width: 100%;
            margin-bottom: 24px;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            transition: box-shadow 0.2s;
            position: relative;
            border-left: 8px solid #ed563b;
        }

        .offre-card:hover {
            box-shadow: 0 8px 32px rgb(237, 86, 59, 0.12);
        }

        .offre-card .offre-title {
            font-size: 1.5em;
            font-weight: 700;
            color: #ed563b;
            margin-right: 32px;
            min-width: 140px;
        }

        .offre-card .offre-details {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 24px 36px;
            align-items: baseline;
            width: 100%;
        }

        .offre-card .offre-detail {
            font-size: 1.08em;
            color: #444;
            margin-bottom: 0;
        }

        .offre-card .offre-detail strong {
            color: #ed563b;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .offre-list {
                flex-direction: column;
                align-items: center;
            }

            .offre-card {
                flex-direction: column;
                min-width: 250px;
                max-width: 98vw;
            }

            .offre-card .offre-title {
                margin-bottom: 16px;
                margin-right: 0;
            }

            .offre-card .offre-details {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->


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
                                <a href="index.html" style="color: rgba(0,123,255,.25) ;">Home</a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#" style="color: rgba(0,123,255,.25);">About</a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="offre_emploi.php" class="active" style="color: rgba(0,123,255,.25);">Offres
                                    d'Emploi</a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="schedules.html" style="color: rgba(0,123,255,.25);">Schedules</a>
                            </li>
                            <li class="has-sub">
                                <a href="javascript:void(0)">Cours</a>
                                <ul class="sub-menu">
                                    <li><a href="video.html">Videos</a></li>
                                    <li><a href="pdf.html">PDF</a></li>
                                </ul>
                            </li>
                            <li class="has-sub">
                                <a href="javascript:void(0)">exams</a>
                                <ul class="sub-menu">
                                    <li><a href="test.html">test</a></li>
                                    <li><a href="quiz.html">quiz</a></li>
                                </ul>
                            </li>

                            <li class="scroll-to-section">
                                <a href="#contact-us" style="color: rgba(0,123,255,.25);">Contact</a>
                            </li>
                            <li class="main-button">
                                <a href="../back/offre_emploi/offre_emploi_list.php">Sign Up</a>
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



    <!-- ***** Features Item Start ***** -->
    <section class="section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Offres d'Emploi</h2>
                        <img src="../assets/images/line-dec.png" alt="waves">
                        <p>Découvrez les dernières offres d'emploi de Startup Academy.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="offre-list">
                        <?php
                        $candidatureCon = new CandidatureCon();
                        foreach ($offres as $offre):
                            // Check candidature for this user and offer
                            $candidature = $candidatureCon->getByUserAndOffre($user_id, $offre['id']);
                            ?>
                            <div class="offre-card">
                                <div class="offre-title">
                                    <?= htmlspecialchars($offre['titre']) ?>
                                </div>
                                <div class="offre-details">
                                    <div class="offre-detail"><strong>Entreprise:</strong>
                                        <?= htmlspecialchars($offre['entreprise']) ?></div>
                                    <div class="offre-detail"><strong>Lieu:</strong> <?= htmlspecialchars($offre['lieu']) ?>
                                    </div>
                                    <div class="offre-detail"><strong>Description:</strong>
                                        <?= htmlspecialchars($offre['description']) ?></div>
                                    <div class="offre-detail"><strong>Date de création:</strong>
                                        <?= htmlspecialchars($offre['date_creation']) ?></div>
                                    <div class="offre-detail"><strong>Date limite:</strong>
                                        <?= htmlspecialchars($offre['date_limit']) ?></div>
                                    <div class="offre-detail"><strong>Salaire:</strong>
                                        <?= htmlspecialchars($offre['salaire']) ?> TND</div>
                                    <div class="offre-detail">
                                        <?php if (!$candidature): ?>
                                            <button type="button" class="btn btn-primary"
                                                style="background:#ed563b;border:none;padding:8px 18px;border-radius:6px;font-weight:600;cursor:pointer;"
                                                onclick="openMotivationModal(<?= htmlspecialchars($offre['id']) ?>, '<?= htmlspecialchars(addslashes($offre['titre'])) ?>')">Postuler</button>
                                        <?php else: ?>
                                            <?php
                                            // $statut = strtolower($candidature['statut']);
                                            $badgeColor = '#888';
                                            // if ($statut === 'acceptee')
                                            //     $badgeColor = '#43a047'; // green
                                            // elseif ($statut === 'refusee')
                                            //     $badgeColor = '#e53935'; // red
                                            ?>
                                            <span class="badge"
                                                style="background:<?= $badgeColor ?>;color:#fff;padding:7px 16px;border-radius:6px;font-weight:600;">
                                                <?= /* htmlspecialchars(ucfirst($candidature['statut'])) */ 'Postuler'  ?>
                                            </span>
                                            <a href="Vue/export_pdf.php?offre_id=<?= htmlspecialchars($offre['id']) ?>" class="btn btn-secondary" style="background:#007bff;border:none;padding:8px 18px;border-radius:6px;font-weight:600;cursor:pointer;color:#fff;margin-left:10px;" target="_blank">Download PDF</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Item End ***** -->



    <!-- ***** Contact Us Area Starts ***** -->
    <section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                        <iframe
                            src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
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
                                        <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*"
                                            placeholder="Your Email*" required="">
                                    </fieldset>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <fieldset>
                                        <input name="subject" type="text" id="subject" placeholder="Subject">
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <textarea name="message" rows="6" id="message" placeholder="Message"
                                            required=""></textarea>
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

                        - Designed by <a rel="nofollow" href="https://templatemo.com" class="tm-text-link"
                            target="_parent">TemplateMo</a></p>

                    <!-- You shall support us a little via PayPal to info@templatemo.com -->

                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../assets/front/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/front/js/popper.js"></script>
    <script src="../assets/front/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="../assets/front/js/scrollreveal.min.js"></script>
    <script src="../assets/front/js/waypoints.min.js"></script>
    <script src="../assets/front/js/jquery.counterup.min.js"></script>
    <script src="../assets/front/js/imgfix.min.js"></script>
    <script src="../assets/front/js/mixitup.js"></script>
    <script src="../assets/front/js/accordions.js"></script>

    <!-- Global Init -->
    <script src="../assets/front/js/custom.js"></script>
    <script src="lettre_motivation_validator.js"></script>
    <script>
        // Hide preloader after page load
        window.addEventListener('load', function() {
            var preloader = document.getElementById('js-preloader');
            if (preloader) preloader.style.display = 'none';
        });
    </script>
    <!-- Motivation Letter Modal -->
    <div id="motivationModal" class="modal"
        style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;overflow:auto;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div
            style="background:#fff;margin:60px auto;padding:32px 28px;max-width:400px;width:95vw;border-radius:12px;position:relative;box-shadow:0 2px 24px rgba(0,0,0,0.18);">
            <span onclick="closeMotivationModal()"
                style="position:absolute;top:14px;right:18px;font-size:1.7em;cursor:pointer;color:#ed563b;">&times;</span>
            <h4 id="modalOfferTitle" style="margin-bottom:18px;color:#ed563b;font-weight:700;"></h4>
            <form method="post" action="submit_candidature.php">
                <input type="hidden" name="offre_id" id="modalOffreId" value="">
                <div style="margin-bottom:18px;">
                    <label for="lettre_motivation" style="font-weight:600;">Lettre de motivation :</label><br>
                    <textarea name="lettre_motivation" id="modalLettreMotivation" rows="5"
                        style="width:100%;border-radius:6px;border:1px solid #ccc;padding:10px;resize:vertical;"></textarea>
                    <button type="button" id="generateMotivationBtn" class="btn btn-secondary" style="margin-top:10px;background:#007bff;border:none;padding:8px 18px;border-radius:6px;font-weight:600;cursor:pointer;color:#fff;opacity:0.7;" disabled>Generate with Gemini</button>
                    <span id="geminiLoading" style="display:none;margin-left:10px;color:#ed563b;font-weight:600;">Generating...</span>
                </div>
                <button type="submit" class="btn btn-primary" onclick="return validateLettreMotivationModal()"
                    style="background:#ed563b;border:none;padding:8px 18px;border-radius:6px;font-weight:600;cursor:pointer;">Envoyer
                    ma candidature</button>
            </form>
        </div>
    </div>
    <script>
        function openMotivationModal(offreId, offreTitle) {
            document.getElementById('modalOffreId').value = offreId;
            document.getElementById('modalOfferTitle').innerText = 'Postuler à : ' + offreTitle;
            document.getElementById('modalLettreMotivation').value = '';
            document.getElementById('motivationModal').style.display = 'flex';
            updateGenerateBtnState();
        }
        function closeMotivationModal() {
            document.getElementById('motivationModal').style.display = 'none';
        }
        window.onclick = function (event) {
            var modal = document.getElementById('motivationModal');
            if (event.target == modal) {
                closeMotivationModal();
            }
        }
        // Gemini button logic
        const motivationTextarea = document.getElementById('modalLettreMotivation');
        const generateBtn = document.getElementById('generateMotivationBtn');
        const loadingSpan = document.getElementById('geminiLoading');
        function updateGenerateBtnState() {
            if (motivationTextarea.value.trim().length > 0) {
                generateBtn.disabled = false;
                generateBtn.style.opacity = '1';
                generateBtn.style.cursor = 'pointer';
            } else {
                generateBtn.disabled = true;
                generateBtn.style.opacity = '0.7';
                generateBtn.style.cursor = 'not-allowed';
            }
        }
        if (motivationTextarea && generateBtn) {
            motivationTextarea.addEventListener('input', updateGenerateBtnState);
            generateBtn.addEventListener('click', async function() {
                const info = motivationTextarea.value.trim();
                if (!info) return;
                loadingSpan.style.display = 'inline';
                generateBtn.disabled = true;
                generateBtn.innerText = 'Generating...';
                try {
                    // Replace YOUR_GEMINI_API_KEY and endpoint as needed
                    const apiKey = 'AIzaSyAAwLPYs2L-z3Mgfm4JnFYMgBtXdNJxsNc';
                    const endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' + apiKey;
                    const prompt = `Génère une lettre de motivation professionnelle en français pour une candidature à un emploi, basée sur ces informations : ${info}. La lettre doit être courte, directe, sans objet, sans date, sans formule d'adresse ni de politesse de mail, et commencer immédiatement par le contenu sans aucune introduction ou phrase comme \"Voici une lettre de motivation\". Donne uniquement le texte de la lettre, sans explication ni commentaire.`;
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            contents: [{ parts: [{ text: prompt }] }]
                        })
                    });
                    const data = await response.json();
                    if (data && data.candidates && data.candidates[0] && data.candidates[0].content && data.candidates[0].content.parts && data.candidates[0].content.parts[0].text) {
                        motivationTextarea.value = data.candidates[0].content.parts[0].text;
                    } else {
                        alert('Failed to generate letter.');
                    }
                } catch (e) {
                    alert('Error generating letter: ' + e.message);
                } finally {
                    loadingSpan.style.display = 'none';
                    generateBtn.disabled = false;
                    generateBtn.innerText = 'Generate with Gemini';
                    updateGenerateBtnState();
                }
            });
        }
    </script>
</body>

</html>