<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affectations extends MY_model {

    protected $table = 'affectations';

    const classe = 'Affectation';

    /**
     * Ajout d'un objet de la classe Affectation à la BDD
     * @param Affectation $affectation Objet de la classe Affectation
     */
    public function ajouter(Affectation $affectation) {
        $this->db
                ->set('affectationOriginId', $affectation->getAffectationOriginId())
                ->set('affectationChantierId', $affectation->getAffectationChantierId())
                ->set('affectationPersonnelId', $affectation->getAffectationPersonnelId())
                ->set('affectationPlaceId', $affectation->getAffectationPlaceId())
                ->set('affectationNbDemi', $affectation->getAffectationNbDemi())
                ->set('affectationDebutDate', $affectation->getAffectationDebutDate())
                ->set('affectationDebutMoment', $affectation->getAffectationDebutMoment())
                ->set('affectationFinDate', $affectation->getAffectationFinDate())
                ->set('affectationFinMoment', $affectation->getAffectationFinMoment())
                ->set('affectationCases', $affectation->getAffectationCases())
                ->set('affectationHeuresPlanifiees', $affectation->getAffectationHeuresPlanifiees())
                ->set('affectationHeuresPointees', 0)
                ->set('affectationCommentaire', $affectation->getAffectationCommentaire())
                ->set('affectationType', $affectation->getAffectationType())
                ->set('affectationAffichage', $affectation->getAffectationAffichage())
                ->insert($this->table);
        $affectation->setAffectationId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Affectation
     * @param Affectation $affectation Objet de la classe Affectation
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Affectation $affectation) {
        try {
            $this->db
                    ->set('affectationOriginId', $affectation->getAffectationOriginId())
                    ->set('affectationChantierId', $affectation->getAffectationChantierId())
                    ->set('affectationPersonnelId', $affectation->getAffectationPersonnelId())
                    ->set('affectationPlaceId', $affectation->getAffectationPlaceId())
                    ->set('affectationNbDemi', $affectation->getAffectationNbDemi())
                    ->set('affectationDebutDate', $affectation->getAffectationDebutDate())
                    ->set('affectationDebutMoment', $affectation->getAffectationDebutMoment())
                    ->set('affectationFinDate', $affectation->getAffectationFinDate())
                    ->set('affectationFinMoment', $affectation->getAffectationFinMoment())
                    ->set('affectationCases', $affectation->getAffectationCases())
                    ->set('affectationHeuresPlanifiees', $affectation->getAffectationHeuresPlanifiees())
//                ->set('affectationHeuresPointees', $affectation->getAffectationHeuresPointees())
                    ->set('affectationCommentaire', $affectation->getAffectationCommentaire())
                    ->set('affectationType', $affectation->getAffectationType())
                    ->set('affectationAffichage', $affectation->getAffectationAffichage())
                    ->where('affectationId', $affectation->getAffectationId())
                    ->update($this->table);
        } catch (Exception $e) {
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($e, true));
        }
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe affectation
     *
     * @param Affectation Objet de la classe Affectation
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Affectation $affectation) {
        $this->db->where('affectationId', $affectation->getAffectationId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Affectations correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des affectations
     * @param array $tri Critères de tri des affectations
     * @return array Liste d'objets de la classe Affectation
     */
    public function getAffectations($where = array(), $tri = 'affectationDebutDate ASC', $type = 'object') {
        $query = $this->db->select('a.*, c.chantierEtat AS affectationChantierEtat')
                ->from('affectations a')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires d', 'd.affaireId = c.chantierAffaireId', 'left')
                ->where('d.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Affectation correspondant à l'id
     * @param type $ref
     * @return \Affectation|boolean
     */
    public function getAffectationById($affectationId, $type = 'object') {
        $query = $this->db->select('a.*, c.chantierEtat AS affectationChantierEtat')
                ->from('affectations a')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires d', 'd.affaireId = c.chantierAffaireId', 'left')
                ->where('d.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where(array('a.affectationId' => $affectationId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffectationPlanningById($affectationId, $type = 'object') {
        $query = $this->db->select('a.*, c.chantierEtat AS affectationChantierEtat,'
                        . ' d.affaireId AS affectationAffaireId,'
                        . ' d.affaireEtat AS affectationAffaireEtat,'
                        . ' c.chantierCouleur AS affectationChantierCouleur,'
                        . ' c.chantierCouleurSecondaire AS affectationChantierCouleurSecondaire,'
                        . ' c.chantierObjet AS affectationChantierObjet,'
                        . ' cl.clientNom AS affectationClientNom')
                ->from('affectations a')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires d', 'd.affaireId = c.chantierAffaireId', 'left')
                ->join('clients cl', 'cl.clientId = d.affaireClientId')
                ->where('d.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where(array('a.affectationId' => $affectationId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffectationByOriginId($affectationId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('affectations a')
                ->where(array('a.affectationOriginId' => $affectationId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffectationsPlanning($premierJour, $dernierJour, $etat = 1, $tri = 'affectationDebutDate ASC', $type = 'object') {

        $selection = array('a.affectationFinDate >=' => $premierJour, 'c.chantierEtat <=' => $etat);
        if ($dernierJour):
            $selection['a.affectationDebutDate <='] = $dernierJour;
        endif;

        $query = $this->db->select('a.*, c.chantierEtat AS affectationChantierEtat,'
                        . ' d.affaireId AS affectationAffaireId,'
                        . ' d.affaireEtat AS affectationAffaireEtat,'
                        . ' c.chantierCouleur AS affectationChantierCouleur,'
                        . ' c.chantierCouleurSecondaire AS affectationChantierCouleurSecondaire,'
                        . ' c.chantierObjet AS affectationChantierObjet,'
                        . ' cl.clientNom AS affectationClientNom')
                ->from('affectations a')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires d', 'd.affaireId = c.chantierAffaireId', 'left')
                ->join('clients cl', 'cl.clientId = d.affaireClientId')
                ->where('d.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($selection)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAffectationsSaisie($personnelId, $premierJour, $dernierJour, $etat = 1, $tri = 'affectationDebutDate ASC', $type = 'object') {

        $selection = array('a.affectationFinDate >=' => $premierJour, 'c.chantierEtat <=' => $etat, 'affectationPersonnelId' => $personnelId);
        if ($dernierJour):
            $selection['a.affectationDebutDate <='] = $dernierJour;
        endif;

        $query = $this->db->select('a.*, c.chantierEtat AS affectationChantierEtat')
                ->from('affectations a')
                ->join('chantiers c', 'c.chantierId = a.affectationChantierId', 'left')
                ->join('affaires d', 'd.affaireId = c.chantierAffaireId', 'left')
                ->where('d.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($selection)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
