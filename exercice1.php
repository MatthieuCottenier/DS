<?php

// création de la classe Vehicule
abstract class Vehicule 
{
    protected $demarrer = FALSE;
    protected $vitesse = 0;
    protected $vitesseMax;

    abstract function demarrer();
    abstract function eteindre();
    abstract function decelerer($vitesse);
    abstract function accelerer($vitesse);

    public function __toString()
    {
        $chaine = "Ceci est un véhicule\n";
        $chaine .= "----------------------\n";
        return $chaine;
    }
}

// création de la classe Voiture qui hérite de Vehicule
class Voiture extends Vehicule 
{
    private static $nombreVoiture = 0;

    public function __construct($vitesseMax)
    {
        $this->vitesseMax = $vitesseMax;
        self::$nombreVoiture++;
    }

    // compte le nombre de voitures
    public static function getNombreVoiture()
    {
        return self::$nombreVoiture;
    }

    // fonction démarrer pour la voiture
    public function demarrer()
    {
        if (!$this->demarrer) {
            $this->demarrer = TRUE;
            echo "La voiture démarre.\n";
        } else {
            echo "La voiture est déjà démarrée.\n";
        }
    }

    // fonction éteindre pour la voiture
    public function eteindre()
    {
        if ($this->demarrer) {
            $this->demarrer = FALSE;
            $this->vitesse = 0;
            echo "La voiture s’éteint.\n";
        } else {
            echo "La voiture est déjà éteinte.\n";
        }
    }

    // fonction accélérer pour la voiture
    public function accelerer($vitesse)
    {
        if (!$this->demarrer) {
            echo "Impossible d'accélérer : la voiture est éteinte.\n";
            return;
        }

        //limite l'accélération à 30% de la vitesse actuelle ou 10 km/h si à l'arrêt
        if ($this->vitesse == 0) {
            $limite = 10;
        } else {
            $limite = $this->vitesse * 0.3;
        }

        if ($vitesse > $limite) {
            echo "Accélération limitée à +{$limite} km/h (30% de la vitesse actuelle).\n";
            $vitesse = $limite;
        }

        $this->vitesse += $vitesse;

        //limite la vitesse maximale
        if ($this->vitesse > $this->vitesseMax) {
            $this->vitesse = $this->vitesseMax;
            echo "Vitesse maximale atteinte ({$this->vitesseMax} km/h).\n";
        } else {
            echo "La voiture accélère à {$this->vitesse} km/h.\n";
        }
    }

    // fonction décélérer pour la voiture
    public function decelerer($vitesse)
    {
        $this->vitesse -= $vitesse;
        if ($vitesse > 20) {
            echo "Décelération limitée à 20 km/h maximum.\n";
            $this->vitesse += ($vitesse - 20);
        }
        if ($this->vitesse < 0) $this->vitesse = 0;
        echo "La voiture décélère à {$this->vitesse} km/h.\n";
    }

    public function __toString()
    {
        $chaine = parent::__toString();
        $chaine .= "Vitesse actuelle : {$this->vitesse} km/h\n";
        $chaine .= "Vitesse max : {$this->vitesseMax} km/h\n";
        $chaine .= "État : " . ($this->demarrer ? "Démarrée" : "Éteinte") . "\n\n";
        return $chaine;
    }
}

// === TESTS ===
$veh1 = new Voiture(110);
$veh1->demarrer();
$veh1->accelerer(40);
$veh1->accelerer(40);
$veh1->accelerer(20);
$veh1->accelerer(20);
$veh1->decelerer(200);
$veh1->decelerer(200);
$veh1->decelerer(200);
$veh1->decelerer(200);
echo $veh1;
