<?php

class Maps {
    /* Clé Google Maps API */

    CONST key = "";

    public function __construct() {

    }

    public function geocode($adresse, Etablissement $etablissement = null) {

        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $adresse . "&key=" . self::key;
        $response = json_decode(file_get_contents($url));

        if ($response->status == 'OK') {
            //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($response, true));
            $latitude = isset($response->results[0]->geometry->location->lat) ? $response->results[0]->geometry->location->lat : "";
            $longitude = isset($response->results[0]->geometry->location->lng) ? $response->results[0]->geometry->location->lng : "";
            $adresseFormatee = isset($response->results[0]->formatted_address) ? $response->results[0]->formatted_address : "";
            $ville = '';
            foreach ($response->results[0]->address_components as $details):
                if ($details->types[0] == 'locality'):
                    $ville = $details->long_name;
                    continue;
                endif;
            endforeach;
            $placeGoogleId = isset($response->results[0]->place_id) ? $response->results[0]->place_id : "";

            if ($latitude && $longitude && $adresseFormatee):
                $CI = & get_instance();
                $distance = $this->distance(($etablissement ? $etablissement->getEtablissementGps() : $CI->session->userdata('etablissementGPS')), ($latitude . ',' . $longitude));

                return array(
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'adresse' => $adresseFormatee,
                    'ville' => $ville,
                    'placeGoogleId' => $placeGoogleId,
                    'distance' => $distance['distance'],
                    'duree' => $distance['duree']
                );
            else:
                return false;
            endif;
        }
    }

    public function distance($origine, $destination) {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=" . str_replace(' ', '', $origine) . "&destinations=" . $destination . "&key=" . self::key;
        $response = json_decode(file_get_contents($url));

        if ($response->status == 'OK') {

            $distance = isset($response->rows[0]->elements[0]->distance->value) ? $response->rows[0]->elements[0]->distance->value : "";
            $duree = isset($response->rows[0]->elements[0]->duration->value) ? $response->rows[0]->elements[0]->duration->value : "";

            if ($distance && $duree):
                return array('distance' => $distance, 'duree' => $duree);
            else:
                return false;
            endif;
        }
    }

    public function distanceVolOiseau($origineLat, $origineLon, $destinationLat, $destinationLon) {
        $earth_radius = 6378137;
        $rlo1 = deg2rad($origineLon);
        $rla1 = deg2rad($origineLat);
        $rlo2 = deg2rad($destinationLon);
        $rla2 = deg2rad($destinationLat);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earth_radius * $d, 0);
    }

}
