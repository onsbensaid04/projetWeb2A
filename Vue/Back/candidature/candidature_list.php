<?php
require_once __DIR__ . '/../../../controller/candidature_con.php';

$controller = new CandidatureCon();
$candidatures = $controller->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste des Candidatures</title>
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
                    <div class="clearfix mb-20">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Liste des Candidatures</h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Utilisateur</th>
                                    <th>ID Offre</th>
                                    <th>Lettre de motivation</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidatures as $cand): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cand['id']) ?></td>
                                        <td><?= htmlspecialchars($cand['id_user']) ?></td>
                                        <td><?= htmlspecialchars($cand['id_offre']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="showLettre('<?= htmlspecialchars(addslashes($cand['lettre_motivation'])) ?>')">Voir</button>
                                        </td>
                                        <td><?= htmlspecialchars($cand['date']) ?></td>
                                        <td>
                                            <?php
                                            $statut = $cand['statut'];
                                            $color = 'badge-warning';
                                            if ($statut === 'acceptee') $color = 'badge-success';
                                            else if ($statut === 'refusee') $color = 'badge-danger';
                                            ?>
                                            <span class="badge <?= $color ?>"><?php echo $statut; ?></span>
                                        </td>
                                        <td>
                                            <a href="candidature_update.php?id=<?= urlencode($cand['id']) ?>" class="btn btn-sm btn-info">Changer Statut</a>
                                            <a href="../../Front/Vue/export_pdf.php?offre_id=<?= htmlspecialchars($cand['id_offre']) ?>" class="btn btn-sm btn-info" target="_blank">Download PDF</a>
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
    <div class="footer-wrap pd-20 mb-20 card-box">
        DeskApp - Bootstrap Admin Template By <a href="https://github.com/dropways/deskapp" target="_blank">dropways</a>
    </div>
<!-- Lettre de motivation Modal -->
<div id="lettreModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
  <div style="background:#fff;padding:24px 24px 12px 24px;max-width:500px;width:90vw;max-height:80vh;overflow:auto;position:relative;border-radius:8px;box-shadow:0 2px 16px rgba(0,0,0,0.2);">
    <h5>Lettre de motivation</h5>
    <div id="lettreContent" style="white-space:pre-line;margin-bottom:18px;"></div>
    <button onclick="closeLettre()" class="btn btn-primary" style="float:right;">Fermer</button>
  </div>
</div>
<script>
function showLettre(lettre) {
    document.getElementById('lettreContent').innerText = lettre;
    document.getElementById('lettreModal').style.display = 'flex';
}
function closeLettre() {
    document.getElementById('lettreModal').style.display = 'none';
}
// Optional: Close modal on outside click
window.onclick = function(event) {
  var modal = document.getElementById('lettreModal');
  if (event.target === modal) {
    closeLettre();
  }
}
</script>
</body>
</html>
