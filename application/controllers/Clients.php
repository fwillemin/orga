<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clients extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(30, 31)))) :
            redirect('organibat/board');
        endif;
    }

    public function liste() {

        $clients = $this->managerClients->getClients();
        foreach ($clients as $client):
            $client->hydrateAffaires();
            $client->hydratePlaces();
        endforeach;


        $data = array(
            'clients' => $clients,
            'title' => 'Clients',
            'description' => 'Fichier clients de l\'entreprise',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheClient($clientId = null) {

        if (!$clientId || !$this->existClient($clientId)):
            redirect('clients/liste');
        endif;

        $client = $this->managerClients->getClientById($clientId);
        $client->hydratePlaces();
        $client->hydrateAffaires();

        $data = array(
            'client' => $client,
            'title' => $client->getClientNom(),
            'description' => 'Fiche client',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addClient() {

        if (!$this->ion_auth->in_group(31)):
            redirect('clients/liste');
            exit;
        endif;

        if (!$this->form_validation->run('addClient')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addClientId')):
                $client = $this->managerClients->getClientById($this->input->post('addClientId'));
                $client->setClientNom(mb_strtoupper($this->input->post('addClientNom')));
                $client->setClientAdresse(ucfirst($this->input->post('addClientAdresse')));
                $client->setClientCp($this->input->post('addClientCp'));
                $client->setClientPays(strtoupper($this->input->post('addClientPays')));
                $client->setClientVille(ucfirst($this->input->post('addClientVille')));
                $client->setClientFixe($this->input->post('addClientFixe'));
                $client->setClientPortable($this->input->post('addClientPortable'));
                $client->setClientEmail($this->input->post('addClientEmail'));
                $this->managerClients->editer($client);
                $place = null;

            else:

                $dataClient = array(
                    'clientEtablissementId' => $this->session->userdata('etablissementId'),
                    'clientNom' => mb_strtoupper($this->input->post('addClientNom')),
                    'clientAdresse' => ucfirst($this->input->post('addClientAdresse')),
                    'clientCp' => $this->input->post('addClientCp'),
                    'clientVille' => $this->input->post('addClientVille'),
                    'clientPays' => strtoupper($this->input->post('addClientMessage')),
                    'clientFixe' => $this->input->post('addClientFixe'),
                    'clientPortable' => $this->input->post('addClientPortable'),
                    'clientEmail' => $this->input->post('addClientEmail')
                );
                $client = new Client($dataClient);
                $this->managerClients->ajouter($client);

                /* On insere une place */
                $result = $this->maps->geocode(urlencode($this->input->post('addClientAdresse') . ' ' . $this->input->post('addClientCp') . ' ' . $this->input->post('addClientVille') . ' ' . $this->input->post('addClientPays')));
                if ($result):

                    $volOiseau = $this->maps->distanceVolOiseau(explode(',', $this->session->userdata('etablissementGPS'))[0], explode(',', $this->session->userdata('etablissementGPS'))[1], $result['latitude'], $result['longitude']);
                    $zone = floor($volOiseau / 10000) + 1;
                    if ($zone > 6):
                        $zone = 6;
                    endif;

                    $arrayPlace = array(
                        'placeClientId' => $client->getClientId(),
                        'placeEtablissementId' => $this->session->userdata('etablissementId'),
                        'placeLat' => $result['latitude'],
                        'placeLon' => $result['longitude'],
                        'placeAdresse' => $result['adresse'],
                        'placeVille' => $result['ville'],
                        'placeGoogleId' => $result['placeGoogleId'],
                        'placeDistance' => $result['distance'],
                        'placeDuree' => $result['duree'],
                        'placeZone' => $zone,
                        'placeVolOiseau' => $volOiseau
                    );

                    $place = new Place($arrayPlace);
                    $this->managerPlaces->ajouter($place);

                else:
                    log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur de geoCodage');
                endif;
            endif;

            echo json_encode(array(
                'type' => 'success',
                'client' => $client ? $this->managerClients->getClientById($client->getClientId(), 'array') : false,
                'place' => $place ? $this->managerPlaces->getPlaceById($place->getPlaceId(), 'array') : false
            ));

        endif;
    }

    public function addPlace() {
        if (!$this->ion_auth->in_group(31)):
            redirect('clients/liste');
            exit;
        endif;

        if (!$this->form_validation->run('addPlace')):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur AddPlace');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $result = $this->maps->geocode(urlencode($this->input->post('addPlaceAdresse') . ' FRANCE'));
            if ($result):

                $volOiseau = $this->maps->distanceVolOiseau(explode(',', $this->session->userdata('etablissementGPS'))[0], explode(',', $this->session->userdata('etablissementGPS'))[1], $result['latitude'], $result['longitude']);
                $zone = floor($volOiseau / 10000) + 1;
                if ($zone > 6):
                    $zone = 6;
                endif;

            else:
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur de geoCodage');
                echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'encodage de l\'adresse'));
            endif;

            if ($this->input->post('addPlaceId')):
                $place = $this->managerPlaces->getPlaceById($this->input->post('addPlaceId'));
                $place->setPlaceLat($result['latitude']);
                $place->setPlaceLon($result['longitude']);
                $place->setPlaceAdresse($result['adresse']);
                $place->setPlaceVille($result['ville']);
                $place->setPlaceGoogleId($result['placeGoogleId']);
                $place->setPlacDistance($result['distance']);
                $place->setPlacDistance($result['duree']);
                $place->setPlacDistance($zone);
                $place->setPlacDistance($volOiseau);

                $this->managerPlaces->editer($place);

            else:

                $arrayPlace = array(
                    'placeClientId' => $this->input->post('addPlaceClientId'),
                    'placeEtablissementId' => $this->session->userdata('etablissementId'),
                    'placeLat' => $result['latitude'],
                    'placeLon' => $result['longitude'],
                    'placeAdresse' => $result['adresse'],
                    'placeVille' => $result['ville'],
                    'placeGoogleId' => $result['placeGoogleId'],
                    'placeDistance' => $result['distance'],
                    'placeDuree' => $result['duree'],
                    'placeZone' => $zone,
                    'placeVolOiseau' => $volOiseau
                );

                $place = new Place($arrayPlace);
                $this->managerPlaces->ajouter($place);

            endif;
            echo json_encode(array(
                'type' => 'success',
                'place' => $place ? $this->managerPlaces->getPlaceById($place->getPlaceId(), 'array') : false
            ));

        endif;
    }

    public function delPlace() {
        if (!$this->ion_auth->in_group(31)):
            redirect('clients/liste');
            exit;
        endif;

        if (!$this->form_validation->run('getPlace')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $place = $this->managerPlaces->getPlaceById($this->input->post('placeId'));
//        $place->hydrateUtilisations();
//        if ($place->getPlaceUtilisations() > 0):
//            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
//        else:
        $this->managerPlaces->delete($place);
        echo json_encode(array('type' => 'success'));
//        endif;
    }

    public function getPlacesClient() {
        if (!$this->form_validation->run('getClient')):
            echo json_encode(array('type' => 'error', 'message' => 'Client introuvable'));
            exit;
        endif;
        $places = $this->managerPlaces->getPlaces(array('placeClientId' => $this->input->post('clientId')), 'placeId ASC', 'array');
        echo json_encode(array('type' => 'success', 'places' => $places));
    }

    public function delClient() {
        if (!$this->ion_auth->in_group(31) || !$this->existClient($this->input->post('clientId'))):
            redirect('clients/liste');
            exit;
        endif;

        $client = $this->managerClients->getClientById($this->input->post('clientId'));
        $client->hydrateAffaires();
        if (!empty($client->getClientAffaires())):
            echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer un client avec des affaires.'));
        else:
            $this->managerClients->delete($client);
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
