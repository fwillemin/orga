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

    public function index() {
        redirect('contacts/liste');
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

    public function addContact() {
        if (!$this->ion_auth->in_group(array(91))):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            if (!$this->form_validation->run('addContact')):
                echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            else:

                if ($this->input->post('addContactId')):
                    $contact = $this->managerContacts->getContactById($this->input->post('addContactId'));
                    $contact->setContactDate($this->own->mktimeFromInputDate($this->input->post('addContactDate')));
                    $contact->setContactMode($this->input->post('addContactMode'));
                    $contact->setContactSource($this->input->post('addContactSource'));
                    $contact->setContactNom($this->input->post('addContactNom'));
                    $contact->setContactAdresse($this->input->post('addContactAdresse'));
                    $contact->setContactCp($this->input->post('addContactCp'));
                    $contact->setContactVille($this->input->post('addContactVille'));
                    $contact->setContactTelephone($this->input->post('addContactTelephone'));
                    $contact->setContactEmail($this->input->post('addContactEmail'));
                    $contact->setContactCategorieId($this->input->post('addContactCategorieId'));
                    $contact->setContactCommercialId($this->input->post('addContactCommercialId'));
                    $contact->setContactObjet($this->input->post('addContactObjet'));
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

    public function getContact() {
        if (!$this->form_validation->run('getContact')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            echo json_encode(array('type' => 'success', 'contact' => $this->managerContacts->getContactById($this->input->post('contactId'), 'array')));
        endif;
    }

}
