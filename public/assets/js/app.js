$(function(){

   $('.button_modifier').each(function(){
      console.log($(this));

      $($(this)).on('click', function(){


         var idVille = $(this).attr('id');
        // console.log('nom id => ', $('#liste-ville-' + idVille));
         console.log('nom id => ', idVille);

         var codePostal=$('#code-postal-' +idVille).text();
         console.log('codePostal => ',codePostal );

          var nomVille=$('#nom-' +idVille).text();
          console.log('nomVille => ',nomVille );

          var newInputCP=document.createElement("input");
          newInputCP.type="text";
          newInputCP.name="nvCodePostal";
          newInputCP.value=codePostal;
          newInputCP.id="nvCodePostal";

          var newInputNom=document.createElement("input");
          newInputNom.type="text";
          newInputNom.name="nvNom";
          newInputNom.value=nomVille;
          newInputNom.id="nvNom";

          var codePostal2=$('#code-postal-' +idVille);
          var nom2=$('#nom-' +idVille);
          codePostal2.replaceWith(newInputCP);
          nom2.replaceWith(newInputNom);
          //TODO récupére les données
          $('.button_modifier').on('click', function(){
          //récupérer les valeurs des inouts
          var nvCP=$('#nvCodePostal').val();
          var nvnom=$('#nvNom').val();
          console.log('nv Code postal=>', nvCP);
          console.log('nv nom=>', nvnom);

          //faire une requete ajax

          //réaficher le tableau

      });
      });

   });
});