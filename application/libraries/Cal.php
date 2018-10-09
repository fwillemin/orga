<?php

class Cal {

    /**
     * Donne le premier jour de la semaine (lundi) au format timestamp à 0:00 pour une date donnée
     * @param int $debut Timestamp de la date
     * @return int Timestamp du lundi de la semaine
     */
    public function premierJourSemaine($debut, $nbSemainesAvant = 0) {
        $jour = $debut - ($nbSemainesAvant * 7 * 86400);
        return $jour - (mdate('%N', $jour) - 1) * 86400;
    }

    public function dernierJourSemaine($debut) {
        //$jour = $debut + ($nbSemainesApres * 7 * 86400);
        return $debut + (7 - mdate('%N', $debut)) * 86400;
    }

    public function premierJourFromNumSemaine($semaine, $annee) {
        $timestamp = (mktime(0, 0, 0, 1, 1, $annee) - (date('N', mktime(0, 0, 0, 1, 1, $annee)) - 1) * 86400) + ($semaine - 1) * 604800;
        /* cas des années à 53 semaines (le 01/01 est un jeudi ou plus tard dans la semaine) */
        if (date('w', mktime(0, 0, 0, 1, 1, $annee)) > 4 || date('w', mktime(0, 0, 0, 1, 1, $annee)) == 0):
            $timestamp += 604800;
        endif;
        if (date('I', $timestamp) == 1):
            $timestamp -= 3600;
        endif;
        return $timestamp;
    }

    public function dateFrancais($date = null, $compo = 'JDMA') {
        $CI = & get_instance();
        $CI->lang->load('calendar_lang', 'french');

        if (!$date):
            return false;
        endif;

        $dateFR = '';
        $composition = str_split($compo);
        foreach ($composition as $c):
            switch ($c):
                case 'J':
                    $dateFR .= $CI->lang->line('cal_' . strtolower(date('l', $date))) . ' ';
                    break;
                case 'j':
                    $dateFR .= $CI->lang->line('cal_' . strtolower(date('D', $date))) . ' ';
                    break;
                case 'D':
                    $dateFR .= date('d', $date) . ' ';
                    break;
                case 'M':
                    $dateFR .= $CI->lang->line('cal_' . strtolower(date('F', $date))) . ' ';
                    break;
                case 'm':
                    $dateFR .= $CI->lang->line('cal_' . strtolower(date('M', $date))) . ' ';
                    break;
                case 'A':
                    $dateFR .= date('Y', $date) . ' ';
                    break;
                case 'a':
                    $dateFR .= date('y', $date) . ' ';
                    break;
            endswitch;
        endforeach;

        return $dateFR;
    }

    public function nbDemiEntreDates($debutDate, $debutMoment, $finDate, $finMoment) {
        if ($debutMoment == 2) {
            $nbDemiAffectation = 1;
        } else {
            $nbDemiAffectation = 2;
        }

        while ($debutDate < $finDate) {
            $debutDate += 86400;
            if (date('w', $debutDate) > 0 && date('w', $debutDate) < 6) {
                $nbDemiAffectation += 2;
            }
        }
        if ($finMoment == 1 && date('w', $debutDate) > 0 && date('w', $debutDate) < 6) {
            $nbDemiAffectation--;
        }
        return $nbDemiAffectation;
    }

    public function nbCasesEntreDates($debutDate, $debutMoment, $finDate, $finMoment) {
        if ($debutMoment == 2) {
            $nbCasesAffectation = 1;
        } else {
            $nbCasesAffectation = 2;
        }

        while ($debutDate < $finDate) {
            $debutDate += 86400;
            $nbCasesAffectation += 2;
        }
        if ($finMoment == 1 && date('w', $debutDate) > 0 && date('w', $debutDate) < 6) {
            $nbCasesAffectation--;
        }
        return $nbCasesAffectation;
    }

    public function decalageNbDemi(Affectation $affectation, $decalage) {

        /* On calcule la nouvelle date de début */
        if ($decalage > 0):
            $debutDate = $affectation->getAffectationDebutDate() + floor($decalage / 2) * 86400;
            if ($decalage % 2 != 0 && $affectation->getAffectationDebutMoment() == 2):
                $debutDate += 86400;
            endif;
        else:
            $debutDate = $affectation->getAffectationDebutDate() + ceil($decalage / 2) * 86400;
            if ($decalage % 2 != 0 && $affectation->getAffectationDebutMoment() == 1):
                $debutDate -= 86400;
            endif;
        endif;


        /* Puis le 1/2 de debut et de fin */
        if ($decalage % 2 != 0):
            $debutMoment = $affectation->getAffectationDebutMoment() == 1 ? 2 : 1;
            $finMoment = $affectation->getAffectationFinMoment() == 1 ? 2 : 1;
        else:
            $debutMoment = $affectation->getAffectationDebutMoment();
            $finMoment = $affectation->getAffectationFinMoment();
        endif;

        /* Puis la date de fin */
        $finDate = $this->calculeDateFinDemi($debutDate, $debutMoment, $affectation->getAffectationNbDemi());
        $cases = $this->nbCasesEntreDates($debutDate, $debutMoment, $finDate, $finMoment);

        return array(
            'debutDate' => $debutDate,
            'debutMoment' => $debutMoment,
            'finDate' => $finDate,
            'finMoment' => $finMoment,
            'cases' => $cases
        );
    }

    /* Recalcule la date de fin d'une affectation en fonction de ses informations de début et du nombre de 1/2 journées
     * en ajoutant les WE
     */

    public function calculeDateFinDemi($debutDate, $debutMoment, $nbDemi) {
        $finDate = $debutDate;
        $nbDemiRestant = $nbDemi;
        /* Fin du premier jour */
        if ($debutMoment == 1) {
            $nbDemiRestant -= 2;
        } else {
            $nbDemiRestant--;
        }
        while ($nbDemiRestant > 0) {
            $finDate += 86400;
            if (date('w', $finDate) > 0 && date('w', $finDate) < 6) {
                $nbDemiRestant -= 2;
            }
        }
        return $finDate;
    }

    public function calculeDateFinCases($debutDate, $debutMoment, $nbCases) {
        $finDate = $debutDate;
        $nbCasesRestantes = $nbCases;
        /* Fin du premier jour */
        if ($debutMoment == 1) {
            $nbCasesRestantes -= 2;
        } else {
            $nbCasesRestantes--;
        }
        while ($nbCasesRestantes > 0) {
            $finDate += 86400;
            $nbCasesRestantes -= 2;
        }
        return $finDate;
    }

}
