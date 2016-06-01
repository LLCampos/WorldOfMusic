var base_url = "http://appserver.di.fc.ul.pt/~aw008/webservices";

// ######### General Functions ############# //

var scrollWindowTo= function(value) {
    $("html, body").animate({ scrollTop: value}, 1000);
};

var cleanInputs = function() {
    $('#input_artist_name').val('');
};

var getFBAccessTokenParam = function() {
    // Returns 'access_token={user_acess_token} if user is authenthicated, empty string otherwise.'

    var auth_response = FB.getAuthResponse();

    if (auth_response) {
        return 'access_token=' + auth_response.accessToken;
    } else {
        return '';
    }
};

var setHeights = function() {
    $('#map-zone').css('height', function() {return $(window).height() - parseInt($('#header').css('height'), 10);});
    $('#country-zone').css('minHeight', $(window).height());
};

var onPageResize = function() {
    setHeights();
    map_zone.updateMap();
    top_artist_zone.updateSize();
};

var activateCountriesAutocomplete = function() {
    $.get(base_url + '/country', function(data) {
        var countries_list = [];

        for (var i in data.countries) {
            countries_list.push(data.countries[i].name);
        }

        $('#' + editionModal.input_id).autocomplete({
           source: countries_list,
           appendTo: "#" + editionModal.form_id
        });

    });
};

// ########### AJAX ##############

var getCountryArtists = function(country_code, number_of_artists, order) {
    // Request to GET country/{country_code}/artists resource
    return $.getJSON(base_url + '/country/' + country_code + '/artists', {limit: number_of_artists, order: order});
};

var getArtistInfo = function(artist_name) {
    // Request to GET artist/{name_of_artist} resource
    return $.getJSON(base_url + '/artist/' + artist_name);
};

var getCountryInfo = function(country_code) {
    // Request to GET country/{country_code} resource
    return $.getJSON(base_url + '/country/' + country_zone.current_country_code);
};

var addArtistService = function(artist_name) {

    access_token_param = getFBAccessTokenParam();

    $.ajax({
        url: base_url + '/artist/' + artist_name + '?' +
                                                    'country=' + country_zone.current_country_code + '&' +
                                                    access_token_param,
        method: 'POST',
        beforeSend: function() {
            $('#artist_addition_form').spin({top: '50%', left: '115%'});
        },
        success: function(content) {
            warning_modal.activateWithText("Request submitted!");
        },
        error: function(jqXHR, textStatus, errorThrown) {
            response_text = $.parseJSON(jqXHR.responseText);
            warning_modal.activateWithText(response_text.message);
        },
        complete: function() {
            $('#input_artist_name').val('');
            $('#artist_addition_form').spin(false);
        }
    });
};

var ajaxCallPUTArtist = function(artist_name, param_changing, value) {

    access_token_param = getFBAccessTokenParam();

    $.ajax({
        url: base_url + "/artist/" + artist_name + '?' + param_changing + '=' + value + '&' + access_token_param,
        method: "PUT",
        beforeSend: function() {
            $('#' + editionModal.form_id).spin();
        },
        success: function(response) {
            $('#' + editionModal.form_id).spin(false);
            $('#' + editionModal.form_id).hide();
            $('#' + editionModal.submitted_screen_id).show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response_text = $.parseJSON(jqXHR.responseText).message;

            $('#' + editionModal.form_id).spin(false);
            alert(response_text);
            $('#' + editionModal.id).modal('hide');
        }
    });
};


var deleteCurrentArtist = function() {

    var param = getFBAccessTokenParam();

    $.ajax({
        url: base_url + '/artist/' + artist_zone.artist_name + '?' + param,
        method: 'DELETE',
        success: function(content) {
            warning_modal.activateWithText('Request submitted!');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            response_text = $.parseJSON(jqXHR.responseText).message;
            warning_modal.activateWithText(responseText);
        }
    });
};


// ###### ONLOAD ####

$(function() {

    setHeights();

    // Resize divs when user resizes window
    $(window).resize(function () {onPageResize();});

    cleanInputs();

    // User initialy can't see the country and artist zone
    $('#country-zone').hide();
    $('#artist-zone').hide();

    map_zone.insertMap('world_mill');
    map_zone.activateOptions();

    $('#artist_top_chart_svg').on('click', 'p', function() {artist_zone.update($(this).text());});

    type_of_vote_buttons.activate();
    go_to_random_artist_button.activate();
    submit_artist_button.activate();
    help_us_button.activate();
    feedback_options.activate();

});
