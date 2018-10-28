<!DOCTYPE html>
<html lang="fr">
  <head>
    
    <!-- Le styles -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

  </head>

  <body>
  
  <?php
$periode = explode('-',$this->uri->segment(4));
?>
      
      <div class="row">
          <div class="col-lg-12" align="center">
              <h2>Relevé d'heures</h2>
              <p>
              Salarié : <strong><?php echo $personnel->nom.' '.$personnel->prenom; ?></strong><br/>
              Période : <strong><?php echo $this->organibat->get_mois($periode[0]).' '.$periode[1]; ?></strong>
              </p>
          </div>    
      </div>      
<div class="row" style="margin-top:15px;">

    <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-12" style="background-color: #FFF; border-radius:15px; padding:15px; border:1px solid #000; width:90%; margin-left:15px;">
        
        
        <table class="table table-condensed">
        <?php
        $semaine = null;
        
        $total_mois = 0;
        foreach ($heures as $h):
          
            if($semaine != date('W',$h->date)):
                if($semaine): ?>
                    <TR><TD align="right"><strong>Total </strong></TD><TD style="text-align: right; font-weight: bold;"><?php echo $total_hebdo; ?></TD><TD style="text-align: left; font-weight: bold;">Heures</TD></TR>
                    <TR height="15"><TD colspan="3">-</TD></TR>
                <?php
                endif;
                $total_hebdo = 0;
                $semaine = date('W',$h->date); ?>
                    <TR bgcolor="#5bc0de"><TD colspan="3"><font color="#FFF"><strong>Semaine <?php echo $semaine; ?></strong></font></TD></TR>
            <?php    
            endif;
            
            $total_mois += $h->nb_heure;
            $total_hebdo += $h->nb_heure;
            ?>
            <TR>
                <TD><?php echo $this->organibat->get_jour(date('l',$h->date)).' '.date('d',$h->date).' '.$this->organibat->get_mois(date('F',$h->date)).' '.date('Y',$h->date); ?></TD>
                <TD style="text-align: right;"><?php echo $h->nb_heure; ?></TD>
                <TD style="text-align: right;">
                    <a href="<?php echo site_url('chantier/worksite/'.$h->chantier); ?>"><?php echo $h->client; ?></a>
                </TD>
            </TR>
            
            
        <?php    
        endforeach;
        ?>
        <TR><TD align="right"><strong>Total </strong></TD><TD style="text-align: right; font-weight: bold;"><?php echo $total_hebdo; ?></TD><TD style="text-align: left; font-weight: bold;">Heures</TD></TR>    
        <TR style="background-color: #5cb85c; color:#FFF; font-weight: bold;">
            <TD align="right"><font color="#FFF">Total mensuel </font></TD>
            <TD style="text-align: right; font-weight: bold;"><font color="#FFF"><?php echo $total_mois; ?></font></TD>
            <TD style="text-align: left; font-weight: bold;">
                <font color="#FFF">Heures</font>
            </TD>
        </TR>
        </table>
    </div>


</div>    

  
  
  
  
  
  </body>
</html>