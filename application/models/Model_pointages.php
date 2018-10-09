<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_pointages extends MY_model {

    protected $table = 'pointages';

    const classe = 'Pointage';

    /**
     * Ajout d'un objet de la classe Pointage à la BDD
     * @param Pointage $pointage Objet de la classe Pointage
     */
    public function ajouter(Pointage $pointage) {
        $this->db
                ->set('pointagePersonnelId', $pointage->getPointagePersonnelId())
                ->set('pointageMois', $pointage->getPointageMois())
                ->set('pointageAnnee', $pointage->getPointageAnnee())
                ->set('pointageHTML', $pointage->getPointageHTML())
                ->insert($this->table);
        $pointage->setPointageId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Pointage
     * @param Pointage $pointage Objet de la classe Pointage
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Pointage $pointage) {
        $this->db
                ->set('pointagePersonnelId', $pointage->getPointagePersonnelId())
                ->set('pointageMois', $pointage->getPointageMois())
                ->set('pointageAnnee', $pointage->getPointageAnnee())
                ->set('pointageHTML', $pointage->getPointageHTML())
                ->where('pointageId', $pointage->getPointageId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Pointage Objet de la classe Pointage
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Pointage $pointage) {
        $this->db->where('pointageId', $pointage->getPointageId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Pointages correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Pointage
     */
    public function getPointage($personnelId = null, $mois, $annee, $type = 'object') {
        $query = $this->db->select('*')
                ->from('pointages p')
                ->join('personnels pe', 'pe.personnelId = p.pointagePersonnelId', 'left')
                ->where('pe.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where(array('pointagePersonnelId' => $personnelId, 'pointageMois' => $mois, 'pointageAnnee' => $annee))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne un objet de la classe Pointage correspondant à l'id
     * @param integer $pointageId ID de l'raisonSociale
     * @return \Pointage|boolean
     */
    public function getPointageById($pointageId, $type = 'object') {
        $query = $this->db->select('*')
                ->from('pointages p')
                ->join('personnels pe', 'pe.personnelId = p.pointagePersonnelId', 'left')
                ->where('pe.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where('pointageId', $pointageId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
