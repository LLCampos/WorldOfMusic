<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/artist_functions.php";

$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
header("access-control-allow-origin: *");


$artist_name = $path_params[1];

# Ser o método utilizado para aceder a este webservice fôr o GET, devolve
# informação sobre o artista no url.
if ($REQUEST_METHOD == 'GET') {

    # Se o url tiver o formato (...)/artist.php/{name_of_artist}
    if (sizeof($path_params) == 2) {
      GETArtist($artist_name, $outputType);
      exit;
    } elseif (sizeof($path_params) == 0) {
        echo 'not implemented. yet.';
        exit;
    }

# Se o método utilizado para aceder a este webservice fôr o PUT, faz update de informação do artista.
} elseif ($REQUEST_METHOD == 'PUT') {

    # Se o url tiver o formato (...)/artist.php/{name_of_artist}
    if (sizeof($path_params) == 2) {
        $id = checkAuthentication($_REQUEST, $outputType);
        PUTArtist($artist_name, $_REQUEST, $id, $outputType);
        exit;
    } elseif (sizeof($path_params) == 0) {
        http_response_code(405);
        header('Allow: GET');
        exit;
    }

#
} elseif ($REQUEST_METHOD == 'DELETE') {
    # Se o url tiver o formato /artist/{name_of_artist}
    if (sizeof($path_params) == 2) {
        $user_id = checkAuthentication($_REQUEST, $outputType);
        DELETEArtist($artist_name, $user_id, $outputType);
        exit;
    }

} elseif ($REQUEST_METHOD = 'POST') {
    if (sizeof($path_params) == 2) {
        $id = checkAuthentication($_REQUEST, $outputType);
        POSTArtist($artist_name, $outputType, $id, $_REQUEST);
        exit;
    } elseif (sizeof($path_params) == 0) {
        http_response_code(405);
        header('Allow: GET');
        exit;
    }
}

include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
$response = "Some error has ocurred. Check your syntax.";
simpleResponse($response, $outputType, 400);


####################################   DOCUMENTATION #####################################################


/**
*
* @api {post} /artist/:name_of_artist Submit a new Artist addition request
* @apiName AddArtist
* @apiGroup Artist
* @apiVersion 0.0.1
* @apiDescription Submits a new Artist to approval by other users. In practise, this method creates a new Pending Addition. The Artist will be added if it receive 5 positive votes. If it receives 5 negatives votes it will not.
*
* @apiParam {String} name_of_artist Name of musical Artist to add
* @apiParam {String} country ISO 3166-1 alpha-2 code of the Country
*
* @apiSuccessExample {json} Success Response (JSON):
* {"message":"Request submitted."}
*
*
* @apiSuccessExample {xml} Success Response (XML):
* <message>Request submitted.</message>
*
*/

/**
*
* @api {put} /artist/:name_of_artist Edit information about Artist
* @apiName EditArtist
* @apiGroup Artist
* @apiVersion 0.0.1
* @apiDescription Submits a new edition to approval by other users. In practise, this method creates a new Pending Edition. The edition will be accepted if it receive 5 positive votes. If it receives 5 negatives votes it will not.

You can only request edition of one parameter.
*
*
* @apiParam {String} name_of_artist Name of Artist of which information you want to edit
* @apiParam {String} [style] New music genre played by Artist
* @apiParam {String} [country] New country name or ISO 3166-1 alpha-2 country code of Artist
* @apiParam {Number} [facebook_url] New artist Facebook page URL
*
* @apiExample Example of changing the style of Metallica to Thrash Metal:
*             PUT /artist/Metallica?style=Thrash Metal
*
*/

/**
*
* @api {delete} /artist/:name_of_artist Submit an Artist deletion request
* @apiName DeleteArtist
* @apiGroup Artist
* @apiVersion 0.0.1
* @apiDescription Submits a deletion of Artist to approval by other users. In practise, this method creates a new Pending Deletion. The Artist will be deleted if it receive 5 positive votes. If it receives 5 negatives votes it will not.
*
* @apiParam {String} name_of_artist Name of musical Artist to delete
*
* @apiSuccessExample {json} Success Response (JSON):
* {"message":"Request submitted."}
*
*
* @apiSuccessExample {xml} Success Response (XML):
* <message>Request submitted.</message>
*
*/

/**
*
*
* @api {get} /artist/:artist_name Get information about Artist
* @apiName GetArtistInformation
* @apiGroup Artist
* @apiVersion 0.0.1
*
* @apiParam {String} artist_name Name of Artist
*
* @apiSuccess (200 OK) {String} name Name of Artist
* @apiSuccess (200 OK) {String} style Music genre played by Artist
* @apiSuccess (200 OK) {String} country Country of Artist
* @apiSuccess (200 OK) {String} picture_url Image of Artist
* @apiSuccess (200 OK) {String} lastfm_url  URL to Artist Last.fm page
* @apiSuccess (200 OK) {Number} number_of_lastfm_listeners Number of Artist in Last.fm website
* @apiSuccess (200 OK) {String} biography Short biography Artist
* @apiSuccess (200 OK) {String} music_video  An ID of one Youtube music video from Artist
* @apiSuccess (200 OK) {Number} facebook_id Artist Facebook page ID
* @apiSuccess (200 OK) {Number} number_of_facebook_likes  Number of likes at Artist Facebook Page
* @apiSuccess (200 OK) {String} twitter_url URL to Artist Twitter Page
* @apiSuccess (200 OK) {Number} number_of_twitter_followers  Number of followers of Artist Twitter Page
* @apiSuccess (200 OK) {String} musicbrainz_id Artist Musicbrainz ID
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*   "name": "Capicua",
*   "style": "hip-hop",
*   "country": "Portugal",
*   "picture_url": "http://img2-ak.lst.fm/i/u/174s/4a7fbbf4749645cda4025c1deb829273.png",
*   "lastfm_url": "http://www.last.fm/music/Capicua",
*   "number_of_lastfm_listeners": ​3880,
*   "biography": "About CAPICUA\n\nSe chegaste até aqui (...)",
*   "music_video": null,
*   "facebook_id": "272101826169708",
*   "number_of_facebook_likes": ​80212,
*   "twitter_url": null,
*   "number_of_twitter_followers": null,
*   "musicbrainz_id": "451107dc-7d40-4bd4-86e6-f76e566ff17b"
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <?xml version="1.0"?>
* <artist>
*     <name>Capicua</name>
*     <style>hip-hop</style>
*     <country>Portugal</country>
*     <picture_url>http://img2-ak.lst.fm/i/u/174s/4a7fbbf4749645cda4025c1deb829273.png</picture_url>
*     <lastfm_url>http://www.last.fm/music/Capicua</lastfm_url>
*     <number_of_lastfm_listeners>3880</number_of_lastfm_listeners>
*     <biography>About CAPICUA\n\nSe chegaste até aqui (...)</biography>
*     <music_video/>
*     <facebook_id>272101826169708</facebook_id>
*     <number_of_facebook_likes>80212</number_of_facebook_likes>
*     <twitter_url/>
*     <number_of_twitter_followers/>
*     <musicbrainz_id>451107dc-7d40-4bd4-86e6-f76e566ff17b</musicbrainz_id>
* </artist>
*/



?>
