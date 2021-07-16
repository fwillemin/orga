<?php

/**
 * Classe de gestion des Horaires
 * Manager : Model_horaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Horaire {

    protected $horaireId;
    protected $horaireOriginId;
    protected $horaireEtablissementId;
    protected $horaireNom;
    protected $horaireLun1;
    protected $horaireLun2;
    protected $horaireLun3;
    protected $horaireLun4;
    protected $horaireLunAM;
    protected $horaireLunPM;
    protected $horaireLun;
    protected $horaireMar1;
    protected $horaireMar2;
    protected $horaireMar3;
    protected $horaireMar4;
    protected $horaireMarAM;
    protected $horaireMarPM;
    protected $horaireMar;
    protected $horaireMer1;
    protected $horaireMer2;
    protected $horaireMer3;
    protected $horaireMer4;
    protected $horaireMerAM;
    protected $horaireMerPM;
    protected $horaireMer;
    protected $horaireJeu1;
    protected $horaireJeu2;
    protected $horaireJeu3;
    protected $horaireJeu4;
    protected $horaireJeuAM;
    protected $horaireJeuPM;
    protected $horaireJeu;
    protected $horaireVen1;
    protected $horaireVen2;
    protected $horaireVen3;
    protected $horaireVen4;
    protected $horaireVenAM;
    protected $horaireVenPM;
    protected $horaireVen;
    protected $horaireSam1;
    protected $horaireSam2;
    protected $horaireSam3;
    protected $horaireSam4;
    protected $horaireSamAM;
    protected $horaireSamPM;
    protected $horaireSam;
    protected $horaireDim1;
    protected $horaireDim2;
    protected $horaireDim3;
    protected $horaireDim4;
    protected $horaireDimAM;
    protected $horaireDimPM;
    protected $horaireDim;
    protected $horaireTotal;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
    }

    /* Retourne le nombre de minute pour une journée */

    public function getHoraireMinutes($jour, $moment = 3) {
        switch ($jour):
            case 0:
                if ($moment == 3):
                    return $this->horaireDim * 60;
                elseif ($moment == 1):
                    return $this->horaireDimAM * 60;
                else:
                    return $this->horaireDimPM * 60;
                endif;
                break;
            case 1:
                if ($moment == 3):
                    return $this->horaireLun * 60;
                elseif ($moment == 1):
                    return $this->horaireLunAM * 60;
                else:
                    return $this->horaireLunPM * 60;
                endif;
                break;
            case 2:
                if ($moment == 3):
                    return $this->horaireMar * 60;
                elseif ($moment == 1):
                    return $this->horaireMarAM * 60;
                else:
                    return $this->horaireMarPM * 60;
                endif;
                break;
            case 3:
                if ($moment == 3):
                    return $this->horaireMer * 60;
                elseif ($moment == 1):
                    return $this->horaireMerAM * 60;
                else:
                    return $this->horaireMerPM * 60;
                endif;
                break;
            case 4:
                if ($moment == 3):
                    return $this->horaireJeu * 60;
                elseif ($moment == 1):
                    return $this->horaireJeuAM * 60;
                else:
                    return $this->horaireJeuPM * 60;
                endif;
                break;
            case 5:
                if ($moment == 3):
                    return $this->horaireVen * 60;
                elseif ($moment == 1):
                    return $this->horaireVenAM * 60;
                else:
                    return $this->horaireVenPM * 60;
                endif;
                break;
            case 6:
                if ($moment == 3):
                    return $this->horaireSam * 60;
                elseif ($moment == 1):
                    return $this->horaireSamAM * 60;
                else:
                    return $this->horaireSamPM * 60;
                endif;
                break;
        endswitch;
    }

    function getHoraireId() {
        return $this->horaireId;
    }

    function getHoraireEtablissementId() {
        return $this->horaireEtablissementId;
    }

    function getHoraireNom() {
        return $this->horaireNom;
    }

    function getHoraireLun1() {
        return $this->horaireLun1;
    }

    function getHoraireLun2() {
        return $this->horaireLun2;
    }

    function getHoraireLun3() {
        return $this->horaireLun3;
    }

    function getHoraireLun4() {
        return $this->horaireLun4;
    }

    function getHoraireLunAM() {
        return $this->horaireLunAM;
    }

    function getHoraireLunPM() {
        return $this->horaireLunPM;
    }

    function getHoraireLun() {
        return $this->horaireLun;
    }

    function getHoraireMar1() {
        return $this->horaireMar1;
    }

    function getHoraireMar2() {
        return $this->horaireMar2;
    }

    function getHoraireMar3() {
        return $this->horaireMar3;
    }

    function getHoraireMar4() {
        return $this->horaireMar4;
    }

    function getHoraireMarAM() {
        return $this->horaireMarAM;
    }

    function getHoraireMarPM() {
        return $this->horaireMarPM;
    }

    function getHoraireMar() {
        return $this->horaireMar;
    }

    function getHoraireMer1() {
        return $this->horaireMer1;
    }

    function getHoraireMer2() {
        return $this->horaireMer2;
    }

    function getHoraireMer3() {
        return $this->horaireMer3;
    }

    function getHoraireMer4() {
        return $this->horaireMer4;
    }

    function getHoraireMerAM() {
        return $this->horaireMerAM;
    }

    function getHoraireMerPM() {
        return $this->horaireMerPM;
    }

    function getHoraireMer() {
        return $this->horaireMer;
    }

    function getHoraireJeu1() {
        return $this->horaireJeu1;
    }

    function getHoraireJeu2() {
        return $this->horaireJeu2;
    }

    function getHoraireJeu3() {
        return $this->horaireJeu3;
    }

    function getHoraireJeu4() {
        return $this->horaireJeu4;
    }

    function getHoraireJeuAM() {
        return $this->horaireJeuAM;
    }

    function getHoraireJeuPM() {
        return $this->horaireJeuPM;
    }

    function getHoraireJeu() {
        return $this->horaireJeu;
    }

    function getHoraireVen1() {
        return $this->horaireVen1;
    }

    function getHoraireVen2() {
        return $this->horaireVen2;
    }

    function getHoraireVen3() {
        return $this->horaireVen3;
    }

    function getHoraireVen4() {
        return $this->horaireVen4;
    }

    function getHoraireVenAM() {
        return $this->horaireVenAM;
    }

    function getHoraireVenPM() {
        return $this->horaireVenPM;
    }

    function getHoraireVen() {
        return $this->horaireVen;
    }

    function getHoraireSam1() {
        return $this->horaireSam1;
    }

    function getHoraireSam2() {
        return $this->horaireSam2;
    }

    function getHoraireSam3() {
        return $this->horaireSam3;
    }

    function getHoraireSam4() {
        return $this->horaireSam4;
    }

    function getHoraireSamAM() {
        return $this->horaireSamAM;
    }

    function getHoraireSamPM() {
        return $this->horaireSamPM;
    }

    function getHoraireSam() {
        return $this->horaireSam;
    }

    function getHoraireDim1() {
        return $this->horaireDim1;
    }

    function getHoraireDim2() {
        return $this->horaireDim2;
    }

    function getHoraireDim3() {
        return $this->horaireDim3;
    }

    function getHoraireDim4() {
        return $this->horaireDim4;
    }

    function getHoraireDimAM() {
        return $this->horaireDimAM;
    }

    function getHoraireDimPM() {
        return $this->horaireDimPM;
    }

    function getHoraireDim() {
        return $this->horaireDim;
    }

    function getHoraireTotal() {
        return $this->horaireTotal;
    }

    function setHoraireId($horaireId) {
        $this->horaireId = $horaireId;
    }

    function setHoraireEtablissementId($horaireEtablissementId) {
        $this->horaireEtablissementId = $horaireEtablissementId;
    }

    function setHoraireNom($horaireNom) {
        $this->horaireNom = $horaireNom;
    }

    function setHoraireLun1($horaireLun1) {
        $this->horaireLun1 = $horaireLun1;
    }

    function setHoraireLun2($horaireLun2) {
        $this->horaireLun2 = $horaireLun2;
    }

    function setHoraireLun3($horaireLun3) {
        $this->horaireLun3 = $horaireLun3;
    }

    function setHoraireLun4($horaireLun4) {
        $this->horaireLun4 = $horaireLun4;
    }

    function setHoraireLunAM($horaireLunAM) {
        $this->horaireLunAM = $horaireLunAM;
    }

    function setHoraireLunPM($horaireLunPM) {
        $this->horaireLunPM = $horaireLunPM;
    }

    function setHoraireLun($horaireLun) {
        $this->horaireLun = $horaireLun;
    }

    function setHoraireMar1($horaireMar1) {
        $this->horaireMar1 = $horaireMar1;
    }

    function setHoraireMar2($horaireMar2) {
        $this->horaireMar2 = $horaireMar2;
    }

    function setHoraireMar3($horaireMar3) {
        $this->horaireMar3 = $horaireMar3;
    }

    function setHoraireMar4($horaireMar4) {
        $this->horaireMar4 = $horaireMar4;
    }

    function setHoraireMarAM($horaireMarAM) {
        $this->horaireMarAM = $horaireMarAM;
    }

    function setHoraireMarPM($horaireMarPM) {
        $this->horaireMarPM = $horaireMarPM;
    }

    function setHoraireMar($horaireMar) {
        $this->horaireMar = $horaireMar;
    }

    function setHoraireMer1($horaireMer1) {
        $this->horaireMer1 = $horaireMer1;
    }

    function setHoraireMer2($horaireMer2) {
        $this->horaireMer2 = $horaireMer2;
    }

    function setHoraireMer3($horaireMer3) {
        $this->horaireMer3 = $horaireMer3;
    }

    function setHoraireMer4($horaireMer4) {
        $this->horaireMer4 = $horaireMer4;
    }

    function setHoraireMerAM($horaireMerAM) {
        $this->horaireMerAM = $horaireMerAM;
    }

    function setHoraireMerPM($horaireMerPM) {
        $this->horaireMerPM = $horaireMerPM;
    }

    function setHoraireMer($horaireMer) {
        $this->horaireMer = $horaireMer;
    }

    function setHoraireJeu1($horaireJeu1) {
        $this->horaireJeu1 = $horaireJeu1;
    }

    function setHoraireJeu2($horaireJeu2) {
        $this->horaireJeu2 = $horaireJeu2;
    }

    function setHoraireJeu3($horaireJeu3) {
        $this->horaireJeu3 = $horaireJeu3;
    }

    function setHoraireJeu4($horaireJeu4) {
        $this->horaireJeu4 = $horaireJeu4;
    }

    function setHoraireJeuAM($horaireJeuAM) {
        $this->horaireJeuAM = $horaireJeuAM;
    }

    function setHoraireJeuPM($horaireJeuPM) {
        $this->horaireJeuPM = $horaireJeuPM;
    }

    function setHoraireJeu($horaireJeu) {
        $this->horaireJeu = $horaireJeu;
    }

    function setHoraireVen1($horaireVen1) {
        $this->horaireVen1 = $horaireVen1;
    }

    function setHoraireVen2($horaireVen2) {
        $this->horaireVen2 = $horaireVen2;
    }

    function setHoraireVen3($horaireVen3) {
        $this->horaireVen3 = $horaireVen3;
    }

    function setHoraireVen4($horaireVen4) {
        $this->horaireVen4 = $horaireVen4;
    }

    function setHoraireVenAM($horaireVenAM) {
        $this->horaireVenAM = $horaireVenAM;
    }

    function setHoraireVenPM($horaireVenPM) {
        $this->horaireVenPM = $horaireVenPM;
    }

    function setHoraireVen($horaireVen) {
        $this->horaireVen = $horaireVen;
    }

    function setHoraireSam1($horaireSam1) {
        $this->horaireSam1 = $horaireSam1;
    }

    function setHoraireSam2($horaireSam2) {
        $this->horaireSam2 = $horaireSam2;
    }

    function setHoraireSam3($horaireSam3) {
        $this->horaireSam3 = $horaireSam3;
    }

    function setHoraireSam4($horaireSam4) {
        $this->horaireSam4 = $horaireSam4;
    }

    function setHoraireSamAM($horaireSamAM) {
        $this->horaireSamAM = $horaireSamAM;
    }

    function setHoraireSamPM($horaireSamPM) {
        $this->horaireSamPM = $horaireSamPM;
    }

    function setHoraireSam($horaireSam) {
        $this->horaireSam = $horaireSam;
    }

    function setHoraireDim1($horaireDim1) {
        $this->horaireDim1 = $horaireDim1;
    }

    function setHoraireDim2($horaireDim2) {
        $this->horaireDim2 = $horaireDim2;
    }

    function setHoraireDim3($horaireDim3) {
        $this->horaireDim3 = $horaireDim3;
    }

    function setHoraireDim4($horaireDim4) {
        $this->horaireDim4 = $horaireDim4;
    }

    function setHoraireDimAM($horaireDimAM) {
        $this->horaireDimAM = $horaireDimAM;
    }

    function setHoraireDimPM($horaireDimPM) {
        $this->horaireDimPM = $horaireDimPM;
    }

    function setHoraireDim($horaireDim) {
        $this->horaireDim = $horaireDim;
    }

    function setHoraireTotal($horaireTotal) {
        $this->horaireTotal = $horaireTotal;
    }

    function getHoraireOriginId() {
        return $this->horaireOriginId;
    }

    function setHoraireOriginId($horaireOriginId) {
        $this->horaireOriginId = $horaireOriginId;
    }

}
