<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_chantiers extends MY_model {

    protected $table = 'chantiers';

    const classe = 'Chantier';

    /**
     * Ajout d'un objet de la classe Chantier à la BDD
     * @param Chantier $chantier Objet de la classe Chantier
     */
    public function ajouter(Chantier $chantier) {
        $this->db
                ->set('chantierAffaireId', $chantier->getChantierAffaireId())
                ->set('chantierPlaceId', $chantier->getChantierPlaceId())
                ->set('chantierOriginId', $chantier->getChantierOriginId())
                ->set('chantierCategorieId', $chantier->getChantierCategorieId())
                ->set('chantierObjet', $chantier->getChantierObjet())
                ->set('chantierPrix', $chantier->getChantierPrix())
                ->set('chantierCouleur', $chantier->getChantierCouleur())
                ->set('chantierCouleurSecondaire', $chantier->getChantierCouleurSecondaire())
                ->set('chantierEtat', $chantier->getChantierEtat())
                ->set('chantierDateCloture', $chantier->getChantierDateCloture())
                ->set('chantierHeuresPrevues', $chantier->getChantierHeuresPrevues())
                ->set('chantierBudgetAchats', $chantier->getChantierBudgetAchats())
                ->set('chantierFraisGeneraux', $chantier->getChantierFraisGeneraux())
                ->set('chantierTauxHoraireMoyen', $chantier->getChantierTauxHoraireMoyen())
                ->set('chantierRemarque', $chantier->getChantierRemarque())
                ->insert($this->table);
        $chantier->setChantierId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Chantier
     * @param Chantier $chantier Objet de la classe Chantier
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Chantier $chantier) {
        $this->db
                ->set('chantierAffaireId', $chantier->getChantierAffaireId())
                ->set('chantierPlaceId', $chantier->getChantierPlaceId())
                ->set('chantierCategorieId', $chantier->getChantierCategorieId())
                ->set('chantierObjet', $chantier->getChantierObjet())
                ->set('chantierPrix', $chantier->getChantierPrix())
                ->set('chantierCouleur', $chantier->getChantierCouleur())
                ->set('chantierCouleurSecondaire', $chantier->getChantierCouleurSecondaire())
                ->set('chantierEtat', $chantier->getChantierEtat())
                ->set('chantierDateCloture', $chantier->getChantierDateCloture())
                ->set('chantierHeuresPrevues', $chantier->getChantierHeuresPrevues())
                ->set('chantierBudgetAchats', $chantier->getChantierBudgetAchats())
                ->set('chantierFraisGeneraux', $chantier->getChantierFraisGeneraux())
                ->set('chantierTauxHoraireMoyen', $chantier->getChantierTauxHoraireMoyen())
                ->set('chantierRemarque', $chantier->getChantierRemarque())
                ->where('chantierId', $chantier->getChantierId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Chantier Objet de la classe Chantier
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Chantier $chantier) {
        $this->db->where('chantierId', $chantier->getChantierId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Chantiers correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Chantier
     */
    public function getChantiers($where = array(), $tri = 'chantierId ASC', $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getChantiersByAffaireId($affaireId, $tri = 'chantierId ASC', $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('chantierAffaireId', $affaireId)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Chantier correspondant à l'id
     * @param integer $chantierId ID de l'raisonSociale
     * @return \Chantier|boolean
     */
    public function getChantierById($chantierId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('chantierId', $chantierId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}