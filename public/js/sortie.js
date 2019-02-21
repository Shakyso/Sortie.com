$(function() {

    var pathVille = $('.sortie_ville').attr('data-path');
    var valueVille = $('#sortie_lieu_ville_ville').val();
console.log(valueVille);
    $.ajax({
        url: pathVille,
        method: 'POST',
        data: {'idVille': valueVille},
        error: function (result) {

        },
        success: function (result) {
            var res = JSON.parse(result);
            var lieu = res['listeLieu'];
            var listLieu = $('#sortie_lieu');

            listLieu.empty();
            listLieu.removeAttr('value');
            listLieu.removeAttr('selected');

            for (var i = 0; i < lieu.length; i++) {
                listLieu.append($('<option />').attr('value', lieu[i].id).html(lieu[i].nom));
                $('.sortie_lieu_rue').html(lieu[i].rue);
                $('.sortie_lieu_lat').html(lieu[i].latitude);
                $('.sortie_lieu_long').html(lieu[i].longitude);
                $('.sortie_ville_codepstal').html(lieu[i].codePostal);
                $('.sortie_lieu option').eq(lieu[0].id).attr('selected', true);
            }
        }
    });


$('#sortie_lieu_ville_ville').on("change", function(){

        var valueVille = $(this).val();
    console.log(valueVille);

    $.ajax({
        url: pathVille ,
        method: 'POST',
        data: {'idVille' : valueVille},
        error: function(result) {

        },
        success: function(result) {
            var res = JSON.parse(result);
            console.log(res['listeLieu']);
            var lieu =  res['listeLieu'];
            var listLieu = $('#sortie_lieu');

            listLieu.empty();
            listLieu.removeAttr('value');
            listLieu.removeAttr('selected');

            for(var i=0; i<lieu.length;i++)            {
                listLieu.append($('<option />').attr('value', lieu[i].id).html(lieu[i].nom));
                 $('.sortie_lieu_rue').html(lieu[i].rue);
                 $('.sortie_lieu_lat').html(lieu[i].latitude);
                 $('.sortie_lieu_long').html(lieu[i].longitude);
                 $('.sortie_ville_codepstal').html(lieu[i].codePostal);

                $('.sortie_lieu option').eq(lieu[0].id).attr('selected', true);
            }
        }
    });

    return false;

});

});