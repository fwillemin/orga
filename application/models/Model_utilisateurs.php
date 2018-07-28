<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_utilisateurs extends MY_model {

    protected $table = 'users';

    const classe = 'Utilisateur';

    /**
     * Mise à jour de la BDD pour un objet de la classe Utilisateur
     * @param Utilisateur $utilisateur Objet de la classe Utilisateur
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Utilisateur $utilisateur) {
        $this->db
                ->set('userNom', $utilisateur->getUserNom())
                ->set('userPrenom', $utilisateur->getUserPrenom())
                ->set('email', $utilisateur->getEmail())
                ->where('id', $utilisateur->getId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Utilisateurs correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Utilisateur
     */
    public function getUtilisateurs($where = array(), $tri = 'userNom DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('userEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getCommerciaux($where = array(), $tri = 'userNom DESC', $type = 'object') {
        $query = $this->db->select('u.*')
                ->from($this->table . ' u')
                ->join('users_groups ug', 'ug.user_id = u.id', 'left')
                ->where('u.userEtablissementId', $this->session->userdata('etablissementId'))
                ->where('ug.group_id', 3)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Utilisateur correspondant à l'id
     * @param integer $utilisateurId ID de l'raisonSociale
     * @return \Utilisateur|boolean
     */
    public function getUtilisateurById($userId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('id', $userId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
