<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_parametres extends MY_model {

    protected $table = 'parametres';

    const classe = 'Parametre';

    /**
     * Ajout d'un objet de la classe Parametre à la BDD
     * @param Parametre $parametre Objet de la classe Parametre
     */
    public function ajouter(Parametre $parametre) {
        $this->db
                ->set('parametreEtablissementId', $parametre->getParametreEtablissementId())
                ->set('nbSemainesAvant', $parametre->getNbSemainesAvant())
                ->set('nbSemainesApres', $parametre->getNbSemainesApres())
                ->set('tranchePointage', $parametre->getTranchePointage())
                ->set('tailleAffectations', $parametre->getTailleAffectations())
                ->set('genererPaniers', $parametre->getGenererPaniers())
                ->insert($this->table);
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Parametre
     * @param Parametre $parametre Objet de la classe Parametre
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Parametre $parametre) {
        $this->db
                ->set('nbSemainesAvant', $parametre->getNbSemainesAvant())
                ->set('nbSemainesApres', $parametre->getNbSemainesApres())
                ->set('tranchePointage', $parametre->getTranchePointage())
                ->set('tailleAffectations', $parametre->getTailleAffectations())
                ->set('genererPaniers', $parametre->getGenererPaniers())
                ->where('parametreEtablissementId', $parametre->getParametreEtablissementId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un objet de la classe Parametre correspondant à l'id
     * @return \Parametre|boolean
     */
    public function getParametres($type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('parametreEtablissementId', $this->session->userdata('etablissementId'))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
