<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_dossiers extends MY_model {

    protected $table = 'dossiers';

    const classe = 'Dossier';

    /**
     * Ajout d'un objet de la classe Dossier à la BDD
     * @param Dossier $dossier Objet de la classe Dossier
     */
    public function ajouter(Dossier $dossier) {
        $this->db
                ->set('dossierClient', $dossier->getDossierClient())
                ->set('dossierDescriptif', $dossier->getDossierDescriptif())
                //->set('dossierSortieEtat', $dossier->getDossierSortieEtat())
                ->set('dossierPao', $dossier->getDossierPao())
                ->set('dossierFab', $dossier->getDossierFab())
                ->set('dossierPose', $dossier->getDossierPose())
                ->set('dossierClos', $dossier->getDossierClos())
                ->insert($this->table);
        $dossier->setDossierId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Dossier
     * @param Dossier $dossier Objet de la classe Dossier
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Dossier $dossier) {
        $this->db
                ->set('dossierClient', $dossier->getDossierClient())
                ->set('dossierDescriptif', $dossier->getDossierDescriptif())
                //->set('dossierSortieEtat', $dossier->getDossierSortieEtat())
                ->set('dossierPao', $dossier->getDossierPao())
                ->set('dossierFab', $dossier->getDossierFab())
                ->set('dossierPose', $dossier->getDossierPose())
                ->set('dossierClos', $dossier->getDossierClos())
                ->where('dossierId', $dossier->getDossierId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe dossier
     *
     * @param Dossier Objet de la classe Dossier
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Dossier $dossier) {
        $this->db->where('dossierId', $dossier->getDossierId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Dossiers correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des dossiers
     * @param array $tri Critères de tri des dossiers
     * @return array Liste d'objets de la classe Dossier
     */
    public function liste($where = array(), $tri = 'dossierClient ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Dossier correspondant à l'id
     * @param type $ref
     * @return \Dossier|boolean
     */
    public function getDossierById($dossierId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('dossierId' => intval($dossierId)))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function dossiersPlanning($premierJour, $dernierJour, $type = 'array') {

        $query = $this->db->query('SELECT * FROM dossiers WHERE ( dossierFab = 1 AND dossierFabDebut <= ' . $dernierJour . ' AND dossierFabFin >= ' . $premierJour . ''
                . ') OR ( dossierPose = 1 AND dossierPoseDebut <= ' . $dernierJour . ' AND dossierPoseFin >= ' . $premierJour . ')');

        return $this->retourne($query, $type, self::classe);
    }

}
