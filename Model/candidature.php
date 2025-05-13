<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Candidature
{
    private $id, $id_user, $id_offre, $lettre_motivation, $date, $statut;

    public function __construct($id, $id_user, $id_offre, $lettre_motivation, $date, $statut)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_offre = $id_offre;
        $this->lettre_motivation = $lettre_motivation;
        $this->date = $date;
        $this->statut = $statut;
    }

    public function set_id($val)
    {
        $this->id = $val;
    }
    public function get_id()
    {
        return $this->id;
    }

    public function set_id_user($val)
    {
        $this->id_user = $val;
    }
    public function get_id_user()
    {
        return $this->id_user;
    }

    public function set_id_offre($val)
    {
        $this->id_offre = $val;
    }
    public function get_id_offre()
    {
        return $this->id_offre;
    }

    public function set_lettre_motivation($val)
    {
        $this->lettre_motivation = $val;
    }
    public function get_lettre_motivation()
    {
        return $this->lettre_motivation;
    }

    public function set_date($val)
    {
        $this->date = $val;
    }
    public function get_date()
    {
        return $this->date;
    }

    public function set_statut($val)
    {
        $this->statut = $val;
    }
    public function get_statut()
    {
        return $this->statut;
    }
}

?>