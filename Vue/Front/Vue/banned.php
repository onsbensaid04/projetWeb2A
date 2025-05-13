<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte banni</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        body { background: #f8d7da; margin: 0; font-family: 'Poppins', Arial, sans-serif; }
        .banned-container {
            max-width: 450px;
            margin: 120px auto 0 auto;
            background: #fff;
            border: 2px solid #f5c2c7;
            border-radius: 8px;
            padding: 32px 24px 24px 24px;
            text-align: center;
            box-shadow: 0 2px 16px rgba(220,53,69,0.1);
        }
        .banned-title {
            color: #dc3545;
            font-size: 2em;
            margin-bottom: 16px;
        }
        .banned-msg {
            color: #333;
            font-size: 1.1em;
            margin-bottom: 28px;
        }
        .logout-btn {
            display: inline-block;
            padding: 10px 28px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #b52a37;
            color: #fff;
        }
        .logout-btn i {
            margin-right: 7px;
        }
        /* Header styles for consistency with index.php */
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 30px 18px 30px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }
        .main-nav .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3em;
            color: #222;
        }
        .main-nav .logo img {
            height: 40px;
            margin-right: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="banned-container">
        <div class="banned-title"><i class="fa fa-ban"></i> Compte banni</div>
        <div class="banned-msg">
            Votre compte a été <strong>banni</strong>.<br>
            Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur du site.
        </div>
        <a href="logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i>Se déconnecter</a>
    </div>
</body>
</html>
