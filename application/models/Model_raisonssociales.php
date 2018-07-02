<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_raisonssociales extends MY_model {

    protected $table = 'raisonsSociales';

    const classe = 'RaisonSociale';

    /**
     * Ajout d'un objet de la classe RaisonSociale à la BDD
     * @param RaisonSociale $raisonSociale Objet de la classe RaisonSociale
     */
    public function ajouter(RaisonSociale $raisonSociale) {
        $this->db
                ->set('rsNom', $raisonSociale->getRsNom())
                ->set('rsInscription', $raisonSociale->getRsInscription())
                ->set('rsMoisFiscal', $raisonSociale->getRsMoisFiscal())
                ->set('rsCategorieNC', $raisonSociale->getRsCategorieNC())
                ->insert($this->table);
        $raisonSociale->setRsId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe RaisonSociale
     * @param RaisonSociale $raisonSociale Objet de la classe RaisonSociale
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(RaisonSociale $raisonSociale) {
        $this->db
                ->set('rsNom', $raisonSociale->getRsNom())
                ->set('rsInscription', $raisonSociale->getRsInscription())
                ->set('rsMoisFiscal', $raisonSociale->getRsMoisFiscal())
                ->set('rsCategorieNC', $raisonSociale->getRsCategorieNC())
                ->where('rsId', $raisonSociale->getRaisonSocialeId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param RaisonSociale Objet de la classe RaisonSociale
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(RaisonSociale $raisonSociale) {
        $this->db->where('rsId', $raisonSociale->getRsId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des RaisonSociales correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe RaisonSociale
     */
    public function liste($where = array(), $tri = 'rsNom DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe RaisonSociale correspondant à l'id
     * @param integer $raisonSocialeId ID de l'raisonSociale
     * @return \RaisonSociale|boolean
     */
    public function getRaisonSocialeById($rsId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('rsId', $rsId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
