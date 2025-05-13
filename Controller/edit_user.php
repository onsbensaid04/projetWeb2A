<?php
session_start();
require_once __DIR__. '/../Controller/utilisateurBack.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../Vue/Front/Vue/connexion.php');
    exit;
}

$utilisateurModel = new UtilisateurBack();

require_once __DIR__ . '/../Vue/Front/Vue/check_ban.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user = $utilisateurModel->getUtilisateurById($id);

    if (!$user) {
        header('Location: ../Vue/Back/dashboard.php');
        exit;
    }
} else {
    header('Location: ../Vue/Back/dashboard.php');
    exit;
}

// Enregistrer la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    $utilisateurModel->modifierUtilisateur($id, $prenom, $nom, $email, $role);

    header('Location: ../Vue/Back/dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier Utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Vue/Back/Back/styles/core.css">
    <link rel="stylesheet" type="text/css" href="../Vue/Back/Back/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="../Vue/Back/Back/styles/style.css">
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
                            <img src="../Vue/Back/Back/images/photo1.jpg" alt="">
                        </span>
                        <span class="user-name">Admin</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="../Vue/Front/Vue/index.php"><i class="dw dw-user1"></i> Home</a>
                        <a class="dropdown-item" href="../Vue/Front/Vue/logout.php"><i class="dw dw-logout"></i> Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="#">
                <img src="../Vue/Back/Back/images/logoicon.png" alt="" class="dark-logo">
                <img src="../Vue/Back/Back/images/logo.png" alt="" class="light-logo">
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li><a href="../Vue/Back/dashboard.php" class="dropdown-toggle no-arrow"><span
                                class="micon dw dw-list"></span><span class="mtext">Liste des Utilisateurs</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <h4>Modifier Utilisateur</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12 mx-auto">
                        <div class="card-box mb-30">
                            <div class="pd-20">
                                <form method="POST">
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" class="form-control" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Rôle</label>
                                        <select class="form-control" name="role" required>
                                            <option value="etudiant" <?php if($user['role'] == 'etudiant') echo 'selected'; ?>>Etudiant</option>
                                            <option value="formateur" <?php if($user['role'] == 'formateur') echo 'selected'; ?>>Formateur</option>
                                            <option value="recruteur" <?php if($user['role'] == 'recruteur') echo 'selected'; ?>>Recruteur</option>
                                            <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="dashboardController.php" class="btn btn-secondary ml-2">Retour</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    .dropdown-menu {
        display: none;
    }
    .dropdown-menu.show {
        display: block;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdownToggle = document.querySelector('.dropdown-toggle');
    var dropdownMenu = document.querySelector('.dropdown-menu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
</script>
</html>
