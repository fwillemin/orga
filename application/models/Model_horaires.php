<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_horaires extends MY_model {

    protected $table = 'horaires';

    const classe = 'Horaire';

    /**
     * Ajout d'un objet de la classe Horaire à la BDD
     * @param Horaire $horaire Objet de la classe Horaire
     */
    public function ajouter(Horaire $horaire) {
        $this->db
                ->set('horaireNom', $horaire->getHoraireNom())
                ->set('horaireEtablissementId', $this->session->userdata('etablissementId'))
                ->set('horaireLun1', $horaire->getHoraireLun1())
                ->set('horaireLun2', $horaire->getHoraireLun2())
                ->set('horaireLun3', $horaire->getHoraireLun3())
                ->set('horaireLun4', $horaire->getHoraireLun4())
                ->set('horaireMar1', $horaire->getHoraireMar1())
                ->set('horaireMar2', $horaire->getHoraireMar2())
                ->set('horaireMar3', $horaire->getHoraireMar3())
                ->set('horaireMar4', $horaire->getHoraireMar4())
                ->set('horaireMer1', $horaire->getHoraireMer1())
                ->set('horaireMer2', $horaire->getHoraireMer2())
                ->set('horaireMer3', $horaire->getHoraireMer3())
                ->set('horaireMer4', $horaire->getHoraireMer4())
                ->set('horaireJeu1', $horaire->getHoraireJeu1())
                ->set('horaireJeu2', $horaire->getHoraireJeu2())
                ->set('horaireJeu3', $horaire->getHoraireJeu3())
                ->set('horaireJeu4', $horaire->getHoraireJeu4())
                ->set('horaireVen1', $horaire->getHoraireVen1())
                ->set('horaireVen2', $horaire->getHoraireVen2())
                ->set('horaireVen3', $horaire->getHoraireVen3())
                ->set('horaireVen4', $horaire->getHoraireVen4())
                ->set('horaireSam1', $horaire->getHoraireSam1())
                ->set('horaireSam2', $horaire->getHoraireSam2())
                ->set('horaireSam3', $horaire->getHoraireSam3())
                ->set('horaireSam4', $horaire->getHoraireSam4())
                ->set('horaireDim1', $horaire->getHoraireDim1())
                ->set('horaireDim2', $horaire->getHoraireDim2())
                ->set('horaireDim3', $horaire->getHoraireDim3())
                ->set('horaireDim4', $horaire->getHoraireDim4())
                ->insert($this->table);
        $horaire->setHoraireId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Horaire
     * @param Horaire $horaire Objet de la classe Horaire
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Horaire $horaire) {
        $this->db
                ->set('horaireNom', $horaire->getHoraireNom())
                ->set('horaireLun1', $horaire->getHoraireLun1())
                ->set('horaireLun2', $horaire->getHoraireLun2())
                ->set('horaireLun3', $horaire->getHoraireLun3())
                ->set('horaireLun4', $horaire->getHoraireLun4())
                ->set('horaireMar1', $horaire->getHoraireMar1())
                ->set('horaireMar2', $horaire->getHoraireMar2())
                ->set('horaireMar3', $horaire->getHoraireMar3())
                ->set('horaireMar4', $horaire->getHoraireMar4())
                ->set('horaireMer1', $horaire->getHoraireMer1())
                ->set('horaireMer2', $horaire->getHoraireMer2())
                ->set('horaireMer3', $horaire->getHoraireMer3())
                ->set('horaireMer4', $horaire->getHoraireMer4())
                ->set('horaireJeu1', $horaire->getHoraireJeu1())
                ->set('horaireJeu2', $horaire->getHoraireJeu2())
                ->set('horaireJeu3', $horaire->getHoraireJeu3())
                ->set('horaireJeu4', $horaire->getHoraireJeu4())
                ->set('horaireVen1', $horaire->getHoraireVen1())
                ->set('horaireVen2', $horaire->getHoraireVen2())
                ->set('horaireVen3', $horaire->getHoraireVen3())
                ->set('horaireVen4', $horaire->getHoraireVen4())
                ->set('horaireSam1', $horaire->getHoraireSam1())
                ->set('horaireSam2', $horaire->getHoraireSam2())
                ->set('horaireSam3', $horaire->getHoraireSam3())
                ->set('horaireSam4', $horaire->getHoraireSam4())
                ->set('horaireDim1', $horaire->getHoraireDim1())
                ->set('horaireDim2', $horaire->getHoraireDim2())
                ->set('horaireDim3', $horaire->getHoraireDim3())
                ->set('horaireDim4', $horaire->getHoraireDim4())
                ->where('horaireId', $horaire->getHoraireId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Horaires correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Horaire
     */
    public function getHoraires($where = array(), $tri = 'horaireNom DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('horaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Horaire correspondant à l'id
     * @param integer $horaireId ID de l'raisonSociale
     * @return \Horaire|boolean
     */
    public function getHoraireById($horaireId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('horaireId', $horaireId)
                ->where('horaireEtablissementId', $this->session->userdata('etablissementId'))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
