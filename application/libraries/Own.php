<?php

class Own {

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

}
