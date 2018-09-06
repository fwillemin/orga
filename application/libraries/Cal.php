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

    public function dernierJourSemaine($debut, $nbSemainesApres = 0) {
        $jour = $debut + ($nbSemainesApres * 7 * 86400);
        return $jour + (7 - mdate('%N', $jour)) * 86400;
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

}
