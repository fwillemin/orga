<?php

/**
 * Classe de gestion des Categories
 * Manager : Model_Categories
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Categorie {

    protected $categorieId;
    protected $categorieRsId;
    protected $categorieNom;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
    }

    function getCategorieId() {
        return $this->categorieId;
    }

    function getCategorieRsId() {
        return $this->categorieRsId;
    }

    function getCategorieNom() {
        return $this->categorieNom;
    }

    function setCategorieId($categorieId) {
        $this->categorieId = $categorieId;
    }

    function setCategorieRsId($categorieRsId) {
        $this->categorieRsId = $categorieRsId;
    }

    function setCategorieNom($categorieNom) {
        $this->categorieNom = $categorieNom;
    }

}
