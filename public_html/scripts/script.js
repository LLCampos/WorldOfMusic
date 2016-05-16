
// ######### General Functions ############# //

var scrollWindowTo= function(value) {
    $("html, body").animate({ scrollTop: value}, 1000);
};

// ######### Map Zone Functions ############# //

var insertMap = function(map) {
    $('#map').empty();

    var header_height = $('#header').height();

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
    // $('#country-zone').css('height', $(window).height());
    scrollWindowTo($('#map-zone').height() + $('#header').height());
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
    scrollWindowTo($('#map-zone').height() + $('#header').height() + $('#country-zone').outerHeight(true) + parseInt($('#artist-zone').css('margin-top'), 10));
};

var clearArtistZone = function() {

    $('#header-artist-zone-name').empty();
    $('#artist-genre-artist-zone').empty();
    $('#artist-country-artist-zone').empty();
    $('#artist-biography-text').empty();
    $('#artist_picture').attr('src', '');
    $('#youtube_video_artist iframe').attr('src', '');

    $('#artist_d3_circle_graphs').html('');
};

var fillArtistZone = function(artist_object) {

    clearArtistZone();

    $('#header-artist-zone-name').text(artist_object.name);
    $('#artist-genre-artist-zone').text(titleCaps(artist_object.style));
    $('#artist-country-artist-zone').text(artist_object.country);
    $('#artist_zone_artist_picture').attr('src', artist_object.picture_url);
    $('#youtube_video_artist iframe').attr('src', 'https://www.youtube.com/embed/' + artist_object.music_video);

    intact_bibliography = artist_object.bibliography;
    bibliography_end_start = intact_bibliography.search('<a href');
    bibliography_without_end = intact_bibliography.substr(0, bibliography_end_start - 1);
    bibliography_end = intact_bibliography.substr(bibliography_end_start);

    output_bibliography = bibliography_without_end.substr(0, 500) + '... ';

    $('#artist-biography-text').text(output_bibliography);
    $('#artist-biography-text').append(bibliography_end);

    $.when(

        $.get('http://appserver.di.fc.ul.pt/~aw008/webservices/country/' + current_country_code + '/artists?order=likes&limit=1'),
        $.get('http://appserver.di.fc.ul.pt/~aw008/webservices/country/' + current_country_code + '/artists?order=lastfm&limit=1')

    ).then(function(facebook_top_artist, lastfm_top_artist) {

        var facebook_top_artist_name = facebook_top_artist[0].artist[0].name;
        var lastfm_top_artist_name = lastfm_top_artist[0].artist[0].name;

        $.when(
            $.get('http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + facebook_top_artist_name),
            $.get('http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + lastfm_top_artist_name)

        ).then(function(facebook_top_artist, lastfm_top_artist) {

            var facebook_top_artist_likes = facebook_top_artist[0].number_of_facebook_likes;
            var lastfm_top_artist_lastfm = lastfm_top_artist[0].number_of_lastfm_listeners;

            var facebook_url = 'https://facebook.com/' + artist_object.facebook_id;
            var lastfm_url = artist_object.lastfm_url;

            create_artist_circle_graphs(facebook_top_artist_likes, artist_object.number_of_facebook_likes, facebook_url,
                                        lastfm_top_artist_lastfm, artist_object.number_of_lastfm_listeners, lastfm_url);
        });
    });
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

// #### Modal ####

global_submissions_user_not_want_to_vote = {addition: [], deletion: []};
global_submission_types_without_votes = [];

var submissionIDsUserAlreadyVoted = function(submission_type, user_id, callback) {
    // Returns an array with the ids of the submissions of the type submission_type in which the user already voted

    var service_url = 'http://appserver.di.fc.ul.pt/~aw008/webservices/user/' + user_id + '/votes';

    $.ajax({
        url: service_url,
        success: function(response) {

            // If user as any votes of type submission_type
            if (response[submission_type + '_votes']) {

                var list_of_votes = response[submission_type + '_votes'];
                list_of_submissions_ids = [];

                for (var i=0; i < list_of_votes.length; ++i) {
                    list_of_submissions_ids.push(list_of_votes[i][submission_type + '_id']);
                }

                callback(submission_type, list_of_submissions_ids, 1);

            } else {
                callback(submission_type, [], 1);
            }
        }
    });
};


// Displays message informing user that there are no submissions to vote on.
var noSubmissionsToVoteOn = function() {
    $('#modal_no_submissions_message').show();
};

var modalLoading = function(type) {
    // Type if a boolean. true to activate the loading and false to deactivate it.

    if (type) {
        $('#modal_body_addition_deletion').hide();
        $('#modal_no_submissions_message').hide();
        $('#modal_no_auth').hide();
        $('.modal-body').spin();
    } else if (type === false) {
        $('.modal-body').spin(false);
    }
};

var activateButtons = function(submission_type, submission_id) {

    $('#modal_vote_buttons').on('click', 'button', function(event) {

        // Gets id of the button pressed.
        var button_id = event.target.id;


        // If the button pressed was the positive or negative one, makes POST request. Otherwise, show another submission for the user to vote at.
        if (button_id == 'vote_button_positive' || button_id == 'vote_button_negative') {

            if (submission_type == 'addition') {
                service_url = 'http://appserver.di.fc.ul.pt/~aw008/webservices/pending_addition/' + submission_id + '/';
            } else if (submission_type == 'deletion') {
                service_url = 'http://appserver.di.fc.ul.pt/~aw008/webservices/pending_deletion/' + submission_id + '/';
            }

            // What a positive or negative vote means depends on the type of submission (addition or deletion)
            if (button_id == 'vote_button_positive') {
                if (submission_type == 'addition') {
                    service_url += 'positive_vote';
                } else if (submission_type == 'deletion') {
                    service_url += 'negative_vote';
                }
            } else {
                if (submission_type == 'addition') {
                    service_url += 'negative_vote';
                } else if (submission_type == 'deletion') {
                    service_url += 'positive_vote';
                }
            }

            var access_token_param = getFBAccessTokenParam();
            service_url += "?" + access_token_param;

            $.ajax({
                url: service_url,
                method: 'POST',

                success: function(response) {
                    onModalActivation();
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    response_text = $.parseJSON(jqXHR.responseText).message;
                    alert(response_text);
                },
            });
        } else if (button_id == 'vote_button_neutral') {
            global_submissions_user_not_want_to_vote[submission_type].push(submission_id);
            onModalActivation();
        }
    });
};

var deactivateButtons = function() {
   $('#modal_vote_buttons').off();
};

var onModalActivation = function() {

    modalLoading(true);

    // Remove handlers from buttons that could be attached to them from the last call of this function.
    deactivateButtons();

    FB.getLoginStatus(function(response) {
        if (response.status != 'connected') {

            modalLoading(false);
            $('#modal_no_auth').show();

        } else {

            var types_of_submissions = ['addition', 'deletion'];

            var types_of_submissions_left = $(types_of_submissions).not(global_submission_types_without_votes).get();

            if (types_of_submissions_left.length === 0) {
                modalLoading(false);
                noSubmissionsToVoteOn();
            } else {

                var submission_type = types_of_submissions_left[Math.floor(Math.random()*types_of_submissions_left.length)];

                var user_id = FB.getUserID();

                $('#feedback_modal .modal-title').text("Should we have this artists on our app?");

                // Gets a list of IDs of the submissions the user already voted and then calls getSubmissionVote with that list as one of the attributes.
                // This list will be used to avoid that a submission in which the user already voted at appear again.
                submissionIDsUserAlreadyVoted(submission_type, user_id, getSubmissionToVote);
            }
        }
    });
};

var getSubmissionToVote = function(submission_type, list_of_submissions_ids, page) {

    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/pending_' + submission_type + '/?limit=1&page=' + page,  // Get a random pending submission.
        success: function(content) {

            if ($.isEmptyObject(content)) {
                modalLoading(false);
                global_submission_types_without_votes.push(submission_type);
                onModalActivation();
            } else {
                if (submission_type == 'addition') {
                    submission_info = content.pending_additions[0];
                } else {
                    submission_info = content.pending_deletions[0];
                }

                var submission_id = submission_info.id;

                if (($.inArray(submission_id, list_of_submissions_ids) != -1) || ($.inArray(submission_id, global_submissions_user_not_want_to_vote[submission_type])) != -1 ) {
                    // If user already voted on this submissions or skipped it, try next one.
                    getSubmissionToVote(submission_type, list_of_submissions_ids, page + 1);
                } else {
                    var change_artist_name = submission_info.artist_name;
                    fillModalDeletionOrAddition(submission_type, change_artist_name, submission_id);
                }
            }
        },

        error: function(jqXHR, textStatus, errorThrown) {
        }
    });

};

var fillModalDeletionOrAddition = function(submission_type, artist_name, id) {

    $.ajax({
        url: 'http://appserver.di.fc.ul.pt/~aw008/webservices/artist/' + artist_name,

        success: function(response) {
            createContentForModalDeletionOrAddition(response);
            modalLoading(false);
            $('#modal_body_addition_deletion').show();
            activateButtons(submission_type, id);
        },

        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
};

var createContentForModalDeletionOrAddition = function(response) {
    var artist_country = response.country;
    var artist_name = response.name;
    var artist_genre = titleCaps(response.style);
    var picture = response.picture_url;
    var lastfm_url = response.lastfm_url;

    $('#modal_artist_picture').attr('src', picture);
    $('#modal_artist_name').text(artist_name);
    $('#modal_country_name').text(artist_country);
    $('#modal_genre').text(artist_genre);
    $('#lastfm-modal-logo-link').attr('href', lastfm_url);
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

var setHeights = function() {
    $('#map-zone').css('height', function() {return $(window).height() - parseInt($('#header').css('height'), 10);});
    $('#country-zone').css('height', $(window).height());
    $('#artist-zone').css('height', $(window).height());
};

var onPageResize = function() {
    setHeights();
    $('#map-buttons').trigger('click');
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

    insertMap('world_mill');

    $('#map-buttons').on('click', 'button', function() {
        $('#map-buttons').find('.active').toggleClass("active");
        $(this).toggleClass("active");
        var button_pressed = $(this).text();
        button_pressed = button_pressed.toLowerCase();
        button_pressed = button_pressed.replace(' ', '_');
        insertMap(button_pressed + '_mill');
    });

    $('#go-to-random-artist-button').on('click', function() {getRandomArtistFromCountryAndGoToAndFillArtistZone(current_country_code);});

    $('#country_artists_top').on('click', 'p', function() {goToAndFillArtistZone($(this).text());});

    $('#add_artist_button').on('click', function(event) {userClickOnSubmitArtist(event);});

    $('#delete_artist_button').on('click', function() {deleteCurrentArtist();});

    $('#help_us_button').on('click', function() {onModalActivation();});

});
