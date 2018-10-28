<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Contacts extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(90, 91)))) :
            redirect('organibat/board');
        endif;
    }

    public function rechContactEtat() {
        if (in_array($this->input->post('etat'), array(0, 1, 2, 3, 4, 5))):
            $this->session->set_userdata('rechContactEtat', $this->input->post('etat'));
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Etat invalide'));
        endif;
    }

    public function liste() {

        $where = array();
        if ($this->session->userdata('rechContactEtat')):
            $where['contactEtat'] = $this->session->userdata('rechContactEtat');
        endif;

        $contacts = $this->managerContacts->getContacts($where);

        $data = array(
            'commerciaux' => $this->managerUtilisateurs->getCommerciaux(),
            'categories' => $this->managerCategories->getCategories(),
            'contacts' => $contacts,
            'title' => 'Contacts entrants',
            'description' => 'Liste des contacts entrants',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

//    public function ficheContact($contactId = null) {
//        if (!$this->ion_auth->in_group(21)):
//            redirect('contacts/liste');
//        endif;
//
//        if (!$contactId || !$this->existContact($contactId)):
//            redirect('contacts/liste');
//        endif;
//
//        $contact = $this->managerContacts->getContactById($contactId);
//        $contact->hydratePlaces();
//        $contact->hydrateAffaires();
//
//        $data = array(
//            'contact' => $contact,
//            'title' => $contact->getContactNom(),
//            'description' => 'Fiche contact',
//            'content' => $this->viewFolder . '/' . __FUNCTION__
//        );
//        $this->load->view('template/content', $data);
//    }

    public function addContact() {
        if (!$this->ion_auth->in_group(array(91))):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            if (!$this->form_validation->run('addContact')):
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            else:

                if ($this->input->post('addContactId')):
                    $contact = $this->managerContacts->getContactById($this->input->post('addContactId'));
                    $contact->setContact();
                    $this->managerContacts->editer($contact);

                else:

                    $dataContact = array(
                        'contactEtablissementId' => $this->session->userdata('etablissementId'),
                        'contactDate' => $this->own->mktimeFromInputDate($this->input->post('addContactDate')),
                        'contactMode' => $this->input->post('addContactMode'),
                        'contactSource' => $this->input->post('addContactSource'),
                        'contactNom' => mb_strtoupper($this->input->post('addContactNom')),
                        'contactAdresse' => $this->input->post('addContactAdresse'),
                        'contactCp' => $this->input->post('addContactCp'),
                        'contactVille' => $this->input->post('addContactVille'),
                        'contactObjet' => $this->input->post('addContactObjet'),
                        'contactCategorieId' => $this->input->post('addContactCategorieId'),
                        'contactTelephone' => $this->input->post('addContactTelephone'),
                        'contactEmail' => $this->input->post('addContactEmail'),
                        'contactCommercialId' => $this->input->post('addContactCommercialId'),
                        'contactEtat' => 1
                    );
                    $contact = new Contact($dataContact);
                    $this->managerContacts->ajouter($contact);

                endif;
                echo json_encode(array('type' => 'success'));
            endif;
        endif;
    }

    public function avancementContact() {
        if (!$this->ion_auth->in_group(array(91))):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            if (!$this->form_validation->run('getContact')):
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            else:
                $contact = $this->managerContacts->getContactById($this->input->post('contactId'));
                $contact->setContactEtat($this->input->post('contactEtat'));
                $this->managerContacts->editer($contact);
                echo json_encode(array('type' => 'success'));
            endif;
        endif;
    }

    public function delContact() {
        if (!$this->ion_auth->in_group(array(91))):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            if (!$this->form_validation->run('getContact')):
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            else:
                $contact = $this->managerContacts->getContactById($this->input->post('contactId'));
                $this->managerContacts->delete($contact);
                echo json_encode(array('type' => 'success'));
            endif;
        endif;
    }

}
