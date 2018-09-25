<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_fournisseurs extends MY_model {

    protected $table = 'fournisseurs';

    const classe = 'Fournisseur';

    /**
     * Ajout d'un objet de la classe Fournisseur à la BDD
     * @param Fournisseur $fournisseur Objet de la classe Fournisseur
     */
    public function ajouter(Fournisseur $fournisseur) {
        $this->db
                ->set('fournisseurOriginId', $fournisseur->getFournisseurOriginId())
                ->set('fournisseurEtablissementId', !empty($fournisseur->getFournisseurEtablissementId()) ?: $this->session->userdata('etablissementId'))
                ->set('fournisseurNom', $fournisseur->getFournisseurNom())
                ->set('fournisseurAdresse', $fournisseur->getFournisseurAdresse())
                ->set('fournisseurCp', $fournisseur->getFournisseurCp())
                ->set('fournisseurVille', $fournisseur->getFournisseurVille())
                ->set('fournisseurTelephone', $fournisseur->getFournisseurTelephone())
                ->set('fournisseurEmail', $fournisseur->getFournisseurEmail())
                ->insert($this->table);
        $fournisseur->setFournisseurId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Fournisseur
     * @param Fournisseur $fournisseur Objet de la classe Fournisseur
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Fournisseur $fournisseur) {
        $this->db
                ->set('fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->set('fournisseurNom', $fournisseur->getFournisseurNom())
                ->set('fournisseurAdresse', $fournisseur->getFournisseurAdresse())
                ->set('fournisseurCp', $fournisseur->getFournisseurCp())
                ->set('fournisseurVille', $fournisseur->getFournisseurVille())
                ->set('fournisseurTelephone', $fournisseur->getFournisseurTelephone())
                ->set('fournisseurEmail', $fournisseur->getFournisseurEmail())
                ->where('fournisseurId', $fournisseur->getFournisseurId())
                ->where('fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Fournisseur Objet de la classe Fournisseur
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Fournisseur $fournisseur) {
        $this->db
                ->where('fournisseurId', $fournisseur->getFournisseurId())
                ->where('fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Fournisseurs correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Fournisseur
     */
    public function getFournisseurs($where = array(), $tri = 'fournisseurNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Fournisseur correspondant à l'id
     * @param integer $fournisseurId ID de l'raisonSociale
     * @return \Fournisseur|boolean
     */
    public function getFournisseurById($fournisseurId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('fournisseurEtablissementId', $this->session->userdata('etablissementId'))
                ->where('fournisseurId', $fournisseurId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getFournisseurByOriginId($fournisseurOriginId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('fournisseurOriginId', $fournisseurOriginId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
