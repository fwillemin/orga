<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_model extends CI_Model {

    public function count($where = array()) {
        return (int) $this->db
                        ->where($where)
                        ->count_all_results($this->table);
    }

    /**
     * Gère le retour des données en fonction du résultat de la requête et du type de retour souhaité
     * @param type $result Résultat de la requete
     * @param type $type Objet ou Array
     * @return boolean
     */
    protected function retourne($result, $type, $classe, $unique = false) {

        if ($result->num_rows() > 0):
            foreach ($result->result() AS $row):
                if ($type == 'object'):
                    $datas[] = new $classe((array) $row);
                else:
                    $datas[] = $row;
                endif;
            endforeach;
            if ($unique):
                return $datas[0];
            else:
                return $datas;
            endif;
        else:
            return FALSE;
        endif;
    }

}
