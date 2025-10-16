<?php

abstract class Vehicule 
{
    protected $demarrer = FALSE;
    protected $vitesse = 0;
    protected $vitesseMax;

    // On oblige les classes filles à définir les méthodes abstract
    abstract function decelerer($vitesse);
    abstract function accelerer($vitesse);

    // Fonction pour démarrer le véhicule
    public function demarrer() 
    {
        $this->demarrer = TRUE;
    }

    // Fonction pour éteindre le véhicule
    public function eteindre() 
    {
        $this->demarrer = FALSE;
    }

    // Vérifier si le véhicule est démarré
    public function estDemarre() 
    {
        return $this->demarrer;
    }

    // Vérifier si le véhicule est éteint
    public function estEteint() 
    {
        return !$this->demarrer;
    }

    // Obtenir la vitesse actuelle
    public function getVitesse() 
    {
        return $this->vitesse;
    }

    // Obtenir la vitesse maximale
    public function getVitesseMax() 
    {
        return $this->vitesseMax;
    }

    // Méthode magique toString pour afficher un véhicule
    public function __toString() 
    {
        $chaine = "Ceci est un véhicule <br/>";
        $chaine .= "---------------------- <br/>";
        return $chaine;
    }
}
