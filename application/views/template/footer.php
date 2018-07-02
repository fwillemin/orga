
<div class="modal fade" id="modalSession" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Données de session</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-condensed table-striped">
                    <?php
                    foreach ($this->session->userdata() as $key => $val):
                        if (is_array($val)):
                            echo '<tr><td>' . $key . '</td><td>' . nl2br(print_r($val, 1)) . '</td></tr>';
                        else:
                            echo '<tr><td>' . $key . '</td><td>' . $val . '</td></tr>';
                        endif;
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- fichiers js -->
<!--<script defer type="text/javascript" src="<?= base_url('assets/js/min.js'); ?>"></script>-->

<script defer type="text/javascript" src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?= base_url('assets/jqueryConfirm/jquery-confirm.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?= base_url('assets/js/toaster.js'); ?>"></script>
<!--<script defer type="text/javascript" src="<?= base_url('assets/js/fontawesome.js'); ?>"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

<?php if ($this->ion_auth->logged_in()): ?>
    <script defer type="text/javascript" src="<?= base_url('assets/MegaNavbarBS4/assets/js/MegaNavbarBS4.js'); ?>"></script>
    <!--<script defer type="text/javascript" src="<?= base_url('assets/bootstrap3.3.7/js/bootstrap-datepicker.min.js'); ?>"></script>
    <script defer type="text/javascript" src="<?= base_url('assets/bootstrap3.3.7/js/bootstrap-datepicker.fr.min.js'); ?>"></script>-->
    <script defer type="text/javascript" src="<?= base_url('assets/js/dataTables.js'); ?>"></script>
    <script defer type="text/javascript" src="<?= base_url('assets/js/bootstrap-select.min.js'); ?>"></script>
    <!--<script defer type="text/javascript" src="<?= base_url('assets/js/date.format.js'); ?>"></script>
    <script defer type="text/javascript" src="<?= base_url('assets/jquery-ui/jquery-ui.min.js'); ?>"></script>-->
<?php endif; ?>

<script defer type="text/javascript" src="<?= base_url('assets/js/organibat.js'); ?>"></script>
<script defer type="text/javascript" src="<?= base_url('assets/js/' . $this->uri->segment(1) . '.js'); ?>"></script>


</body>
</html>
