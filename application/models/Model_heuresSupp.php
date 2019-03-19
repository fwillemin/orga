<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_heuresSupp extends MY_model {

    protected $table = 'heuresSupplementaires';

    const classe = 'HeureSupp';

    public function ajouter(HeureSupp $hs) {
        $this->db
                ->set('hsSemaine', $hs->getHsSemaine())
                ->set('hsAnnee', $hs->getHsAnnee())
                ->set('hsPersonnelId', $hs->getHsPersonnelId())
                ->set('hsNbHeuresSupp', $hs->getHsNbHeuresSupp())
                ->insert($this->table);
        $hs->setHeureId($this->db->insert_id());
    }

    public function editer(HeureSupp $hs, $where = null) {

        if ($where && is_array($where)):

            $this->db
                    ->set('hsSemaine', $hs->getHsSemaine())
                    ->set('hsAnnee', $hs->getHsAnnee())
                    ->set('hsPersonnelId', $hs->getHsPersonnelId())
                    ->set('hsNbHeuresSupp', $hs->getHsNbHeuresSupp())
                    ->where($where)
                    ->update($this->table);
            return $this->db->affected_rows();

        else:
            return false;
        endif;
    }

    public function delete(HeureSupp $hs) {
        $this->db
                ->where('hsAnnee', $hs->getHsAnnee())
                ->where('hsSemaine', $hs->getHsSemaine())
                ->where('hsPersonnelId', $hs->getHsPersonnelId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getHs($where = array(), $tri = 'h.hsAnnee, h.hsSemaine, h.hsPersonnelId  ASC', $type = 'object') {
        $query = $this->db->select('h.*')
                ->from('heuresSupp h')
                ->join('personnels p', 'p.personnelId = h.hsPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
