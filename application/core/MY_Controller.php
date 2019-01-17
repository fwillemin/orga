<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        switch ($this->session->userdata('parametres')['tailleAffectations']):
            case 1:
                $this->hauteur = 40;
                $this->largeur = 30;
                break;
            case 2:
                $this->hauteur = 50;
                $this->largeur = 35;
                break;
            case 3:
                $this->hauteur = 60;
                $this->largeur = 40;
                break;
        endswitch;
        $this->messageDroitsInsuffisants = 'Vous ne possédez pas les droits nécessaires à cette action.';
    }

    public function passwordCheck($str) {
        $this->form_validation->set_message('passwordCheck', 'Votre mot de passe doit contenir au moins une lettre et un chiffre');
        if (!$str || (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str))) {
            return TRUE;
        }
        return FALSE;
    }

    public function existUtilisateur($utilisateurId) {
        $this->form_validation->set_message('existUtilisateur', 'Cet utilisateur est introuvable.');
        if ($this->managerUtilisateurs->count(array('id' => $utilisateurId, 'userEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$utilisateurId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existHoraire($horaireId) {
        $this->form_validation->set_message('existHoraire', 'Cet horaire est introuvable.');
        if ($this->managerHoraires->count(array('horaireId' => $horaireId, 'horaireEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$horaireId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existPersonnel($personnelId) {
        $this->form_validation->set_message('existPersonnel', 'Ce personnel est introuvable.');
        if ($this->managerPersonnels->count(array('personnelId' => $personnelId, 'personnelEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$personnelId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existEquipe($equipeId) {
        $this->form_validation->set_message('existEquipe', 'Cette équipe est introuvable.');
        if ($this->managerEquipes->count(array('equipeId' => $equipeId, 'equipeEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$equipeId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existTauxHoraire($tauxId) {
        $this->form_validation->set_message('existTauxHoraire', 'Ce taux horaire est introuvable.');
        if ($this->managerTauxHoraires->count(array('tauxHoraireId' => $tauxId)) == 1 || !$tauxId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existClient($clientId) {
        $this->form_validation->set_message('existClient', 'Ce client est introuvable.');
        if ($this->managerClients->count(array('clientId' => $clientId)) == 1 || !$clientId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existPlace($placeId) {
        $this->form_validation->set_message('existPlace', 'Cette place est introuvable.');
        if ($this->managerPlaces->count(array('placeId' => $placeId)) == 1 || !$placeId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existCategorie($categorieId) {
        $this->form_validation->set_message('existCategorie', 'Cette catégorie est introuvable.');
        if ($this->managerCategories->count(array('categorieId' => $categorieId)) == 1 || !$categorieId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAffaire($affaireId) {
        $this->form_validation->set_message('existAffaire', 'Cette affaire est introuvable.');
        if ($this->managerAffaires->count(array('affaireId' => $affaireId)) == 1 || !$affaireId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existChantier($chantier) {
        $this->form_validation->set_message('existChantier', 'Ce chantier est introuvable.');
        if ($this->managerChantiers->count(array('chantierId' => $chantier)) == 1 || !$chantier) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAchat($achatId) {
        $this->form_validation->set_message('existAchat', 'Cet achat est introuvable.');
        if ($this->managerAchats->count(array('achatId' => $achatId)) == 1 || !$achatId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existRS($rsId) {
        $this->form_validation->set_message('existRS', 'Cette raison sociale est introuvable.');
        if ($this->managerAchats->count(array('rsId' => $rsId)) == 1 || !$rsId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAffectation($affectationId) {
        $this->form_validation->set_message('existAffectation', 'Cette affectation est introuvable.');
        if ($this->managerAffectations->count(array('affectationId' => $affectationId)) == 1 || !$affectationId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAffectationMigration($affectationId, $chantierId) {
        $this->form_validation->set_message('existAffectation', 'Cette affectation est introuvable.');
        if ($this->managerAffectations->count(array('affectationId' => $affectationId)) == 1 || !$affectationId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existHeure($heureId) {
        $this->form_validation->set_message('existHeure', 'Cette heure est introuvable.');
        if ($this->managerHeures->count(array('heureId' => $heureId)) == 1 || !$heureId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existFournisseur($fournisseurId) {
        $this->form_validation->set_message('existFournisseur', 'Ce fournisseur est introuvable.');
        if ($this->managerFournisseurs->count(array('fournisseurId' => $fournisseurId)) == 1 || !$fournisseurId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existLivraison($livraisonId) {
        $this->form_validation->set_message('existLivraison', 'Cette livraison est introuvable.');
        if ($this->managerLivraisons->count(array('livraisonId' => $livraisonId)) == 1 || !$livraisonId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existIndispo($indispoId) {
        $this->form_validation->set_message('existIndispo', 'Cette indisponibilité est introuvable.');
        if ($this->managerIndisponibilites->count(array('indispoId' => $indispoId)) == 1 || !$indispoId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existPointage($pointageId) {
        $this->form_validation->set_message('existPointage', 'Ce pointage est introuvable.');
        if ($this->managerPointages->count(array('pointageId' => $pointageId)) == 1 || !$pointageId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existContact($contactId) {
        $this->form_validation->set_message('existContact', 'Ce contact est introuvable.');
        if ($this->managerContacts->count(array('contactId' => $contactId)) == 1 || !$contactId) :
            return true;
        else :
            return false;
        endif;
    }

    public function isPortable($numero) {
        $this->form_validation->set_message('isPortable', 'Le numéro de portable doit commencer par 06 ou 07 ou +336 ou +337');
        if (preg_match("/^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/", $numero) || !$numero):
            return true;
        else :
            return false;
        endif;
    }

    public function getCouleurSecondaire() {
        echo json_encode(array('type' => 'success', 'couleur' => $this->couleurSecondaire($this->input->post('couleur'))));
    }

    public function couleurSecondaire($couleur) {
        return $this->own->getCouleurSecondaire($couleur, 120);
    }

    /* Analyse d'un chantier */

    protected function analyseChantier(Chantier $chantier) {
        /* Analyse */

        $analyse['heures']['prevues'] = $chantier->getChantierHeuresPrevues();
        $analyse['heures']['planifiees'] = $chantier->getChantierheuresPlanifiees();
        $analyse['heures']['pointees'] = $chantier->getChantierheuresPointees();
        $analyse['heures']['finChantier'] = $chantier->getChantierheuresPointees(); /* Somme des heures déja pointees et des heures restantes pour les affectations du chantier */

        // Couts de main d'oeuvre
        $analyse['mainO']['tempsReel'] = 0; /* Total des coûts des heures pointees */
        $analyse['mainO']['restant'] = 0; /* Total des coûts des heures restantes planifiées */
        $analyse['mainO']['finChantier'] = 0; /* Total des coûts des heures planifiées + pointees */
        $analyse['mainO']['commercial'] = $chantier->getChantierHeuresPrevues() * $chantier->getChantierTauxHoraireMoyen(); /* Total des coûts des heures prévues */

        if (!empty($chantier->getChantierAffectations())):
            foreach ($chantier->getChantierAffectations() as $affectation):

                $tauxHoraireAffectation = $affectation->getAffectationPersonnel()->getTauxHoraireADate($affectation->getAffectationDebutDate());
                if ($tauxHoraireAffectation == 0):
                    $tauxHoraireAffectation = $chantier->getChantierTauxHoraireMoyen();
                endif;

                if ($chantier->getChantierEtat() == 1):
                    $heuresPlanifieesRestantes = $affectation->getAffectationHeuresPlanifiees() - $affectation->getAffectationHeuresPointees();
                    $analyse['heures']['finChantier'] += $heuresPlanifieesRestantes;
                else:
                    $heuresPlanifieesRestantes = 0;
                endif;

                if (!empty($affectation->getAffectationHeures())):
                    foreach ($affectation->getAffectationHeures() as $heure):
                        $coutHeuresPointees = ($heure->getHeureDuree() / 60) * $tauxHoraireAffectation;
                        $analyse['mainO']['tempsReel'] += $coutHeuresPointees;
                    endforeach;
                endif;
                $analyse['mainO']['restant'] += $heuresPlanifieesRestantes * $tauxHoraireAffectation;
            endforeach;
            $analyse['mainO']['finChantier'] += $analyse['mainO']['tempsReel'] + $analyse['mainO']['restant'];
        endif;

        if ($analyse['mainO']['tempsReel'] > 0):
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
        if ($analyse['mainO']['finChantier'] > 0 && $analyse['mainO']['commercial'] > 0):
            $ecart = round(($analyse['mainO']['finChantier'] / $analyse['mainO']['commercial']) * 100);
            $analyse['mainO']['ecartFinChantier'] = ($ecart - 100);
            if ($ecart <= 100):
                $analyse['mainO']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
            else:
                $analyse['mainO']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
            endif;
        else:
            $analyse['mainO']['ecartFinChantier'] = null;
            $analyse['mainO']['ecartFinChantierHtml'] = '<span class="badge-secondary">-</span>';
        endif;

        // Achats
        $analyse['achats']['restants'] = 0;
        $analyse['achats']['tempsReel'] = $chantier->getChantierBudgetConsomme();
        $analyse['achats']['commercial'] = $chantier->getChantierBudgetAchats();
        if (!empty($chantier->getChantierAchats() && $chantier->getChantierEtat() == 1)):
            foreach ($chantier->getChantierAchats() as $achat):
                if ($achat->getAchatTotal() == 0):
                    $analyse['achats']['restants'] += $achat->getAchatTotalPrevisionnel();
                endif;
            endforeach;
        endif;
        $analyse['achats']['finChantier'] = $chantier->getChantierBudgetConsomme() + $analyse['achats']['restants'];

        if ($chantier->getChantierBudgetConsomme() > 0 && $chantier->getChantierBudgetAchats() > 0):
            $ecart = round(($chantier->getChantierBudgetConsomme() / $chantier->getChantierBudgetAchats()) * 100);
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

        if ($analyse['achats']['finChantier'] > 0 && $chantier->getChantierBudgetAchats() > 0):
            $ecart = round(($analyse['achats']['finChantier'] / $chantier->getChantierBudgetAchats()) * 100);
            $analyse['achats']['ecartFinChantier'] = ($ecart - 100);
            if ($ecart <= 100):
                $analyse['achats']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
            else:
                $analyse['achats']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
            endif;
        else:
            $analyse['achats']['ecartFinChantier'] = null;
            $analyse['achats']['ecartFinChantierHtml'] = '<span class="badge-secondary">-</span>';
        endif;

        // Deboursé Sec
        $analyse['debourseSec']['commercial'] = $analyse['mainO']['commercial'] + $chantier->getChantierBudgetAchats();
        $analyse['debourseSec']['tempsReel'] = $analyse['mainO']['tempsReel'] + $chantier->getChantierBudgetConsomme();
        $analyse['debourseSec']['finChantier'] = $analyse['mainO']['finChantier'] + $analyse['achats']['finChantier'];
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

        if ($analyse['debourseSec']['finChantier'] > 0 && $analyse['debourseSec']['commercial'] > 0):
            $ecart = round(($analyse['debourseSec']['finChantier'] / $analyse['debourseSec']['commercial']) * 100);
            $analyse['debourseSec']['ecartFinChantier'] = ($ecart - 100);
            if ($ecart <= 100):
                $analyse['debourseSec']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-success">' . ($ecart - 100) . '%</span>';
            else:
                $analyse['debourseSec']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">+' . ($ecart - 100) . '%</span>';
            endif;
        else:
            $analyse['debourseSec']['ecartFinChantier'] = null;
            $analyse['debourseSec']['ecartFinChantierHtml'] = '<span class="badge-secondary">-</span>';
        endif;

        $analyse['fraisGeneraux'] = round($chantier->getChantierPrix() * $chantier->getChantierFraisGeneraux() / 100, 2);

        // Marges
        $analyse['marge']['commerciale'] = $chantier->getChantierPrix() - $analyse['fraisGeneraux'] - $chantier->getChantierBudgetAchats() - $analyse['mainO']['commercial'];
        $analyse['margeHoraire']['commerciale'] = round($analyse['marge']['commerciale'] / $chantier->getChantierHeuresPrevues(), 2);
        $analyse['marge']['tempsReel'] = $chantier->getChantierPrix() - $analyse['fraisGeneraux'] - $chantier->getChantierBudgetConsomme() - $analyse['mainO']['tempsReel'];
        if ($chantier->getChantierHeuresPointees()):
            $analyse['margeHoraire']['tempsReel'] = round($analyse['marge']['tempsReel'] / $chantier->getChantierHeuresPointees(), 2);
        else:
            $analyse['margeHoraire']['tempsReel'] = 0;
        endif;
        $analyse['marge']['finChantier'] = $chantier->getChantierPrix() - $analyse['fraisGeneraux'] - $analyse['achats']['finChantier'] - $analyse['mainO']['finChantier'];
        if ($chantier->getChantierheuresPlanifiees() && $chantier->getChantierheuresPlanifiees() > 0):
            $analyse['margeHoraire']['finChantier'] = round($analyse['marge']['finChantier'] / $chantier->getChantierheuresPlanifiees(), 2);
        else:
            $analyse['margeHoraire']['finChantier'] = 0;
        endif;

        $analyse['marge']['ecartTempsReel'] = round($analyse['marge']['tempsReel'] - $analyse['marge']['commerciale'], 2);
        if ($analyse['marge']['ecartTempsReel'] > 0):
            $analyse['marge']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-success">+' . $analyse['marge']['ecartTempsReel'] . '</span>';
        elseif ($analyse['marge']['ecartTempsReel'] < 0):
            $analyse['marge']['ecartTempsReelHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">' . $analyse['marge']['ecartTempsReel'] . '</span>';
        else:
            $analyse['marge']['ecartTempsReelHtml'] = '<span class="badge-secondary"><0/span>';
        endif;

        $analyse['marge']['ecartFinChantier'] = round($analyse['marge']['finChantier'] - $analyse['marge']['commerciale'], 2);
        if ($analyse['marge']['ecartFinChantier'] > 0):
            $analyse['marge']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-success">+' . $analyse['marge']['ecartFinChantier'] . '</span>';
        elseif ($analyse['marge']['ecartFinChantier'] < 0):
            $analyse['marge']['ecartFinChantierHtml'] = '<span class="badgeAnalyseChantier badge badge-warning">' . $analyse['marge']['ecartFinChantier'] . '</span>';
        else:
            $analyse['marge']['ecartFinChantierHtml'] = '<span class="badge-secondary"><0/span>';
        endif;
        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($analyse, true));

        return $analyse;
    }

}
