<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/country_functions.php";

$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];


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
* @apiParam {String="likes", "random"} [order]
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
*     {
*       "name": "Jorge Palma"
*     },
*     {
*       "name": "Ena P\u00e1 2000"
*     },
*     {
*       "name": "Capicua"
*     },
*     {
*       "name": "Regula"
*     },
*     {
*       "name": "Virgem Suta"
*     }
*   ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <artists>
*     <artist>
*         <name>Jorge Palma</name>
*     </artist>
*     <artist>
*         <name>Ena P&#xE1; 2000</name>
*     </artist>
*     <artist>
*         <name>Capicua</name>
*     </artist>
*     <artist>
*         <name>Regula</name>
*     </artist>
*     <artist>
*         <name>Virgem Suta</name>
*     </artist>
* </artists>
*/

?>
