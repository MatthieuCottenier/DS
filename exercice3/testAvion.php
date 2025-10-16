<?php
require_once 'login.php';
require_once 'Avion.class.php';
require_once 'ManagerAvion.class.php';

$manager = new ManagerAvion($pdo);

// Créer un avion
$a1 = new Avion([
    'nom' => 'F4U Corsair',
    'pays' => 'Etats-Unis',
    'anneeService' => 1943,
    'constructeur' => 'Chance Vought Aircraft Division'
]);

$a2 = new Avion([
    'nom' => 'Beluga',
    'pays' => 'France',
    'anneeService' => 2025,
    'constructeur' => 'Airbus'
]);

$a3 = new Avion([
    'nom' => 'A380',
    'pays' => 'France',
    'anneeService' => 2012,
    'constructeur' => 'Airbus'
]);

$manager->add($a1);

// Récupérer tous les avions
$avions = $manager->getAll();
foreach ($avions as $avion) {
    echo $avion->getNom() . " - " . $avion->getPays() . " - " . $avion->getAnneeService() . " - " . $avion->getConstructeur() . "\n";
}