<?php
class Avion
{
    private $id;
    private $nom;
    private $pays;
    private $anneeService;
    private $constructeur;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPays() { return $this->pays; }
    public function getAnneeService() { return $this->anneeService; }
    public function getConstructeur() { return $this->constructeur; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPays($pays) { $this->pays = $pays; }
    public function setAnneeService($anneeService) { $this->anneeService = $anneeService; }
    public function setConstructeur($constructeur) { $this->constructeur = $constructeur; }
}
?>
