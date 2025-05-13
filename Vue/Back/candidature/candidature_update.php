<?php
require_once __DIR__ . '/../../../controller/candidature_con.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$controller = new CandidatureCon();
$msg = '';
$candidature = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = $controller->getOne($id);
    if ($data) {
        $candidature = $data;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['statut'])) {
    $id = intval($_POST['id']);
    $statut = $_POST['statut'];
    $controller->updateStatut($id, $statut);
    // Récupérer l'email du candidat
    require_once __DIR__ . '/../../../config.php';
    $pdo = Config::getConnexion();
    $stmt = $pdo->prepare("SELECT u.email, u.nom, u.prenom, o.titre AS offre_titre, c.date FROM candidature c JOIN utilisateur u ON c.id_user = u.id JOIN offre_emploi o ON c.id_offre = o.id WHERE c.id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    // var_dump($user);
    if ($user && !empty($user['email'])) {
        // echo "Email: ". $user['email'];
        require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/Exception.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/SMTP.php';
        $your_mail = "startupacademy2025@gmail.com";
        $your_password = "sire ibrv slkn kukw";
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $your_mail;
            $mail->Password = $your_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($your_mail, 'Startup Academy');
            $mail->addAddress($user['email'], $user['prenom'] . ' ' . $user['nom']);
            $mail->isHTML(true);
            $mail->Subject = 'Mise à jour du statut de votre candidature';
            $logoUrl = 'https://i.ibb.co/S4J8f7wG/logo.png';
            $brandColor = '#2a3f54';
            $statutMsg = '';
            $statusTitle = '';
            $statusColor = '';
            $nextStep = '';
            if ($statut === 'acceptee') {
                $statutMsg = 'Félicitations, votre candidature a été <b>acceptée</b> ! Nous sommes ravis de vous compter parmi les candidats retenus.';
                $statusTitle = 'Candidature Acceptée';
                $statusColor = '#27ae60';
                $nextStep = '<p style="font-size:15px;color:#444;margin-bottom:18px;">Nous vous contacterons prochainement pour les prochaines étapes du processus d\'intégration. Veuillez vérifier régulièrement votre boîte mail.</p>';
            } elseif ($statut === 'refusee') {
                $statutMsg = 'Nous sommes désolés, votre candidature a été <b>refusée</b>. Nous vous remercions pour l\'intérêt porté à Startup Academy et vous encourageons à postuler à d\'autres opportunités.';
                $statusTitle = 'Candidature Refusée';
                $statusColor = '#e74c3c';
                $nextStep = '<p style="font-size:15px;color:#444;margin-bottom:18px;">N\'hésitez pas à consulter nos autres offres et à retenter votre chance à l\'avenir.</p>';
            } else {
                $statutMsg = 'Votre candidature est actuellement <b>en attente</b>. Nous vous tiendrons informé dès qu\'une décision sera prise.';
                $statusTitle = 'Candidature en Attente';
                $statusColor = '#f1c40f';
                $nextStep = '<p style="font-size:15px;color:#444;margin-bottom:18px;">Vous pouvez suivre l\'état de votre candidature depuis votre espace personnel.</p>';
            }
            $mail->Body = '<div style="font-family:Inter,Arial,sans-serif;background:#f6f8fa;padding:0;margin:0;">
                <div style="max-width:600px;margin:auto;background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.07);overflow:hidden;">
                    <div style="background:' . $brandColor . ';padding:24px 0;text-align:center;">
                        <img src="' . $logoUrl . '" alt="Startup Academy" style="height:60px;">
                    </div>
                    <div style="padding:32px 24px 24px 24px;">
                        <h2 style="color:' . $statusColor . ';margin-bottom:8px;">' . $statusTitle . '</h2>
                        <p style="font-size:18px;color:#222;margin-bottom:8px;">Bonjour <b>' . htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']) . '</b>,</p>
                        <p style="font-size:16px;color:#444;margin-bottom:8px;">Nous accusons réception de votre candidature pour le poste de <b>' . htmlspecialchars($user['offre_titre']) . '</b>.</p>
                        <p style="font-size:15px;color:#888;margin-bottom:18px;">Date de candidature : <b>' . htmlspecialchars(date('d/m/Y', strtotime($user['date']))) . '</b></p>
                        <p style="font-size:16px;color:#444;margin-bottom:18px;">' . $statutMsg . '</p>' . $nextStep . '
                        <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
                        <div style="font-size:14px;color:#888;line-height:1.6;">
                            <p>Pour toute question, contactez-nous à <a href="mailto:contact@startupacademy.tn" style="color:' . $brandColor . ';text-decoration:none;">contact@startupacademy.tn</a> ou visitez notre <a href="https://startupacademy.tn" style="color:' . $brandColor . ';text-decoration:none;">site web</a>.</p>
                            <p style="margin-top:12px;">Suivez-nous sur <a href="https://www.facebook.com/startupacademy.tn" style="color:' . $brandColor . ';">Facebook</a> | <a href="https://www.linkedin.com/company/startupacademy-tn/" style="color:' . $brandColor . ';">LinkedIn</a></p>
                            <p style="margin-top:18px;">Cordialement,<br>L\'équipe Startup Academy</p>
                        </div>
                    </div>
                </div>
            </div>';
            $mail->AltBody = strip_tags($statusTitle . "\nBonjour " . $user['prenom'] . " " . $user['nom'] . "\nPoste : " . $user['offre_titre'] . "\nDate : " . date('d/m/Y', strtotime($user['date'])) . "\n" . $statutMsg . "\n" . strip_tags($nextStep) . "\nCordialement, L'équipe Startup Academy");
            $mail->send();
            // echo 'Message envoyé avec succès';
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail : " . $mail->ErrorInfo);
            // echo 'Erreur lors de l\'envoi du mail' . $mail->ErrorInfo;
        }
    }
    header('Location: candidature_list.php?updated=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Changer Statut Candidature</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../back/styles/core.css">
    <link rel="stylesheet" type="text/css" href="../back/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="../back/styles/style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="menu-icon dw dw-menu"></div>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="../back/img/photo1.jpg" alt="">
                        </span>
                        <span class="user-name">Admin</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="dw dw-logout"></i> Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
        <div class="left-side-bar">
            <div class="brand-logo">
                <a href="../index.php">
                    <img src="../back/img/logoicon.png" alt="" class="dark-logo">
                    <img src="../back/img/logo.png" alt="" class="light-logo">
                </a>
                <div class="close-sidebar" data-toggle="left-sidebar-close">
                    <i class="ion-close-round"></i>
                </div>
            </div>
            <div class="menu-block customscroll">
                <div class="sidebar-menu">
                    <ul id="accordion-menu">
                        <li class="dropdown">
                            <li>
                                <a href="../index.php" class="dropdown-toggle no-arrow">
                                    <span class="micon dw dw-calendar1"></span><span class="mtext">Home</span>
                                </a>
                            </li>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon dw dw-library"></span><span class="mtext">Cours</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../Back/ajouterVideo.php">Add video</a></li>
                                <li><a href="../Back/afficherVideo.php">Video List</a></li>
                                <li><a href="../Back/ajouter_pdf.php">Add PDF</a></li>
                                <li><a href="../Back/Aafficherpdf.php">PDF List</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon dw dw-library"></span><span class="mtext">Exams</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../Back/ajouterQuiz.php">addquiz</a></li>
                                <li><a href="../Back/afficherQuiz.php">Quiz List</a></li>
                                <li><a href="../Back/ajouterTest.php">Add Test</a></li>
                                <li><a href="../Back/afficherTest.php">Test List</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="../offre_emploi/offre_emploi_list.php" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-list"></span><span class="mtext">Liste des Offres</span>
                            </a>
                        </li>
                        <li>
                            <a href="candidature_list.php" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-user1"></span><span class="mtext">Liste des Candidatures</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <h4 class="text-blue h4">Changer Statut de la Candidature</h4>
                    <?php if (!$candidature): ?>
                        <div class="alert alert-danger">Candidature non trouvée !</div>
                    <?php else: ?>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($candidature['id']) ?>">
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="statut">Statut</label>
                            <div class="col-sm-12 col-md-10">
                                <select class="form-control" name="statut" id="statut" required>
                                    <option value="en attente" <?= $candidature['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="acceptee" <?= $candidature['statut'] === 'acceptee' ? 'selected' : '' ?>>Acceptée</option>
                                    <option value="refusee" <?= $candidature['statut'] === 'refusee' ? 'selected' : '' ?>>Refusée</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-10 offset-md-2">
                                <button class="btn btn-primary" type="submit">Mettre à jour</button>
                                <a href="candidature_list.php" class="btn btn-secondary">Retour à la liste</a>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-wrap pd-20 mb-20 card-box">
        DeskApp - Bootstrap Admin Template By <a href="https://github.com/dropways/deskapp" target="_blank">dropways</a>
    </div>
</body>
</html>
