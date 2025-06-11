<?php
class Producte {
    private $id;
    private $nom;
    private $quantitat;
    private $preu;
    private $descripcio;

    public function __construct($id, $nom = "", $descripcio = "", $preu = 0.0, $quantitat = 0) {
        $this->id = $id;
        $this->nom = $nom;
        $this->descripcio = $descripcio;
        $this->preu = $preu;
        $this->quantitat = $quantitat;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getQuantitat() {
        return $this->quantitat;
    }

    public function setQuantitat($quantitat) {
        $this->quantitat = $quantitat;
    }

    public function getPreu() {
        return $this->preu;
    }

    public function setPreu($preu) {
        $this->preu = $preu;
    }

    public function getDescripcio() {
        return $this->descripcio;
    }

    public function setDescripcio($descripcio) {
        $this->descripcio = $descripcio;
    }
}
?>
