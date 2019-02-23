$(document).ready(function () {
    
    $('#changeAnalyseAnnee').on('change', function(){
        $.post(chemin+'statistiques/changeAnneeAnalyse', {annee:$(this).val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });
   
});

