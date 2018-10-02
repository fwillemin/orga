<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_motifs extends MY_model {

    protected $table = 'motifs';

    const classe = 'Motif';

    /**
     * Retourne un array avec des Motifs correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Motif
     */
    public function getMotifs($tri = 'motifGroupe, motifNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Motif correspondant à l'id
     * @param integer $motifId ID de l'raisonSociale
     * @return \Motif|boolean
     */
    public function getMotifById($motifId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('motifId', $motifId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
