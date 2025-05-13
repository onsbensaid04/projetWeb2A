<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/offre_emploi.php';

class OffreEmploiCon {

    public function getAll() {
        $sql = "SELECT * FROM offre_emploi ORDER BY date_creation DESC";
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
        $sql = "SELECT * FROM offre_emploi WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function add($offre) {
        $sql = "INSERT INTO offre_emploi (titre, description, entreprise, lieu, salaire, date_creation, date_limit) VALUES (:titre, :description, :entreprise, :lieu, :salaire, :date_creation, :date_limit)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $offre->get_titre(),
                'description' => $offre->get_description(),
                'entreprise' => $offre->get_entreprise(),
                'lieu' => $offre->get_lieu(),
                'salaire' => $offre->get_salaire(),
                'date_creation' => $offre->get_date_creation(),
                'date_limit' => $offre->get_date_limit()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function update($offre) {
        $sql = "UPDATE offre_emploi SET titre = :titre, description = :description, entreprise = :entreprise, lieu = :lieu, salaire = :salaire, date_creation = :date_creation, date_limit = :date_limit WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $offre->get_id(),
                'titre' => $offre->get_titre(),
                'description' => $offre->get_description(),
                'entreprise' => $offre->get_entreprise(),
                'lieu' => $offre->get_lieu(),
                'salaire' => $offre->get_salaire(),
                'date_creation' => $offre->get_date_creation(),
                'date_limit' => $offre->get_date_limit()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM offre_emploi WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function searchAndSort($search = '', $sort = 'date_creation', $order = 'DESC') {
        $allowedSort = ['id', 'titre', 'entreprise', 'lieu', 'salaire', 'date_creation', 'date_limit'];
        $allowedOrder = ['ASC', 'DESC'];
        $sort = in_array($sort, $allowedSort) ? $sort : 'date_creation';
        $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'DESC';
        $sql = "SELECT * FROM offre_emploi WHERE 1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND (titre LIKE :search OR entreprise LIKE :search OR lieu LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        $sql .= " ORDER BY $sort $order";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>
