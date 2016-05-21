var base_url = "http://appserver.di.fc.ul.pt/~aw008/webservices";

// ######### General Functions ############# //

var scrollWindowTo= function(value) {
    $("html, body").animate({ scrollTop: value}, 1000);
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
                            country_zone.update(country_code);
                          }
                        });
};

var updateMap = function() {

    // Inserts the map again. Used for update of map size after window resize.
    $('#map-options').trigger('click');
};

// ######### Country Zone ############# //

var country_zone = {

    current_country_code: '',

    clear: function() {
        $('#country_name').empty();
        $('#country_zone_capital').empty();
        $('#country_zone_population').empty();
        $('#country_zone_region').empty();
        $('#country_zone_subregion').empty();
        $('#country_zone_music_description').empty();
        $('#country_zone_music_description').removeData();
        $('#country_zone_flag').attr('src', '#');
        $('#country_artists_top ol').empty();
    },

    scrollTo: function() {
        $('#country-zone').show();
        scrollWindowTo($('#map-zone').height() + $('#header').height());
    },

    setMinHeight: function(minHeight) {
        $('#country-zone').css('minHeight', minHeight);
    },

    fill: function() {

        var ajax_call = getCountryInfo(country_zone.current_country_code);

        ajax_call.then(function(country_info) {

            country_zone.setMinHeight($(window).height());

            country_zone.clear();

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

            country_zone.fillTopArtistsChart(10, 'likes');
        });
    },

    update: function(country_code) {
        country_zone.current_country_code = country_code;
        $('#artist-zone').hide();
        country_zone.fill();
        country_zone.scrollTo();
    },

    fillTopArtistsChart: function(number_of_artists, order) {
        var ajax_call = getCountryArtists(country_zone.current_country_code, number_of_artists, order);

        ajax_call.then(function(list_of_artists) {

            $.each(list_of_artists.artist, function(i, artist) {
                $('#country_artists_top ol').append('<li><p>' + artist.name + '</p></li>');
            });

        });
    },
};


// ######### Artist Zone ############# //

var artist_zone = {

    artist_name: '',

    scrollTo: function() {
        scrollWindowTo($('#map-zone').height() + $('#header').height() + $('#country-zone').outerHeight(true) + parseInt($('#artist-zone').css('margin-top'), 10));
    },

    clear: function() {

        $('#header-artist-zone-name').empty();
        $('#artist-genre-artist-zone').empty();
        $('#artist-country-artist-zone').empty();
        $('#artist-biography-text').empty();
        $('#artist_picture').attr('src', '#');
        $('#youtube_video_artist').attr('src', '#');

        $('#artist_d3_circle_graphs').html('');
    },

    fillBiography: function(biography) {

        biography_end_start = biography.search('<a href');
        biography_without_end = biography.substr(0, biography_end_start - 1);
        biography_end = biography.substr(biography_end_start);

        output_biography = biography_without_end.substr(0, 500) + '... ';

        $('#artist-biography-text').text(output_biography);
        $('#artist-biography-text').append(biography_end);
    },

    fillCirclePlotZone: function(facebook_url, lastfm_url, number_of_facebook_likes, number_of_lastfm_listeners) {

        $.when(

            $.get(base_url + '/country/' + country_zone.current_country_code + '/artists?order=likes&limit=1'),
            $.get(base_url + '/country/' + country_zone.current_country_code + '/artists?order=lastfm&limit=1')

        ).then(function(facebook_top_artist, lastfm_top_artist) {

            var facebook_top_artist_name = facebook_top_artist[0].artist[0].name;
            var lastfm_top_artist_name = lastfm_top_artist[0].artist[0].name;

            $.when(
                $.get(base_url + '/artist/' + facebook_top_artist_name),
                $.get(base_url + '/artist/' + lastfm_top_artist_name)

            ).then(function(facebook_top_artist, lastfm_top_artist) {

                var facebook_top_artist_likes = facebook_top_artist[0].number_of_facebook_likes;
                var lastfm_top_artist_lastfm = lastfm_top_artist[0].number_of_lastfm_listeners;

                create_artist_circle_graphs(facebook_top_artist_likes, number_of_facebook_likes, facebook_url,
                                            lastfm_top_artist_lastfm, number_of_lastfm_listeners, lastfm_url);
            });
        });

    },

    fill: function() {

        artist_zone.clear();

        var ajax_call = getArtistInfo(artist_zone.artist_name);

        ajax_call.then(function(artist_object) {

            $('#header-artist-zone-name').text(artist_object.name);
            $('#artist-genre-artist-zone').text(titleCaps(artist_object.style));
            $('#artist-country-artist-zone').text(artist_object.country);
            $('#artist_zone_artist_picture').attr('src', artist_object.picture_url);
            $('#youtube_video_artist').attr('src', 'https://www.youtube.com/embed/' + artist_object.music_video);

            artist_zone.fillBiography(artist_object.bibliography);

            var facebook_url = 'https://facebook.com/' + artist_object.facebook_id;
            var lastfm_url = artist_object.lastfm_url;
            var number_of_facebook_likes = artist_object.number_of_facebook_likes;
            var number_of_lastfm_listeners = artist_object.number_of_lastfm_listeners;

            artist_zone.fillCirclePlotZone(facebook_url, lastfm_url, number_of_facebook_likes, number_of_lastfm_listeners);

        });
    },

    randomUpdate: function(country_code) {
        var ajax_call = getCountryArtists(country_code, 1, 'random');

        ajax_call.then(function(artist_object) {
            artist_name = artist_object.artist[0].name;
            artist_zone.update(artist_name);
        });
    },

    update: function(artist_name) {
        artist_zone.artist_name = artist_name;

        $('#artist-zone').show();
        artist_zone.scrollTo();

        artist_zone.fill();
    },
};


var onFeedbackOptionClick = function(btn_pressed) {

    var id_btn_pressed = $(btn_pressed).attr('id');

    if (id_btn_pressed == 'delete_artist_button') {
        deleteCurrentArtist();
    } else {
        onEditionModalActivation(id_btn_pressed);
    }

};


// #### Artist Addition ####

var submit_artist_button = {

    id: '#add_artist_button',

    activate: function() {$(submit_artist_button.id).on('click', function(event) {submit_artist_button.click(event);});},

    click : function(event) {
                var artist_name = $('#input_artist_name').val();
                addArtistService(successArtistSubmission, errorArtistSubmission, artist_name);
                event.preventDefault();
            }
};


var addArtistService = function(callback_success, callback_error, artist_name) {

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
            callback_success(content);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            callback_error(jqXHR);
        },
        complete: function() {
            $('#input_artist_name').val('');
            $('#artist_addition_form').spin(false);
        }
    });
};

var successArtistSubmission = function(content) {
    activateWarningModal("Request submitted!");
};

var errorArtistSubmission = function(jqXHR) {
    response_text = $.parseJSON(jqXHR.responseText);
    activateWarningModal(response_text.message);
};

// #### Artist Deletion ####

var deleteCurrentArtist = function() {
    var artist_name = $('#header-artist-zone-name').text();

    var param = getFBAccessTokenParam();

    $.ajax({
        url: base_url + '/artist/' + artist_name + '?' + param,
        method: 'DELETE',
        beforeSend: function() {
        },
        success: function(content) {
            activateWarningModal('Request submitted!');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            response_text = $.parseJSON(jqXHR.responseText).message;
            activateWarningModal(responseText);
        }
    });
};

// #### Votes Modal ####

global_submissions_user_not_want_to_vote = {addition: [], deletion: []};
global_submission_types_without_votes = [];

var submissionIDsUserAlreadyVoted = function(submission_type, user_id, callback) {
    // Returns an array with the ids of the submissions of the type submission_type in which the user already voted

    var service_url = base_url + '/user/' + user_id + '/votes';

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
    $('#feedback_modal_no_submissions_message').show();
};

var feedbackModalLoading = function(type) {
    // Type if a boolean. true to activate the loading and false to deactivate it.

    if (type) {
        $('#feedback_modal_body_addition_deletion').hide();
        $('#feedback_modal_no_submissions_message').hide();
        $('#feedback_modal_no_auth').hide();
        $('#feedback_modal .modal-body').spin();
    } else if (type === false) {
        $('#feedback_modal .modal-body').spin(false);
    }
};

var activateButtons = function(submission_type, submission_id) {

    $('#feedback_modal_vote_buttons').on('click', 'button', function(event) {

        // Gets id of the button pressed.
        var button_id = event.target.id;


        // If the button pressed was the positive or negative one, makes POST request. Otherwise, show another submission for the user to vote at.
        if (button_id == 'vote_button_positive' || button_id == 'vote_button_negative') {

            if (submission_type == 'addition') {
                service_url = base_url + '/pending_addition/' + submission_id + '/';
            } else if (submission_type == 'deletion') {
                service_url = base_url + '/pending_deletion/' + submission_id + '/';
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
                    onFeedbackModalActivation();
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    response_text = $.parseJSON(jqXHR.responseText).message;
                    alert(response_text);
                },
            });
        } else if (button_id == 'vote_button_neutral') {
            global_submissions_user_not_want_to_vote[submission_type].push(submission_id);
            onFeedbackModalActivation();
        }
    });
};

var deactivateButtons = function() {
   $('#feedback_modal_vote_buttons').off();
};

var onFeedbackModalActivation = function() {

    feedbackModalLoading(true);

    // Remove handlers from buttons that could be attached to them from the last call of this function.
    deactivateButtons();

    FB.getLoginStatus(function(response) {
        if (response.status != 'connected') {

            feedbackModalLoading(false);
            $('#feedback_modal_no_auth').show();

        } else {

            var types_of_submissions = ['addition', 'deletion'];

            var types_of_submissions_left = $(types_of_submissions).not(global_submission_types_without_votes).get();

            if (types_of_submissions_left.length === 0) {
                feedbackModalLoading(false);
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
        url: base_url + '/pending_' + submission_type + '/?limit=1&page=' + page,  // Get a random pending submission.
        success: function(content) {

            if ($.isEmptyObject(content)) {
                feedbackModalLoading(false);
                global_submission_types_without_votes.push(submission_type);
                onFeedbackModalActivation();
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
        url: base_url + '/artist/' + artist_name,

        success: function(response) {
            createContentForModalDeletionOrAddition(response);
            feedbackModalLoading(false);
            $('#feedback_modal_body_addition_deletion').show();
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

    $('#feedback_modal_artist_picture').attr('src', picture);
    $('#feedback_modal_artist_name').text(artist_name);
    $('#feedback_modal_country_name').text(artist_country);
    $('#feedback_modal_genre').text(artist_genre);
    $('#feedback_modal_lastfm_logo_link').attr('href', lastfm_url);
};


// #### Edition Modal ####

var onEditionModalClosure = function() {
    $('#edition_modal_submit_button').off();
    $('#edition_modal').off();

    $('#edition_modal_input').autocomplete('destroy');

    $('#edition_modal_input').removeAttr('name type');

    $('#edition_modal_form').spin(false);
};

var onEditionModalActivation = function(id_btn_pressed) {
    $('#edition_modal_input').val('');

    $('#edition_modal_form').show();
    $('#edition_modal_submitted_screen').hide();

    $('#edition_modal').modal();

    $('#edition_modal').on('hidden.bs.modal', function(){
         onEditionModalClosure();
    });

    var text_to_show_on_header;
    var edition_input_id;
    var edition_input_type;
    var param_changing;

    if (id_btn_pressed == 'country_edition_btn') {
        text_to_show_on_header = 'country';
        edition_input_name = 'country';
        edition_input_type = 'text';
        param_changing = 'country';

        activateCountriesAutocomplete();

    } else if (id_btn_pressed == 'facebook_edition_btn') {
        text_to_show_on_header = 'Facebook URL';
        edition_input_name = 'facebook';
        edition_input_type = 'url';
        param_changing = 'facebook_url';

    } else if (id_btn_pressed == 'genre_edition_btn') {
        text_to_show_on_header = 'genre';
        edition_input_name = 'genre';
        edition_input_type = 'text';
        param_changing = 'style';
    }

    $('#attribute_being_edited_edition_modal_header').text(text_to_show_on_header);
    $('#edition_modal_input').attr('name', edition_input_name);
    $('#edition_modal_input').attr('type', edition_input_type);

    $('#edition_modal_submit_button').on('click', function(e) {
        var user_input = $('#edition_modal_input').val();
        var artist_name = $("#header-artist-zone-name").text();

        ajaxCallPUTArtist(artist_name, param_changing, user_input);
        e.preventDefault();
    });
};

var ajaxCallPUTArtist = function(artist_name, param_changing, value) {

    access_token_param = getFBAccessTokenParam();

    $.ajax({
        url: base_url + "/artist/" + artist_name + '?' + param_changing + '=' + value + '&' + access_token_param,
        method: "PUT",
        beforeSend: function() {
            $('#edition_modal_form').spin();
        },
        success: function(response) {
            $('#edition_modal_form').spin(false);
            $('#edition_modal_form').hide();
            $('#edition_modal_submitted_screen').show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response_text = $.parseJSON(jqXHR.responseText).message;

            $('#edition_modal_form').spin(false);
            alert(response_text);
            $('#edition_modal').modal('hide');
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

var setHeights = function() {
    $('#map-zone').css('height', function() {return $(window).height() - parseInt($('#header').css('height'), 10);});
    $('#country-zone').css('minHeight', $(window).height());
};

var onPageResize = function() {
    setHeights();
    updateMap();
};

var activateCountriesAutocomplete = function() {
    $.get(base_url + '/country', function(data) {
        var countries_list = [];

        for (var i in data.countries) {
            countries_list.push(data.countries[i].name);
        }

        $('#edition_modal_input').autocomplete({
           source: countries_list,
           appendTo: "#edition_modal_form"
        });

    });
};

var closeAlert = function(event) {
    $(event.target).parent().hide();
    $(".close").off();
    event.preventDefault();
};

var activateWarningModal = function(text) {
    $('#warning_modal').modal();
    $('#warning_modal_body_text').text(text);
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

    $('#map-options').on('click', 'li', function() {
        $('#map-options').find('.active_option').toggleClass("active_option");
        $(this).toggleClass("active_option");
        var option_pressed = $(this).text();
        option_pressed = option_pressed.toLowerCase();
        option_pressed = option_pressed.replace(' ', '_');
        insertMap(option_pressed + '_mill');
    });

    $('#go-to-random-artist-button').on('click', function() {artist_zone.randomUpdate(country_zone.current_country_code);});

    $('#country_artists_top').on('click', 'p', function() {artist_zone.update($(this).text());});

    submit_artist_button.activate();

    $('#help_us_button').on('click', function() {onFeedbackModalActivation();});

    $('#feedback-options').on('click', 'li', function() {onFeedbackOptionClick(this);});

});
