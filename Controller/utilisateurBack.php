<?php
require_once __DIR__ . '/../config.php';

class UtilisateurBack {
    private $pdo;

    public function __construct() {
        $this->pdo = Config::getConnexion();
    }

    public function getAllUtilisateurs() {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUtilisateurById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function supprimerUtilisateur($id) {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function modifierUtilisateur($id, $prenom, $nom, $email, $role) {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET prenom = ?, nom = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $email, $role, $id]);
    }

    public function bloquerUtilisateur($id) {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET statut = 'bloque' WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    public function debloquerUtilisateur($id) {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET statut = 'actif' WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    public function rechercherUtilisateurs($motCle) {
        $sql = "SELECT * FROM utilisateur 
                WHERE prenom LIKE :motcle 
                   OR nom LIKE :motcle 
                   OR email LIKE :motcle";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'motcle' => "%$motCle%"
        ]);
        return $stmt->fetchAll();
    }

    public function modifierUtilisateurFront($id, $prenom, $nom, $email) {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET prenom = ?, nom = ?, email = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $email, $id]);
    }
    
    
    
}
?>
