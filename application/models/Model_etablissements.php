<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_etablissements extends MY_model {

    protected $table = 'etablissements';

    const classe = 'Etablissement';

    /**
     * Ajout d'un objet de la classe Etablissement à la BDD
     * @param Etablissement $etablissement Objet de la classe Etablissement
     */
    public function ajouter(Etablissement $etablissement) {
        $this->db
                ->set('rsNom', $etablissement->getRsNom())
                ->set('rsInscription', $etablissement->getRsInscription())
                ->set('rsMoisFiscal', $etablissement->getRsMoisFiscal())
                ->set('rsCategorieNC', $etablissement->getRsCategorieNC())
                ->insert($this->table);
        $etablissement->setRsId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Etablissement
     * @param Etablissement $etablissement Objet de la classe Etablissement
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Etablissement $etablissement) {
        $this->db
                ->set('rsNom', $etablissement->getRsNom())
                ->set('rsInscription', $etablissement->getRsInscription())
                ->set('rsMoisFiscal', $etablissement->getRsMoisFiscal())
                ->set('rsCategorieNC', $etablissement->getRsCategorieNC())
                ->where('rsId', $etablissement->getEtablissementId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Etablissement Objet de la classe Etablissement
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Etablissement $etablissement) {
        $this->db->where('rsId', $etablissement->getRsId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Etablissements correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Etablissement
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
     * Retourne un objet de la classe Etablissement correspondant à l'id
     * @param integer $etablissementId ID de l'raisonSociale
     * @return \Etablissement|boolean
     */
    public function getEtablissementById($etablissementId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('etablissementId', $etablissementId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
