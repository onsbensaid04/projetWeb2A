<?php
require_once(__DIR__ . "/../../../config.php");

session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    echo "Utilisateur non connecté.";
    exit;
}

$id_utilisateur = $_SESSION['user']['id'];  // Utilisateur connecté

if (!isset($_GET['id_video'])) {
    echo "Aucune vidéo sélectionnée.";
    exit;
}

$id_video = $_GET['id_video'];  // ID de la vidéo

// Connexion à la base de données
$pdo = config::getConnexion();

// Récupérer les infos de la vidéo
$stmt = $pdo->prepare("SELECT * FROM video WHERE id_video = :id_video");
$stmt->execute(['id_video' => $id_video]);
$video = $stmt->fetch();

$stmt = $pdo->prepare("SELECT note FROM video_rating WHERE id_utilisateur = :id_utilisateur AND id_video = :id_video ORDER BY id_rating DESC LIMIT 1");
$stmt->execute([
    'id_utilisateur' => $id_utilisateur,
    'id_video' => $id_video
]);
$lastNote = 0;

if ($row = $stmt->fetch()) {
    $lastNote = (int)$row['note'];
}
if (!$video) {
    echo "Vidéo introuvable.";
    exit;
}

// Vérifie si c’est une vidéo YouTube
$isYouTube = false;
$videoId = null;

if (strpos($video['url'], 'youtube.com') !== false || strpos($video['url'], 'youtu.be') !== false) {
    // Extraire l’ID de la vidéo YouTube
    if (strpos($video['url'], 'watch?v=') !== false) {
        parse_str(parse_url($video['url'], PHP_URL_QUERY), $ytParams);
        $videoId = $ytParams['v'] ?? null;
    } else {
        // lien court youtu.be
        $path = parse_url($video['url'], PHP_URL_PATH);
        $videoId = ltrim($path, '/');
    }
    $isYouTube = true;
}
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
                            <li class="scroll-to-section"><a href="/Vue/Front/Vue/index.php" class="active">Home</a></li>

                          
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
    <div class="container py-5">
        <h2 class="mb-4"><?= htmlspecialchars($video['titre']) ?></h2>

        <?php if ($isYouTube && $videoId): ?>
            <div class="ratio ratio-16x9 mb-4">
                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>" 
                        title="YouTube video player"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        <?php else: ?>
            <video width="100%" height="400" controls class="mb-4">
                <source src="/project/Vue/Front/uploadsVideo/<?= htmlspecialchars($video['url']) ?>" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        <?php endif; ?>

        <p><strong>Description :</strong> <?= htmlspecialchars($video['description']) ?></p>
        <p><strong>Duration :</strong> <?= htmlspecialchars($video['duree']) ?> minutes</p>
        <p><strong>Date_added :</strong> <?= htmlspecialchars($video['date_ajout']) ?></p>

        <h5 class="mt-5">Give your feedback ou Provide your feedback :</h5>
        <!-- Affichage des anciens commentaires de l'utilisateur -->
<!-- Affichage des anciens commentaires de l'utilisateur -->
<!-- Formulaire de note et commentaire -->
<form action="save_rating.php" method="POST" class="mb-5">
    <input type="hidden" name="id_video" value="<?= htmlspecialchars($video['id_video']) ?>">

    <!-- 1. Saisie des étoiles -->
    <div class="mb-3">
        <label class="form-label">Your grade:</label><br>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?= $i ?>" name="note" value="<?= $i ?>" <?= ($i == $lastNote) ? 'checked' : '' ?>>
                <label for="star<?= $i ?>" title="<?= $i ?> étoiles">&#9733;</label>
            <?php endfor; ?>
        </div>
    </div>

    <!-- 2. Anciennes notes et commentaires -->
    <?php
    $stmt = $pdo->prepare("SELECT commentaire, note, date_avis FROM video_rating WHERE id_utilisateur = :id_utilisateur AND id_video = :id_video ORDER BY date_avis DESC");
    $stmt->execute([
        'id_utilisateur' => $id_utilisateur,
        'id_video' => $video['id_video']
    ]);
    $commentaires = $stmt->fetchAll();
    ?>

    <?php if ($commentaires): ?>
        <h4 class="mb-3">Your previous comments </h4>
        <div class="list-group mb-4">
            <?php foreach ($commentaires as $com): ?>
                <div class="list-group-item p-4 rounded shadow-sm mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($com['date_avis'])) ?></small>
                        <div>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi <?= ($i <= $com['note']) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div>
                        <?php if (!empty($com['commentaire'])): ?>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($com['commentaire'])) ?></p>
                        <?php else: ?>
                            <p class="text-muted fst-italic mb-0">No comment added. </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- 3. Saisie du commentaire (après les anciens commentaires) -->
    <div class="mb-3">
        <label for="commentaire" class="form-label">Add a comment (optional)"</label>
        <textarea class="form-control" name="commentaire" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit
    </button>
</form>








        <a href="pdf.php" class="btn btn-secondary mt-3">← Back</a>
    </div>
</body>
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
</html>
