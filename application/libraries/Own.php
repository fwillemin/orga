<?php

class Own {

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

    public function mktimeFromInputDate($input = null) {
        date_default_timezone_set('Europe/Paris');
        if ($input == '' || !$input || $input == 0): return 0;
        else:
            $temp = explode('-', $input);
            return mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
        endif;
    }

}
