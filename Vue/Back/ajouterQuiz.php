<?php
require_once '../../config.php';
require_once '../../Model/Quiz.php';
require_once '../../Controller/ajouterQuiz.php';
require_once '../../Controller/TestC.php';

$errors = [];
$quiz_name = $questionQ1 = $option1 = $option2 = $option3 = $correct_option1 = "";
$questionQ2 = $op1 = $op2 = $op3 = $correct_op2 = "";
$questionQ3 = $opt1 = $opt2 = $opt3 = $correct_opt3 = "";
$id_video = "";
$idTest=null;
$testController = new TestC();
$tests = $testController->afficherTests(); // Méthode qui retourne tous les tests


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des inputs
    $quiz_name = trim($_POST['quiz_name']);
    $questionQ1 = trim($_POST['questionQ1']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $option3 = trim($_POST['option3']);
    $correct_option1 = trim($_POST['correct_option1']);

    $questionQ2 = trim($_POST['questionQ2']);
    $op1 = trim($_POST['op1']);
    $op2 = trim($_POST['op2']);
    $op3 = trim($_POST['op3']);
    $correct_op2 = trim($_POST['correct_op2']);

    $questionQ3 = trim($_POST['questionQ3']);
    $opt1 = trim($_POST['opt1']);
    $opt2 = trim($_POST['opt2']);
    $opt3 = trim($_POST['opt3']);
    $correct_opt3 = trim($_POST['correct_opt3']);

    $id_video = trim($_POST['id_video']);
    $idTest =$_POST['idTest'] ?? null ;

    // Contrôles
    if (empty($quiz_name)) $errors['quiz_name'] = "❌ Le titre est requis.";
	if (strlen($quiz_name) < 3) 
    $errors['quiz_name_length'] = "❌ Le titre doit avoir au moins 3 caractères.";
	 if (isset($errors['quiz_name_length'])) echo "<p style='color:red;'>".$errors['quiz_name_length']."</p>"; 

    if (empty($questionQ1)) $errors['questionQ1'] = "❌ La question 1 est requise.";
    if (empty($option1) || empty($option2) || empty($option3)) $errors['optionsQ1'] = "❌ Les 3 options de Q1 sont requises.";
    if (!in_array($correct_option1, [$option1, $option2, $option3])) $errors['correct_option1'] = "❌ La réponse 1 n'est pas valide.";

    if (empty($questionQ2)) $errors['questionQ2'] = "❌ La question 2 est requise.";
    if (empty($op1) || empty($op2) || empty($op3)) $errors['optionsQ2'] = "❌ Les 3 options de Q2 sont requises.";
    if (!in_array($correct_op2, [$op1, $op2, $op3])) $errors['correct_op2'] = "❌ La réponse 2 n'est pas valide.";

    if (empty($questionQ3)) $errors['questionQ3'] = "❌ La question 3 est requise.";
    if (empty($opt1) || empty($opt2) || empty($opt3)) $errors['optionsQ3'] = "❌ Les 3 options de Q3 sont requises.";
    if (!in_array($correct_opt3, [$opt1, $opt2, $opt3])) $errors['correct_opt3'] = "❌ La réponse 3 n'est pas valide.";

    if (empty($id_video) || !is_numeric($id_video)) $errors['id_video'] = "❌ L'ID vidéo est requis et doit être un nombre.";

	
    // Ajout si pas d'erreurs
    if (empty($errors)) {
        $quiz = new Quiz(
            $quiz_name, $questionQ1, $option1, $option2, $option3, $correct_option1,
            $questionQ2, $op1, $op2, $op3, $correct_op2,
            $questionQ3, $opt1, $opt2, $opt3, $correct_opt3,
            $id_video, (int)$idTest
        );
        $quizController = new QuizC();
        $quizController->ajouterQuiz($quiz);
        echo "<p style='color: green;'>✅ Quiz ajouté avec succès.</p>";
        
        // Réinitialiser les champs
        $quiz_name = $questionQ1 = $option1 = $option2 = $option3 = $correct_option1 = "";
        $questionQ2 = $op1 = $op2 = $op3 = $correct_op2 = "";
        $questionQ3 = $opt1 = $opt2 = $opt3 = $correct_opt3 = "";
        $id_video = "";
		$idTest=null;

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
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-library"></span><span class="mtext">Tables</span>
						</a>
						<ul class="submenu">
							<li><a href="basic-table.html">Basic Tables</a></li>
							<li><a href="datatable.html">DataTables</a></li>
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
								<h4>QUIZ</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Add QUIZ</li>
								</ol>
							</nav>
						</div>
						
					</div>
				</div>
				<!-- Default Basic Forms Start -->
				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">QUIZ</h4>
							<!--<p class="mb-30">All bootstrap element classies</p>-->
						</div>
						<div class="pull-right">
							<a href="#basic-form1" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-toggle="collapse" role="button"><i class="fa fa-code"></i> Source Code</a>
						</div>
					</div>
					<!-- Formulaire HTML pour ajouter un quiz -->
					
					<form method="POST" action="" novalidate>
    <!-- Titre du Quiz -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Titre</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" type="text" name="quiz_name" value="<?= htmlspecialchars($quiz_name ?? '') ?>" placeholder="Titre du quiz" required>
            <?php if (isset($errors['quiz_name'])) echo "<p style='color:red;'>".$errors['quiz_name']."</p>"; ?>
<?php if (isset($errors['quiz_name_length'])) echo "<p style='color:red;'>".$errors['quiz_name_length']."</p>"; ?>
        </div>
    </div>

    <!-- Question 1 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Question 1</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="questionQ1" value="<?= htmlspecialchars($questionQ1 ?? '') ?>" placeholder="Question 1" type="text" required>
           

			<?php if (isset($errors['questionQ1'])) echo "<p style='color:red;'>".$errors['questionQ1']."</p>"; ?>
			<?php if (isset($errors['questionQ1_length'])) echo "<p style='color:red;'>".$errors['questionQ1_length']."</p>"; ?>
        </div>
    </div>
    <!-- Options pour Question 1 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 1</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="option1" value="<?= htmlspecialchars($option1 ?? '') ?>" type="text" placeholder="Option 1" required>
			<?php if (isset($errors['option1'])) echo "<p style='color:red;'>".$errors['option1']."</p>"; ?>
			<?php if (isset($errors['option1_length'])) echo "<p style='color:red;'>".$errors['option1_length']."</p>"; ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 2</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="option2" value="<?= htmlspecialchars($option2 ?? '') ?>" type="text" placeholder="Option 2" required>
			<?php if (isset($errors['option2'])) echo "<p style='color:red;'>".$errors['option2']."</p>"; ?>
			<?php if (isset($errors['option2_length'])) echo "<p style='color:red;'>".$errors['option2_length']."</p>"; ?>
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 3</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="option3" value="<?= htmlspecialchars($option3 ?? '') ?>" type="text" placeholder="Option 3" required>
			<?php if (isset($errors['option3'])) echo "<p style='color:red;'>".$errors['option3']."</p>"; ?>
			<?php if (isset($errors['option3_length'])) echo "<p style='color:red;'>".$errors['option3_length']."</p>"; ?>
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Correct Option 1</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="correct_option1" value="<?= htmlspecialchars($correct_option1 ?? '') ?>" type="text" placeholder="Option correcte 1" required>
            <?php if (isset($errors['correct_option1'])): ?>
                <small style="color: red;"><?= $errors['correct_option1'] ?></small>
            <?php endif; ?>
			<?php if (isset($errors['correct_option1'])) echo "<p style='color:red;'>".$errors['correct_option1']."</p>"; ?>
			<?php if (isset($errors['correct_option1_length'])) echo "<p style='color:red;'>".$errors['correct_option1_length']."</p>"; ?>
		
        </div>
    </div>

    <!-- Question 2 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Question 2</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="questionQ2" value="<?= htmlspecialchars($questionQ2 ?? '') ?>" placeholder="Question 2" type="text" required>
			<?php if (isset($errors['questionQ2'])) echo "<p style='color:red;'>".$errors['questionQ2']."</p>"; ?>
			<?php if (isset($errors['questionQ2_length'])) echo "<p style='color:red;'>".$errors['questionQ2_length']."</p>"; ?>
		
		</div>
    </div>
    <!-- Options pour Question 2 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 1</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="op1" value="<?= htmlspecialchars($op1 ?? '') ?>" type="text" placeholder="Option 1" required>
			<?php if (isset($errors['op1'])) echo "<p style='color:red;'>".$errors['op1']."</p>"; ?>
			<?php if (isset($errors['op1_length'])) echo "<p style='color:red;'>".$errors['op1_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 2</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="op2" value="<?= htmlspecialchars($op2 ?? '') ?>" type="text" placeholder="Option 2" required>
			<?php if (isset($errors['op2'])) echo "<p style='color:red;'>".$errors['op2']."</p>"; ?>
			<?php if (isset($errors['op2_length'])) echo "<p style='color:red;'>".$errors['op2_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 3</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="op3" value="<?= htmlspecialchars($op3 ?? '') ?>" type="text" placeholder="Option 3" required>
			<?php if (isset($errors['op3'])) echo "<p style='color:red;'>".$errors['op3']."</p>"; ?>
			<?php if (isset($errors['op3_length'])) echo "<p style='color:red;'>".$errors['op3_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Correct Option 2</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="correct_op2" value="<?= htmlspecialchars($correct_op2 ?? '') ?>" type="text" placeholder="Option correcte 2" required>
            <?php if (isset($errors['correct_op2'])): ?>
                <small style="color: red;"><?= $errors['correct_op2'] ?></small>
            <?php endif; ?>
			<?php if (isset($errors['correct_op2'])) echo "<p style='color:red;'>".$errors['correct_op2']."</p>"; ?>
			<?php if (isset($errors['correct_op2_length'])) echo "<p style='color:red;'>".$errors['correct_op2_length']."</p>"; ?>
		
		</div>
    </div>

    <!-- Question 3 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Question 3</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="questionQ3" value="<?= htmlspecialchars($questionQ3 ?? '') ?>" placeholder="Question 3" type="text" required>
			<?php if (isset($errors['questionQ3'])) echo "<p style='color:red;'>".$errors['questionQ3']."</p>"; ?>
			<?php if (isset($errors['questionQ3_length'])) echo "<p style='color:red;'>".$errors['questionQ3_length']."</p>"; ?>
		
		</div>
    </div>
    <!-- Options pour Question 3 -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 1</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="opt1" value="<?= htmlspecialchars($opt1 ?? '') ?>" type="text" placeholder="Option 1" required>
			<?php if (isset($errors['opt1'])) echo "<p style='color:red;'>".$errors['opt1']."</p>"; ?>
			<?php if (isset($errors['opt1_length'])) echo "<p style='color:red;'>".$errors['opt1_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 2</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="opt2" value="<?= htmlspecialchars($opt2 ?? '') ?>" type="text" placeholder="Option 2" required>
			<?php if (isset($errors['opt2'])) echo "<p style='color:red;'>".$errors['opt2']."</p>"; ?>
			<?php if (isset($errors['opt2_length'])) echo "<p style='color:red;'>".$errors['opt2_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 3</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="opt3" value="<?= htmlspecialchars($opt3 ?? '') ?>" type="text" placeholder="Option 3" required>
			<?php if (isset($errors['opt3'])) echo "<p style='color:red;'>".$errors['opt3']."</p>"; ?>
			<?php if (isset($errors['opt3_length'])) echo "<p style='color:red;'>".$errors['opt3_length']."</p>"; ?>
		
		</div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Correct Option 3</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="correct_opt3" value="<?= htmlspecialchars($correct_opt3 ?? '') ?>" type="text" placeholder="Option correcte 3" required>
            <?php if (isset($errors['correct_opt3'])): ?>
                <small style="color: red;"><?= $errors['correct_opt3'] ?></small>
            <?php endif; ?>
			<?php if (isset($errors['correct_opt3'])) echo "<p style='color:red;'>".$errors['correct_opt3']."</p>"; ?>
			<?php if (isset($errors['correct_opt3_length'])) echo "<p style='color:red;'>".$errors['correct_opt3_length']."</p>"; ?>
		
		</div>
    </div>

    <!-- ID Vidéo -->
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">ID Vidéo</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" name="id_video" value="<?= htmlspecialchars($id_video ?? '') ?>" type="number" placeholder="ID Vidéo" required>
            <?php if (isset($errors['id_video'])): ?>
                <small style="color: red;"><?= $errors['id_video'] ?></small>
            <?php endif; ?>
			<?php if (isset($errors['option3'])) echo "<p style='color:red;'>".$errors['option3']."</p>"; ?>
			<?php if (isset($errors['option3_length'])) echo "<p style='color:red;'>".$errors['option3_length']."</p>"; ?>
		
		</div>
    </div>
	<div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">ID Test</label>
    <div class="col-sm-12 col-md-10">
        <select class="form-control" name="idTest" >
            <option value="">-- Choisir un test --</option>
            <?php foreach ($tests as $test): ?>
                <option value="<?= $test['idTest'] ?>" <?= (isset($_POST['idTest']) && $_POST['idTest'] == $test['idTest']) ? 'selected' : '' ?>>
              <?= $test['idTest'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['idTest'])): ?>
            <small style="color: red;"><?= $errors['idTest'] ?></small>
        <?php endif; ?>
    </div>
</div>


    <!-- Bouton de soumission -->
    <div class="form-group row">
        <div class="col-sm-12 col-md-10 offset-md-2">
            <button type="submit" class="btn btn-primary">Ajouter Quiz</button>
        </div>
    </div>
</form>


					<div class="collapse collapse-box" id="basic-form1" >
						<div class="code-box">
							<div class="clearfix">
								<a href="javascript:;" class="btn btn-primary btn-sm code-copy pull-left"  data-clipboard-target="#copy-pre"><i class="fa fa-clipboard"></i> Copy Code</a>
								<a href="#basic-form1" class="btn btn-primary btn-sm pull-right" rel="content-y"  data-toggle="collapse" role="button"><i class="fa fa-eye-slash"></i> Hide Code</a>
							</div>
							<pre><code class="xml copy-pre" id="copy-pre">
<!--<form>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Text</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" type="text" placeholder="Johnny Brown">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Search</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" placeholder="Search Here" type="search">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Email</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" value="bootstrap@example.com" type="email">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">URL</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" value="https://getbootstrap.com" type="url">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Telephone</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" value="1-(111)-111-1111" type="tel">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Password</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" value="password" type="password">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Number</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control" value="100" type="number">
		</div>
	</div>
	<div class="form-group row">
		<label for="example-datetime-local-input" class="col-sm-12 col-md-2 col-form-label">Date and time</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control datetimepicker" placeholder="Choose Date anf time" type="text">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Date</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control date-picker" placeholder="Select Date" type="text">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Month</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control month-picker" placeholder="Select Month" type="text">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Time</label>
		<div class="col-sm-12 col-md-10">
			<input class="form-control time-picker" placeholder="Select time" type="text">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Select</label>
		<div class="col-sm-12 col-md-10">
			<select class="custom-select col-12">
				<option selected="">Choose...</option>
				<option value="1">One</option>
				<option value="2">Two</option>
				<option value="3">Three</option>
			</select>
		</div>
	</div>
	
	
</form>-->
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