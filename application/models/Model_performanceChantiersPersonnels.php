<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_performanceChantiersPersonnels extends MY_model {

    protected $table = 'performanceChantiersPersonnels';

    const classe = 'PerformanceChantierPersonnel';

    public function ajouter(PerformanceChantierPersonnel $performance) {
        $this->db
                ->set('performanceChantierId', $performance->getPerformanceChantierId())
                ->set('performancePersonnelId', $performance->getPerformancePersonnelId())
                ->set('performanceHeuresPointees', $performance->getPerformanceHeuresPointees())
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
                ->where('performanceChantierId', $chantier->getChantierId())
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getPerformancesByPersonnel(Personnel $personnel, $annee, $type = 'object') {
        $query = $this->db->select('p.*, c.chantierDateCloture AS performanceChantierDateCloture, cl.clientNom AS performanceClientNom')
                ->from($this->table . ' p')
                ->join('chantiers c', 'c.chantierId = p.performanceChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->join('clients cl', 'cl.clientId = a.affaireClientId')
                ->where(array("p.performancePersonnelId" => $personnel->getPersonnelId(), "FROM_UNIXTIME(c.chantierDateCloture, '%Y') =" => $annee))
                ->order_by('c.chantierDateCloture ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getPerformancesByPersonnelRangeTaux(Personnel $personnel, $annee, $min, $max, $type = 'object') {
        $query = $this->db->select('p.*')
                ->from($this->table . ' p')
                ->join('chantiers c', 'c.chantierId = p.performanceChantierId')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireId <>', $this->session->userdata('affaireDiversId'))
                ->where(array("p.performancePersonnelId" => $personnel->getPersonnelId(), "FROM_UNIXTIME(c.chantierDateCloture, '%Y') =" => $annee, 'p.performanceImpactTaux >= ' => $min, 'p.performanceImpactTaux <' => $max))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
