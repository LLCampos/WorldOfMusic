
// ######### General Functions ############# //

var scrollWindowTo= function(value) {
    $("html, body").animate({ scrollTop: value}, 1000);
};

// ######### Map Zone Functions ############# //

var goToMapZone = function() {
    // $('#country-zone').hide(1000);
    // $('#artist-zone').hide(1000);
    scrollWindowTo(0);
};

var insertMap = function(map) {
    $('#map').empty();

    var header_height = parseInt($('#header').css('height'), 10);
    $('#map').css('height', $(window).height() - header_height);

    $('#map').vectorMap({map: map,
                         backgroundColor: background_color,
                         series: {
                            regions: [{
                                attribute: 'fill',
                                values: regions_colors
                            }],
                         },
                         onRegionClick: function(event, country_code) {
                            current_country_code = country_code;
                            goToAndFillCountryZone(event, country_code);
                          }
                        });
};

// ######### Country Zone Functions ############# //

var getCountryInfoAndFillCountryZone = function(country_code) {
    getCountryInfoFromService(fillCountryZone, country_code);
};

var getCountryTopArtistsAndFillCountryTopArtistsChart = function(country_code, number_of_artists) {
    getCountryArtists(fillCountryTopArtistsChart, country_code, number_of_artists, 'likes');
};

var getCountryInfoFromService = function(callback, country_code) {
    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/country/' + country_code,
        dataType: 'JSON',
        success: function(content) {
            callback(content, country_code);
        }
    });
};

var getCountryArtists = function(callback, country_code, number_of_artists, order) {
    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/country/' + country_code + '/artists',
        data: {limit: number_of_artists, order: order},
        dataType: 'JSON',
        success: function(content) {
            callback(content);
        }
    });
};

var fillCountryTopArtistsChart = function(list_of_artists) {
    $.each(list_of_artists.artist, function(i, artist) {
        $('#country_artists_top ol').append('<li><p>' + artist.name + '</p></li>');
    });
};

var goToCountryZone = function(event, country_code) {
    $('#country-zone').show();
    scrollWindowTo($(window).height());
};

cleanCountryZone = function() {

    $('#country_name').empty();
    $('#country_zone_capital').empty();
    $('#country_zone_population').empty();
    $('#country_zone_region').empty();
    $('#country_zone_subregion').empty();
    $('#country_zone_music_description').empty();
    $('#country_zone_music_description').removeData();
    $('#country_zone_flag').attr('src', '');
    $('#country_artists_top ol').empty();
};


var fillCountryZone = function(country_info, country_code) {

    cleanCountryZone();

    $('#country_name').text(country_info.name);
    $('#country_zone_capital').text(country_info.capital);
    $('#country_zone_population').text(country_info.population);
    $('#country_zone_region').text(country_info.region);
    $('#country_zone_subregion').text(country_info.subregion);
    $('#country_zone_music_description').text(country_info.description_of_music);
    $('#country_zone_flag').attr('src', country_info.flag_img_url);

    $('#country_zone_music_description').shorten({
        moreText: 'Read more...',
        lessText: 'Read less.',
        showChars: 200,
    });

    getCountryTopArtistsAndFillCountryTopArtistsChart(country_code, 10);
};

var goToAndFillCountryZone = function(event, country_code) {
    $('#artist-zone').hide(0);
    getCountryInfoAndFillCountryZone(country_code);
    goToCountryZone();
};


// ######### Artist Zone Functions ############# //

var getArtistInfo = function(callback, artist_name) {
    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + artist_name,
        dataType: 'JSON',
        success: function(content) {
            callback(content);
        }
    });
};

var getRandomArtistFromCountryAndGoToAndFillArtistZone = function(country_code) {
    getCountryArtists(function(artist_object) {goToAndFillArtistZone(artist_object.artist[0].name);}, country_code, 1, 'random');
};

var goToArtistZone = function() {
    $('#artist-zone').show();
    scrollWindowTo($(document).height());
};

var clearArtistZone = function() {

    $('#header-artist-zone-name').empty();
    $('#artist-genre-artist-zone').empty();
    $('#artist-country-artist-zone').empty();
    $('#facebook-logo-link').attr('href', '');
    $('#lastfm-logo-link').attr('href', '');
    // $('#twitter-logo-link').attr('href', '');
    $('#number-of-lastfm-listeners').empty();
    $('#number-of-facebook-likes').empty();
    // $('#number-of-twitters-followers').empty();
    $('#artist-biography-text').empty();
    $('#profile-pic-artist-zone').attr('src', '');
    $('#youtube_video_artist iframe').attr('src', '');
};

var fillArtistZone = function(artist_object) {

    clearArtistZone();

    $('#header-artist-zone-name').text(artist_object.name);
    $('#artist-genre-artist-zone').text(titleCaps(artist_object.style));
    $('#artist-country-artist-zone').text(artist_object.country);
    $('#facebook-logo-link').attr('href', 'https://facebook.com/' + artist_object.facebook_id);
    $('#lastfm-logo-link').attr('href', artist_object.lastfm_url);
    // $('#twitter-logo-link').attr('href', artist_object.twitter_url);
    $('#number-of-lastfm-listeners').text(artist_object.number_of_lastfm_listeners);
    $('#number-of-facebook-likes').text(artist_object.number_of_facebook_likes);
    // $('#number-of-twitters-followers').text(artist_object.number_of_twitter_followers + 'followers');
    $('#profile-pic-artist-zone').attr('src', artist_object.picture_url);
    $('#youtube_video_artist iframe').attr('src', 'https://www.youtube.com/embed/' + artist_object.music_video);

    intact_bibliography = artist_object.bibliography;
    bibliography_end_start = intact_bibliography.search('<a href');
    bibliography_without_end = intact_bibliography.substr(0, bibliography_end_start - 1);
    bibliography_end = intact_bibliography.substr(bibliography_end_start);

    output_bibliography = bibliography_without_end.substr(0, 500) + '... ';

    $('#artist-biography-text').text(output_bibliography);
    $('#artist-biography-text').append(bibliography_end);
};

var goToAndFillArtistZone = function(artist_name) {
    getArtistInfo(fillArtistZone, artist_name);
    goToArtistZone();
};


// #### Artist Addition ####

var userClickOnSubmitArtist = function(event) {
    var artist_name = $('#input_artist_name').val();
    addArtistService(successArtistSubmission, errorArtistSubmission, artist_name);

    event.preventDefault();
};

var addArtistService = function(callback_success, callback_error, artist_name) {

    access_token_param = getFBAccessTokenParam();

    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + artist_name + '?' +
                                                    'country=' + current_country_code + '&' +
                                                    access_token_param,
        method: 'POST',
        beforeSend: function() {
            $("#artist_addition_form_zone_spinner").spin({top: '90%', left: '40%'});
            cleanArtistSubmissionAlerts();
        },
        success: function(content) {
            callback_success(content);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            callback_error(jqXHR);
        }
    });
};

var successArtistSubmission = function(content) {
    $("#artist_addition_form_zone_spinner").spin(false);
    $('#input_artist_name').val('');

    $('#alert_unsuccess_artist_submission').hide(500);
    $('#alert_success_artist_submission').show(500);
};

var errorArtistSubmission = function(jqXHR) {
    $("#artist_addition_form_zone_spinner").spin(false);
    $('#input_artist_name').val('');

    response_text = $.parseJSON(jqXHR.responseText);

    $('#alert_unsuccess_artist_submission').text(response_text.message);

    $('#alert_success_artist_submission').hide(500);
    $('#alert_unsuccess_artist_submission').show(500);
};

var cleanArtistSubmissionAlerts = function() {
    $('#alert_unsuccess_artist_submission').hide(500);
    $('#alert_success_artist_submission').hide(500);
};

// #### Artist Deletion ####

var deleteCurrentArtist = function() {
    var artist_name = $('#header-artist-zone-name').text();

    var param = getFBAccessTokenParam();

    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + artist_name + '?' + param,
        method: 'DELETE',
        beforeSend: function() {
        },
        success: function(content) {
            alert('Request submitted!');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            response_text = $.parseJSON(jqXHR.responseText).message;
            alert(response_text);
        }
    });
};


// #### General ####

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

var cleanAlerts = function() {
    // Hide all user alerts.
    cleanArtistSubmissionAlerts();
};

// ###### ONLOAD ####

$(function() {

    $('#map-zone').css('height', function() {return $(window).height() - parseInt($('#header').css('height'), 10);});
    $('#country-zone').css('height', $(window).height());
    $('#artist-zone').css('height', $(window).height());

    cleanInputs();

    // User initialy can't see the country and artist zone
    $('#country-zone').hide();
    $('#artist-zone').hide();

    insertMap('world_mill');

    $('#map-buttons').on('click', 'button', function() {
        $('#map-buttons').find('.active').toggleClass("active");
        $(this).toggleClass("active");
        var button_pressed = $(this).text();
        button_pressed = button_pressed.toLowerCase();
        button_pressed = button_pressed.replace(' ', '_');
        insertMap(button_pressed + '_mill');
    });

    $('#button-to-map').on('click', 'button', function() {goToMapZone();});

    $('#button-to-country').on('click', 'button', function() {goToCountryZone();});

    $('#go-to-random-artist-button').on('click', function() {getRandomArtistFromCountryAndGoToAndFillArtistZone(current_country_code);});

    $('#country_artists_top').on('click', 'p', function() {goToAndFillArtistZone($(this).text());});

    $('#add_artist_button').on('click', function(event) {userClickOnSubmitArtist(event);});

    $('#delete_artist_button').on('click', function() {deleteCurrentArtist();});

});
