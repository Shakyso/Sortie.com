console.log('je suis le js');


// si le bouton rechercher est cliquer
$('#rechercher').on('click', function() {


// recupérer les données du forumalire


// faire la requete en base
    var request = $.ajax({
        url : path,
        type : 'POST',
        data : {'site' : site,
                'motCle' : motCle,
                'organisateur' : organisateur,
                'pasInscrite' : pasInscrite,
                'inscrite' : inscrite,
                'passees' : passees},
        success : function (res){

            // actualiser la liste l'écran


        } // fermeture sucess
   }); //fermeture AJAX
});  // fonction principale


