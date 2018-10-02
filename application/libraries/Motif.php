<?php

/**
 * Classe de gestion des Motifs
 * Manager : Model_Motifs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Motif {

    protected $motifId;
    protected $motifNom;
    protected $motifGroupe;

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

    function getMotifId() {
        return $this->motifId;
    }

    function getMotifNom() {
        return $this->motifNom;
    }

    function getMotifGroupe() {
        return $this->motifGroupe;
    }

    function setMotifId($motifId) {
        $this->motifId = $motifId;
    }

    function setMotifNom($motifNom) {
        $this->motifNom = $motifNom;
    }

    function setMotifGroupe($motifGroupe) {
        $this->motifGroupe = $motifGroupe;
    }

}
