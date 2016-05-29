<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/country_functions.php";

$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
header("access-control-allow-origin: *");


if (sizeof($path_params) > 1) {
    $country = strtolower($path_params[1]);
    $country = urldecode($country);

    include_once "/home/aw008/database/utility_functions/country_utility_functions.php";

    if (!countryExists($country)) {
        $response = 'Country does not exist';
        simpleResponse($response, $outputType, 404);
    }
}

# Ser o método utilizado para aceder a este webservice fôr o GET...
if ($REQUEST_METHOD == 'GET') {

    # Se o link é /country enviar lista de países
    if (sizeof($path_params) == 0) {
		outPutCountriesListService($outputType);
        exit;
	}

    # Se o link é /country/{nome_do_país} enviar info sobre o país
    elseif (sizeof($path_params) == 2) {
		outPutCountryInfo($country, $outputType);
        exit;
	}

    # Se o link é /country/{nome_do_país}/artists enviar lista de artistas do país
    elseif (sizeof($path_params) == 3) {
        if ($path_params[2] == 'artists') {
            outPutListOfArtists($country, $outputType);
            exit;
        }
    }
} elseif (sizeof($path_params) < 4) {
    if ($path_params[2] == 'artists') {
        http_response_code(405);
        header("Allow: GET");
        exit;
    }
}

$response = "Some error has ocurred. Check your syntax.";
simpleResponse($response, $outputType, 400);

####################################   DOCUMENTATION #####################################################

/**
* @api {get} /country Get list of Countries
* @apiName GetCountries
* @apiGroup Country
* @apiVersion 0.0.1
*
*
* @apiSuccess (200 OK) {String} name Name of the Country
* @apiSuccess (200 OK) {String} url Path to the Country resource
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "countries": [
*   {
*     "code": "af"
*     "name": "Afghanistan",
*     "url": "/country/Afghanistan"
*    },
*    {
*     "code": "ax"
*     "name": "Åland Islands",
*     "url": "/country/Åland Islands"
*     },
* (...)
*    ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <countries>
*     <country>
*         <code>af</code>
*         <name>Afghanistan</name>
*         <url>/country/Afghanistan</url>
*     </country>
*     <country>
*         <code>ax</code>
*         <name>Åland Islands</name>
*         <url>/country/Åland Islands</url>
*     </country>
*     (...)
* </countries>
*/

/**
* @api {get} /country/:country_code Get information about Country
* @apiName GetCountry
* @apiGroup Country
* @apiVersion 0.0.1
*
* @apiParam {String} country_code ISO 3166-1 alpha-2 code for the country
*
* @apiSuccess (200 OK) {String} name Name of the Country
* @apiSuccess (200 OK) {String} name_alpha2 ISO 3166-1 alpha-2 code for the country
* @apiSuccess (200 OK) {String} flag_img_url URL to an image of the country flag
* @apiSuccess (200 OK) {String} capital Name of the country's capital
* @apiSuccess (200 OK) {Number} population Number of people in the country
* @apiSuccess (200 OK) {String} region Name of the country's continent
* @apiSuccess (200 OK) {String} subregion Name of the subregion of the country
* @apiSuccess (200 OK) {String} description_of_music Description of the music in the country
*
*
*
* @apiSuccessExample {json} Success Response (JSON):
*     {
*         "name": "China",
*         "name_alpha2": "cn",
*         "flag_img_url": "http://www.geonames.org/flags/x/cn.gif",
*         "capital": "Beijing",
*         "population": ​1371590000,
*         "region": "Asia",
*         "subregion": "Eastern Asia",
*         "description_of_music": "Music of China refers to the music of the (...)"
*     }
*
* @apiSuccessExample {xml} Success Response (XML)
* <country>
*     <name>China</name>
*     <name_alpha2>cn</name_alpha2>
*     <flag_img_url>http://www.geonames.org/flags/x/cn.gif</flag_img_url>
*     <capital>Beijing</capital>
*     <population>1371590000</population>
*     <region>Asia</region>
*     <subregion>Eastern Asia</subregion>
*     <description_of_music>Music of China refers to the music of (...) </description_of_music>
* </country>
*/

/**
* @api {get} /country/:name_of_country/artists Get list of Artists of Country
* @apiName GetCountryArtists
* @apiGroup Country
* @apiVersion 0.0.1
*
* @apiParam {String} name_of_country Name of the Country
* @apiParam {Number} [limit=20] Number of Artists/Group to be returned
* @apiParam {String="likes", "random", "lastfm"} [order]
* @apiParam {Number} [page=1] Page to be returned
*
* @apiSuccess (200 OK) {String} name Name of the Artist
*
* @apiExample Getting a random list of 5 Portuguese Artists:
*             GET /country/Portugal/artists?limit=5&order=random
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*   "artist": [
*       {
*           "name": "Jorge Palma",
*           "style": "singer-songwriter",
*           "country": "Portugal",
*           "picture_url": "http://img2-ak.lst.fm/i/u/174s/3e48b70219b24a03a7e7bc8cee06de9f.png",
*           "lastfm_url": "http://www.last.fm/music/Jorge+Palma",
*           "number_of_lastfm_listeners": 23591,
*           "music_video": "NgUPRDIwu1U",
*           "facebook_id": "288425643789",
*           "number_of_facebook_likes": 239059,
*           "twitter_url": null,
*           "number_of_twitter_followers": null,
*           "musicbrainz_id": "386c9f8f-31d8-4815-b4c5-7c875f96c2b0"
*       },
*       {
*
*           "name": "Ena Pá 2000",
*           "style": "rock",
*           "country": "Portugal",
*           "picture_url": "http://img2-ak.lst.fm/i/u/174s/dd106ce8a47a45e486e6ffe890ae3eb8.png",
*           "lastfm_url": "http://www.last.fm/music/+noredirect/Ena+P%C3%A1+2000",
*           "number_of_lastfm_listeners": 4971,
*           "music_video": "CR6K5iaAHho",
*           "facebook_id": "137403672998884",
*           "number_of_facebook_likes": 4435,
*           "twitter_url": null,
*           "number_of_twitter_followers": null,
*           "musicbrainz_id": "4dc776c2-a16e-46d8-8a81-8c63804f373f"
*       }
*   ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <artists>
*    <artist>
*        <name>Jorge Palma</name>
*        <style>singer-songwriter</style>
*        <country>Portugal</country>
*        <picture_url>http://img2-ak.lst.fm/i/u/174s/3e48b70219b24a03a7e7bc8cee06de9f.png</picture_url>
*        <lastfm_url>http://www.last.fm/music/Jorge+Palma</lastfm_url>
*        <number_of_lastfm_listeners>23591</number_of_lastfm_listeners>
*        <music_video>NgUPRDIwu1U</music_video>
*        <facebook_id>288425643789</facebook_id>
*        <number_of_facebook_likes>239059</number_of_facebook_likes>
*        <twitter_url/>
*        <number_of_twitter_followers/>
*        <musicbrainz_id>386c9f8f-31d8-4815-b4c5-7c875f96c2b0</musicbrainz_id>
*    </artist>
*    <artist>
*        <name>Ena P&#xE1; 2000</name>
*        <style>rock</style>
*        <country>Portugal</country>
*        <picture_url>http://img2-ak.lst.fm/i/u/174s/dd106ce8a47a45e486e6ffe890ae3eb8.png</picture_url>
*        <lastfm_url>http://www.last.fm/music/+noredirect/Ena+P%C3%A1+2000</lastfm_url>
*        <number_of_lastfm_listeners>4971</number_of_lastfm_listeners>
*        <music_video>CR6K5iaAHho</music_video>
*        <facebook_id>137403672998884</facebook_id>
*        <number_of_facebook_likes>4435</number_of_facebook_likes>
*        <twitter_url/>
*        <number_of_twitter_followers/>
*        <musicbrainz_id>4dc776c2-a16e-46d8-8a81-8c63804f373f</musicbrainz_id>
*    </artist>
* </artists>
*/

?>
