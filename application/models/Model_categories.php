<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_categories extends MY_model {

    protected $table = 'categories';

    const classe = 'Categorie';

    /**
     * Ajout d'un objet de la classe Categorie à la BDD
     * @param Categorie $categorie Objet de la classe Categorie
     */
    public function ajouter(Categorie $categorie) {
        $this->db
                ->set('categorieRsId', $this->session->userdata('rsId'))
                ->set('categorieNom', $categorie->getCategorieNom())
                ->insert($this->table);
        $categorie->setCategorieId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Categorie
     * @param Categorie $categorie Objet de la classe Categorie
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Categorie $categorie) {
        $this->db
                ->set('categorieNom', $categorie->getCategorieNom())
                ->where('categorieId', $categorie->getCategorieId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Categorie Objet de la classe Categorie
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Categorie $categorie) {
        $this->db->where('categorieId', $categorie->getCategorieId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Categories correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Categorie
     */
    public function getCategories($where = array(), $tri = 'categorieNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('categorieRsId', $this->session->userdata('rsId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Categorie correspondant à l'id
     * @param integer $categorieId ID de l'raisonSociale
     * @return \Categorie|boolean
     */
    public function getCategorieById($categorieId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                //->where('categorieRsId', $this->session->userdata('rsId'))
                ->where('categorieId', $categorieId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
