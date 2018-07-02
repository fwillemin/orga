<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;

// Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');
        $this->load->library('MYPDF');

        $this->parametres = $this->db->select('*')->from('parametres')->get()->result();
//        $this->piedPage1 = $this->parametres[0]->valeur . ', ' . $this->parametres[1]->valeur . ', ' . $this->parametres[3]->valeur . ' ' . $this->parametres[4]->valeur . ' - Tel: ' . $this->parametres[5]->valeur . ' - Email: ' . $this->parametres[6]->valeur . ' - APE: ' . $this->parametres[8]->valeur;
//        $this->piedPage2 = '<br>Siret: ' . $this->parametres[7]->valeur . ' - APE: ' . $this->parametres[8]->valeur . ' - N° TVA Intracommunautaire : ' . $this->parametres[9]->valeur;
        $this->piedPage1 = '';
        $this->piedPage2 = '';
    }

    private function editionAdresseClient(Client $client, $type = 'postal') {

        $adresse = '';
        if ($type == 'postal'):
            $adresse .= '<br><br><br><br><br>';
            $espaceDroite = '<span style="color: #FFF;">____</span>';
        else:
            $espaceDroite = '<span style="color: #FFF;">__</span>';
        endif;

        $adresse .= $client->getClientRaisonSociale() . $espaceDroite
                . '<br>' . wordwrap($client->getClientAdresse1(), 30, $espaceDroite . '<br>') . $espaceDroite;

        if ($client->getClientAdresse2()):
            $adresse .= '<br>' . wordwrap($client->getClientAdresse2(), 30, $espaceDroite . '<br>') . $espaceDroite;
        endif;
        $adresse .= '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . $espaceDroite
                . '<br>' . $client->getClientPays() . $espaceDroite;
        return $adresse;
    }

    public function editionDevis($affaireId = null) {

        if (!$affaireId):
            redirect('ventes/noway');
            exit;
        endif;

        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydrateClients();
        foreach ($affaire->getAffaireClients() as $c):
            if ($c->getClientPrincipal() == 1):
                $client = $c;
                continue;
            endif;
        endforeach;
        $articles = $this->managerAffaireArticles->liste(array('affaireArticleAffaireId' => $affaire->getAffaireId()));

        /* --- Génération du HEADER ---- */
        $header = '<table>
    <tr style="font-size:12px;">
        <td style="text-align: right; width:283px; ">
        </td>
        <td style="text-align: right; width:300px; ">

            <table style="width:270px;" cellspacing="0" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px; border: 1px solid black;">
                        DEVIS
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px; border: 1px solid black;">N° devis</td>
                    <td style="width: 90px; border: 1px solid black;">Date</td>
                    <td style="width: 90px; border: 1px solid black;">Code Affaire</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px; border: 1px solid black;">' . $affaire->getAffaireDevisId() . '</td>
                    <td style=" height: 20px; border: 1px solid black;">' . date('d/m/Y', $affaire->getAffaireDevisDate()) . '</td>
                    <td style=" height: 20px; border: 1px solid black;">' . $affaire->getAffaireId() . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-size:12px;">'
                . $this->editionAdresseClient($client)
                . '</td>
                </tr>
            </table>

        </td>
    </tr>
</table>';

        $data = array(
            'affaire' => $affaire,
            'client' => $client,
            'articles' => $articles,
            'title' => 'Devis',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

// Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

// create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Devis ');
        $pdf->SetSubject('Devis ');

        $pdf->SetMargins(13, 79, 5);
// set auto page breaks
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage('', '', FALSE, FALSE, $header);

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Devis' . $affaire->getAffaireDevisId() . '.pdf', 'FI');
    }

    public function editionFicheAtelier($affaireId = null) {

        if (!$affaireId):
            redirect('ventes/noway');
            exit;
        endif;

        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydrateClients();
        foreach ($affaire->getAffaireClients() as $c):
            if ($c->getClientPrincipal() == 1):
                $client = $c;
                continue;
            endif;
        endforeach;
        $articles = $this->managerAffaireArticles->liste(array('affaireArticleAffaireId' => $affaire->getAffaireId()));
        if (!empty($articles)):
            foreach ($articles as $article):
                $article->hydrateAffaireOptions();
                if (!empty($article->getAffaireArticleOptions())):
                    foreach ($article->getAffaireArticleOptions() as $option):
                        $option->hydrateComposantEtOption();
                    endforeach;
                endif;
            endforeach;
        endif;

        /* --- Génération du HEADER ---- */
        $header = '<table>
        <tr style="font-size:12px;">
        <td style="text-align: center; width:180px;"><img src="assets/img/logoNB.png" style="width: 150px; height: 50px;">
        </td>

        <td style = "text-align: right; width:240px;">
        <table style = "width:240px;" cellspacing = "0" cellpadding = "2">
        <tr>
        <td colspan = "3" style = "text-align: center; font-weight: bold; height: 20px; font-size:15px; border: 1px solid black;">
        FICHE ATELIER
        </td>
        </tr>
        <tr style = "background-color: lightgrey; text-align: center; font-weight: bold;">
        <td style = "width: 120px; border: 1px solid black;">Date</td>
        <td style = "width: 120; border: 1px solid black;">Code Affaire</td>
        </tr>
        <tr style = "text-align: center;">
        <td style = " height: 20px; border: 1px solid black;">' . date('d/m/Y', $affaire->getAffaireCommandeDate()) . '</td>
        <td style = " height: 20px; border: 1px solid black;">' . $affaire->getAffaireId() . '</td>
        </tr>
        </table>
        </td>

        <td style = "text-align: right; width:260px;">' . $this->editionAdresseClient($client, 'court') . '</td>
        <td style = "text-align: right; width:130px;">' . ($affaire->getAffaireCommandeCertifiee() ? '<img src="assets/img/certifieNB.jpg" style="height: 100px;">' : '') . '</td>
        </tr>
        </table>';

        $data = array(
            'affaire' => $affaire,
            'client' => $client,
            'articles' => $articles,
            'title' => 'Fiche atelier',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

// Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

// create new PDF document
        $pdf = new MYPDF('LANDSCAPE', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Atelier ');
        $pdf->SetSubject('Atelier ');

        $pdf->SetMargins(7, 48, 5);
// set auto page breaks
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage('', '', FALSE, FALSE, $header);

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Atelier' . $affaire->getAffaireCommandeId() . '.pdf', 'FI');
    }

    public function editionFacture($factureId = null) {

        if (!$factureId || !$this->existFacture($factureId)):
            redirect('ventes/noway');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $facture->hydrateLignes();

        $client = $facture->getFactureClient();
        $header = '<table>
        <tr style = "font-size:12px;">
        <td style = "text-align: right; width:283px; ">
        </td>
        <td style = "text-align: right; width:300px; ">

        <table style = "width:270px;" cellspacing = "0" cellpadding = "2">
        <tr>
        <td colspan = "3" style = "text-align: center; font-weight: bold; height: 20px; font-size:15px; border: 1px solid black;">
        FACTURE N°' . $facture->getFactureId() .
                '</td>
        </tr>
        <tr style = "background-color: lightgrey; text-align: center; font-weight: bold;">
        <td style = "width: 90px; border: 1px solid black;">N° Facture</td>
        <td style = "width: 90px; border: 1px solid black;">Date</td>
        <td style = "width: 90px; border: 1px solid black;">Réglement</td>
        </tr>
        <tr style = "text-align: center;">
        <td style = " height: 20px; border: 1px solid black;">' . $facture->getFactureId() . '</td>
        <td style = " height: 20px; border: 1px solid black;">' . date('d/m/Y', $facture->getFactureDate()) . '</td>
        <td style = " height: 20px; border: 1px solid black;">' . $facture->getFactureModeReglementText() . '</td>
        </tr>
        <tr>
        <td colspan = "3" style = "text-align: right; font-size:12px;"> ' . $this->editionAdresseClient($client) . '</td>
        </tr>
        </table>
        </td>
        </tr>
        </table>';

        $data = array(
            'facture' => $facture,
            'title' => 'Facture ' . $facture->getFactureId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

// Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

// create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Facture ' . $facture->getFactureId());
        $pdf->SetSubject('Facture ' . $facture->getFactureId());

        $pdf->SetMargins(13, 79, 5);
// set auto page breaks
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage('', '', FALSE, FALSE, $header);

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Facture' . $facture->getFactureId() . '.pdf', 'FI');
    }

    public function editionAvoir($avoirId = null) {

        if (!$avoirId || !$this->existAvoir($avoirId)):
            redirect('ventes/noway');
            exit;
        endif;
        $avoir = $this->managerAvoirs->getAvoirById($avoirId);
        $avoir->hydrateClient();
        $client = $avoir->getAvoirClient();
        $header = '<table>
        <tr style = "font-size:12px;">
        <td style = "text-align: right; width:283px; ">
        </td>
        <td style = "text-align: right; width:300px; ">

        <table style = "width:270px;" cellspacing = "0" cellpadding = "2">
        <tr>
        <td colspan = "3" style = "text-align: center; font-weight: bold; height: 20px; font-size:15px; border: 1px solid black;">
        AVOIR N°' . $avoir->getAvoirId() . '
        </td>
        </tr>
        <tr style = "background-color: lightgrey; text-align: center; font-weight: bold;">
        <td style = "width: 90px; border: 1px solid black;">N° Avoir</td>
        <td style = "width: 90px; border: 1px solid black;">Date</td>
        <td style = "width: 90px; border: 1px solid black;">Facture liée</td>
        </tr>
        <tr style = "text-align: center;">
        <td style = " height: 20px; border: 1px solid black;">' . $avoir->getAvoirId() . '</td>
        <td style = " height: 20px; border: 1px solid black;">' . date('d/m/Y', $avoir->getAvoirDate()) . '</td>
        <td style = " height: 20px; border: 1px solid black;">' . $avoir->getAvoirFactureId() . '</td>
        </tr>
        <tr>
        <td colspan = "3" style = "text-align: right; font-size:12px;"> ' . $this->editionAdresseClient($client) . '</td>
        </tr>
        </table>
        </td></tr></table>';

        $data = array(
            'avoir' => $avoir,
            'title' => 'Avoir ' . $avoir->getAvoirId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

// Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

// create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->parametres[0]->valeur);
        $pdf->SetTitle('Avoir' . $avoir->getAvoirId());
        $pdf->SetSubject('Avoir' . $avoir->getAvoirId());

        $pdf->SetMargins(13, 79, 5);
// set auto page breaks
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage('', '', FALSE, FALSE, $header);

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Avoir' . $avoir->getAvoirId() . '.pdf', 'FI');
    }

}
