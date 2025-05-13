<?php
require_once __DIR__ . '/../config.php';

class Utilisateur {
    public static $GOOGLE_CLIENT_ID = '765599042456-6i6h29tjton122i0rqodtf3kbm19gdgc.apps.googleusercontent.com';
    public static $GOOGLE_CLIENT_SECRET = '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';
    public static $GITHUB_CLIENT_ID = 'Ov23liDpIWSjizrUvyCO';
    public static $GITHUB_CLIENT_SECRET = '2757b2750fc3cdc7ffe16eade6f7c545e79543e8';
    public static $BAN_DIR = '/work/Mariem Hamdi/mariem from madra/integration/Vue/Front/Vue/banned.php';
    private $pdo;

    public function __construct() {
        $this->pdo = Config::getConnexion();
    }

    

    public function ajouterUtilisateur($prenom, $nom, $email, $telephone, $genre, $mot_de_passe, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, telephone, genre, mot_de_passe, role)
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $email, $telephone, $genre, $mot_de_passe, $role]);
    }

    public function getUtilisateurByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function emailExiste($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
