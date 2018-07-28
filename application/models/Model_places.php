<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_places extends MY_model {

    protected $table = 'places';

    const classe = 'Place';

    /**
     * Ajout d'un objet de la classe Place à la BDD
     * @param Place $place Objet de la classe Place
     */
    public function ajouter(Place $place) {
        $this->db
                //->set('placeEtablissementId', $this->session->userdata('etablissementId'))
                ->set('placeEtablissementId', $place->getPlaceEtablissementId())
                ->set('placeClientId', $place->getPlaceClientId())
                ->set('placeAdresse', $place->getPlaceAdresse())
                ->set('placeLat', $place->getPlaceLat())
                ->set('placeLon', $place->getPlaceLon())
                ->set('placeDistance', $place->getPlaceDistance())
                ->set('placeDuree', $place->getPlaceDuree())
                ->set('placeVolOiseau', $place->getPlaceVolOiseau())
                ->set('placeZone', $place->getPlaceZone())
                ->set('placeGoogleId', $place->getPlaceGoogleId())
                ->insert($this->table);
        $place->setPlaceId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Place
     * @param Place $place Objet de la classe Place
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Place $place) {
        $this->db
                ->set('placeAdresse', $place->getPlaceAdresse())
                ->set('placeLat', $place->getPlaceLat())
                ->set('placeLon', $place->getPlaceLon())
                ->set('placeDistance', $place->getPlaceDistance())
                ->set('placeDuree', $place->getPlaceDuree())
                ->set('placeVolOiseau', $place->getPlaceVolOiseau())
                ->set('placeZone', $place->getPlaceZone())
                ->set('placeGoogleId', $place->getPlaceGoogleId())
                ->where('placeId', $place->getPlaceId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Place Objet de la classe Place
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Place $place) {
        $this->db->where('placeId', $place->getPlaceId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Places correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Place
     */
    public function getPlaces($where = array(), $tri = 'placeAdresse DESC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('placeEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Place correspondant à l'id
     * @param integer $placeId ID de l'raisonSociale
     * @return \Place|boolean
     */
    public function getPlaceById($placeId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('placeEtablissementId', $this->session->userdata('etablissementId'))
                ->where('placeId', $placeId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
