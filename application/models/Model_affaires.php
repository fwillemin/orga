<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_affaires extends MY_model {

    protected $table = 'affaires';

    const classe = 'Affaire';

    /**
     * Ajout d'un objet de la classe Affaire à la BDD
     * @param Affaire $affaire Objet de la classe Affaire
     */
    public function ajouter(Affaire $affaire) {
        $this->db
                //->set('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->set('affaireEtablissementId', $affaire->getAffaireEtablissementId())
                ->set('affaireOriginId', $affaire->getAffaireOriginId())
                ->set('affaireCreation', $affaire->getAffaireCreation())
                ->set('affaireClientId', $affaire->getAffaireClientId())
                ->set('affairePlaceId', $affaire->getAffairePlaceId())
                ->set('affaireCommercialId', $affaire->getAffaireCommercialId())
                ->set('affaireCategorieId', $affaire->getAffaireCategorieId())
                ->set('affaireDevis', $affaire->getAffaireDevis())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affairePrix', $affaire->getAffairePrix())
                ->set('affaireDateSignature', $affaire->getAffaireDateSignature())
                ->set('affaireDateCloture', $affaire->getAffaireDateCloture())
                ->set('affaireEtat', $affaire->getAffaireEtat())
                ->set('affaireCouleur', $affaire->getAffaireCouleur())
                ->set('affaireCouleurSecondaire', $affaire->getAffaireCouleurSecondaire())
                ->set('affaireRemarque', $affaire->getAffaireRemarque())
                ->insert($this->table);
        $affaire->setAffaireId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Affaire
     * @param Affaire $affaire Objet de la classe Affaire
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Affaire $affaire) {
        $this->db
                ->set('affaireClientId', $affaire->getAffaireClientId())
                ->set('affaireCreation', $affaire->getAffaireCreation())
                ->set('affaireCommercialId', $affaire->getAffaireCommercialId())
                ->set('affaireCategorieId', $affaire->getAffaireCategorieId())
                ->set('affairePlaceId', $affaire->getAffairePlaceId())
                ->set('affaireDevis', $affaire->getAffaireDevis())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affairePrix', $affaire->getAffairePrix())
                ->set('affaireDateSignature', $affaire->getAffaireDateSignature())
                ->set('affaireDateCloture', $affaire->getAffaireDateCloture())
                ->set('affaireEtat', $affaire->getAffaireEtat())
                ->set('affaireCouleur', $affaire->getAffaireCouleur())
                ->set('affaireCouleurSecondaire', $affaire->getAffaireCouleurSecondaire())
                ->set('affaireRemarque', $affaire->getAffaireRemarque())
                ->where('affaireId', $affaire->getAffaireId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Affaire Objet de la classe Affaire
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Affaire $affaire) {
        $this->db->where('affaireId', $affaire->getAffaireId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Affaires correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Affaire
     */
    public function getAffaires($where = array(), $tri = 'affaireCreation ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAffairesCreationNull($tri = 'affaireCreation ASC', $type = 'object') {
        $query = $this->db->query("SELECT * FROM affaires WHERE affaireCreation IS NULL AND affaireEtablissementId = " . $this->session->userdata('etablissementId'));

        return $this->retourne($query, $type, self::classe);
    }

    public function getAffairesPlanning($affairesCloturees = array(), $tri = 'c.ClientNom ASC', $type = 'object') {
        if (!empty($affairesCloturees)):
            $query = $this->db->select('*')
                    ->from('affaires a')
                    ->join('clients c', 'c.clientId = a.affaireClientId', 'left')
                    ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                    ->group_start()
                    ->where('a.affaireEtat', 2)
                    ->or_where_in('a.affaireId', $affairesCloturees)
                    ->group_end()
                    ->order_by($tri)
                    ->get();
        else:
            $query = $this->db->select('*')
                    ->from('affaires a')
                    ->join('clients c', 'c.clientId = a.affaireClientId', 'left')
                    ->where('a.affaireEtablissementId', $this->session->userdata('etablissementId'))
                    ->where('a.affaireEtat', 2)
                    ->order_by($tri)
                    ->get();
        endif;
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Affaire correspondant à l'id
     * @param integer $affaireId ID de l'raisonSociale
     * @return \Affaire|boolean
     */
    public function getAffaireById($affaireId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('affaireId', $affaireId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffaireByOriginId($originId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireOriginId', $originId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffairesByClientId($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('affaireClientId', $clientId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLastAffaireByClientId($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('affaireClientId', $clientId)
                ->order_by('affaireDateSignature DESC')
                ->limit(1, 0)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getAffairesByEtablissementId($etablissementId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $etablissementId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
