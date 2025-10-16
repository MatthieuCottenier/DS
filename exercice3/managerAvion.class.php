<?php
require_once 'Avion.class.php';

class ManagerAvion
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(Avion $avion)
    {
        $stmt = $this->pdo->prepare("INSERT INTO avions (Nom, Pays, AnneeService, Constructeur) VALUES (:nom, :pays, :anneeService, :constructeur)");
        $stmt->execute([
            ':nom' => $avion->getNom(),
            ':pays' => $avion->getPays(),
            ':anneeService' => $avion->getAnneeService(),
            ':constructeur' => $avion->getConstructeur()
        ]);
        $avion->setId($this->pdo->lastInsertId());
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM avions");
        $avions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $avions[] = new Avion($row);
        }
        return $avions;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM avions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Avion($row);
        }
        return null;
    }
}
?>
