<?php
require_once __DIR__ . '/../../../controller/offre_emploi_con.php';
require_once __DIR__ . '/../../../model/offre_emploi.php';

$controller = new OffreEmploiCon();
$msg = '';
$offre = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = $controller->getOne($id);
    if ($data) {
        $offre = $data;
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier une Offre d'Emploi</title>
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
            <a href="index.html">
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
                        <a href="offre_emploi_list.php" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-list"></span><span class="mtext">Liste des Offres</span>
                        </a>
                    </li>
                    <li>
                        <a href="../candidature/candidature_list.php" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-user1"></span><span class="mtext">Liste des Candidatures</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>
    <!-- Main Content -->
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-20">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Modifier une Offre d'Emploi</h4>
                        </div>
                    </div>
                    <?php if (!$offre): ?>
                        <div class="alert alert-danger">Offre non trouvée !</div>
                    <?php else: ?>
                    <form method="post" action="offre_emploi_update_action.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($offre['id']) ?>">
                        <input type="hidden" name="date_creation" value="<?= htmlspecialchars($offre['date_creation']) ?>">
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="titre">Titre</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control" type="text" name="titre" id="titre" value="<?= htmlspecialchars($offre['titre']) ?>" required>
                                <div class="invalid-feedback d-block" id="titreErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-12 col-md-10">
                                <textarea class="form-control" name="description" id="description" required><?= htmlspecialchars($offre['description']) ?></textarea>
                                <div class="invalid-feedback d-block" id="descErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="entreprise">Entreprise</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control" type="text" name="entreprise" id="entreprise" value="<?= htmlspecialchars($offre['entreprise']) ?>" required>
                                <div class="invalid-feedback d-block" id="entErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="lieu">Lieu</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control" type="text" name="lieu" id="lieu" value="<?= htmlspecialchars($offre['lieu']) ?>" required>
                                <div class="invalid-feedback d-block" id="lieuErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="salaire">Salaire</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control" type="number" name="salaire" id="salaire" value="<?= htmlspecialchars($offre['salaire']) ?>" required>
                                <div class="invalid-feedback d-block" id="salErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label" for="date_limit">Date Limite</label>
                            <div class="col-sm-12 col-md-10">
                                <input class="form-control date-picker" type="date" name="date_limit" id="date_limit" value="<?= htmlspecialchars($offre['date_limit']) ?>" required>
                                <div class="invalid-feedback d-block" id="dateErr"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-10 offset-md-2">
                                <button class="btn btn-primary" type="submit" onclick="return validform();">Mettre à jour</button>
                                <a href="offre_emploi_list.php" class="btn btn-secondary">Retour à la liste</a>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="footer-wrap pd-20 mb-20 card-box">
        DeskApp - Bootstrap Admin Template By <a href="https://github.com/dropways/deskapp" target="_blank">dropways</a>
    </div>
    <script src="offre_emploi_update_validator.js"></script>
</body>
</html>
