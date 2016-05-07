<?php

function connectToLastFM($params) {
    # Makes a call to Lastfm API and returns results.

    require "/home/aw008/variables/sensible_info.php";

    $encoded_params = array();

    foreach ($params as $k => $v){
      $encoded_params[] = urlencode($k).'='.urlencode($v);
    }

    $url = "http://ws.audioscrobbler.com/2.0/?".implode('&', $encoded_params);

    $rsp = file_get_contents($url);
    $rsp_obj = json_decode($rsp);

    if  (property_exists($rsp_obj, 'error')) {
        return false;
    }
    else {
        return $rsp_obj;
    }

}

function connectToArtistGetInfoLastFMMethodByName($artist_name) {
    /* Makes a call to Lastfm's artist.getInfo method. If there is an error,
    returns false. Otherwise, returns a PHP object resulting from the decoding
    of the response.

    Requires: $artist_name is a string representing the name of a artist. */

    require "/home/aw008/variables/sensible_info.php";

    $params = array(
        'api_key' => $lastfm_api_key,
        'method' => "artist.getInfo",
        'artist' => $artist_name,
        'format' => 'json'
        );

    $result = connectToLastFM($params);

    return $result;
}

function connectToArtistGetInfoLastFMMethodByMID($mid) {
    /* Makes a call to Lastfm's artist.getInfo method. If there is an error,
    returns false. Otherwise, returns a PHP object resulting from the decoding
    of the response.

    Requires: $mid is a string representing the musicbrainz id of the artist. */

    require "/home/aw008/variables/sensible_info.php";

    $params = array(
        'api_key' => $lastfm_api_key,
        'method' => "artist.getInfo",
        'mbid' => $mid,
        'format' => 'json'
        );

    $result = connectToLastFM($params);

    return $result;
}


function getArtistsByCountry($country, $number_of_artists) {
    /* Gets a list of artists of a country.

    Requires: $country is a string with the name of a country in uppercase.
    $number_of_artists is an int with the number of artists to be in the output array.

    Ensures: an array of arrays, each sub-array having two key -> value pair, one of them having as key "name"
    and the value the name of the artist and the other as key "country" and the value the country given.
    The output array is ordered by lastfm tag count.

    */

    require "/home/aw008/variables/sensible_info.php";

    $params = array(
        'api_key' => $lastfm_api_key,
        'method' => "tag.getTopArtists",
        'limit' => $number_of_artists,
        'tag' => $country,
        'format' => 'json'
        );

    $encoded_params = array();

    foreach ($params as $k => $v){
      $encoded_params[] = urlencode($k).'='.urlencode($v);
    }

    $url = "http://ws.audioscrobbler.com/2.0/?".implode('&', $encoded_params);

    $rsp = file_get_contents($url);
    $rsp_obj = json_decode($rsp);

    $artists_array = $rsp_obj->topartists->artist;

    $array_of_artists = array();

    foreach ($artists_array as $k => $v) {
         $artist_name = $v->name;
         $array_of_artists[] = array('name' => $artist_name, 'country' => $country);
    }

    return $array_of_artists;
}


?>
