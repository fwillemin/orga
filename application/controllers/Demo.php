<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Demo extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';
    }

    public function phpServer() {
        phpinfo();
    }

    private function executeSqlFile($fileSQL) {
        $req = file_get_contents($fileSQL);
        $array = explode(';', $req);
        foreach ($array as $sql) {
            if ($sql != '') {
                //echo $sql;
                $this->db->query($sql);
            }
        }
    }

    public function initDemo() {
        try {
            $this->resetDemo();
            /* Passage des auto-increment à 0 */
            $this->executeSqlFile('demoOrganibat-ResetAutoincrement.sql');
            $this->hydrateDemo();

            /* Mise à la date du moment de l'activité */
            $this->teleporteDemo();

            redirect('organibat/deconnexion');
        } catch (Exception $e) {
            print_r($e);
        }
    }

    private function resetDemo() {

        /**
         * Suppression des clients et donc par cascade des affaires, chantiers, affectations, heures
         */
        $clients = $this->managerClients->getClients();
        if (!empty($clients)):
            foreach ($clients as $client):
                $this->managerClients->delete($client);
            endforeach;
        endif;

        /**
         * Suppression du personnel et donc par cascade des indispo
         */
        $personnels = $this->managerPersonnels->getPersonnels();
        if (!empty($personnels)):
            foreach ($personnels as $personnel):
                $this->managerPersonnels->delete($personnel);
            endforeach;
        endif;

        /* Suppression des catégories */
        $categories = $this->managerCategories->getCategories();
        if (!empty($categories)):
            foreach ($categories as $categorie):
                $this->managerCategories->delete($categorie);
            endforeach;
        endif;

        /* Suppression des horaires */
        $horaires = $this->managerHoraires->getHoraires();
        if (!empty($horaires)):
            foreach ($horaires as $horaire):
                $this->managerHoraires->delete($horaire);
            endforeach;
        endif;

        /* Suppression des contacts */
        $contacts = $this->managerContacts->getContacts();
        if (!empty($contacts)):
            foreach ($contacts as $contact):
                $this->managerContacts->delete($contact);
            endforeach;
        endif;

        /* Suppression des fournisseurs */
        $fournisseurs = $this->managerFournisseurs->getFournisseurs();
        if (!empty($fournisseurs)):
            foreach ($fournisseurs as $fournisseur):
                $this->managerFournisseurs->delete($fournisseur);
            endforeach;
        endif;

        /* Suppression des equipes */
        $equipes = $this->managerEquipes->getEquipes();
        if (!empty($equipes)):
            foreach ($equipes as $equipe):
                $this->managerEquipes->delete($equipe);
            endforeach;
        endif;

        /* Suppression des users */
        $users = $this->managerUtilisateurs->getUtilisateurs(array());
        if (!empty($users)):
            foreach ($users as $user):
                $this->managerUtilisateurs->delete($user);
            endforeach;
        endif;
    }

    private function hydrateDemo() {

        /* création du user demo */
        $additional_data = array(
            'userNom' => 'Démo',
            'userPrenom' => 'Démo',
            'userEtablissementId' => $this->session->userdata('etablissementId'),
            'userOriginId' => null,
            'userClairMdp' => 'demonstration2019',
            'userCode' => 0000
        );
        $this->ion_auth->register('demo.demo@organibat.com', 'demonstration2019', 'demo.demo@organibat.com', $additional_data, array(1, 3, 10, 11, 20, 21, 25, 26, 30, 31, 40, 50, 51, 52, 53, 54, 55, 56, 57, 58, 60, 61, 70, 80, 81, 82, 83, 90, 91));

        /* Creation des équipes */
        $arrayEquipe = array(
            'equipeNom' => 'Couvreurs',
            'equipeCouleur' => '#accbe0',
            'equipeCouleurSecondaire' => '#0f3956'
        );
        $equipe = new Equipe($arrayEquipe);
        $this->managerEquipes->ajouter($equipe);

        $arrayEquipe = array(
            'equipeNom' => 'Plaquistes',
            'equipeCouleur' => '#d6cd7a',
            'equipeCouleurSecondaire' => '#4c4609'
        );
        $equipe = new Equipe($arrayEquipe);
        $this->managerEquipes->ajouter($equipe);

        /* création des horaires */
        $dataHoraire = array(
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
            'horaireJeu3' => '16:00',
            'horaireJeu4' => '13:00',
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
            'horaireDim4' => '00:00',
        );
        $horaire = new Horaire($dataHoraire);
        $this->managerHoraires->ajouter($horaire);

        /* Creation des catégories */
        $cats = array('Agencement', 'Couverture', 'Fenêtre Alu', 'Fenêtre PVC', 'Maçonnerie', 'Maison individuelle', 'Platrerie', 'Plomberie', 'Porte alu', 'Porte Bois');
        foreach ($cats as $cat):
            $dataCategorie = array(
                'categorieRsId' => $this->session->userdata('rsId'),
                'categorieNom' => $cat,
            );
            $categorie = new Categorie($dataCategorie);
            $this->managerCategories->ajouter($categorie);
        endforeach;

        /* Création des fournisseurs */
        $dataFournisseur = array(
            'fournisseurOriginId' => null,
            'fournisseurEtablissementId' => $this->session->userdata('etablissementId'),
            'fournisseurNom' => 'Leroy Merlin',
            'fournisseurAdresse' => 'Rue du près',
            'fournisseurCp' => '59300',
            'fournisseurVille' => 'Valenciennes',
            'fournisseurTelephone' => '03.27.00.00.00',
            'fournisseurEmail' => 'lm@organibat.com'
        );
        $fournisseur = new Fournisseur($dataFournisseur);
        $this->managerFournisseurs->ajouter($fournisseur);

        $dataFournisseur = array(
            'fournisseurOriginId' => null,
            'fournisseurEtablissementId' => $this->session->userdata('etablissementId'),
            'fournisseurNom' => 'Loxam',
            'fournisseurAdresse' => 'Rue de la place verte',
            'fournisseurCp' => '59300',
            'fournisseurVille' => 'Valenciennes',
            'fournisseurTelephone' => '03.27.00.00.00',
            'fournisseurEmail' => 'loxam@organibat.com'
        );
        $fournisseur = new Fournisseur($dataFournisseur);
        $this->managerFournisseurs->ajouter($fournisseur);

        /* Personnels */
        $this->executeSqlFile('demoOrganibat-PersonnelsClientsContacts.sql');
        $this->executeSqlFile('demoOrganibat-Activite.sql');
    }

    private function teleporteDemo() {
        $deltaSemaines = floor((mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1559512800) / 604800);
        $delta = $deltaSemaines * 7;
        $affaires = $this->managerAffaires->getAffaires();
        foreach ($affaires as $affaire):
            $affaire->setAffaireCreation($affaire->getAffaireCreation() + $delta);
            $affaire->setAffaireDateSignature($affaire->getAffaireDateSignature() + $delta);
            if ($affaire->getAffaireDateCloture()):
                $affaire->setAffaireDateCloture($affaire->getAffaireDateCloture() + $delta);
            endif;
            $this->managerAffaires->editer($affaire);
        endforeach;
        $chantiers = $this->managerChantiers->getChantiers(array('chantierDateCloture >' => 0));
        if (!empty($chantiers)):
            foreach ($chantiers as $chantier):
                $chantier->setChantierDateCloture($chantier->getChantierDateCloture() + $delta);
                $this->managerChantiers->editer($chantier);
            endforeach;
        endif;
        $affectations = $this->managerAffectations->getAffectations();
        foreach ($affectations as $affectation):
            $affectation->setAffectationDebutDate($affectation->getAffectationDebutDate() + $delta);
            $affectation->setAffectationFinDate($affectation->getAffectationFinDate() + $delta);
            $this->managerAffectations->editer($affectation);
        endforeach;
        $achats = $this->managerAchats->getAchats();
        foreach ($achats as $achat):
            $achat->setAchatDate($achat->getAchatDate() + $delta);
            $achat->setAchatLivraisonDate($achat->getAchatLivraisonDate() + $delta);
            $this->managerAchats->editer($achat);
        endforeach;
        $livraisons = $this->managerLivraisons->getLivraisons();
        foreach ($livraisons as $livraison):
            $livraison->setLivraisonDate($livraison->getLivraisonDate() + $delta);
            $this->managerLivraisons->editer($livraison);
        endforeach;
        $heures = $this->managerHeures->getHeures();
        foreach ($heures as $heure):
            $heure->setHeureDate($heure->getHeureDate() + $delta);
            $this->managerHeures->editer($heure);
        endforeach;
        $indisponibilites = $this->managerIndisponibilites->getIndisponibilites();
        foreach ($indisponibilites as $indispo):
            $indispo->setIndispoDebutDate($indispo->getIndispoDebutDate() + $delta);
            $indispo->setIndispoFinDate($indispo->getIndispoFinDate() + $delta);
            $this->managerIndisponibilites->editer($indispo);
        endforeach;
        $contacts = $this->managerContacts->getContacts();
        foreach ($contacts as $contact):
            $contact->setContactDate($contact->getContactDate() + $delta);
            $this->managerContacts->editer($contact);
        endforeach;
    }

}
