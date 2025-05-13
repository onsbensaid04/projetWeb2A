<?php
require_once __DIR__ . '/../../../controller/offre_emploi_con.php';

$controller = new OffreEmploiCon();
// $offres = $controller->getAll();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_creation';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$offres = $controller->searchAndSort($search, $sort, $order);
?>
<?php /* DeskApp/Bootstrap styled Offers List */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste des Offres d'Emploi</title>
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
                            <h4 class="text-blue h4">Liste des Offres d'Emploi</h4>
                        </div>
                        <div class="pull-right">
                            <a href="offre_emploi_add.php" class="btn btn-primary">Ajouter une Offre</a>
                        </div>
                    </div>
                    <form method="get" class="mb-20" style="display:flex;gap:10px;align-items:center;">
                        <input type="text" name="search" placeholder="Recherche (titre, entreprise, lieu)" value="<?= htmlspecialchars($search) ?>" class="form-control" style="flex-grow:2;min-width:450px;max-width:800px;">
                        <select name="sort" class="form-control">
                            <option value="date_creation"<?= $sort=='date_creation'?' selected':''; ?>>Date Création</option>
                            <option value="titre"<?= $sort=='titre'?' selected':''; ?>>Titre</option>
                            <option value="entreprise"<?= $sort=='entreprise'?' selected':''; ?>>Entreprise</option>
                            <option value="lieu"<?= $sort=='lieu'?' selected':''; ?>>Lieu</option>
                            <option value="salaire"<?= $sort=='salaire'?' selected':''; ?>>Salaire</option>
                        </select>
                        <select name="order" class="form-control">
                            <option value="DESC"<?= strtoupper($order)=='DESC'?' selected':''; ?>>Décroissant</option>
                            <option value="ASC"<?= strtoupper($order)=='ASC'?' selected':''; ?>>Croissant</option>
                        </select>
                        <button type="submit" class="btn btn-info">Rechercher / Trier</button>
                    </form>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">Erreur : <?= htmlspecialchars($_GET['error']) ?></div>
                    <?php endif; ?>
                    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                        <div class="alert alert-success">Offre supprimée avec succès!</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
                        <div class="alert alert-success">Offre mise à jour avec succès!</div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Entreprise</th>
                                    <th>Lieu</th>
                                    <th>Salaire</th>
                                    <th>Date Création</th>
                                    <th>Date Limite</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offres as $offre): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($offre['id']) ?></td>
                                        <td><?= htmlspecialchars($offre['titre']) ?></td>
                                        <td><?= htmlspecialchars($offre['description']) ?></td>
                                        <td><?= htmlspecialchars($offre['entreprise']) ?></td>
                                        <td><?= htmlspecialchars($offre['lieu']) ?></td>
                                        <td><?= htmlspecialchars($offre['salaire']) ?></td>
                                        <td><?= htmlspecialchars($offre['date_creation']) ?></td>
                                        <td><?= htmlspecialchars($offre['date_limit']) ?></td>
                                        <td>
                                            <a href="offre_emploi_update.php?id=<?= urlencode($offre['id']) ?>" class="btn btn-sm btn-info">Modifier</a>
                                            <a href="offre_emploi_delete_action.php?id=<?= urlencode($offre['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette offre ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="footer-wrap pd-20 mb-20 card-box">
        DeskApp - Bootstrap Admin Template By <a href="https://github.com/dropways/deskapp" target="_blank">dropways</a>
    </div>
</body>
</html>
