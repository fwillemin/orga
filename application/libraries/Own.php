<?php

class Own {

    function enleverCaracteresSpeciaux($text) {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '//' => '', // conversion d'un tiret UTF-8 en un tiret simple
            '/[\']/u' => '', // guillemet simple
            '/[«»]/u' => '', // guillemet double
            '/ /' => '' // espace insécable (équiv. à 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    public function mktimeFromInputDate($input = null) {
        date_default_timezone_set('Europe/Paris');
        if ($input == '' || !$input || $input == 0): return 0;
        else:
            $temp = explode('-', $input);
            return mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
        endif;
    }

    function getCouleurSecondaire($color, $bright) {
        $color = substr($color, -6);
        $bgr = explode('x', wordwrap($color, 2, 'x', 3));
        if (!isset($bgr[2])):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' Erreur avec la couleur : ' . $color);
            $bgr = array('FF', 'FF', 'FF');
        endif;

        $color = '';

        for ($i = 0; $i <= 2; $i++) {
            $bgr[$i] = hexdec($bgr[$i]);
        }
        $contraste = $bgr[0] + $bgr[1] + $bgr[2];
        if ($contraste > 400): $bright = -1 * $bright;
        endif;

        for ($i = 0; $i <= 2; $i++):
            $bgr[$i] = $bgr[$i] + $bright;
            if ($bgr[$i] < 0): $bgr[$i] = 0;
            endif;
            if ($bgr[$i] > 255): $bgr[$i] = 255;
            endif;
            $color .= StrToUpper(substr('0' . dechex($bgr[$i]), -2));
        endfor;
        return '#' . $color;
    }

    /* Convert hexdec color string to rgb(a) string */

    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) == 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    public function nbCasesAffectation(Affectation $affectation) {

        $nbCases = (($affectation->getAffectationFinDate() - $affectation->getAffectationDebutDate()) / 43200) + 2;
        if ($affectation->getAffectationDebutMoment() == 2):
            $nbCases--;
        endif;
        if ($affectation->getAffectationFinMoment() == 1):
            $nbCases--;
        endif;
        return $nbCases;
    }

    public function nbHeuresAffectation(Affectation $affectation) {
        $CI = & get_instance();
        $horaire = $affectation->getAffectationPersonnel()->getPersonnelHoraire();
        $nbHeures = 0;

        for ($i = $affectation->getAffectationDebutDate(); $i <= $affectation->getAffectationFinDate(); $i += 86400):

            $jour = trim($CI->cal->dateFrancais($i, 'j'));
            // Premier jour
            if ($i == $affectation->getAffectationDebutDate() && date('N', $i) < 6):
                if ($affectation->getAffectationDebutMoment() == 1):
                    /* On ajoute les heures complètes du jour dans l'horaire */
                    $nbHeures += ($horaire ? $horaire->{'getHoraire' . $jour}() : 7);
                else:
                    /* On ajoute les heures de l'aprem dans l'horaire */
                    $nbHeures += ($horaire ? $horaire->{'getHoraire' . $jour . 'PM'}() : 3.5);
                endif;

            endif;

            // Dernier jour
            if ($i == $affectation->getAffectationFinDate() && $affectation->getAffectationFinDate() != $affectation->getAffectationDebutDate() && date('N', $i) < 6):
                if ($affectation->getAffectationFinMoment() == 2):
                    /* On ajoute les heures complètes du jour dans l'horaire */
                    $nbHeures += ($horaire ? $horaire->{'getHoraire' . $jour}() : 7);
                else:
                    /* On ajoute les heures de l'aprem dans l'horaire */
                    $nbHeures += ($horaire ? $horaire->{'getHoraire' . $jour . 'AM'}() : 3.5);
                endif;
            elseif ($i == $affectation->getAffectationFinDate() && $affectation->getAffectationFinDate() == $affectation->getAffectationDebutDate() && $affectation->getAffectationFinMoment() == 1):
                $nbHeures -= ($horaire ? $horaire->{'getHoraire' . $jour . 'PM'}() : 3.5);
            endif;

            // Autres jours
            if ($i != $affectation->getAffectationDebutDate() && $i != $affectation->getAffectationFinDate() && date('N', $i) < 6):
                /* On ajoute les heures complètes du jour dans l'horaire */
                $nbHeures += ($horaire ? $horaire->{'getHoraire' . $jour}() : 7);
            endif;

        endfor;
        return $nbHeures;
    }

    public function finAffectationWithNbHeures(Horaire $horaire = null, $debutDate, $debutMoment, $nbHeures) {

        $CI = & get_instance();
        $compteur = $nbHeures; /* Compteur decroissant des heures à planifier */
        $jourEnCours = $debutDate - 86400;

        while ($compteur > 0):
            $jourEnCours += 86400;
            $jour = trim($CI->cal->dateFrancais($jourEnCours, 'j'));
            if (date('N', $jourEnCours) < 6):

                if ($jourEnCours == $debutDate && $debutMoment == 2):
                    $compteur -= ($horaire ? $horaire->{'getHoraire' . $jour . 'PM'}() : 3.5);
                else:
                    $dureeMatin = ($horaire ? $horaire->{'getHoraire' . $jour . 'AM'}() : 3.5);
                    if ($dureeMatin >= $compteur):
                        $compteur -= ($horaire ? $horaire->{'getHoraire' . $jour . 'AM'}() : 3.5);
                        $finMoment = 1;
                    else:
                        /* On ajoute les heures complètes du jour dans l'horaire */
                        $compteur -= ($horaire ? $horaire->{'getHoraire' . $jour}() : 7);
                        $finMoment = 2;
                    endif;
                endif;

            endif;
        endwhile;
        $finDate = $jourEnCours;
        return ['finDate' => $finDate, 'finMoment' => $finMoment];
    }

    public function normalizeData($valeur, $liste = array()) {
        $valMax = max($liste);
        $valMin = min($liste);
        $newValeur = $valeur == 0 ? 0 : round((($valeur - $valMin) / ($valMax - $valMin)), 2) * 100;
        return $newValeur;
    }

}
