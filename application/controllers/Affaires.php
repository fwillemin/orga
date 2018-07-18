<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Affaires extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(50, 51, 52)))) :
            redirect('organibat/board');
        endif;
    }

    public function liste() {

        $where = array();
        if ($this->session->userdata('rechAffaireEtat')):
            $where['affaireEtat'] = $this->session->userdata('rechAffaireEtat');
        endif;

        $affaires = $this->managerAffaires->getAffaires($where);
        if ($affaires):
            foreach ($affaires as $affaire):
                $affaire->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'affaires' => $affaires,
            'title' => 'Affaires',
            'description' => 'Liste des affaires',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function rechAffaireEtat() {
        if (in_array($this->input->post('etat'), array(0, 1, 2, 3))):
            $this->session->set_userdata('rechAffaireEtat', $this->input->post('etat'));
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Etat invalide'));
        endif;
    }

    public function ficheAffaire($affaireId = null) {
        if (!$this->ion_auth->in_group(21)):
            redirect('affaires/liste');
        endif;

        if (!$affaireId || !$this->existAffaire($affaireId)):
            redirect('affaires/liste');
        endif;

        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydratePlaces();
        $affaire->hydrateAffaires();

        $data = array(
            'affaire' => $affaire,
            'title' => $affaire->getAffaireNom(),
            'description' => 'Fiche affaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addAffaire() {

        if (!$this->form_validation->run('addAffaire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addAffaireId')):
                $affaire = $this->managerAffaires->getAffaireById($this->input->post('addAffaireId'));
                $affaire->setAffaireNom(mb_strtoupper($this->input->post('addAffaireNom')));
                $affaire->setAffaireAdresse(ucfirst($this->input->post('addAffaireAdresse')));
                $affaire->setAffaireCp($this->input->post('addAffaireCp'));
                $affaire->setAffairePays(strtoupper($this->input->post('addAffairePays')));
                $affaire->setAffaireVille(ucfirst($this->input->post('addAffaireVille')));
                $affaire->setAffaireFixe($this->input->post('addAffaireFixe'));
                $affaire->setAffairePortable($this->input->post('addAffairePortable'));
                $affaire->setAffaireEmail($this->input->post('addAffaireEmail'));
                $this->managerAffaires->editer($affaire);

            else:

                $dataAffaire = array(
                    'affaireEtablissementId' => $this->session->userdata('etablissementId'),
                    'affaireNom' => mb_strtoupper($this->input->post('addAffaireNom')),
                    'affaireAdresse' => ucfirst($this->input->post('addAffaireAdresse')),
                    'affaireCp' => $this->input->post('addAffaireCp'),
                    'affaireVille' => $this->input->post('addAffaireVille'),
                    'affairePays' => strtoupper($this->input->post('addAffaireMessage')),
                    'affaireFixe' => $this->input->post('addAffaireFixe'),
                    'affairePortable' => $this->input->post('addAffairePortable'),
                    'affaireEmail' => $this->input->post('addAffaireEmail')
                );
                $affaire = new Affaire($dataAffaire);
                $this->managerAffaires->ajouter($affaire);

                /* On insere une place */
                $result = $this->maps->geocode(urlencode($this->input->post('addAffaireAdresse') . ' ' . $this->input->post('addAffaireCp') . ' ' . $this->input->post('addAffaireVille') . ' ' . $this->input->post('addAffairePays')));
                if ($result):

                    $volOiseau = $this->maps->distanceVolOiseau(explode(',', $this->session->userdata('etablissementGPS'))[0], explode(',', $this->session->userdata('etablissementGPS'))[1], $result['latitude'], $result['longitude']);
                    $zone = floor($volOiseau / 10000) + 1;
                    if ($zone > 6):
                        $zone = 6;
                    endif;

                    $arrayPlace = array(
                        'placeAffaireId' => $affaire->getAffaireId(),
                        'placeEtablissementId' => $this->session->userdata('etablissementid'),
                        'placeLat' => $result['latitude'],
                        'placeLon' => $result['longitude'],
                        'placeAdresse' => $result['adresse'],
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

            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function addPlace() {

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
                $place->setPlaceGoogleId($result['placeGoogleId']);
                $place->setPlacDistance($result['distance']);
                $place->setPlacDistance($result['duree']);
                $place->setPlacDistance($zone);
                $place->setPlacDistance($volOiseau);

                $this->managerPlaces->editer($place);

            else:

                $arrayPlace = array(
                    'placeAffaireId' => $this->input->post('addPlaceAffaireId'),
                    'placeEtablissementId' => $this->session->userdata('etablissementid'),
                    'placeLat' => $result['latitude'],
                    'placeLon' => $result['longitude'],
                    'placeAdresse' => $result['adresse'],
                    'placeGoogleId' => $result['placeGoogleId'],
                    'placeDistance' => $result['distance'],
                    'placeDuree' => $result['duree'],
                    'placeZone' => $zone,
                    'placeVolOiseau' => $volOiseau
                );

                $place = new Place($arrayPlace);
                $this->managerPlaces->ajouter($place);

            endif;
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function delPlace() {

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

}
