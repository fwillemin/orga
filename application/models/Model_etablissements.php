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
                ->set('etablissementOriginId', $etablissement->getEtablissementOriginId() ?: null)
                ->set('etablissementRsId', $etablissement->getEtablissementRsId())
                ->set('etablissementNom', $etablissement->getEtablissementNom())
                ->set('etablissementAdresse', $etablissement->getEtablissementAdresse())
                ->set('etablissementCp', $etablissement->getEtablissementCp())
                ->set('etablissementVille', $etablissement->getEtablissementVille())
                ->set('etablissementTelephone', $etablissement->getEtablissementTelephone())
                ->set('etablissementContact', $etablissement->getEtablissementContact())
                ->set('etablissementEmail', $etablissement->getEtablissementEmail())
                ->set('etablissementGps', $etablissement->getEtablissementGps())
                ->set('etablissementStatut', $etablissement->getEtablissementStatut())
                ->set('etablissementAffaireDiversId', $etablissement->getEtablissementAffaireDiversId())
                ->set('etablissementMessage', $etablissement->getEtablissementMessage())
                ->set('etablissementTauxFraisGeneraux', $etablissement->getEtablissementTauxFraisGeneraux())
                ->set('etablissementTauxHoraireMoyen', $etablissement->getEtablissementTauxHoraireMoyen())
                ->set('etablissementBaseHebdomadaire', $etablissement->getEtablissementBaseHebdomadaire())
                ->set('etablissementExpiration', $etablissement->getEtablissementExpiration())
                ->set('etablissementLimiteActifs', $etablissement->getEtablissementLimiteActifs())
                ->insert($this->table);
        $etablissement->setEtablissementId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Etablissement
     * @param Etablissement $etablissement Objet de la classe Etablissement
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Etablissement $etablissement) {
        $this->db
                ->set('etablissementRsId', $etablissement->getEtablissementRsId())
                ->set('etablissementNom', $etablissement->getEtablissementNom())
                ->set('etablissementAdresse', $etablissement->getEtablissementAdresse())
                ->set('etablissementCp', $etablissement->getEtablissementCp())
                ->set('etablissementVille', $etablissement->getEtablissementVille())
                ->set('etablissementTelephone', $etablissement->getEtablissementTelephone())
                ->set('etablissementContact', $etablissement->getEtablissementContact())
                ->set('etablissementEmail', $etablissement->getEtablissementEmail())
                ->set('etablissementGps', $etablissement->getEtablissementGps())
                ->set('etablissementStatut', $etablissement->getEtablissementStatut())
                ->set('etablissementAffaireDiversId', $etablissement->getEtablissementAffaireDiversId())
                ->set('etablissementMessage', $etablissement->getEtablissementMessage())
                ->set('etablissementTauxFraisGeneraux', $etablissement->getEtablissementTauxFraisGeneraux())
                ->set('etablissementTauxHoraireMoyen', $etablissement->getEtablissementTauxHoraireMoyen())
                ->set('etablissementBaseHebdomadaire', $etablissement->getEtablissementBaseHebdomadaire())
                ->set('etablissementExpiration', $etablissement->getEtablissementExpiration())
                ->set('etablissementLimiteActifs', $etablissement->getEtablissementLimiteActifs())
                ->where('etablissementId', $etablissement->getEtablissementId())
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
    public function getEtablissements($where = array(), $tri = '', $type = 'object') {
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
