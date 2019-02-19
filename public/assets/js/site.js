$(function(){

    $('.button_modifier').each(function(){
        //récupère ID
        var idSite = $(this).attr('id');
        console.log("je récupère l'id de chaque bouton modifier=> ", idSite);
        console.log($(this));
        $('.button_modifier[id='+idSite+']').on('click', function() {
            var idSite = $(this).attr('id');
            // console.log('nom id => ', $('#liste-ville-' + idVille));
            console.log('nom id  premier clic=> ', idSite);

            var nomSite = $('#nom-' + idSite).text();
            console.log('nomVille  premier clic => ', nomSite);

            var newInputNom = document.createElement("input");
            newInputNom.type = "text";
            newInputNom.name = "nvSite";
            newInputNom.value = nomSite;
            newInputNom.id = "nvSite";
            var nom2 = $('#nomSite'+idSite);
            nom2.replaceWith(newInputNom);
            console.log('je suis dans la première partie de la requete js');

            //$($(this)).removeClass("button_modifier").addClass("buttonMAJ").text('mettre à jour');
            //$('.button_MAJ').innerHTML('Mettre à jour');
            $($(this)).hide();
            $('.buttonMAJ[id='+idSite+']').show();

        });//
    });



    $('.buttonMAJ').each(function(){
        //TODO récupére les données
        var idSite = $(this).attr('id');
        console.log("je récupère l'id de chaque bouton MAJ=> ", idSite);
        console.log($(this));

        $('.buttonMAJ[id='+idSite+']').on('click', function() {

            console.log('je suis au début de la 2ème partie de ma requete avant l"AJAX');
            // $('.button_modifier').on('click', function(){
            //récupérer les valeurs des inouts

            var nvSite=$('#nvSite').val();
            var idSite = $(this).attr('id');
                    console.log('nv nom modifié=>', nvSite);
                    console.log('id NON MODIFIE=>', idSite);

            //passage de l'url  recupe de l'url dans la div dans le create.html.twig
            var path =$("#url").attr("data-path");

            var request = $.ajax({
                url : path,
                type : 'POST',
                data : {'nvNomSite' : nvSite, 'idSite' : idSite},
                success : function (res){

                    var nvSite = JSON.parse(res);
                    console.log('ma nouvelle ville =>', nvSite);
                    //remplacer les input
                    var nomVilleSpan=document.createElement('span');
                    nomVilleSpan.id="nomSite";
                    nomVilleSpan.innerHTML=nvSite.nomSite;
                    var inputNom = $('#nvSite');

                    inputNom.replaceWith(nomVilleSpan);
                    $('.button_modifier[id='+idSite+']').show();
                    $('.buttonMAJ[id='+idSite+']').hide();
                } // fermeture sucess
            }); //fermeture AJAX
        });// on click
    }); // each bouton MAJ
});  // fonction principale