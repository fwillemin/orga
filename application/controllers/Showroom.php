<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class showroom extends My_Controller {
    /* Clé Google Maps API */

    const googleApiKey = "";

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';
    }

//    public function index() {
//
//        $data = array(
//            'type' => 'website',
//            'url' => site_url(),
//            'image' => base_url('assets/img/wallpaper2.jpg'),
//            'title' => 'Logiciel de planification de chantiers pour les professionnels du bâtiment',
//            'description' => 'Logiciel de planification des interventions sur chantier pour les entreprises du batiment, de la maintenance industrielle et d\'entretien de locaux',
//            'content' => $this->viewFolder . __FUNCTION__
//        );
//        $this->load->view('template/contentShowroom', $data);
//    }

    public function index() {
        redirect('secure/login');
    }

    public function tarifs() {

        $data = array(
            'type' => 'website',
            'url' => site_url('tarifs'),
            'image' => '',
            'title' => 'Tarifs',
            'description' => 'Prix des licences Organibat : flexibilité et liberté',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function inscription() {
        $data = array(
            'type' => 'website',
            'url' => site_url('essai-gratuit-logiciel-gestion-chantier'),
            'image' => '',
            'title' => 'Créer un compte gratuitement',
            'description' => 'Créer un compte et utilisez Organibat sans restriction pendant 1 mois. Planification de chantier, gestion du personnel, saisie des heures et des pointages, gestion des achats, ...',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function getDomaine() {
        $chaine = strtolower($this->own->enleverCaracteresSpeciaux(trim(str_replace(' ', '-', $this->input->post('prenom')))))
                . '.'
                . strtolower($this->own->enleverCaracteresSpeciaux(trim(str_replace(' ', '-', $this->input->post('nom')))))
                . '@'
                . strtolower($this->own->enleverCaracteresSpeciaux(trim(str_replace(' ', '-', $this->input->post('chaine')))))
                . ".com";
        echo json_encode(array('type' => 'success', 'domaine' => $chaine));
    }

    public function addInscription() {

        // Création de la RS
        $dataRs = array('rsOriginId' => null, 'rsNom' => mb_strtoupper($this->input->post('inscriptionRS')), 'rsInscription' => time(), 'rsMoisFiscal' => $this->input->post('inscriptionMoisFiscal'), 'rsCategorieNC' => null);
        $rs = new RaisonSociale($dataRs);
        $this->managerRaisonsSociales->ajouter($rs);

        $adresse = $this->input->post('inscriptionAdresse') . ' ' . $this->input->post('inscriptionCp') . ' ' . $this->input->post('inscriptionVille');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($adresse) . "&key=" . self::googleApiKey;
        $response = json_decode(file_get_contents($url));

        if ($response->status == 'OK'):
            $gps = $response->results[0]->geometry->location->lat . ',' . $response->results[0]->geometry->location->lng;
        else:
            $gps = "0,0";
        endif;

        // Création de l'établissement
        $dataEtablissement = array(
            'etablissementRsId' => $rs->getRsId(),
            'etablissementNom' => mb_strtoupper($this->input->post('inscriptionRS')),
            'etablissementAdresse' => $this->input->post('inscriptionAdresse'),
            'etablissementCp' => $this->input->post('inscriptionCp'),
            'etablissementVille' => mb_strtoupper($this->input->post('inscriptionVille')),
            'etablissementContact' => ucfirst($this->input->post('inscriptionPrenom')) . ' ' . mb_strtoupper($this->input->post('inscriptionNom')),
            'etablissementTelephone' => $this->input->post('inscriptionTelephone'),
            'etablissementEmail' => $this->input->post('inscriptionEmail'),
            'etablissementGps' => $gps,
            'etablissementStatut' => 1,
            'etablissementAffaireDiversId' => null,
            'etablissementMessage' => 'Bienvenue sur Organibat',
            'etablissementTauxFraisGeneraux' => 15,
            'etablissementTauxHoraireMoyen' => 21,
            'etablissementBaseHebdomadaire' => 0,
            'etablissementExpiration' => time() + 2678400,
            'etablissementLimiteActifs' => 5
        );
        $etablissement = new Etablissement($dataEtablissement);
        $this->managerEtablissements->ajouter($etablissement);

        //Creation des paramètres
        $dataParam = array(
            'parametreEtablissementId' => $etablissement->getEtablissementId(),
            'tranchePointage' => 30,
            'nbSemainesAvant' => 2,
            'nbSemainesApres' => 6,
            'tailleAffectations' => 2,
            'genererPaniers' => 1,
            'messageEtablissement' => 'Bienvenue chez Organibat',
            'limiteHeuresSupp' => 35,
            'distanceZI' => 2
        );
        $parametre = new Parametre($dataParam);
        $this->managerParametres->ajouter($parametre);

        // Création d'un utilisateur Admin
        $passwd = 'Organibat' . date('Y');
        $additional_data = array(
            'userNom' => mb_strtoupper($this->input->post('inscriptionNom')),
            'userPrenom' => ucfirst($this->input->post('inscriptionPrenom')),
            'userEtablissementId' => $etablissement->getEtablissementId(),
            'userOriginId' => null,
            'userClairMdp' => $passwd
        );
        $email = $this->input->post('inscriptionDomaine');

        $groups = $this->db->select('id')->from('groups')->where('id <> ', 2)->where('id <> ', 4)->where('id <> ', 9)->get()->result();
        foreach ($groups as $g):
            $group[] = $g->id;
        endforeach;

        try {
            $userId = $this->ion_auth->register($email, $passwd, $this->input->post('inscriptionEmail'), $additional_data, $group);
            $user = $this->managerUtilisateurs->getUtilisateurById($userId);
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }

        // Création d'un client avec cet Etablissement
        $dataClient = array(
            'clientEtablissementId' => $etablissement->getEtablissementId(),
            'clientNom' => 'DIVERS-' . mb_strtoupper($etablissement->getEtablissementNom()),
            'clientAdresse' => $etablissement->getEtablissementAdresse(),
            'clientCp' => $etablissement->getEtablissementCp(),
            'clientVille' => $etablissement->getEtablissementVille(),
            'clientPays' => 'FRANCE',
            'clientEmail' => $this->input->post('inscriptionEmail'),
            'clientFixe' => '',
            'clientPortable' => ''
        );
        $clientDivers = new Client($dataClient);
        $this->managerClients->ajouter($clientDivers);

        // Création d'une affaire DIVERS
        $dataAffaire = array(
            'affaireEtablissementId' => $etablissement->getEtablissementId(),
            'affaireCreation' => time(),
            'affaireClientId' => $clientDivers->getClientId(),
            'affaireObjet' => 'Gestion des Divers Chantiers',
            'affairePrix' => 1,
            'affaireDateSignature' => time(),
            'affaireEtat' => 2,
            'affaireCouleur' => '#FFFFFF',
            'affaireCouleurSecondaire' => '#878787',
            'affaireDevis' => '',
            'affaireRemarque' => ''
        );
        $divers = new Affaire($dataAffaire);
        $this->managerAffaires->ajouter($divers);
        $etablissement->setEtablissementAffaireDiversId($divers->getAffaireId());
        $this->managerEtablissements->editer($etablissement);

        // Création horaire de base
        $dataHoraire = array(
            'horaireOriginId' => null,
            'horaireEtablissementId' => $etablissement->getEtablissementId(),
            'horaireNom' => 'BASE',
            'horaireLun1' => '08:00',
            'horaireLun2' => '12:00',
            'horaireLun3' => '13:00',
            'horaireLun4' => '16:00',
            'horaireMar1' => '08:00',
            'horaireMar2' => '12:00',
            'horaireMar3' => '13:00',
            'horaireMar4' => '16:00',
            'horaireMer1' => '08:00',
            'horaireMer2' => '12:00',
            'horaireMer3' => '13:00',
            'horaireMer4' => '16:00',
            'horaireJeu1' => '08:00',
            'horaireJeu2' => '12:00',
            'horaireJeu3' => '13:00',
            'horaireJeu4' => '16:00',
            'horaireVen1' => '08:00',
            'horaireVen2' => '12:00',
            'horaireVen3' => '13:00',
            'horaireVen4' => '16:00',
            'horaireSam1' => '00:00',
            'horaireSam2' => '00:00',
            'horaireSam3' => '00:00',
            'horaireSam4' => '00:00',
            'horaireDim1' => '00:00',
            'horaireDim2' => '00:00',
            'horaireDim3' => '00:00',
            'horaireDim4' => '00:00'
        );
        $horaire = new Horaire($dataHoraire);
        $this->managerHoraires->ajouter($horaire);

        $this->mail->emailInscription($user);
        $this->mail->emailADMINInscription($etablissement, ($this->input->post('inscriptionHelp') ?: null));

        echo json_encode(array('type' => 'success'));
    }

    public function inscriptionValidee() {
        $data = array(
            'type' => 'website',
            'url' => site_url('showroom/inscriptionValidee'),
            'image' => '',
            'title' => 'Inscription validée',
            'description' => 'Votre inscription sur Organibat est confirmée',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function articlePlanifier() {
        $data = array(
            'type' => 'article',
            'url' => site_url('planifier-ses-chantier-un-petit-effort-organisation-pour-une-meilleure-visibilite'),
            'image' => base_url('assets/img/attente-des-clients.png'),
            'title' => 'Planifier ses chantiers pour une meilleure visibilité',
            'description' => 'Planifier et organiser ses interventions sur chantier est aujourd\'hui l\'une desc lés de la compétitivité de votre entreprise du bâtiment. Comment faire ?',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function localOuSaas() {
        $data = array(
            'type' => 'article',
            'url' => site_url('logiciel-de-gestion-de-chantier-en-local-ou-en-ligne'),
            'image' => base_url('assets/img/cloud-vs-local.png'),
            'title' => 'Logiciel installé ou en ligne',
            'description' => 'Faut-il passer à un logiciel de planification de chantier en ligne ? Quelles sont les différences entre les logiciels installés en local sur votre ordinateur et les logiciels SaaS ?',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function noway() {
        $this->output->set_status_header('404');
        $data = array(
            'type' => 'website',
            'url' => site_url('showroom/noway'),
            'image' => '',
            'title' => 'Erreur 404. Page inexistante',
            'description' => 'La page que vous souhaitez n\'existe pas ou a été supprimée.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

}
