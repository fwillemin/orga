<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_achats extends MY_model {

    protected $table = 'achats';

    const classe = 'Achat';

    /**
     * Ajout d'un objet de la classe Achat à la BDD
     * @param Achat $achat Objet de la classe Achat
     */
    public function ajouter(Achat $achat) {
        $this->db
                ->set('achatChantierId', $achat->getAchatChantierId())
                ->set('achatDate', $achat->getAchatDate())
                ->set('achatDescription', $achat->getAchatDescription())
                ->set('achatType', $achat->getAchatType())
                ->set('achatQtePrevisionnel', $achat->getAchatQtePrevisionnel())
                ->set('achatPrixPrevisionnel', $achat->getAchatPrixPrevisionnel())
                ->set('achatQte', $achat->getAchatQte())
                ->set('achatPrix', $achat->getAchatPrix())
                ->insert($this->table);
        $achat->setAchatId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Achat
     * @param Achat $achat Objet de la classe Achat
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Achat $achat) {
        $this->db
                ->set('achatDate', $achat->getAchatDate())
                ->set('achatDescription', $achat->getAchatDescription())
                ->set('achatType', $achat->getAchatType())
                ->set('achatQtePrevisionnel', $achat->getAchatQtePrevisionnel())
                ->set('achatPrixPrevisionnel', $achat->getAchatPrixPrevisionnel())
                ->set('achatQte', $achat->getAchatQte())
                ->set('achatPrix', $achat->getAchatPrix())
                ->where('achatId', $achat->getAchatId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Achat Objet de la classe Achat
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Achat $achat) {
        $this->db->where('achatId', $achat->getAchatId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Achats correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Achat
     */
    public function getAchats($chantierId = null, $where = array(), $tri = 'achatDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('achatChantierId', $chantierId)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Achat correspondant à l'id
     * @param integer $achatId ID de l'raisonSociale
     * @return \Achat|boolean
     */
    public function getAchatById($achatId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('achatId', $achatId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
