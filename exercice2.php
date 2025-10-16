<?php

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

// création de la classe Avion qui hérite de Vehicule
class Avion extends Vehicule
{
    private static $PLAFOND_GLOBAL = 40000;
    private static $VITESSE_MAX_GLOBALE = 2000;

    private $altitude = 0;
    private $plafond;
    private $trainSorti = true;
    private $enVol = false;

    // Constructeur avec vérifications des limites globales
    public function __construct($vitesseMax, $plafond)
    {
        if ($vitesseMax > self::$VITESSE_MAX_GLOBALE) {
            throw new Exception("Vitesse max dépasse la limite globale (" . self::$VITESSE_MAX_GLOBALE . " km/h).");
        }
        if ($plafond > self::$PLAFOND_GLOBAL) {
            throw new Exception("Plafond demandé dépasse le plafond global (" . self::$PLAFOND_GLOBAL . " m).");
        }
        if ($plafond < 0) {
            throw new Exception("Plafond invalide.");
        }

        $this->vitesseMax = $vitesseMax;
        $this->plafond = $plafond;
    }

    // fonction pour démarrer
    public function demarrer()
    {
        if ($this->demarrer) {
            echo "Le moteur est déjà démarré.\n";
            return;
        }
        $this->demarrer = true;
        echo "Moteur démarré.\n";
    }

    // fonction pour éteindre
    public function eteindre()
    {
        if (!$this->demarrer) {
            echo "Le moteur est déjà éteint.\n";
            return;
        }
        if ($this->altitude > 0) {
            throw new Exception("Impossible d'éteindre le moteur en vol.");
        }
        $this->demarrer = false;
        $this->vitesse = 0;
        echo "Moteur éteint.\n";
    }

    // Accélération
    public function accelerer($vitesse)
    {
        if (!$this->demarrer) {
            throw new Exception("Impossible d'accélérer : moteur éteint.");
        }
        if ($vitesse < 0) {
            throw new Exception("Valeur d'accélération négative interdite.");
        }

        if ($this->vitesse == 0) {
            $limite = 10;
        } else {
            // on limite à 70% de la vitesse actuelle
            $limite = $this->vitesse * 0.7;
        }

        if ($vitesse > $limite) {
            echo "Accélération limitée à +{$limite} km/h (30% de la vitesse actuelle).\n";
            $vitesse = $limite;
        }

        $this->vitesse += $vitesse;

        if ($this->vitesse > $this->vitesseMax) {
            $this->vitesse = $this->vitesseMax;
            echo "Vitesse maximale de l'avion atteinte ({$this->vitesseMax} km/h).\n";
        } else {
            echo "Avion accélère à {$this->vitesse} km/h.\n";
        }
    }

    // Décélération simple (pas de limite spéciale ici, on garde logique de sécurité)
    public function decelerer($vitesse)
    {
        if (!$this->demarrer) {
            throw new Exception("Impossible de décélérer : moteur éteint.");
        }
        if ($vitesse < 0) {
            throw new Exception("Valeur de décélération négative interdite.");
        }

        $this->vitesse -= $vitesse;
        if ($this->vitesse < 0) $this->vitesse = 0;
        echo "Avion décélère à {$this->vitesse} km/h.\n";
    }

    // fonctions pour décoller avec vérifications
    public function decoller()
    {
        if (!$this->demarrer) {
            throw new Exception("Impossible de décoller : moteur éteint.");
        }
        if ($this->altitude > 0) {
            throw new Exception("Impossible de décoller : l'avion est déjà en vol.");
        }
        if ($this->vitesse < 120) {
            throw new Exception("Vitesse insuffisante pour décoller (120 km/h requis).");
        }

        $this->altitude = 100;
        $this->enVol = true;
        echo "Décollage : altitude instantanée = 100 m.\n";
    }

    // fonctions pour atterrir avec vérifications
    public function atterrir()
    {
        if (!$this->enVol) {
            throw new Exception("Impossible d'atterrir : l'avion n'est pas en vol.");
        }
        if (!$this->trainSorti) {
            throw new Exception("Impossible d'atterrir : le train d'atterrissage est rentré.");
        }
        if ($this->vitesse < 80 || $this->vitesse > 110) {
            throw new Exception("Impossible d'atterrir : vitesse doit être entre 80 et 110 km/h (actuelle: {$this->vitesse}).");
        }
        if ($this->altitude < 50 || $this->altitude > 150) {
            throw new Exception("Impossible d'atterrir : altitude doit être entre 50 et 150 m (actuelle: {$this->altitude}).");
        }

        $this->altitude = 0;
        $this->vitesse = 0;
        $this->enVol = false;
        $this->trainSorti = true;
        echo "Atterrissage réussi : altitude = 0 m, vitesse = 0 km/h.\n";
    }

    // fonction pour monter en altitude avec vérifications
    public function monterAltitude($m)
    {
        if (!$this->enVol || $this->altitude == 0) {
            throw new Exception("Impossible de prendre de l'altitude : l'avion n'a pas décollé.");
        }
        if ($m <= 0) {
            throw new Exception("Le gain d'altitude doit être positif.");
        }

        if ($this->altitude >= 300 && $this->trainSorti) {
            throw new Exception("Impossible de prendre de l'altitude : train d'atterrissage sorti et altitude >= 300 m. Rentrez le train d'abord.");
        }

        $nouvelle = $this->altitude + $m;
        if ($nouvelle > $this->plafond) {
            $nouvelle = $this->plafond;
            echo "Plafond de l'avion atteint ({$this->plafond} m).\n";
        }

        $this->altitude = $nouvelle;
        echo "⬆Altitude actuelle : {$this->altitude} m.\n";
    }

    // fonction pour descendre en altitude avec vérifications
    public function perdreAltitude($m)
    {
        if (!$this->enVol || $this->altitude == 0) {
            throw new Exception("Impossible de perdre de l'altitude : l'avion n'a pas décollé.");
        }
        if ($m <= 0) {
            throw new Exception("La perte d'altitude doit être positive.");
        }

        $this->altitude -= $m;
        if ($this->altitude < 0) $this->altitude = 0;

        if ($this->altitude == 0) {
            $this->enVol = false;
            $this->trainSorti = true;
        }
        echo "Altitude actuelle : {$this->altitude} m.\n";
    }

    // fonction pour sortir le train d'atterrissage
    public function sortirTrain()
    {
        if ($this->trainSorti) {
            echo "Le train est déjà sorti.\n";
            return;
        }
        $this->trainSorti = true;
        echo "Train d'atterrissage sorti.\n";
    }

    // On autorise la rentrée du train uniquement si l'altitude est supérieur à 300 mètres
    public function rentrerTrain()
    {
        if (!$this->enVol || $this->altitude == 0) {
            throw new Exception("Impossible de rentrer le train : l'avion est au sol ou pas en vol.");
        }
        if ($this->altitude < 300) {
            throw new Exception("Impossible de rentrer le train : altitude insuffisante (< 300 m).");
        }
        if (!$this->trainSorti) {
            echo "Le train est déjà rentré.\n";
            return;
        }
        $this->trainSorti = false;
        echo "Train d'atterrissage rentré.\n";
    }

    public function __toString()
    {
        $chaine = parent::__toString();
        $chaine .= "Type : Avion\n";
        $chaine .= "Vitesse actuelle : {$this->vitesse} km/h\n";
        $chaine .= "Vitesse max (avion) : {$this->vitesseMax} km/h\n";
        $chaine .= "Altitude : {$this->altitude} m\n";
        $chaine .= "Plafond : {$this->plafond} m\n";
        $chaine .= "Train : " . ($this->trainSorti ? "Sorti" : "Rentré") . "\n";
        $chaine .= "En vol : " . ($this->enVol ? "Oui" : "Non") . "\n\n";
        return $chaine;
    }
}

// ===================== TESTS =====================
$av1 = new Avion(800, 30000);
$av1->demarrer();
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->accelerer(100);
$av1->decoller();
$av1->monterAltitude(500);
$av1->rentrerTrain();
$av1->monterAltitude(3000);
$av1->accelerer(200);
$av1->perdreAltitude(3500);
$av1->decelerer(400);

$av1->sortirTrain();
$av1->decelerer(50);
$av1->atterrir();
$av1->eteindre();
echo $av1;