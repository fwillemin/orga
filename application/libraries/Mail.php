<?php

/**
 * Classe de généraliste du site
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
require 'vendor/autoload.php';

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class Mail {

    private function enteteEmail() {

        $code = '<!DOCTYPE HTML>'
                . '<html xmlns="http://www.w3.org/1999/xhtml">'
                . '<head>'
                . '<title>News Organibat</title>'
                . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
                . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
                . '</head>'
                . '<body style="margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; background-color: #FFF; ">'
                . '<div class="row">'
                . '<div style="width:100%; background-color: #293042;">'
                . '<img src="https://www.organibat.com/assets/img/logoClair.png" height="40" style="padding: 12px 8px 8px 8px;">'
                . '</div>'
                . '</div>';
        return $code;
    }

    private function footerEmail() {

        $code = '</body>'
                . '</html>';

        return $code;
    }

    public function sendMail($destinataireNom, $destinataireEmail, $messageTitre, $message) {

        $httpClient = new GuzzleAdapter(new Client(['base_uri' => 'https://foo.com/api/']));
        $sparky = new SparkPost($httpClient, ['key' => '951f602d2c1fc3208cab6c5a00dff80a9a480fb2']);

        $contents = [
            'content' => [
                'from' => [
                    'name' => 'François Organibat',
                    'email' => 'francois@organibat.com',
                ],
                'subject' => $messageTitre,
                'html' => $message,
                'text' => 'Pas de version texte de cet email',
            ],
            'recipients' => [
                [
                    'address' => [
                        'name' => $destinataireNom,
                        'email' => $destinataireEmail,
                    ]
                ]
            ]
        ];
        $promise = $sparky->transmissions->post($contents);

        try {
            $response = $promise->wait();
        } catch (\Exception $e) {
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . $e->getCode());
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . $e->getMessage());
        }
    }

    public function emailInscription(Utilisateur $user) {

        $message = $this->enteteEmail();

        $message .= '<div style="width:100%; font-size:14px;">'
                . '<h1>Votre compte est créé</h1>'
                . '<p>Bonjour ' . $user->getUserPrenom() . ',</p> '
                . '<p>Votre compte est activé sur Organibat.<br>Pour vous connecter, rendez-vous à l\'adresse <a href="https://www.organibat.com/acces-client">https://www.organibat.com/acces-client</a>'
                . '<br><br>Identifiant : <b>' . $user->getUsername() . '</b>'
                . '<br>Mot de passe : <b>Organibat2019</b></p>'
                . '<p>Vous pouvez, pendant 1 mois, accéder sans aucune restriciton à toutes les fonctionnalités de notre logiciel de planification et gestion de chantier.<br>'
                . 'Si vous souhaitez un accompagnement dans la prise en main, appellez-nous !</p>'
                . '<span style="font-size:24px font-weight:bold;">'
                . '06.51.73.18.08'
                . '</span>'
                . '<p>François WILLEMIN<br>Responsable développement<br>francois@organibat.com</p>'
                . '</div>';

        $message .= $this->footerEmail();

        $this->sendMail(($user->getUserPrenom() . ' ' . $user->getUserNom()), $user->getEmail(), 'Bienvenue chez Organibat', $message);
    }

    public function emailADMINInscription(Etablissement $etablissement, $help = null) {

        $message = $this->enteteEmail();

        $message .= '<div style="width:100%; font-size:14px;">'
                . '<h1>Nouveau compte Organibat créé</h1>'
                . '<p>Bonjour François,</p> '
                . '<p>'
                . 'Un nouveau compte de DEMO vient d\'être créé :'
                . '<br>' . $etablissement->getEtablissementNom()
                . '<br>' . $etablissement->getEtablissementCp() . ' ' . $etablissement->getEtablissementVille()
                . '<br>' . $etablissement->getEtablissementEmail()
                . '<br>' . $etablissement->getEtablissementTelephone()
                . '<br>' . $etablissement->getEtablissementContact()
                . '</p>'
                . '</div>';

        $message .= $this->footerEmail();

        if ($help):
            $titre = '[HELP] Nouveau compte de DEMO';
        else:
            $titre = 'Nouveau compte de DEMO';
        endif;

        $this->sendMail('Francois', 'willeminfrancois@gmail.com', $titre, $message);
    }

}
