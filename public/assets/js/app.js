
$(document).ready(function(){
   /*
    $( "tr" ).each(function (index) {
        $( "#testVille" ).click(function() {
            alert( "Handler for .click() called." );
        });
    });
*/


   var i = 0;
    //faire une boucle
   $( ".listeVille" ).each(function (index) {



       //console.log($(ville));
       // $( "button").click(function () {
       //var id=$( ".button_modifier").attr("id");
       console.log($(this));

       var test = $('.action > .button_modifier').attr('id');
       console.log('test => ', test);
       var codePostal = $('.villeCodePostal').val();
       console.log("Code postal => ", codePostal);
       var villeId = $(".button_modifier").attr("id");
       console.log(villeId);
       $( ".button_modifier").click(function () {
            console.log(villeId);
       //var ville=$(this);

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