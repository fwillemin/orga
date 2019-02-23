<div class="row" style="margin-bottom:5px;">
    <div class="col-8 offset-2 col-sm-4 offset-sm-4 col-md-2 offset-md-5 text-center" id="statsAnneeSelector">
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
    </div>
</div>