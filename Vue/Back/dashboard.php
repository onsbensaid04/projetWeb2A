<?php

require_once __DIR__ . '/../../Controller/utilisateurBack.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../Vue/Front/Vue/connexion.php');
    exit;
}

$utilisateurModel = new UtilisateurBack();

require_once __DIR__ . '/../../Vue/Front/Vue/check_ban.php';



// Vérifier si une recherche a été faite
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $motCle = trim($_GET['search']);
    $listeUtilisateurs = $utilisateurModel->rechercherUtilisateurs($motCle);
} else {
    $listeUtilisateurs = $utilisateurModel->getAllUtilisateurs();
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>

    <title>Liste des Offres d'Emploi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./Back/styles/core.css">
    <link rel="stylesheet" type="text/css" href="./Back/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="./Back/styles/style.css">
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
                    <a class="dropdown-toggle" href="#" role="button">
                        <span class="user-icon">
                            <img src="./Back/images/photo1.jpg" alt="">
                        </span>
                        <span class="user-name">Admin</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="../Front/Vue/index.php"><i class="dw dw-user1"></i> Home</a>
                        <a class="dropdown-item" href="../Front/Vue/logout.php"><i class="dw dw-logout"></i> Log
                            Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
<div class="left-side-bar">
		<div class="brand-logo">
			<a href="index.html">
				<img src="vendors/images/logoicon.png" alt="" class="dark-logo">
				<img src="vendors/images/logo.png" alt="" class="light-logo">
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                            <li>
                    <a href="index.html" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-calendar1"></span><span class="mtext">Home</span>
                    </a>
                </li>
                <li>
                    <a href="./dashboard.php" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-list"></span><span class="mtext">Liste des Utilisateurs</span>
                    </a>
                </li>


                <!-- Cours Dropdown -->
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle">
                                <span class="micon dw dw-library"></span><span class="mtext">Cours</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="ajouterVideo.php">Add video</a></li>
                                <li><a href="afficherVideo.php">Video List</a></li>
                                <li><a href="ajouter_pdf.php">Add PDF</a></li>
								<li><a href="Aafficherpdf.php">PDF List</a></li>

                            </ul>
                        </li>



                <!-- Exams Dropdown -->
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
                    </div>

                    <div class="col-sm-12 col-md-10">
                        <form method="GET" class="search-form d-flex align-items-center">
                            <input type="text" name="search" placeholder="Rechercher par nom, prénom ou email"
                                class="form-control mr-2"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" class="btn btn-primary ms-2">🔍 Rechercher</button>
                        </form>
                    </div>

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
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listeUtilisateurs as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td><?php echo htmlspecialchars($user['date_inscription']); ?></td>
                                        <td class="actions">
                                            <a href="../../Controller/edit_user.php?id=<?php echo $user['id']; ?>"
                                                title="Modifier">✏️</a>
                                            <a href="../../Controller/delete_user.php?id=<?php echo $user['id']; ?>"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">🗑️</a>
                                            <?php if ($user['statut'] === 'actif'): ?>
                                                <a href="../../Controller/toggle_block_user.php?id=<?php echo $user['id']; ?>&action=bloquer"
                                                    title="Bloquer"
                                                    onclick="return confirm('Voulez-vous bloquer cet utilisateur ?');">🚫</a>
                                            <?php else: ?>
                                                <a href="../../Controller/toggle_block_user.php?id=<?php echo $user['id']; ?>&action=debloquer"
                                                    title="Débloquer"
                                                    onclick="return confirm('Voulez-vous débloquer cet utilisateur ?');">✅</a>
                                            <?php endif; ?>
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

<style>
    .dropdown-menu {
        display: none;
    }

    .dropdown-menu.show {
        display: block;
    }
    .submenu {
    display: none;
}
.dropdown.open > .submenu {
    display: block !important;
}

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $(".dropdown-toggle").click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        var submenu = $(this).next(".submenu");

        // Fermer tous les autres menus
        $(".submenu").not(submenu).slideUp("fast");

        // Afficher/cacher le menu courant
        submenu.stop(true, true).slideToggle("fast");
    });

    // Clic hors menu : tout fermer
    $(document).click(function () {
        $(".submenu").slideUp("fast");
    });

    // Empêche la fermeture si clic dans le sous-menu
    $(".submenu").click(function (e) {
        e.stopPropagation();
    });
});
</script>


</html>