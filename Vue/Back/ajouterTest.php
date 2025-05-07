<?php
require_once '../../config.php';
require_once '../../Model/Test.php';
require_once '../../Controller/TestC.php';
require_once "../../Controller/pdfC.php";
$errors = [];
$test_name = $questionT1 = $reponseT1 = $reponse_correcteT1 = "";
$questionT2 = $repT2 = $rep_correcteT2 = "";
$questionT3 = $reponT3 = $repon_correcteT3 = "";
$idTest = null;$id_pdf=null;
// Récupération des ID PDF pour le select
$pdfController = new pdfC();
$pdfs = $pdfController->afficherPdfs(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des champs
    $test_name = trim($_POST['test_name']);
    $questionT1 = trim($_POST['questionT1']);
    $reponseT1 = trim($_POST['reponseT1']);
    $reponse_correcteT1 = trim($_POST['reponse_correcteT1']);

    $questionT2 = trim($_POST['questionT2']);
    $repT2 = trim($_POST['repT2']);
    $rep_correcteT2 = trim($_POST['rep_correcteT2']);

    $questionT3 = trim($_POST['questionT3']);
    $reponT3 = trim($_POST['reponT3']);
    $repon_correcteT3 = trim($_POST['repon_correcteT3']);
	$id_pdf = trim($_POST['id_pdf']);

    // Contrôles de saisie
    if (empty($test_name)) $errors['test_name'] = "❌ Le titre du test est requis.";
    elseif (strlen($test_name) < 3) $errors['test_name_length'] = "❌ Le titre doit contenir au moins 3 caractères.";

    if (empty($questionT1)) $errors['questionT1'] = "❌ La question 1 est requise.";
    elseif (strlen($questionT1) < 3) $errors['questionT1_length'] = "❌ La question 1 doit contenir au moins 3 caractères.";

    if (empty($reponseT1)) $errors['reponseT1'] = "❌ La réponse 1 est requise.";
    if (empty($reponse_correcteT1)) $errors['reponse_correcteT1'] = "❌ La bonne réponse 1 est requise.";

    if (empty($questionT2)) $errors['questionT2'] = "❌ La question 2 est requise.";
    if (empty($repT2)) $errors['repT2'] = "❌ La réponse 2 est requise.";
    if (empty($rep_correcteT2)) $errors['rep_correcteT2'] = "❌ La bonne réponse 2 est requise.";

    if (empty($questionT3)) $errors['questionT3'] = "❌ La question 3 est requise.";
    if (empty($reponT3)) $errors['reponT3'] = "❌ La réponse 3 est requise.";
    if (empty($repon_correcteT3)) $errors['repon_correcteT3'] = "❌ La bonne réponse 3 est requise.";
	if (empty($id_pdf)) {
		$errors['id_pdf'] = "❌ L'ID PDF est requis.";
	}
	if (empty($id_pdf) || !is_numeric($id_pdf)) {
        $errors['id_pdf'] = "❌ Veuillez sélectionner un ID PDF valide.";
    }
	
    // Si pas d'erreurs, ajouter le test
    if (empty($errors)) {
        $test = new Test(
            $test_name, $questionT1, $reponseT1, $reponse_correcteT1,
            $questionT2, $repT2, $rep_correcteT2,
            $questionT3, $reponT3, $repon_correcteT3, $idTest,(int)$id_pdf
        );

        $testController = new TestC();
        $testController->ajouterTest($test);

        echo "<p style='color:green;'>✅ Le test a été ajouté avec succès !</p>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
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
</head>
<body>
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="vendors/images/dark.png" alt=""></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Loading...
			</div>
		</div>
	</div>

	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			<div class="header-search">
				<form>
					<div class="form-group mb-0">
						<i class="dw dw-search2 search-icon"></i>
						<input type="text" class="form-control search-input" placeholder="Search Here">
						<div class="dropdown">
							<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
								<i class="ion-arrow-down-c"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">From</label>
									<div class="col-sm-12 col-md-10">
										<input class="form-control form-control-sm form-control-line" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">To</label>
									<div class="col-sm-12 col-md-10">
										<input class="form-control form-control-sm form-control-line" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">Subject</label>
									<div class="col-sm-12 col-md-10">
										<input class="form-control form-control-sm form-control-line" type="text">
									</div>
								</div>
								<div class="text-right">
									<button class="btn btn-primary">Search</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>
			<div class="user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
						<i class="icon-copy dw dw-notification"></i>
						<span class="badge notification-active"></span>
					</a>
				
				</div>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						
						<span class="user-name">StartupAcademy</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="profile.html"><i class="dw dw-user1"></i> Profile</a>
						<a class="dropdown-item" href="profile.html"><i class="dw dw-settings2"></i> Setting</a>
						<a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
						<a class="dropdown-item" href="login.html"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>
			
		</div>
	</div>

	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Layout Settings
				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll">
			<div class="right-sidebar-body-content">
				<h4 class="weight-600 font-18 pb-10">Header Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light ">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
				<div class="sidebar-radio-group pb-10 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
					</div>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
				<div class="sidebar-radio-group pb-30 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
					</div>
				</div>

				<div class="reset-options pt-30 text-center">
					<button class="btn btn-danger" id="reset-settings">Reset Settings</button>
				</div>
			</div>
		</div>
	</div>

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
					<li class="dropdown">
						<li>
                            <a href="index.php" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-calendar1"></span><span class="mtext">Home</span>
                            </a>
                        </li>
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

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>TEST</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Add TEST</li>
								</ol>
							</nav>
						</div>
						
					</div>
				</div>
				<!-- Default Basic Forms Start -->
				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">TEST</h4>
							<!--<p class="mb-30">All bootstrap element classies</p>-->
						</div>
						<div class="pull-right">
							<a href="#basic-form1" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-toggle="collapse" role="button"><i class="fa fa-code"></i> Source Code</a>
						</div>
					</div>
					
                    <form method="POST" action="ajouterTest.php" novalidate>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Titre</label>
        <div class="col-sm-12 col-md-10">
            <input name="test_name" class="form-control" type="text" placeholder="Titre" required>
			<?php if (isset($errors['test_name'])) echo "<p style='color:red;'>".$errors['test_name']."</p>"; ?>
<?php if (isset($errors['test_name_length'])) echo "<p style='color:red;'>".$errors['test_name_length']."</p>"; ?>
        
		</div>
    </div>
    <!-- Ajoute d'autres champs ici -->



    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">questionT1</label>
        <div class="col-sm-12 col-md-10">
            <input name="questionT1" class="form-control" type="text">
			<?php if (isset($errors['questionT1'])) echo "<p style='color:red;'>".$errors['questionT1']."</p>"; ?>
<?php if (isset($errors['questionT1_length'])) echo "<p style='color:red;'>".$errors['questionT1_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">reponseT1</label>
        <div class="col-sm-12 col-md-10">
            <input name="reponseT1" class="form-control" type="text">
			<?php if (isset($errors['reponseT1'])) echo "<p style='color:red;'>".$errors['reponseT1']."</p>"; ?>
<?php if (isset($errors['reponseT1_length'])) echo "<p style='color:red;'>".$errors['reponseT1_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">reponse_correcteT1</label>
        <div class="col-sm-12 col-md-10">
            <input name="reponse_correcteT1" class="form-control" type="text">
			<?php if (isset($errors['reponse_correcteT1'])) echo "<p style='color:red;'>".$errors['reponse_correcteT1']."</p>"; ?>
<?php if (isset($errors['reponse_correcteT1_length'])) echo "<p style='color:red;'>".$errors['reponse_correcteT1_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">questionT2</label>
        <div class="col-sm-12 col-md-10">
            <input name="questionT2" class="form-control" type="text">
			<?php if (isset($errors['questionT2'])) echo "<p style='color:red;'>".$errors['questionT2']."</p>"; ?>
<?php if (isset($errors['questionT2_length'])) echo "<p style='color:red;'>".$errors['questionT2_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">repT2</label>
        <div class="col-sm-12 col-md-10">
            <input name="repT2" class="form-control" type="text">
			<?php if (isset($errors['repT2'])) echo "<p style='color:red;'>".$errors['repT2']."</p>"; ?>
<?php if (isset($errors['repT2_length'])) echo "<p style='color:red;'>".$errors['repT2_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">rep_correcteT2</label>
        <div class="col-sm-12 col-md-10">
            <input name="rep_correcteT2" class="form-control" type="text">
			<?php if (isset($errors['rep_correcteT2'])) echo "<p style='color:red;'>".$errors['rep_correcteT2']."</p>"; ?>
<?php if (isset($errors['rep_correcteT2_length'])) echo "<p style='color:red;'>".$errors['rep_correcteT2_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">questionT3</label>
        <div class="col-sm-12 col-md-10">
            <input name="questionT3" class="form-control" type="text">
			<?php if (isset($errors['questionT3'])) echo "<p style='color:red;'>".$errors['questionT3']."</p>"; ?>
<?php if (isset($errors['questionT3_length'])) echo "<p style='color:red;'>".$errors['questionT3_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">reponT3</label>
        <div class="col-sm-12 col-md-10">
            <input name="reponT3" class="form-control" type="text">
			<?php if (isset($errors['reponT3'])) echo "<p style='color:red;'>".$errors['reponT3']."</p>"; ?>
<?php if (isset($errors['reponT3_length'])) echo "<p style='color:red;'>".$errors['reponT3_length']."</p>"; ?>
        
		</div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">repon_correcteT3</label>
        <div class="col-sm-12 col-md-10">
            <input name="repon_correcteT3" class="form-control" type="text">
			<?php if (isset($errors['repon_correcteT3'])) echo "<p style='color:red;'>".$errors['repon_correcteT3']."</p>"; ?>
<?php if (isset($errors['repon_correcteT3_length'])) echo "<p style='color:red;'>".$errors['repon_correcteT3_length']."</p>"; ?>
        
		</div>
		<div class="form-group">
        <label class="col-sm-12 col-md-2 col-form-label">ID PDF</label>
            <select class="form-control" name="id_pdf">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($pdfs as $pdf): ?>
                    <option value="<?= $pdf['id_pdf'] ?>" <?= ($id_pdf == $pdf['id_pdf']) ? 'selected' : '' ?>>
                        <?= $pdf['id_pdf'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_pdf'])): ?>
                <small style="color: red;"><?= $errors['id_pdf'] ?></small>
            <?php endif; ?>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>


					<div class="collapse collapse-box" id="basic-form1" >
						<div class="code-box">
							<div class="clearfix">
								<a href="javascript:;" class="btn btn-primary btn-sm code-copy pull-left"  data-clipboard-target="#copy-pre"><i class="fa fa-clipboard"></i> Copy Code</a>
								<a href="#basic-form1" class="btn btn-primary btn-sm pull-right" rel="content-y"  data-toggle="collapse" role="button"><i class="fa fa-eye-slash"></i> Hide Code</a>
							</div>
							<pre><code class="xml copy-pre" id="copy-pre">

							</code></pre>
						</div>
					</div>
				</div>
				<!-- Default Basic Forms End -->
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				DeskApp - Bootstrap 4 Admin Template By <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>
