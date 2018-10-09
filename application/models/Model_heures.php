<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_heures extends MY_model {

    protected $table = 'heures';

    const classe = 'Heure';

    /**
     * Ajout d'un objet de la classe Heure à la BDD
     * @param Heure $heure Objet de la classe Heure
     */
    public function ajouter(Heure $heure) {
        $this->db
                ->set('heureOriginId', $heure->getHeureOriginId())
                ->set('heureAffectationId', $heure->getHeureAffectationId())
                ->set('heureDate', $heure->getHeureDate())
                ->set('heureDuree', $heure->getHeureDuree())
                ->set('heureValide', $heure->getHeureValide())
                ->insert($this->table);
        $heure->setHeureId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Heure
     * @param Heure $heure Objet de la classe Heure
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Heure $heure) {
        $this->db
                ->set('heureAffectationId', $heure->getHeureAffectationId())
                ->set('heureDate', $heure->getHeureDate())
                ->set('heureDuree', $heure->getHeureDuree())
                ->set('heureValide', $heure->getHeureValide())
                ->where('heureId', $heure->getHeureId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Heure Objet de la classe Heure
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Heure $heure) {
        $this->db->where('heureId', $heure->getHeureId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Heures correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Heure
     */
    public function getHeures($where = array(), $tri = 'h.heureDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('heures h')
                ->join('affectations a', 'a.affectationId = h.heureAffectationId', 'left')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires af', 'af.affaireId = c.chantierAffaireId', 'left')
                ->where('af.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Heure correspondant à l'id
     * @param integer $heureId ID de l'raisonSociale
     * @return \Heure|boolean
     */
    public function getHeureById($heureId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('heures h')
                ->join('affectations a', 'a.affectationId = h.heureAffectationId', 'left')
                ->join('chantiers c', 'c.chantierId = a.AffectationChantierId', 'left')
                ->join('affaires f', 'f.affaireId = c.ChantierAffaireId', 'left')
                ->where('f.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('heureId', $heureId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getHeureByOriginId($originId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('heureOriginId', $originId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getHeuresByAffectationId($affectationId, $tri = 'heureDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('heures h')
                ->join('affectations a', 'a.affectationId = h.heureAffectationId', 'left')
                ->join('chantiers c', 'c.chantierId = a.AffectationChantierId', 'left')
                ->join('affaires f', 'f.affaireId = c.ChantierAffaireId', 'left')
                ->where('f.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('heureAffectationId', $affectationId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
