<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_personnels extends MY_model {

    protected $table = 'personnels';

    const classe = 'Personnel';

    /**
     * Ajout d'un objet de la classe Personnel à la BDD
     * @param Personnel $personnel Objet de la classe Personnel
     */
    public function ajouter(Personnel $personnel) {
        $this->db
                ->set('personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->set('personnelNom', $personnel->getPersonnelNom())
                ->set('personnelPrenom', $personnel->getPersonnelPrenom())
                ->set('personnelQualif', $personnel->getPersonnelQualif())
                ->set('personnelCode', $personnel->getPersonnelCode())
                ->set('personnelMessage', $personnel->getPersonnelMessage())
                ->set('personnelHoraireId', $personnel->getPersonnelHoraireId())
                ->set('personnelEquipeId', $personnel->getPersonnelEquipeId())
                ->set('personnelActif', 1)
                ->insert($this->table);
        $personnel->setPersonnelId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Personnel
     * @param Personnel $personnel Objet de la classe Personnel
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Personnel $personnel) {
        $this->db
                ->set('personnelNom', $personnel->getPersonnelNom())
                ->set('personnelPrenom', $personnel->getPersonnelPrenom())
                ->set('personnelQualif', $personnel->getPersonnelQualif())
                ->set('personnelCode', $personnel->getPersonnelCode())
                ->set('personnelMessage', $personnel->getPersonnelMessage())
                ->set('personnelHoraireId', $personnel->getPersonnelHoraireId())
                ->set('personnelEquipeId', $personnel->getPersonnelEquipeId())
                ->set('personnelActif', $personnel->getPersonnelActif())
                ->where('personnelId', $personnel->getPersonnelId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Personnel Objet de la classe Personnel
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Personnel $personnel) {
        $this->db->where('personnelId', $personnel->getPersonnelId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Personnels correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Personnel
     */
    public function getPersonnels($where = array(), $tri = 'personnelActif DESC, personnelNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Personnel correspondant à l'id
     * @param integer $personnelId ID de l'raisonSociale
     * @return \Personnel|boolean
     */
    public function getPersonnelById($personnelId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where('personnelId', $personnelId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
