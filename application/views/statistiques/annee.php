<div class="row" style="margin-bottom:5px;">
    <div class="col-8 offset-2 col-md-4 offset-md-4 col-lg-2 offset-lg-5 text-center" id="statsAnneeSelector">
        <select class="selectpicker" id="changeAnalyseAnnee" data-width="100%">
            <?php
            if ($this->session->userdata('moisFiscal') > 1 && date('m') < $this->session->userdata('moisFiscal')):
                $currentYear = date('Y') - 1;
            else:
                $currentYear = date('Y');
            endif;
            for ($i = $currentYear; $i >= '2013'; $i--):
                echo '<option value="' . $i . '"' . ($this->session->userdata('analyseAnnee') == $i ? 'selected' : '') . '>' . ($this->session->userdata('moisFiscal') == 1 ? $i : $i . ' - ' . ($i + 1)) . '</option>';
            endfor;
            ?>
        </select>
        <?php if ($this->uri->segment('2') == 'performanceChantiersCategories'): ?>
            <select class="selectpicker" id="changeAnalyseChantiersCategorieId" data-width="100%" data-live-search="true">
                <?php
                echo '<option value="0" data-content="<span style=\'font-size:14px;\'>-</span>"' . (!$this->uri->segment('3') ? 'selected' : '') . '>' . '-' . '</option>';
                if (!empty($categories)):
                    foreach ($categories as $cat):
                        echo '<option value="' . $cat->getCategorieId() . '" data-content="<span style=\'font-size:14px;\'>' . $cat->getCategorieNom() . '</span>"' . ($this->uri->segment('3') == $cat->getCategorieId() ? 'selected' : '') . '>' . $cat->getCategorieNom() . '</option>';
                    endforeach;
                endif;
                ?>
            </select>
            <?php
        endif;
        ?>
    </div>
</div>