// ########## User #################



// ######### Map Zones ############# //

var map_zone = {
    id: 'map-zone',
    map_id: 'map',
    map_options_id: 'map-options',

    insertMap: function(map) {

        $('#' + map_zone.map_id).empty();

        // Sets map size
        var header_height = $('#header').height();
        $('#' + map_zone.map_id).css('height', $(window).height() - header_height);

        $('#' + map_zone.map_id).vectorMap({map: map,
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
    },

    updateMap: function() {
        // Inserts the map again. Used for update of map size after window resize.
        $('#' + map_zone.map_options_id).trigger('click');
    },

    activateOptions: function() {
        $('#' + map_zone.map_options_id).on('click', 'li', function() {
            $('#' + map_zone.map_options_id).find('.active_option').toggleClass("active_option");
            $(this).toggleClass("active_option");
            var option_pressed = $(this).text();
            option_pressed = option_pressed.toLowerCase();
            option_pressed = option_pressed.replace(' ', '_');
            map_zone.insertMap(option_pressed + '_mill');
        });
    }
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

// #### Buttons and Options ####

var submit_artist_button = {

    id: 'add_artist_button',

    activate: function() {$('#' + submit_artist_button.id).on('click', function(event) {submit_artist_button.click(event);});},

    click : function(event) {
                var artist_name = $('#input_artist_name').val();
                addArtistService(artist_name);
                event.preventDefault();
            }
};

var delete_artist_button = {
    id : 'delete_artist_button',
};

var edition_modal_submit_button = {
    id : 'edition_modal_submit_button',

    deactivate: function() {$('#' + edition_modal_submit_button.id).off();},

    activate: function() {
        $('#' + edition_modal_submit_button.id).on('click', function(e) {

            var user_input = $('#' + editionModal.input_id).val();
            var artist_name = artist_zone.artist_name;

            ajaxCallPUTArtist(artist_name, editionModal.param_changing, user_input);
            e.preventDefault();
        });
    },
};

var feedback_modal_vote_buttons = {
    id : 'feedback_modal_vote_buttons',

    activate: function(submission_type, submission_id) {

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
                        feedback_modal.onActivation();
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        response_text = $.parseJSON(jqXHR.responseText).message;
                        alert(response_text);
                    },
                });

            } else if (button_id == 'vote_button_neutral') {
                user_voting.submissions_user_not_want_to_vote[submission_type].push(submission_id);
                feedback_modal.onActivation();
            }
        });
    },

    deactivate: function() {
        $('#feedback_modal_vote_buttons').off();
    }

};

var go_to_random_artist_button = {
    id: 'go-to-random-artist-button',
    activate: function() {
        $('#' + go_to_random_artist_button.id).on('click', function() {artist_zone.randomUpdate(country_zone.current_country_code);});
    }
};

var help_us_button = {
    id: 'help_us_button',
    activate: function() {
        $('#' + help_us_button.id).on('click', function() {feedback_modal.onActivation();});
    }
};

var feedback_options = {
    id: 'feedback-options',
    activate: function() {
        $('#' + feedback_options.id).on('click', 'li', function() {feedback_options.click(this);});
    },
    click: function(btn_pressed) {
        var id_btn_pressed = $(btn_pressed).attr('id');

        if (id_btn_pressed == delete_artist_button.id) {
            deleteCurrentArtist();
        } else {
            editionModal.activate(id_btn_pressed);
        }
    },
};


// #### Votes Modal ####

var feedback_modal = {

    id : 'feedback_modal',
    addition_deletion_body_id: 'feedback_modal_body_addition_deletion',
    simple_message_body_id: 'simple_message_body',
    title_id: 'feedback_modal .modal-title',

    no_auth_message: 'You have to be authenticated to use this feature.',
    no_submissions_message: 'There are currently no submissions for you to vote at. You can explore the rest of the app. :)',

    addition_deletion_title: 'Should we have this artists on our app?',

    activateLoading: function() {
        $('#' + feedback_modal.addition_deletion_body_id).hide();
        $('#' + feedback_modal.simple_message_body_id).hide();
        $('#feedback_modal .modal-body').spin();
    },

    deactivateLoading: function() {
        $('#feedback_modal .modal-body').spin(false);
    },

    onActivation: function() {

        feedback_modal.activateLoading();

        // Remove handlers from buttons that could be attached to them from the last call of this function.
        feedback_modal_vote_buttons.deactivate();

        FB.getLoginStatus(function(response) {

            if (response.status != 'connected') {
                feedback_modal.deactivateLoading();
                feedback_modal.showNoAuthMessage();

            } else {

                if (user_voting.submission_types_left().length === 0) {

                    feedback_modal.deactivateLoading();
                    feedback_modal.showNoSubmittionMessage();
                } else {

                    submission_type = user_voting.getRandomTypeOfSubmission();

                    if ((submission_type == 'addition') || (submission_type == 'deletion')) {
                        $('#' + feedback_modal.title_id).text(feedback_modal.addition_deletion_title);
                    }

                    $.when(
                        user_voting.setSubmissionsUserAlreadyVoted()
                    ).then(function(response) {
                        user_voting.getSubmissionToVote(submission_type, user_voting.submissions_user_already_voted[submission_type], 1);
                    });

                }
            }
        });
    },

    fillWithArtistInformation: function(submission_type, artist_name, id) {

        var ajax_call = $.getJSON(base_url + '/artist/' + artist_name);

        ajax_call.then(function(artist_info) {

            var artist_country = artist_info.country;
            var artist_name = artist_info.name;
            var artist_genre = titleCaps(artist_info.style);
            var picture = artist_info.picture_url;
            var lastfm_url = artist_info.lastfm_url;

            $('#feedback_modal_artist_picture').attr('src', picture);
            $('#feedback_modal_artist_name').text(artist_name);
            $('#feedback_modal_country_name').text(artist_country);
            $('#feedback_modal_genre').text(artist_genre);
            $('#feedback_modal_lastfm_logo_link').attr('href', lastfm_url);

            feedback_modal.deactivateLoading();
            $('#' + feedback_modal.addition_deletion_body_id).show();
            feedback_modal_vote_buttons.activate(submission_type, id);

        });
    },

    showSimpleMessage: function(message) {
        $('#' + feedback_modal.simple_message_body_id).show().text(message);
    },

    showNoAuthMessage: function() {
        feedback_modal.showSimpleMessage(feedback_modal.no_auth_message);
    },

    showNoSubmittionMessage: function() {
        feedback_modal.showSimpleMessage(feedback_modal.no_submissions_message);
    }
};

// ######## WarningModal #############

var warning_modal = {
    id: '#warning_modal',
    body_text_id: '#warning_modal_body_text',

    activate: function() {
        $(warning_modal.id).modal();
    },
    writeTextOnBody: function(text) {
        $(warning_modal.body_text_id).text(text);
    },
    activateWithText: function(text) {
        warning_modal.activate();
        warning_modal.writeTextOnBody(text);
    }
};


// #### Edition Modal ####

var editionModal = {
    id: 'edition_modal',
    input_id: 'edition_modal_input',
    form_id: 'edition_modal_form',
    submitted_screen_id: 'edition_modal_submitted_screen',
    param_changing: '',
    onClose: function() {

        edition_modal_submit_button.deactivate();

        $('#' + editionModal.id).off();
        $('#' + editionModal.input_id).autocomplete('destroy');
        $('#' + editionModal.input_id).removeAttr('name type');
        $('#' + editionModal.form_id).spin(false);

    },

    activate: function(id_btn_pressed) {

        $('#' + editionModal.input_id).val('');
        $('#' + editionModal.form_id).show();
        $('#' + editionModal.submitted_screen_id).hide();
        $('#' + editionModal.id).modal();

        $('#' + editionModal.id).on('hidden.bs.modal', function(){
             editionModal.onClose();
        });

        var text_to_show_on_header;
        var edition_input_id;
        var edition_input_type;

        if (id_btn_pressed == 'country_edition_btn') {
            text_to_show_on_header = 'country';
            edition_input_name = 'country';
            edition_input_type = 'text';
            editionModal.param_changing = 'country';

            activateCountriesAutocomplete();

        } else if (id_btn_pressed == 'facebook_edition_btn') {
            text_to_show_on_header = 'Facebook URL';
            edition_input_name = 'facebook';
            edition_input_type = 'url';
            editionModal.param_changing = 'facebook_url';

        } else if (id_btn_pressed == 'genre_edition_btn') {
            text_to_show_on_header = 'genre';
            edition_input_name = 'genre';
            edition_input_type = 'text';
            editionModal.param_changing = 'style';
        }

        $('#attribute_being_edited_edition_modal_header').text(text_to_show_on_header);
        $('#' + editionModal.input_id).attr('name', edition_input_name);
        $('#' + editionModal.input_id).attr('type', edition_input_type);

        edition_modal_submit_button.activate();
    }
};

var user_voting = {
    submissions_user_not_want_to_vote: {addition: [], deletion: []},
    submission_types_without_votes: [],
    submission_types: ['addition', 'deletion'],
    submission_types_left: function() {return $(user_voting.submission_types).not(user_voting.submission_types_without_votes).get();},
    submissions_user_already_voted: {},


    getRandomTypeOfSubmission: function() {
        // Returns a string with a random type of submission, from the ones that still have votes

        return user_voting.submission_types_left()[Math.floor(Math.random()*user_voting.submission_types_left().length)];
    },

    setSubmissionsUserAlreadyVoted: function() {

        var dfd = new $.Deferred();

        var service_url = base_url + '/user/' + FB.getUserID() + '/votes';

        var ajax_call = $.get(service_url);

        ajax_call.then(function(response) {

            $.each(user_voting.submission_types, function(i, type) {

                var ids = [];
                $.each(response[type + '_votes'], function(j, vote) {
                    ids.push(vote[type + '_id']);
                });
                user_voting.submissions_user_already_voted[type] = ids;

            });

            dfd.resolve();

        });

        return dfd.promise();
    },

    getSubmissionToVote: function(submission_type, list_of_submissions_ids, page) {

        var ajax_call = $.getJSON(base_url + '/pending_' + submission_type + '/?limit=1&page=' + page);

        ajax_call.then(function(content) {

            if ($.isEmptyObject(content)) {
                feedback_modal.deactivateLoading();
                user_voting.submission_types_without_votes.push(submission_type);
                feedback_modal.onActivation();

            } else {
                if (submission_type == 'addition') {
                    submission_info = content.pending_additions[0];
                } else {
                    submission_info = content.pending_deletions[0];
                }

                var submission_id = submission_info.id;

                if (($.inArray(submission_id, list_of_submissions_ids) != -1) || ($.inArray(submission_id, user_voting.submissions_user_not_want_to_vote[submission_type])) != -1 ) {
                    // If user already voted on this submissions or skipped it, try next one.
                    user_voting.getSubmissionToVote(submission_type, list_of_submissions_ids, page + 1);
                } else {
                    var submission_artist_name = submission_info.artist_name;
                    feedback_modal.fillWithArtistInformation(submission_type, submission_artist_name, submission_id);
                }
            }
        });
    }
};
