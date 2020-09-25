<?php

/**
 * Classe de généraliste du site
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
//require 'vendor/autoload.php';
//
//use SparkPost\SparkPost;
//use GuzzleHttp\Client;
//use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class Mail {

    const mailCommercial = 'contact@organibat.com';

    public function sendMailSMTP($destinataireNom, $destinataireEmail, $messageTitre, $message, $replyTo = self::mailCommercial) {
        $CI = & get_instance();
        $CI->email->from(self::mailCommercial, 'Organibat');
        $CI->email->to($destinataireEmail);
        $CI->email->reply_to($replyTo);

        $CI->email->subject($messageTitre);
        $CI->email->message($message);
        try {
            $CI->email->send();
        } catch (\Exception $e) {
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur dans l\'envoi d\'un mail');
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($e, true));
        }
    }

    public function enteteEmail(Utilisateur $user = null) {

        $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns=3D"http://www.w3.org/1999/xhtml" xmlns:v=3D"urn:=
schemas-microsoft-com:vml" xmlns:o=3D"urn:schemas-microsoft-com:office:offi=
ce">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" >
        <meta name="viewport" content="width:device-width; initial-scale=1.0; maximum-scale=1.0;">
        <link href="https://fonts.googleapis.com/css2?family=News+Cycle&display=swap" rel="stylesheet">
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

        <table bgcolor="#F3F4F7" width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>

                        <table align="center" width="590" bgcolor="#F3F4F7" border="0" cellpadding="1" cellspacing="0" style="text-align:center; color:#394249; font-family: \'News Cycle\', Helvetica, sans-serif; font-size:15px;">
                            <tbody>
                                <tr><td height="30" style="font-size:30px; line-height: 30px;">&nbsp;</td></tr>
                                <tr>
                                    <td>
                                        <a href="https://www.organibat.com">
                                            <img src="https://www.organibat.com/assets/img/logoClairTexte.png" width="254" alt="Logo Organibat">
                                        </a>
                                    </td>
                                </tr>
                                <tr><td height="30" style="font-size:30px; line-height: 30px;">&nbsp;</td></tr>
                                <tr>
                                    <td style="text-align: left; font-size:30px; line-height:20px;">
                                        Bonjour' . (isset($user) ? ' ' . $user->getUserPrenom() : '') . ',
                                    </td>
                                </tr>
                                <tr><td height="30" style="font-size:30px; line-height: 30px;">&nbsp;</td></tr>';
        return $code;
    }

    public function footerEmail() {

        $code = '<tr>
            <td style="text-align: left;">
            A très bientôt sur Organibat.
            <br>François
            </td>
            </tr>
            <tr><td height="30" style="font-size:30px; line-height: 30px;">&nbsp;</td></tr>
                                <tr>
                                    <td>
                                        <a href="https://www.organibat.com/acces-client" style="text-decoration:none; color:#394249;">
                                            <b> Logiciel </b>
                                        </a>
                                    </td>
                                </tr>
                                <tr><td height="5" style="font-size:5px; line-height: 5px;">&nbsp;</td></tr>
                                <tr>
                                    <td align="center" style="text-align:center;">
                                        <table align="center" width="100%" border="0" cellpadding="10" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <a href="https://www.facebook.com/organibat">
                                                            <img src="https://www.organibat.com/assets/img/facebook.png" height="30" width="30" alt="Logo Facebook">
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td height="5" style="font-size:5px; line-height: 5px;">&nbsp;</td></tr>
                                <tr>
                                    <td style="font-size:12px;">
                                        Organibat.com<br>2638 Rue Georges Ozaneaux, 59530 Villers Pol, France<br> +33 (0)6.51.73.18.08, contact@organibat.com
                                    </td>
                                </tr>
                                <tr><td height="5" style="font-size:5px; line-height: 5px;">&nbsp;</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </body>
</html>';

        return $code;
    }

    public function emailBienvenue($client) {

        /* Création du message */
        $message = $this->enteteEmail($client);

        $message .= '<tr>
            <td style="text-align: left;">
            Merci de votre inscription sur notre site qui est aussi le vôtre, ami récifaliste.
            <br>Vous trouverez sur notre site tout un eventail de produits et matériel de qualité pour votre aquarium marin.
            <br>Si vous ne trouvez pas un produit en particulier, contactez-nous afin de savoir si nous pourrions le référencer dans le futur.
            <br><br>Bonne visite
            </td>
            </tr>
            <tr><td height="10" style="font-size:10px; line-height: 10px;">&nbsp;</td></tr>';

        $message .= $this->footerEmail();
        $this->sendMailSMTP($client->getClientPrenom() . ' ' . $client->getClientNom(), $client->getClientMail(), 'Bienvenue chez Nourris-ton-recif', $message);
    }

    public function emailInscription(Utilisateur $user) {

        $message = $this->enteteEmail($user);

        $message .= '<tr><td style="text-align: left;">'
                . '<p>Votre compte est activé sur Organibat.<br>Pour vous connecter, rendez-vous à l\'adresse <a href="https://www.organibat.com/acces-client">https://www.organibat.com/acces-client</a>'
                . '<br><br>Identifiant : <b>' . $user->getUsername() . '</b>'
                . '<br>Mot de passe : <b>Organibat' . date('Y') . '</b></p>'
                . '<p>Vous pouvez, pendant 1 mois, accéder sans aucune restriciton à toutes les fonctionnalités de notre logiciel de planification et gestion de chantier.<br>'
                . 'Si vous souhaitez un accompagnement dans la prise en main, appellez-nous !</p>'
                . '<span style="font-size:24px font-weight:bold;">'
                . '06.51.73.18.08'
                . '</span>'
                . '<p>François WILLEMIN<br>Responsable développement<br>francois@organibat.com</p>'
                . '</td></tr><tr><td height="10" style="font-size:10px; line-height: 10px;">&nbsp;</td></tr>';

        $message .= $this->footerEmail();
        $this->sendMailSMTP($user->getUserPrenom() . ' ' . $user->getUserNom(), $user->getEmail(), 'Bienvenue chez Organibat', $message);
    }

    public function emailADMINInscription(Etablissement $etablissement, $help = null) {

        $message = $this->enteteEmail();

        $message .= '<tr><td style="text-align: left;">'
                . 'Un nouveau compte de DEMO vient d\'être créé :'
                . '<br>' . $etablissement->getEtablissementNom()
                . '<br>' . $etablissement->getEtablissementCp() . ' ' . $etablissement->getEtablissementVille()
                . '<br>' . $etablissement->getEtablissementEmail()
                . '<br>' . $etablissement->getEtablissementTelephone()
                . '<br>' . $etablissement->getEtablissementContact()
                . '</p>'
                . '</td></tr><tr><td height="10" style="font-size:10px; line-height: 10px;">&nbsp;</td></tr>';

        $message .= $this->footerEmail();

        if ($help):
            $titre = '[HELP] Nouveau compte de DEMO';
        else:
            $titre = 'Nouveau compte de DEMO';
        endif;
        $this->sendMailSMTP('Francois', 'willeminfrancois@gmail.com', $titre, $message);
    }

}
