<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_achats extends MY_model {

    protected $table = 'achats';

    const classe = 'Achat';

    /**
     * Ajout d'un objet de la classe Achat à la BDD
     * @param Achat $achat Objet de la classe Achat
     */
    public function ajouter(Achat $achat) {
        $this->db
                ->set('achatLivraisonOriginId', $achat->getAchatLivraisonOriginId())
                ->set('achatChantierId', $achat->getAchatChantierId())
                ->set('achatDate', $achat->getAchatDate())
                ->set('achatFournisseurId', $achat->getAchatFournisseurId())
                ->set('achatLivraisonDate', $achat->getAchatLivraisonDate())
                ->set('achatLivraisonAvancement', $achat->getAchatLivraisonAvancement())
                ->set('achatDescription', $achat->getAchatDescription())
                ->set('achatType', $achat->getAchatType())
                ->set('achatQtePrevisionnel', $achat->getAchatQtePrevisionnel())
                ->set('achatPrixPrevisionnel', $achat->getAchatPrixPrevisionnel())
                ->set('achatQte', $achat->getAchatQte())
                ->set('achatPrix', $achat->getAchatPrix())
                ->insert($this->table);
        $achat->setAchatId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Achat
     * @param Achat $achat Objet de la classe Achat
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Achat $achat) {
        $this->db
                ->set('achatDate', $achat->getAchatDate())
                ->set('achatFournisseurId', $achat->getAchatFournisseurId())
                ->set('achatLivraisonDate', $achat->getAchatLivraisonDate())
                ->set('achatLivraisonAvancement', $achat->getAchatLivraisonAvancement())
                ->set('achatDescription', $achat->getAchatDescription())
                ->set('achatType', $achat->getAchatType())
                ->set('achatQtePrevisionnel', $achat->getAchatQtePrevisionnel())
                ->set('achatPrixPrevisionnel', $achat->getAchatPrixPrevisionnel())
                ->set('achatQte', $achat->getAchatQte())
                ->set('achatPrix', $achat->getAchatPrix())
                ->where('achatId', $achat->getAchatId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Achat Objet de la classe Achat
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Achat $achat) {
        $this->db->where('achatId', $achat->getAchatId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Achats correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Achat
     */
    public function getAchats($where = array(), $tri = 'achatDate ASC', $type = 'object') {
        $query = $this->db->select('a.*')
                ->from('achats a')
                ->join('chantiers c', 'c.chantierId = a.achatChantierId')
                ->join('affaires af', 'af.affaireId = c.chantierAffaireId')
                ->where('af.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAchatsPlanning($dateDebut, $dateFin, $tri = 'achatLivraisonDate ASC', $type = 'object') {

        $selection = array('a.achatLivraisonDate >=' => $dateDebut);
        if ($dateFin):
            $selection['a.achatLivraisonDate <='] = $dateFin;
        endif;
        $query = $this->db->select('*')
                ->from('achats a')
                ->join('chantiers c', 'c.chantierId = a.achatChantierId')
                ->join('affaires af', 'af.affaireId = c.chantierAffaireId')
                ->where('af.affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('c.chantierEtat', 1)
                ->where($selection)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Achat correspondant à l'id
     * @param integer $achatId ID de l'raisonSociale
     * @return \Achat|boolean
     */
    public function getAchatById($achatId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('achatId', $achatId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAchatsByFournisseurId($fournisseurId, $where = array(), $tri = 'achatDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('achats a')
                ->join('fournisseurs f', 'f.fournisseurId = a.achatFournisseurId')
                ->where('f.fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->where('achatFournisseurId', $fournisseurId)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAchatsByAffectationId($affectId, $where = array(), $tri = 'achatDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from('achats a')
                ->join('achats_affectations aa', 'aa.achatId = a.achatId', 'LEFT')
                ->where('aa.affectationId', $affectId)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
