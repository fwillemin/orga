<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Categories extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(40)))) :
            redirect('organibat/board');
        endif;
    }

    public function index() {
        redirect('categories/liste');
    }

    public function liste($categorieId = null) {

        $categories = $this->managerCategories->getCategories();

        if ($categorieId && $this->existCategorie($categorieId)):
            $categorie = $this->managerCategories->getCategorieById($categorieId);
        else:
            $categorie = '';
        endif;

        $data = array(
            'categorie' => $categorie,
            'categories' => $categories,
            'title' => 'Categories',
            'description' => 'Categories de chantier',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addCategorie() {

        if (!$this->form_validation->run('addCategorie')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addCategorieId')):
                $categorie = $this->managerCategories->getCategorieById($this->input->post('addCategorieId'));
                $categorie->setCategorieNom(mb_strtoupper($this->input->post('addCategorieNom')));
                $this->managerCategories->editer($categorie);

            else:

                $dataCategorie = array(
                    'categorieRsId' => $this->session->userdata('rsId'),
                    'categorieNom' => mb_strtoupper($this->input->post('addCategorieNom')),
                );
                $categorie = new Categorie($dataCategorie);
                $this->managerCategories->ajouter($categorie);

            endif;

            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function delCategorie() {
        if (!$this->form_validation->run('getCategorie')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $categorie = $this->managerCategories->getCategorieById($this->input->post('categorieId'));
            $this->managerCategories->delete($categorie);
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
