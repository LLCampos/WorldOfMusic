var clickOnCountry = function(e, code) {
     var country_name = getCountryName(code);

    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/country_artists.php",
        dataType: "html",
        data: {country: country_name},

        // Se a chamada ajax fôr bem sucedida, colocar a informação recebida no div #list_of_artists
        success: function(content) {
            $("#list_of_artists").html(content);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
};

$(function() {
    $('#world-map').vectorMap({map: 'world_mill',
                            onRegionClick: function(e, code) {clickOnCountry(e, code);}
                            });
});
