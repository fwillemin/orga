<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_tauxhoraires extends MY_model {

    protected $table = 'tauxHoraires';

    const classe = 'tauxHoraire';

    /**
     * Ajout d'un objet de la classe TauxHoraire à la BDD
     * @param TauxHoraire $tauxHoraire Objet de la classe TauxHoraire
     */
    public function ajouter(TauxHoraire $tauxHoraire) {
        $this->db
                ->set('tauxHorairePersonnelId', $tauxHoraire->getTauxHorairePersonnelId())
                ->set('tauxHoraire', $tauxHoraire->getTauxHoraire())
                ->set('tauxHoraireDate', $tauxHoraire->getTauxHoraireDate())
                ->insert($this->table);
        $tauxHoraire->setTauxHoraireId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe TauxHoraire
     * @param TauxHoraire $tauxHoraire Objet de la classe TauxHoraire
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(TauxHoraire $tauxHoraire) {
        $this->db
                ->set('tauxHoraire', $tauxHoraire->getTauxHoraire())
                ->set('tauxHoraireDate', $tauxHoraire->getTauxHoraireDate())
                ->where('tauxHoraireId', $tauxHoraire->getTauxHoraireId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param TauxHoraire Objet de la classe TauxHoraire
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(TauxHoraire $tauxHoraire) {
        $this->db->where('tauxHoraireId', $tauxHoraire->getTauxHoraireId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des TauxHoraires correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe TauxHoraire
     */
    public function getTauxHoraires($where = array(), $tri = 'tauxHoraireDate DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->join('personnels p', 'p.personnelId = tauxHorairePersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe TauxHoraire correspondant à l'id
     * @param integer $tauxHoraireId ID de l'raisonSociale
     * @return \TauxHoraire|boolean
     */
    public function getTauxHoraireById($tauxHoraireId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->join('personnels p', 'p.personnelId = tauxHorairePersonnelId', 'left')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where('tauxHoraireId', $tauxHoraireId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
