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
                ->set('chantierCoutMo', null)
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
                ->set('chantierCoutMo', $chantier->getChantierCoutMo())
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

    public function getClientByChantierId($chantierId, $type = 'object') {
        $query = $this->db->select('cl.*')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->join('clients cl', 'cl.clientId = a.affaireClientId')
                ->where('affaireEtablissementId', $this->session->userdata('etablissementId'))
                ->where('c.chantierId', $chantierId)
                ->get();
        return $this->retourne($query, $type, 'Client', true);
    }

    public function getChantierByOriginId($chantierId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('chantierOriginId', $chantierId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getPerformancesGlobalesRangeTaux($debut, $fin, $min, $max, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->where('a.affaireId <>', $this->session->userdata('affaireDiversId'))
                ->where(array("a.affaireEtablissementId" => $this->session->userdata('etablissementId'), "c.chantierDateCloture >=" => $debut, "c.chantierDateCloture <" => $fin, 'c.chantierPerformanceHeures >= ' => $min, 'c.chantierPerformanceHeures <' => $max))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getPerformancesMoyennesCategories($debut, $fin, $type = 'array') {
        $query = $this->db->select('cat.categorieNom AS categorie, ROUND(AVG(c.chantierPerformanceHeures),2) AS perfMoyenne, cat.categorieId AS categorieId')
                ->from($this->table . ' c')
                ->join('affaires a', 'a.affaireId = c.chantierAffaireId')
                ->join('categories cat', 'cat.categorieId = c.chantierCategorieId')
                ->where('a.affaireId <>', $this->session->userdata('affaireDiversId'))
                ->where(array("a.affaireEtablissementId" => $this->session->userdata('etablissementId'), "c.chantierDateCloture >=" => $debut, "c.chantierDateCloture <" => $fin))
                ->group_by('c.chantierCategorieId')
                ->order_by('perfMoyenne ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
