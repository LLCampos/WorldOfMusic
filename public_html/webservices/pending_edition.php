<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/pending_submissions_functions.php";


$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

if (sizeof($path_params) > 1) {
    $edition_id = $path_params[1];
    if (!submissionIDExists('edition', $edition_id)) {
        $response = "There's no Pending Addition with that ID!";
        simpleResponse($response, $outputType, 404);
    }
}


if ($REQUEST_METHOD == 'GET') {

    # /pending_edition
    if (sizeof($path_params) == 0) {
      GETPendingSubmissions('edition', $outputType);
      exit;

    # /pending_edition/{id}
    } elseif (sizeof($path_params) == 2) {
        GETOnePendingSubmission('edition', $edition_id, $outputType);
        exit;

    # /pending_edition/{id}/{positive_vote or negative_vote}
    } elseif (sizeof($path_params) == 3) {
        echo 'not implemented. yet.';
    }

} elseif ($REQUEST_METHOD == 'POST') {

    # /pending_edition/{id}/{positive_vote or negative_vote}
    if (sizeof($path_params) == 3) {
        $user_id = checkAuthentication($_REQUEST, $outputType);
        $edition_id = $path_params[1];
        $type_of_vote = $path_params[2];
        POSTPendingSubmission('edition', $edition_id, $user_id, $type_of_vote);
        exit;
    }
}

$response = "Some error has ocurred. Check your syntax.";
simpleResponse($response, $outputType, 400);


################# DOCUMENTATION ################################

/**
* @api {get} /pending_edition Get list of Pending Additions
* @apiName GetPendingAdditions
* @apiGroup Pending Addition
* @apiVersion 0.0.1
*
* @apiParam {Number} [limit=20] Number of Pending Additions to be returned
* @apiParam {String="date_asc", "date_desc", "random"} [order="date_asc"]
* @apiParam {Number} [page=1] Page to be returned
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Addition
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being added
* @apiSuccess (200 OK) {String} country_name Country of the Artist being added
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of adding the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against adding the Artist
* @apiSuccess (200 OK) {String}  added_by  ID of the user who added the Artist
*
*
* @apiExample Getting the more recent Pedding Addition:
*             GET /pending_edition/limit=1&order=data_desc
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "pending_editions": [
*         {
*             "id": ​6,
*             "artist_name": "Megadeth",
*             "country_name": "Afghanistan",
*             "positive_votes": ​0,
*             "negative_votes": ​0,
*             "added_by": "10201440175723123",
*             "creation_time": "2016-05-02 20:43:28"
*         },
*         {
*             "id": ​8,
*             "artist_name": "Slayer",
*             "country_name": "United States",
*             "positive_votes": ​0,
*             "negative_votes": ​0,
*             "added_by": "10201440175723123",
*             "creation_time": "2016-05-03 14:27:07"
*         },
*         (...)
*     ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <pending_editions>
*     <pending_edition>
*         <id>6</id>
*         <artist_name>Megadeth</artist_name>
*         <country_name>Afghanistan</country_name>
*         <positive_votes>0</positive_votes>
*         <negative_votes>0</negative_votes>
*         <added_by>10201440175723123</added_by>
*         <creation_time>2016-05-02 20:43:28</creation_time>
*     </pending_edition>
*     <pending_edition>
*         <id>8</id>
*         <artist_name>Slayer</artist_name>
*         <country_name>United States</country_name>
*         <positive_votes>0</positive_votes>
*         <negative_votes>0</negative_votes>
*         <added_by>10201440175723123</added_by>
*         <creation_time>2016-05-03 14:27:07</creation_time>
*     </pending_edition>
*     (...)
* </pending_editions>
*/

/**
* @api {get} /pending_edition/:pending_edition_id Get information about Pending Addition
* @apiName GetPendingAddition
* @apiGroup Pending Addition
* @apiVersion 0.0.1
*
* @apiParam {String} pending_edition_id ID of the Pending Addition
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Addition
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being added
* @apiSuccess (200 OK) {String} country_name Country of the Artist/Group being added
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of adding the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against adding the Artist
* @apiSuccess (200 OK) {String}  added_by  ID of the user who added the Artist
*
*
* @apiSuccessExample {json} Success Response (JSON):
*   {
*       "id": ​6,
*       "artist_name": "Megadeth",
*       "country_name": "Afghanistan",
*       "positive_votes": ​0,
*       "negative_votes": ​0,
*       "added_by": "10201440175723123",
*       "creation_time": "2016-05-02 20:43:28"
*   }
*
* @apiSuccessExample {xml} Success Response (XML)
*   <pending_edition>
*       <id>6</id>
*       <artist_name>Megadeth</artist_name>
*       <country_name>Afghanistan</country_name>
*       <positive_votes>0</positive_votes>
*       <negative_votes>0</negative_votes>
*       <added_by>10201440175723123</added_by>
*       <creation_time>2016-05-02 20:43:28</creation_time>
*   </pending_edition>
*/

/**
* @api {post} /pending_edition/:pending_edition_id/:type_of_vote Vote on one Pending Addition
* @apiName VotePendingAddition
* @apiGroup Pending Addition
* @apiVersion 0.0.1
*
* @apiParam {String} pending_edition_id ID of the Pending Addition
* @apiParam {String = "positive_vote", "negative_vote"} type_of_vote Type of vote you want to add
*
*/

?>