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
    }

    public function editer(HeureSupp $hs) {
        $this->db
                ->set('hsSemaine', $hs->getHsSemaine())
                ->set('hsAnnee', $hs->getHsAnnee())
                ->set('hsPersonnelId', $hs->getHsPersonnelId())
                ->set('hsNbHeuresSupp', $hs->getHsNbHeuresSupp())
                ->where(array('hsSemaine' => $hs->getHsSemaine(), 'hsAnnee' => $hs->getHsAnnee(), 'hsPersonnelId' => $hs->getHsPersonnelId()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(HeureSupp $hs) {
        $this->db
                ->where('hsAnnee', $hs->getHsAnnee())
                ->where('hsSemaine', $hs->getHsSemaine())
                ->where('hsPersonnelId', $hs->getHsPersonnelId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getHeuresSupp($where = array(), $tri = 'h.hsAnnee, h.hsSemaine, h.hsPersonnelId  ASC', $type = 'object') {
        $query = $this->db->select('h.*')
                ->from('heuresSupplementaires h')
                ->join('personnels p', 'p.personnelId = h.hsPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getSoldeBefore($personnelId, $annee) {
        $query = $this->db->select('SUM(h.hsNbHeuresSupp) AS solde')
                ->from('heuresSupplementaires h')
                ->join('personnels p', 'p.personnelId = h.hsPersonnelId')
                ->where('p.personnelEtablissementId', $this->session->userdata('etablissementId'))
                ->where(array('h.hsPersonnelId' => $personnelId, 'hsAnnee <' => $annee))
                ->get()
                ->result();

        if ($query[0]->solde):
            return $query[0]->solde;
        else:
            return 0;
        endif;
    }

}
