
$(document).ready(function(){
    $( "tr" ).each(function (index) {
        $( "#testVille" ).click(function() {
            alert( "Handler for .click() called." );
        });
    });


    //faire une boucle
   $( "tr" ).each(function (index) {
       var villeId=$(this);
       console.log($(villeId));
      // $( "#updateVille_($(this))" ).click(function () {
            //if(this.id=='modifier') {
        //        console.log(index+ ":" + $(this).text());
            //}
       //récupérer l'id de l'objet
       //var idVille = $('.villeNom').parent().attr('id');
      //console.log(idVille);
        // });


       //});
  });
});
        //récupérer les cellules nom et de code postal pour un id bien particulier
       // $(".villeNom#id")


/*
        $(".villeCodePostal#id")
           $.ajax(n
               {
                   type:"POST",
                   url : "{{ path",
                   data :{"siteId" : siteId, "codePostal" : codePostal},
                   success: function(res){
                       console.log('mes resultats => ', res);
                   },
                   error: function(){
                       console.log('error');
                   }
               }

           );
           $(this).css('background-color','33FF66');

*/




/*console.log("avec jquery2");
   $( "h1" ).click(function() {
       console.log( "You clicked a paragraph!" );
   });


   $( ".update" ).on('click',function() {
       update();
   });
*/