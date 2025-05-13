<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/candidature.php';

class CandidatureCon {
    public function getByUserAndOffre($id_user, $id_offre) {
        $db = config::getConnexion();
        $stmt = $db->prepare('SELECT * FROM candidature WHERE id_user = :id_user AND id_offre = :id_offre');
        $stmt->execute(['id_user' => $id_user, 'id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function postulerIfNotExists($id_user, $id_offre) {
        $existing = $this->getByUserAndOffre($id_user, $id_offre);
        if (!$existing) {
            $now = date('Y-m-d');
            $candidature = new Candidature(
                null,
                $id_user,
                $id_offre,
                '',
                $now,
                'en attente'
            );
            $this->add($candidature);
            return true;
        }
        return false;
    }

    public function getAll() {
        $sql = "SELECT * FROM candidature ORDER BY date DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getOne($id) {
        $sql = "SELECT * FROM candidature WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function add($candidature) {
        $sql = "INSERT INTO candidature (id_user, id_offre, lettre_motivation, date, statut) VALUES (:id_user, :id_offre, :lettre_motivation, :date, :statut)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_user' => $candidature->get_id_user(),
                'id_offre' => $candidature->get_id_offre(),
                'lettre_motivation' => $candidature->get_lettre_motivation(),
                'date' => $candidature->get_date(),
                'statut' => $candidature->get_statut()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateStatut($id, $statut) {
        $sql = "UPDATE candidature SET statut = :statut WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'statut' => $statut
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function update($candidature) {
        $sql = "UPDATE candidature SET id_user = :id_user, id_offre = :id_offre, lettre_motivation = :lettre_motivation, date = :date, statut = :statut WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $candidature->get_id(),
                'id_user' => $candidature->get_id_user(),
                'id_offre' => $candidature->get_id_offre(),
                'lettre_motivation' => $candidature->get_lettre_motivation(),
                'date' => $candidature->get_date(),
                'statut' => $candidature->get_statut()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM candidature WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getCandidatureDataById($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
