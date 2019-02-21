$(function(){

    $('.button_modifier').each(function(){
        //récupère ID
        var idVille = $(this).attr('id');
        console.log("je récupère l'id de chaque bouton modifier=> ", idVille);
        console.log($(this));
        $('.button_modifier[id='+idVille+']').on('click', function() {
            var idVille = $(this).attr('id');
            console.log('nom id => ', $('#liste-ville-' + idVille));
            console.log('nom id  premier clic=> ', idVille);
            var codePostal = $('#code-postal-' + idVille).text();
            console.log('codePostal  premier clic => ', codePostal);
            var nomVille = $('#nom-' + idVille).text();
            console.log('nomVille  premier clic => ', nomVille);

            var newInputCP = document.createElement("input");
            newInputCP.type = "text";
            newInputCP.name = "nvCodePostal";
            newInputCP.value = codePostal;
            newInputCP.id = "nvCodePostal";

            var newInputNom = document.createElement("input");
            newInputNom.type = "text";
            newInputNom.name = "nvNom";
            newInputNom.value = nomVille;
            newInputNom.id = "nvNom";


            var codePostal2 = $('#codeVille'+idVille);
            var nom2 = $('#nomVille'+idVille);
            codePostal2.replaceWith(newInputCP);
            nom2.replaceWith(newInputNom);


            console.log('je suis dans la première partie de la requete js');

            //$($(this)).removeClass("button_modifier").addClass("buttonMAJ").text('mettre à jour');
            //$('.button_MAJ').innerHTML('Mettre à jour');
            // $($(this)).hide();
            // var boutonMAJ = $('.buttonMAJ[id='+idVille+']');
            // console.log ('mon button MAJ=>', boutonMAJ);
            $('.button_modifier[id='+idVille+']').hide();
            $('.buttonMAJ[id='+idVille+']').show();

        });//
    });



    $('.buttonMAJ').each(function(){

        var idVille = $(this).attr('id');
        console.log("je récupère l'id de chaque bouton MAJ=> ", idVille);
       // console.log($(this));

        $('.buttonMAJ[id='+idVille+']').on('click', function() {

            console.log('je suis au début de la 2ème partie de ma requete avant l"AJAX');
            // $('.button_modifier').on('click', function(){
            //récupérer les valeurs des inouts
            var nvCP=$('#nvCodePostal').val();
            var nvnom=$('#nvNom').val();
            var idVille = $(this).attr('id');
//                    console.log('nv Code postal modifié=>', nvCP);
            //                  console.log('nv nom modifié=>', nvnom);
//                    console.log('id NON MODIFIE=>', idVille);

            //passage de l'url  recupe de l'url dans la div dans le create.html.twig
            var path =$("#url").attr("data-path");

            var request = $.ajax({
                url : path,
                type : 'POST',
                //optionnnel date_type : json
                data : {'nvNomVille' : nvnom, 'nvCode' : nvCP, 'idVille' : idVille},
                // data :{'test': 'les données de app.js'}
                success : function (res){
                    // console.log(res);
                    var nvVille = JSON.parse(res);
                    console.log('ma nouvelle ville =>', nvVille);
                    //remplacer les input
                    var nomVilleSpan=document.createElement('span');
                    nomVilleSpan.id="nomVille"+idVille;
                    nomVilleSpan.innerHTML=nvVille.nomVille;

                    var codeVilleSpan=document.createElement('span');
                    codeVilleSpan.id="codeVille"+idVille;
                    codeVilleSpan.innerHTML=nvVille.codePostal;

                    var inputCodePostal = $('#nvCodePostal');
                    var inputNom = $('#nvNom');

                    inputCodePostal.replaceWith(codeVilleSpan);
                    inputNom.replaceWith(nomVilleSpan);
                    $('.button_modifier[id='+idVille+']').show();
                    $('.buttonMAJ[id='+idVille+']').hide();
                } // fermeture sucess
            }); //fermeture AJAX
        });// on click
    }); // each bouton MAJ
});  // fonction principale