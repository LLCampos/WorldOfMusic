<?php

#### Utility Functions ####

function getListOfTagsToIgnore() {
    # Returns an array with all the values in TagsToIgnoreTable

    include "/home/aw008/database/connect_to_database.php";

    $sql = "SELECT tag FROM TagsToIgnore;";

    $tags_to_ignore = $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);

    include "/home/aw008/database/disconnect_database.php";

    return $tags_to_ignore;
}

function addInfoToArtist($artist_array) {
    # Adds info to the artist array given as argument and returns the new array.

    include_once "/home/aw008/database/lastfm_api/lastfm_functions.php";

    $artist_name = $artist_array['name'];
    $rsp_obj = connectToArtistGetInfoLastFMMethodByName($artist_name);

    # if there were no errors in the call to lastfm
    if ($rsp_obj) {

        $artist_obj = $rsp_obj->artist;

        # add info to artist array
        $url = $artist_obj->url;
        $tag = strtolower($artist_obj->tags->tag[0]->name);

        $tags_to_ignore = getListOfTagsToIgnore();

        # ignore tags that are not relevant unless the artist only have non-relevant tags
        $i = 0;
        while (in_array($tag, $tags_to_ignore) && array_key_exists($i,$artist_obj->tags->tag)) {
            $tag = strtolower($artist_obj->tags->tag[$i]->name);
            $i += 1;
        }

        $listeners = $artist_obj->stats->listeners;
        $image_big = $artist_obj->image[2]->{"#text"};
        $bio = $artist_obj->bio->content;

        if (!array_key_exists('name', $artist_array)) {
            $name = $artist_obj->name;
            $artist_array['name'] = $name;
        } elseif (!array_key_exists('mbid', $artist_array)) {
            $mbid = $artist_obj->mbid;
            $artist_array["mbid"] = $mbid;
        }

        $artist_array['url'] = $url;
        $artist_array['tag'] = $tag;
        $artist_array['listeners'] = $listeners;
        $artist_array['image_big'] = $image_big;
        $artist_array['bio'] = $bio;

        include_once "/home/aw008/database/facebook_api/facebook_api_functions.php";

        $artist_array["facebook_id"] = obtainFacebookPageID($artist_name);
        $artist_array["number_of_facebook_likes"] = numberofFacebookLikes($artist_array["facebook_id"]);

        include_once "/home/aw008/database/youtube_api/search_video.php";

        $artist_array['music_video'] = searchTopVideo($artist_name);

        return $artist_array;
    } else {
        return false;
    }
}


function getListOfArtists($list_of_countries, $number_of_artists_per_country) {
    /* Returns an array of arrays, each subarray representing one artist.
    $list_of_countries is an array with names of countries.
    $number_of_artists_per_country is an int.
    */

    $list_of_artists = array();

    foreach ($list_of_countries as $country) {

        include_once "/home/aw008/database/lastfm_api/lastfm_functions.php";

        $array_of_artists = getArtistsByCountry($country, $number_of_artists_per_country);

        foreach ($array_of_artists as $artist) {
            $artist_info = addInfoToArtist($artist);
            $list_of_artists[] = $artist_info;
        }

        # makes an 10 second pause so that lastfm guys don't get upset with us
        sleep(10);
    }

    return $list_of_artists;
}

function populateArtistsTable($artist_array) {
    /* Inserts artist in Artist table.

    $artist_array is an array with info about the artist.*/

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("insert into Artist (name, country_fk, picture_url, bibliography, style, music_video, lastfm_url, number_of_lastfm_listeners, musicbrainz_id, facebook_id, number_of_facebook_likes, Deleted)
    values (:name, :country_fk, :picture_url, :bibliography, :style, :music_video, :lastfm_url, :number_of_lastfm_listeners, :musicbrainz_id, :facebook_id, :number_of_facebook_likes, 0);");

    $sql->bindParam(':name', $artist_array['name']);
    $sql->bindParam(':picture_url', $artist_array['image_big']);
    $sql->bindParam(':bibliography', $artist_array['bio']);
    $sql->bindParam(':style', $artist_array['tag']);
    $sql->bindParam(':music_video', $artist_array['music_video']);
    $sql->bindParam(':lastfm_url', $artist_array['url']);
    $sql->bindParam(':number_of_lastfm_listeners', $artist_array['listeners']);
    $sql->bindParam(':musicbrainz_id', $artist_array['mbid']);
    $sql->bindParam(':facebook_id', $artist_array['facebook_id']);
    $sql->bindParam(':number_of_facebook_likes', $artist_array['number_of_facebook_likes']);

    include_once "/home/aw008/database/utility_functions/country_utility_functions.php";
    $country = $artist_array['country'];
    $country_id = getIDFromCountryCode($country);
    $sql->bindParam(':country_fk', $country_id);

    try {
        $sql->execute();
        return true;
    } catch(PDOException $e) {
        echo $sql . " " . $e->getMessage() . "\n";
    }

    include "/home/aw008/database/disconnect_database.php";
}

function populateArtistTableFromListOfCountries($list_of_countries, $number_of_artists_per_country) {
    /* Inserts $number_of_artists_per_country artists by each country in $list_of_countries array in the Artist table. */

    foreach ($list_of_countries as $country) {

        include_once "/home/aw008/database/lastfm_api/lastfm_functions.php";

        $array_of_artists = getArtistsByCountry($country, $number_of_artists_per_country);

        $list_of_artists = [];

        foreach ($array_of_artists as $artist) {
            $artist_info = addInfoToArtist($artist);
            sleep(1);
            $list_of_artists[] = $artist_info;
        }

        foreach ($list_of_artists as $artist_array) {
            populateArtistsTable($artist_array);
        }
    }
}


function insertArtistInTable($artist_name, $country_code) {
    /* Inserts artist in the Artist table.

    Requires: $artist_name is a string representing the name of a artist,
    $country_code is a string representing the name of a country.
    Ensures: if $artist_name is a valid lastfm artist, inserts artist in Artist table. If not,
    returns false. */

    $artist_array = array("name" => $artist_name,
                          "country" => $country_code);

    $artist_array = addInfoToArtist($artist_array);

    if ($artist_array) {
        populateArtistsTable($artist_array);
        return true;
    } else {
        return false;
    }
}

function fillDatabaseWithArtistsFromDBPedia($list_of_countries) {

    include_once "/home/aw008/database/utility_functions/country_utility_functions.php";
    include_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
    include_once "/home/aw008/database/dbpedia_sparql/dbpedia_functions.php";

    foreach ($list_of_countries as $country) {
        print_r($country . "\n");
        $list_of_artists = getListOfArtistsFromDBPediaFromCountry($country, 100);

        foreach ($list_of_artists as $artist) {
            if (!alreadyInTable($artist)) {
                print_r($artist. "\n");
                insertArtistInTable($artist, $country);
                sleep(1);
            }
        }

    }
}


# function insertArtistInTableByMID($mid, $country) {
#     /* Inserts artist in the Artist table.
#
#     Requires: $mid is a string representing the musicbrainz id of the artist,
#     $country is a string representing the name of a country.
#     Ensures: if $mid is a valid lastfm artist, inserts artist in Artist table. If not,
#     returns false. */
#
#
#     $artist_array = array("musicbrainz_id" => $mid,
#                           "country" => $country);
#
#     $artist_array = addInfoToArtist($artist_array);
#
#     if ($artist_array) {
#         populateArtistsTable($artist_array);
#         return true;
#     } else {
#         return false;
#     }
# }

    #function populateArtistTableFromMusicbrainz() {
    #    // Fills the database with all the artists in the Musicbrainz database. TAKES TOO LONG
#
    #    include "/home/aw008/database/musicbrainz_api/musicbrainz_functions.php";
    #    include "/home/aw008/database/utility_functions/country_utility_functions.php";
    #    include "/home/aw008/database/utility_functions/artist_utility_functions.php";
#
    #    $list_of_countrycodes = outPutCountryCodesList();
#
    #    foreach ($list_of_countrycodes as $country_code) {
    #        $list_of_artists = getAllMusicbrainzArtistFromCountry($country_code);
#
    #        foreach ($list_of_artists as $artist_array) {
#
    #            $artist_array = addInfoToArtist($artist_array);
#
    #            # To control number of calls/minute.
    #            # sleep(1);
#
    #            if ($artist_array) {
    #                if (!alreadyInTable($artist_array['name'])) {
    #                    populateArtistsTable($artist_array);
    #                } else {
    #                    echo "Already in table";
    #                }
    #            }
#
    #        }
    #    }
    #}

?>

