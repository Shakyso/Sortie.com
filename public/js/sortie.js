$(function() {

  var idSortie =  $('.sortie_id').attr('id');
  var path = $('#path_data').text();
    var valueVilleSend = $('#sortie_lieu_ville_ville').val();

    console.log(valueVilleSend);

$('#sortie_lieu_ville_ville').click(function() {


    console.log(valueVilleSend);
    $($(this)).on("click",function() {
        $.ajax({
            url: 'http://localhost/Sortir/public/sortie/Update/{idSortie}/{idVille}' ,
            method: 'GET',
            data: {'idSortie' : idSortie, 'idVille' : valueVilleSend},
            error: function(msg) {
                console.log(msg)
            },
            success: function(msg) {
                console.log(msg)
            }
        });

        return false;
    });


});

$('.sortie_lieu').change(function(){

        var valueLieuSend = $('#sortie_lieu').val();

});

});