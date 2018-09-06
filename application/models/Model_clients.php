<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_clients extends MY_model {

    protected $table = 'clients';

    const classe = 'Client';

    /**
     * Ajout d'un objet de la classe Client à la BDD
     * @param Client $client Objet de la classe Client
     */
    public function ajouter(Client $client) {
        $this->db
                //->set('clientEtablissementId', $this->session->userdata('etablissementId'))
                ->set('clientEtablissementId', $client->getClientEtablissementId())
                ->set('clientNom', $client->getClientNom())
                ->set('clientAdresse', $client->getClientAdresse())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->set('clientFixe', $client->getClientFixe())
                ->set('clientPortable', $client->getClientPortable())
                ->set('clientEmail', $client->getClientEmail())
                ->insert($this->table);
        $client->setClientId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Client
     * @param Client $client Objet de la classe Client
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Client $client) {
        $this->db
                ->set('clientNom', $client->getClientNom())
                ->set('clientAdresse', $client->getClientAdresse())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->set('clientFixe', $client->getClientFixe())
                ->set('clientPortable', $client->getClientPortable())
                ->set('clientEmail', $client->getClientEmail())
                ->where('clientId', $client->getClientId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe raisonSociale
     *
     * @param Client Objet de la classe Client
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Client $client) {
        $this->db->where('clientId', $client->getClientId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Clients correspondant aux critères du paramètre $where
     * @param array $where Critères de selection des raisonSociales
     * @param array $tri Critères de tri des raisonSociales
     * @return array Liste d'objets de la classe Client
     */
    public function getClients($where = array(), $tri = 'clientNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clientEtablissementId', $this->session->userdata('etablissementId'))
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un objet de la classe Client correspondant à l'id
     * @param integer $clientId ID de l'raisonSociale
     * @return \Client|boolean
     */
    public function getClientById($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clientEtablissementId', $this->session->userdata('etablissementId'))
                ->where('clientId', $clientId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getClientByIdMigration($clientId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clientId', $clientId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
