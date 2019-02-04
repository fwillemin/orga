<?php

/**
 * Classe de gestion des Performances du personnel sur les chantiers
 * Manager : Model_PerformanceChantiersPersonnels
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class PerformanceChantierPersonnel {

    protected $performanceChantierId;
    protected $performancePersonnelId;
    protected $performancePersonnel;
    protected $performanceHeuresPointees;
    protected $performanceTauxParticipation;
    protected $performanceImpactHeures;
    protected $performanceImpactTaux;

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

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->performancePersonnel = $CI->managerPersonnels->getPersonnelById($this->performancePersonnelId);
    }

    function getPerformanceChantierId() {
        return $this->performanceChantierId;
    }

    function getPerformancePersonnelId() {
        return $this->performancePersonnelId;
    }

    function getPerformanceTauxParticipation() {
        return $this->performanceTauxParticipation;
    }

    function getPerformanceImpactHeures() {
        return $this->performanceImpactHeures;
    }

    function getPerformanceImpactTaux() {
        return $this->performanceImpactTaux;
    }

    function setPerformanceChantierId($performanceChantierId) {
        $this->performanceChantierId = $performanceChantierId;
    }

    function setPerformancePersonnelId($performancePersonnelId) {
        $this->performancePersonnelId = $performancePersonnelId;
    }

    function setPerformanceTauxParticipation($performanceTauxParticipation) {
        $this->performanceTauxParticipation = $performanceTauxParticipation;
    }

    function setPerformanceImpactHeures($performanceImpactHeures) {
        $this->performanceImpactHeures = $performanceImpactHeures;
    }

    function setPerformanceImpactTaux($performanceImpactTaux) {
        $this->performanceImpactTaux = $performanceImpactTaux;
    }

    function getPerformancePersonnel() {
        return $this->performancePersonnel;
    }

    function getPerformanceHeuresPointees() {
        return $this->performanceHeuresPointees;
    }

    function setPerformancePersonnel($performancePersonnel) {
        $this->performancePersonnel = $performancePersonnel;
    }

    function setPerformanceHeuresPointees($performanceHeuresPointees) {
        $this->performanceHeuresPointees = $performanceHeuresPointees;
    }

}