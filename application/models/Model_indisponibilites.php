<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_indisponibilites extends MY_model {

    protected $table = 'indisponibilites';

    const classe = 'Indisponibilite';

    /**
     * Ajout d'un objet de la classe Indisponibilite à la BDD
     * @param Indisponibilite $indisponibilite Objet de la classe Indisponibilite
     */
    public function ajouter(Indisponibilite $indisponibilite) {
        $this->db
                ->set('indispoPersonnelId', $indisponibilite->getIndispoPersonnelId())
                ->set('indispoDebutDate', $indisponibilite->getIndispoDebutDate())
                ->set('indispoDebutMoment', $indisponibilite->getIndispoDebutMoment())
                ->set('indispoFinDate', $indisponibilite->getIndispoFinDate())
                ->set('indispoFinMoment', $indisponibilite->getIndispoFinMoment())
                ->set('indispoNbDemi', $indisponibilite->getIndispoNbDemi())
                ->set('indispoCases', $indisponibilite->getIndispoCases())
                ->set('indispoMotifId', $indisponibilite->getIndispoMotifId())
                ->set('indispoAffichage', $indisponibilite->getIndispoAffichage())
                ->insert($this->table);
        $indisponibilite->setIndispoId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Indisponibilite
     * @param Indisponibilite $indisponibilite Objet de la classe Indisponibilite
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Indisponibilite $indisponibilite) {
        $this->db
                ->set('indispoPersonnelId', $indisponibilite->getIndispoPersonnelId())
                ->set('indispoDebutDate', $indisponibilite->getIndispoDebutDate())
                ->set('indispoDebutMoment', $indisponibilite->getIndispoDebutMoment())
                ->set('indispoFinDate', $indisponibilite->getIndispoFinDate())
                ->set('indispoFinMoment', $indisponibilite->getIndispoFinMoment())
                ->set('indispoNbDemi', $indisponibilite->getIndispoNbDemi())
                ->set('indispoCases', $indisponibilite->getIndispoCases())
                ->set('indispoMotifId', $indisponibilite->getIndispoMotifId())
                ->set('indispoAffichage', $indisponibilite->getIndispoAffichage())
                ->where('indispoId', $indisponibilite->getIndispoId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Indisponibilite Objet de la classe Indisponibilite
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Indisponibilite $indisponibilite) {
        $this->db->where('indispoId', $indisponibilite->getIndispoId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Indisponibilites correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Indisponibilite
     */
    public function getIndisponibilites($where = array(), $tri = 'indispoDebutDate ASC', $type = 'object') {
        $query = $this->db->select('i.*')
                ->from('indisponibilites i')
                ->join('personnels p', 'p.personnelId = i.indispoPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function repartitionIndisponibilites(Personnel $personnel, $annee, $type = 'array') {
        $query = $this->db->select('ROUND(SUM(indispoNbdemi)/2,1) AS nbJours, m.motifId, m.motifNom AS motif, m.motifGroupe AS groupe')
                ->from('indisponibilites i')
                ->join('personnels p', 'p.personnelId = i.indispoPersonnelId')
                ->join('motifs m', 'm.motifId = i.indispoMotifId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where(array("p.personnelId" => $personnel->getPersonnelId(), "FROM_UNIXTIME(indispoDebutDate, '%Y') =" => $annee))
                ->group_by('i.indispoMotifId')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getJoursIndisponibilites(Personnel $personnel, $motifId, $annee, $type = 'array') {
        return $this->db->select('ROUND(SUM(indispoNbdemi)/2,1) AS nbJours')
                        ->from('indisponibilites i')
                        ->join('personnels p', 'p.personnelId = i.indispoPersonnelId')
                        ->join('motifs m', 'm.motifId = i.indispoMotifId')
                        ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                        ->where(array("p.personnelId" => $personnel->getPersonnelId(), "FROM_UNIXTIME(indispoDebutDate, '%Y') =" => $annee, 'm.motifId' => $motifId))
                        ->group_by('i.indispoMotifId')
                        ->get()
                        ->result();
    }

    /**
     * Retourne un objet de la classe Indisponibilite correspondant à l'id
     * @param integer $indisponibiliteId ID de l'raisonSociale
     * @return \Indisponibilite|boolean
     */
    public function getIndisponibiliteById($indisponibiliteId, $type = 'object') {
        $query = $this->db->select('i.*')
                ->from('indisponibilites i')
                ->join('personnels p', 'p.personnelId = i.indispoPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where('indispoId', $indisponibiliteId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getIndisponibilitesPlanning($premierJour, $dernierJour, $type = 'object') {

        $selection = array('i.indispoFinDate >=' => $premierJour);
        if ($dernierJour):
            $selection['i.indispoDebutDate <='] = $dernierJour;
        endif;

        $query = $this->db->select('i.*')
                ->from('indisponibilites i')
                ->join('personnels p', 'p.personnelId = i.indispoPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($selection)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
