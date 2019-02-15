$(document).ready(function(){




   $('.button_modifier').each(function(){


      console.log($(this));

      $($(this)).on('click', function(){


         var idVille = $(this).attr('id');
         console.log('nom id => ', $('#liste-ville-' + idVille));

      });

   });
});
