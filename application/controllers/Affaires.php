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

    public function index() {
        redirect('affaires/liste');
    }

    public function majAnalysesBDD() {
        $affaires = $this->managerAffaires->getAffaires(array('affaireEtat' => 3, 'affaireCoutMo' => 0));
        if (!empty($affaires)):
            foreach ($affaires as $affaire):
                $affaire->hydrateChantiers();
                if (!empty($affaire->getAffaireChantiers())):
                    $affaire->getAffaireChantiers()[0]->reouvrir();
                    $this->managerChantiers->editer($affaire->getAffaireChantiers()[0]);
                    $affaire->getAffaireChantiers()[0]->cloture();
                    $this->managerChantiers->editer($affaire->getAffaireChantiers()[0]);
                endif;
            endforeach;
        endif;
    }

    private function analyseAffaire(Affaire $affaire) {

        /* Initialisation */
        $analyse['heures']['prevues'] = $analyse['heures']['planifiees'] = $analyse['heures']['pointees'] = $analyse['heures']['finAffaire'] = $analyse['mainO']['commercial'] = $analyse['mainO']['tempsReel'] = $analyse['mainO']['finAffaire'] = $analyse['mainO']['restant'] = $analyse['achats']['commercial'] = $analyse['achats']['tempsReel'] = $analyse['achats']['finAffaire'] = $analyse['debourseSec']['commercial'] = $analyse['debourseSec']['tempsReel'] = $analyse['debourseSec']['finAffaire'] = $analyse['marge']['commerciale'] = $analyse['marge']['tempsReel'] = $analyse['marge']['finAffaire'] = $analyse['fraisGeneraux'] = 0;
        $analyse['margeHoraire']['commerciale'] = $analyse['margeHoraire']['tempsReel'] = $analyse['margeHoraire']['finChantier'] = 0;
        $analyse['mainO']['ecartTempsReelHtml'] = $analyse['mainO']['ecartFinChantierHtml'] = $analyse['achats']['ecartTempsReelHtml'] = $analyse['achats']['ecartFinChantierHtml'] = '<span class="badge-secondary">-</span>';
        $analyse['debourseSec']['ecartTempsReelHtml'] = $analyse['debourseSec']['ecartFinChantierHtml'] = $analyse['marge']['ecartTempsReelHtml'] = $analyse['marge']['ecartFinChantierHtml'] = '<span class="badge-secondary">-</span>';

        if (!empty($affaire->getAffaireChantiers())):
            foreach ($affaire->getAffaireChantiers() as $chantier):
                $chantier->hydrateAchats();
                $chantier->hydrateAffectations();
                if (!empty($chantier->getChantierAffectations())):
                    foreach ($chantier->getChantierAffectations()as $affect):
                        $affect->hydratePersonnel();
                        $affect->hydrateHeures();
                    endforeach;
                endif;

                $analyseChantier = $this->analyseChantier($chantier);
                $analyse['heures']['prevues'] += $analyseChantier['heures']['prevues'];
                $analyse['heures']['planifiees'] += $analyseChantier['heures']['planifiees'];
                $analyse['heures']['pointees'] += $analyseChantier['heures']['pointees'];
                $analyse['heures']['finAffaire'] += $analyseChantier['heures']['finChantier'];
                $analyse['mainO']['commercial'] += $analyseChantier['mainO']['commercial'];
                $analyse['mainO']['tempsReel'] += $analyseChantier['mainO']['tempsReel'];
                $analyse['mainO']['finAffaire'] += $analyseChantier['mainO']['finChantier'];
                $analyse['mainO']['restant'] += $analyseChantier['mainO']['restant'];
                $analyse['achats']['commercial'] += $analyseChantier['achats']['commercial'];
                $analyse['achats']['tempsReel'] += $analyseChantier['achats']['tempsReel'];
                $analyse['achats']['finAffaire'] += $analyseChantier['achats']['finChantier'];
                $analyse['debourseSec']['commercial'] += $analyseChantier['debourseSec']['commercial'];
                $analyse['debourseSec']['tempsReel'] += $analyseChantier['debourseSec']['tempsReel'];
                $analyse['debourseSec']['finAffaire'] += $analyseChantier['debourseSec']['finChantier'];
                $analyse['marge']['commerciale'] += $analyseChantier['marge']['commerciale'];
                $analyse['marge']['tempsReel'] += $analyseChantier['marge']['tempsReel'];
                $analyse['marge']['finAffaire'] += $analyseChantier['marge']['finChantier'];
                $analyse['fraisGeneraux'] += $analyseChantier['fraisGeneraux'];

                /* Marges horaires */
                $analyse['margeHoraire']['commerciale'] = $analyse['heures']['prevues'] > 0 ? round($analyse['marge']['commerciale'] / $analyse['heures']['prevues'], 2) : null;
                $analyse['margeHoraire']['tempsReel'] = $analyse['heures']['pointees'] > 0 ? round($analyse['marge']['tempsReel'] / $analyse['heures']['pointees'], 2) : null;
                $analyse['margeHoraire']['finAffaire'] = $analyse['heures']['finAffaire'] > 0 ? round($analyse['marge']['finAffaire'] / $analyse['heures']['finAffaire'], 2) : null;
                /* Ecarts Achats */
                if ($analyse['achats']['tempsReel'] > 0 && $analyse['achats']['commercial'] > 0):
                    $ecart = round(($analyse['achats']['tempsReel'] / $analyse['achats']['commercial']) * 100);
                    $analyse['achats']['ecartTempsReel'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['achats']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['achats']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['achats']['ecartTempsReel'] = null;
                    $analyse['achats']['ecartTempsReelHtml'] = '<span class="badge-secondary">-</span>';
                endif;
                if ($analyse['achats']['finAffaire'] > 0 && $analyse['achats']['commercial'] > 0):
                    $ecart = round(($analyse['achats']['finAffaire'] / $analyse['achats']['commercial']) * 100);
                    $analyse['achats']['ecartFinAffaire'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['achats']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['achats']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['achats']['ecartFinAffaire'] = null;
                    $analyse['achats']['ecartFinAffaireHtml'] = '<span class="badge-secondary">-</span>';
                endif;
                /* Ecarts Main oeuvre */
                if ($analyse['mainO']['tempsReel'] > 0 && $analyse['mainO']['commercial'] > 0):
                    $ecart = round(($analyse['mainO']['tempsReel'] / $analyse['mainO']['commercial']) * 100);
                    $analyse['mainO']['ecartTempsReel'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['mainO']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['mainO']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['mainO']['ecartTempsReel'] = null;
                    $analyse['mainO']['ecartTempsReelHtml'] = '<span class="badge-secondary">-</span>';
                endif;
                if ($analyse['mainO']['finAffaire'] > 0 && $analyse['mainO']['commercial'] > 0):
                    $ecart = round(($analyse['mainO']['finAffaire'] / $analyse['mainO']['commercial']) * 100);
                    $analyse['mainO']['ecartFinAffaire'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['mainO']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['mainO']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['mainO']['ecartFinAffaire'] = null;
                    $analyse['mainO']['ecartFinAffaireHtml'] = '<span class="badge-secondary">-</span>';
                endif;
                /* Ecarts deboursé Sec */
                if ($analyse['debourseSec']['tempsReel'] > 0 && $analyse['debourseSec']['commercial'] > 0):
                    $ecart = round(($analyse['debourseSec']['tempsReel'] / $analyse['debourseSec']['commercial']) * 100);
                    $analyse['debourseSec']['ecartTempsReel'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['debourseSec']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['debourseSec']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['debourseSec']['ecartTempsReel'] = null;
                    $analyse['debourseSec']['ecartTempsReelHtml'] = '<span class="badge-secondary">-</span>';
                endif;
                if ($analyse['debourseSec']['finAffaire'] > 0 && $analyse['debourseSec']['commercial'] > 0):
                    $ecart = round(($analyse['debourseSec']['finAffaire'] / $analyse['debourseSec']['commercial']) * 100);
                    $analyse['debourseSec']['ecartFinAffaire'] = ($ecart - 100);
                    if ($ecart <= 100):
                        $analyse['debourseSec']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
                    else:
                        $analyse['debourseSec']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
                    endif;
                else:
                    $analyse['debourseSec']['ecartFinAffaire'] = null;
                    $analyse['debourseSec']['ecartFinAffaireHtml'] = '<span class="badge-secondary">-</span>';
                endif;

                $analyse['marge']['ecartTempsReel'] = round($analyse['marge']['tempsReel'] - $analyse['marge']['commerciale'], 2);
                if ($analyse['marge']['ecartTempsReel'] > 0):
                    $analyse['marge']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-success">+' . $analyse['marge']['ecartTempsReel'] . '</span>';
                elseif ($analyse['marge']['ecartTempsReel'] < 0):
                    $analyse['marge']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">' . $analyse['marge']['ecartTempsReel'] . '</span>';
                else:
                    $analyse['marge']['ecartTempsReelHtml'] = '<span class="badge-secondary"><0/span>';
                endif;

                $analyse['marge']['ecartFinAffaire'] = round($analyse['marge']['finAffaire'] - $analyse['marge']['commerciale'], 2);
                if ($analyse['marge']['ecartFinAffaire'] > 0):
                    $analyse['marge']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-success">+' . $analyse['marge']['ecartFinAffaire'] . '</span>';
                elseif ($analyse['marge']['ecartFinAffaire'] < 0):
                    $analyse['marge']['ecartFinAffaireHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">' . $analyse['marge']['ecartFinAffaire'] . '</span>';
                else:
                    $analyse['marge']['ecartFinAffaireHtml'] = '<span class="badge-secondary"><0/span>';
                endif;

            endforeach;
        endif;

        return $analyse;
    }

    public function liste() {

        $where = array('affaireId <>' => $this->session->userdata('affaireDiversId'));
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
            'commerciaux' => $this->managerUtilisateurs->getCommerciaux(),
            'categories' => $this->managerCategories->getCategories(),
            'clients' => $this->managerClients->getClients(),
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

        if (!$affaireId || !$this->existAffaire($affaireId)):
            redirect('affaires/liste');
        endif;

        $clients = $this->managerClients->getClients();


        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydrateClient();
        $affaire->getAffaireClient()->hydratePlaces();
        $affaire->hydrateCommercial();
        $affaire->hydrateChantiers();
        $affaire->hydratePlace();

        $data = array(
            'analyse' => $affaire->getAffaireId() != $this->session->userdata('affaireDiversId') ? $this->analyseAffaire($affaire) : null,
            'commerciaux' => $this->managerUtilisateurs->getCommerciaux(),
            'clients' => $clients,
            'categories' => $this->managerCategories->getCategories(),
            'affaire' => $affaire,
            'title' => 'Fiche Affaire',
            'description' => 'Fiche affaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addAffaire() {

        if (!$this->ion_auth->in_group(51)):
            redirect('affaires/liste');
        endif;

        if (!$this->form_validation->run('addAffaire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addAffaireId')):
                $affaire = $this->managerAffaires->getAffaireById($this->input->post('addAffaireId'));
                $affaire->setAffaireCommercialId($this->input->post('addAffaireCommercialId') ?: null);
                $affaire->setAffairePlaceId($this->input->post('addAffairePlaceId') ?: null);
                $affaire->setAffaireClientId($this->input->post('addAffaireClientId'));
                $affaire->setAffaireCategorieId($this->input->post('addAffaireCategorieId') ?: null);
                $affaire->setAffaireDevis(strtoupper($this->input->post('addAffaireDevis')));
                $affaire->setAffaireObjet(ucfirst($this->input->post('addAffaireObjet')));
                $affaire->setAffairePrix($this->input->post('addAffairePrix'));
                $affaire->setAffaireDateSignature($this->input->post('addAffaireDateSignature') ? $this->own->mktimeFromInputDate($this->input->post('addAffaireDateSignature')) : null);
                $affaire->setAffaireCouleur($this->input->post('addAffaireCouleur'));
                $affaire->setAffaireCouleurSecondaire($this->couleurSecondaire($this->input->post('addAffaireCouleur')));
                $affaire->setAffaireRemarque($this->input->post('addAffaireRemarque'));
                $this->managerAffaires->editer($affaire);

            else:

                $dataAffaire = array(
                    'affaireEtablissementId' => $this->session->userdata('etablissementId'),
                    'affaireCreation' => time(),
                    'affaireClientId' => $this->input->post('addAffaireClientId'),
                    'affairePlaceId' => $this->input->post('addAffairePlaceId'),
                    'affaireCommercialId' => $this->input->post('addAffaireCommercialId') ?: null,
                    'affaireCategorieId' => $this->input->post('addAffaireCategorieId') ?: null,
                    'affaireDevis' => strtoupper($this->input->post('addAffaireDevis')),
                    'affaireObjet' => ucfirst($this->input->post('addAffaireObjet')),
                    'affairePrix' => $this->input->post('addAffairePrix'),
                    'affaireDateSignature' => $this->input->post('addAffaireDateSignature') ? $this->own->mktimeFromInputDate($this->input->post('addAffaireDateSignature')) : null,
                    'affaireCouleur' => $this->input->post('addAffaireCouleur'),
                    'affaireCouleurSecondaire' => $this->couleurSecondaire($this->input->post('addAffaireCouleur')),
                    'affaireRemarque' => $this->input->post('addAffaireRemarque'),
                    'affaireEtat' => 1
                );
                $affaire = new Affaire($dataAffaire);
                $this->managerAffaires->ajouter($affaire);

            endif;

            echo json_encode(array('type' => 'success', 'affaireId' => $affaire->getAffaireId()));
        endif;
    }

    public function clotureAffaire() {

    }

    public function delAffaire() {
        if (!$this->ion_auth->in_group(57) || !$this->form_validation->run('getAffaire') || $this->input->post('affaireId') == $this->session->userdata('affaireDiversId')):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));
            $this->managerAffaires->delete($affaire);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function modAffaireDivers() {
        if (!$this->ion_auth->in_group(57)):
            $affaire = $this->managerAffaires->getAffaireById($this->session->userdata('affaireDiversId'));
            $affaire->setAffaireCouleur($this->input->post('couleur'));
            $affaire->setAffaireCouleurSecondaire($this->couleurSecondaire($this->input->post('couleur')));
            $this->managerAffaires->editer($affaire);

            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        endif;
    }

}
