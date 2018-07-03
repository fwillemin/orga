<?php

class Own {

    public function dateFrancais($date) {
        $CI = & get_instance();
        return $CI->lang->line('cal_' . strtolower(date('l', $date))) . ' ' . date('d', $date) . ' ' . $CI->lang->line('cal_' . strtolower(date('F', $date))) . ' ' . date('Y', $date);
    }

}
