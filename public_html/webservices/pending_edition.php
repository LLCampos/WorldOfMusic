<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/pending_submissions_functions.php";


$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
header("access-control-allow-origin: *");

if (sizeof($path_params) > 1) {
    $edition_id = $path_params[1];
    if (!submissionIDExists('edition', $edition_id)) {
        $response = "There's no Pending Edition with that ID!";
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
* @api {get} /pending_edition Get list of Pending Editions
* @apiName GetPendingEditions
* @apiGroup Pending Edition
* @apiVersion 0.0.1
*
* @apiParam {Number} [limit=20] Number of Pending Editions to be returned
* @apiParam {String="date_asc", "date_desc", "random"} [order="date_asc"]
* @apiParam {Number} [page=1] Page to be returned
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Edition
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being edited
* @apiSuccess (200 OK) {String} attribute_changing Attribute of which an edition was requested
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of adding the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against adding the Artist
* @apiSuccess (200 OK) {String} added_by  ID of the user who edited the Artist
*
*
* @apiExample Getting the more recent Pedding Edition:
*             GET /pending_edition/limit=1&order=data_desc
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "pending_editions": [
*         {
*           "id": ​7,
*           "artist_name": "Megadeth",
*           "attribute_changing": "style",
*           "positive_votes": ​0,
*           "negative_votes": ​0,
*           "added_by": "42",
*           "creation_time": "2016-05-16 16:39:16"
*         },
*         {
*           "id": ​8,
*           "artist_name": "Elena Roger",
*           "attribute_changing": "facebook_url",
*           "positive_votes": ​0,
*           "negative_votes": ​0,
*           "added_by": "42",
*           "creation_time": "2016-05-16 16:48:10"
*         },
*         (...)
*     ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <?xml version="1.0"?>
* <pending_editions>
*     <pending_edition>
*         <id>7</id>
*         <artist_name>Megadeth</artist_name>
*         <attribute_changing>style</attribute_changing>
*         <positive_votes>0</positive_votes>
*         <negative_votes>0</negative_votes>
*         <added_by>42</added_by>
*         <creation_time>2016-05-16 16:39:16</creation_time>
*     </pending_edition>
*     <pending_edition>
*         <id>8</id>
*         <artist_name>Elena Roger</artist_name>
*         <attribute_changing>facebook_url</attribute_changing>
*         <positive_votes>0</positive_votes>
*         <negative_votes>0</negative_votes>
*         <added_by>42</added_by>
*         <creation_time>2016-05-16 16:48:10</creation_time>
*     </pending_edition>
* </pending_editions>
*/

/**
* @api {get} /pending_edition/:pending_edition_id Get information about Pending Edition
* @apiName GetPendingEdition
* @apiGroup Pending Edition
* @apiVersion 0.0.1
*
* @apiParam {String} pending_edition_id ID of the Pending Edition
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Edition
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being edited
* @apiSuccess (200 OK) {String} attribute_changing Attribute of which an edition was requested
* @apiSuccess (200 OK) {String} old_value The value of the attribute that is being replaced
* @apiSuccess (200 OK) {String} new_value The new proposal for the value of the attribute
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of adding the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against adding the Artist
* @apiSuccess (200 OK) {String} added_by  ID of the user who edited the Artist
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "id": ​7,
*     "artist_name": "Megadeth",
*     "attribute_changing": "style",
*     "old_value": "thrash metal",
*     "new_value": "heavy metal",
*     "positive_votes": ​0,
*     "negative_votes": ​0,
*     "added_by": "42",
*     "creation_time": "2016-05-16 16:39:16"
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <pending_edition>
*     <id>7</id>
*     <artist_name>Megadeth</artist_name>
*     <attribute_changing>style</attribute_changing>
*     <old_value>thrash metal</old_value>
*     <new_value>heavy metal</new_value>
*     <positive_votes>0</positive_votes>
*     <negative_votes>0</negative_votes>
*     <added_by>42</added_by>
*     <creation_time>2016-05-16 16:39:16</creation_time>
* </pending_edition>
*/

/**
* @api {post} /pending_edition/:pending_edition_id/:type_of_vote Vote on one Pending Edition
* @apiName VotePendingEdition
* @apiGroup Pending Edition
* @apiVersion 0.0.1
*
* @apiParam {String} pending_edition_id ID of the Pending Edition
* @apiParam {String = "positive_vote", "negative_vote"} type_of_vote Type of vote you want to add
*
*/

?>
