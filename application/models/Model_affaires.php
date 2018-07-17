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
                ->set('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->set('affaireClientId', $affaire->getAffaireClientId())
                ->set('affaireCommercialId', $affaire->getAffaireCommercialId())
                ->set('affaireCategorieId', $affaire->getAffaireCategorieId())
                ->set('affaireDevis', $affaire->getAffaireDevis())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affairePrix', $affaire->getAffairePrix())
                ->set('affaireDateSignature', $affaire->getAffaireDateSignature())
                ->set('affaireDateCloture', $affaire->getAffaireDateCloture())
                ->set('affaireEtat', $affaire->getAffaireEtat())
                ->set('affaireCouleur', $affaire->getAffaireCouleur())
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
                ->set('affaireCommercialId', $affaire->getAffaireCommercialId())
                ->set('affaireCategorieId', $affaire->getAffaireCategorieId())
                ->set('affaireDevis', $affaire->getAffaireDevis())
                ->set('affaireObjet', $affaire->getAffaireObjet())
                ->set('affairePrix', $affaire->getAffairePrix())
                ->set('affaireDateSignature', $affaire->getAffaireDateSignature())
                ->set('affaireDateCloture', $affaire->getAffaireDateCloture())
                ->set('affaireEtat', $affaire->getAffaireEtat())
                ->set('affaireCouleur', $affaire->getAffaireCouleur())
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
    public function getAffaires($where = array(), $tri = 'affaireNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
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

}