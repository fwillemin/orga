<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_performanceChantiersPersonnels extends MY_model {

    protected $table = 'performanceChantiersPersonnels';

    const classe = 'PerformanceChantiersPersonnels';

    public function ajouter(PerformanceChantiersPersonnels $performance) {
        $this->db
                ->set('performanceChantierId', $performance->getPerformanceChantierId())
                ->set('performancePersonnelId', $performance->getPerformancePersonnelId())
                ->set('performanceTauxParticipation', $performance->getPerformanceTauxParticipation())
                ->set('performanceImpactHeures', $performance->getPerformanceImpactHeures())
                ->set('performanceImpactTaux', $performance->getPerformanceImpactTaux())
                ->insert($this->table);
    }

    public function deleteFromChantierId(Chantier $chantier) {
        $this->db->where('performanceChantierId', $chantier->getChantierId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getPerformancesByChantierId(Chantier $chantier, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->join('')
                ->where('performanceChantierId', $chantier->getChantierId())
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
