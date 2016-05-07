
// Faz aparecer uma lista de artistas do país escolhido pelo utilizador
clickOnSubmitCountryForm = function() {
    // Transforma input do user num array
    var array = $('#country_form').serializeArray();

    // Só há um input. coloco o valor dado pelo user numa variável
    var country = array[0].value;
    country = country.trim();

    // Chamada AJAX
    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/country_artists.php",
        dataType: "html",
        data: {country: country, order: 'likes'},

        // Se a chamada ajax fôr bem sucedida, colocar a informação recebida no div #list_of_artists
        success: function(content) {
            $("#list_of_artists").html(content);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
};

// Faz aparecer informação sobre o artista em cujo nome o utilizador clicou
addArtistInfo = function(artist_name) {

    artist_name =  encodeURIComponent(artist_name);

    // Chamada AJAX
    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/artist_info.php",
        dataType: "html",
        data: {artist: artist_name, type: 'show'},

        // Se a chamada ajax fôr bem sucedida, colocar a informação recebida no div #artist_info
        success: function(content) {
            $("#artist_info").html(content);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }

    });
};


// Faz aparece página de edição
showEditionPage = function(artist_name) {

    artist_name =  encodeURIComponent(artist_name);

    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/artist_info.php",
        dataType: "html",
        data: {artist: artist_name, type: 'edit'},

        // Se a chamada AJAX fôr bem sucedida, coloca a página de edição no div #artist_info
        success: function(content) {
            $("#artist_info").html(content);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
};


submitEdition = function(user_input, artist_name) {
    // Array com info sobre o input do user
    var array_user_input = user_input.serializeArray()[0];

    var param_to_edit =  array_user_input.name;
    var new_data = array_user_input.value;

    var access_token = FB.getAccessToken();

    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/webservices/artist.php/" + artist_name + '?access_token=' + access_token + '&' + param_to_edit + '=' + new_data,
        dataType: "html",
        method: 'PUT',

        // Se a chamada AJAX fôr bem sucedida, actualizar o div com info do artista.
        success: function() {
            addArtistInfo(artist_name);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }

    });

};


// Submete a adição de um novo artista para o país seleccionado
addArtistToCountry = function(artist_name, country) {
    artist_name = artist_name.trim();
    var access_token = FB.getAccessToken();

    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/webservices/artist/" + artist_name,
        dataType: "xml",
        method: 'POST',
        data: {'access_token': access_token, 'country': country},

        // Se a chamada ajax fôr bem sucedida.
        success: function(xml) {
            // Envia mensagem ao utilizador.
            alert($(xml).text());

            // Actualiza a lista da artistas.
            $("#country_form").trigger('submit');

            // Abre a página com info sobre o artista adicionado.
            addArtistInfo(artist_name);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
};

deleteArtist = function(artist_name) {
    var access_token = FB.getAccessToken();

    $.ajax({

        url: "http://appserver.di.fc.ul.pt/~aw008/webservices/artist.php/" + artist_name + '?access_token=' + access_token,
        method: 'DELETE',

        // Se a chamada AJAX fôr bem sucedida, actualizar o div com info do artista.
        success: function(x) {
            addArtistInfo(artist_name);
            clickOnSubmitCountryForm();
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
};

function populateDropDown(){
	$.ajax({
		url: "http://appserver.di.fc.ul.pt/~aw008/webservices/country.php",
		method: 'GET',
		dataType: "json",
        success: function(response) {
			//Recolher nomes dos paises e guarda-los numa lista
			var countries_list = [];
			$.each(response, function (object, country) {
				$.each(country, function(id,content){
					$.each(content, function(key,value){
						if (key === 'name'){
							countries_list.push(value);
						}
					});
				});
			});
			//var content = '<select name=countries>';
			var content = '';
			for (var counter = 0; counter < countries_list.length; counter++){
				content += '<option value="' + countries_list[counter] + '">' + countries_list[counter] + '</option>';
			}
			//content += '</select>';
			$('#country_form select').append(content);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }
	});
}


$(document).ready(function() {

//Preencher a lista de paises no dropdown
populateDropDown();

// Se o user carregar no submit da escolha do país.
$("#country_form").submit(function(e) {
    clickOnSubmitCountryForm(e);
    // Previne o comportamento normal do botão submit
    e.preventDefault();
});

// Se o user carregar num nome de artista, na lista de artistas.
$("#list_of_artists").on('click', 'li', function() {
    addArtistInfo($(this).text());
});

// Se o user cerregar no botão para adicionar um artista.
$('#add_artist').on('submit', function(e) {

    var country_array = $('#country_form').serializeArray();
    var country = country_array[0].value;

    var artist_array = $(this).serializeArray();
    var artist = artist_array[0].value;

    addArtistToCountry(artist, country);

    e.preventDefault();
});

// Se o user carregar num dos botões na zona de info sobre o artista.
$('#artist_info').on('click', 'button', function() {
    var artist_name = $('#artist_name').text();

    // Se o ser carregar no botão de edição.
    if ($(this).attr('id') == 'edit_button') {
        showEditionPage(artist_name);

    // Se o user carregar no botão de deleção.
    } else if ($(this).attr('id') == 'delete_button') {
        deleteArtist(artist_name);
    }
});

// Se o user subemeter a edição de informação sobre o artista.
$('#artist_info').on('submit', 'form', function(e) {
    var artist_name = $('#artist_name').text();

    submitEdition($(this), artist_name);
    e.preventDefault();
});

});
