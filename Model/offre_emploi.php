<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OffreEmploi {
    private $id, $titre, $description, $entreprise, $lieu, $salaire, $date_creation, $date_limit;

    public function __construct($id, $titre, $description, $entreprise, $lieu, $salaire, $date_creation, $date_limit) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->entreprise = $entreprise;
        $this->lieu = $lieu;
        $this->salaire = $salaire;
        $this->date_creation = $date_creation;
        $this->date_limit = $date_limit;
    }

    public function set_id($val) { $this->id = $val; }
    public function get_id() { return $this->id; }

    public function set_titre($val) { $this->titre = $val; }
    public function get_titre() { return $this->titre; }

    public function set_description($val) { $this->description = $val; }
    public function get_description() { return $this->description; }

    public function set_entreprise($val) { $this->entreprise = $val; }
    public function get_entreprise() { return $this->entreprise; }

    public function set_lieu($val) { $this->lieu = $val; }
    public function get_lieu() { return $this->lieu; }

    public function set_salaire($val) { $this->salaire = $val; }
    public function get_salaire() { return $this->salaire; }

    public function set_date_creation($val) { $this->date_creation = $val; }
    public function get_date_creation() { return $this->date_creation; }

    public function set_date_limit($val) { $this->date_limit = $val; }
    public function get_date_limit() { return $this->date_limit; }
}

?>
