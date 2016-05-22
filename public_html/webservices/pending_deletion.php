<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/pending_submissions_functions.php";


$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
header("access-control-allow-origin: *");

if (sizeof($path_params) > 1) {
    $deletion_id = $path_params[1];
    if (!submissionIDExists('deletion', $deletion_id)) {
        $response = "There's no Pending Deletion with that ID!";
        simpleResponse($response, $outputType, 404);
    }
}


if ($REQUEST_METHOD == 'GET') {

    # /pending_deletion
    if (sizeof($path_params) == 0) {
      GETPendingSubmissions('deletion', $outputType);
      exit;

    # /pending_deletion/{id}
    } elseif (sizeof($path_params) == 2) {
        GETOnePendingSubmission('deletion', $deletion_id, $outputType);
        exit;

    # /pending_deletion/{id}/{positive_vote or negative_vote}
    } elseif (sizeof($path_params) == 3) {
        echo 'not implemented. yet.';
    }

} elseif ($REQUEST_METHOD == 'POST') {

    # /pending_deletion/{id}/{positive_vote or negative_vote}
    if (sizeof($path_params) == 3) {
        $user_id = checkAuthentication($_REQUEST, $outputType);
        $deletion_id = $path_params[1];
        $type_of_vote = $path_params[2];
        POSTPendingSubmission('deletion', $deletion_id, $user_id, $type_of_vote);
        exit;
    }
}

$response = "Some error has ocurred. Check your syntax.";
simpleResponse($response, $outputType, 400);


################# DOCUMENTATION ################################

/**
* @api {get} /pending_deletion Get list of Pending Deletion
* @apiName GetPendingDeletions
* @apiGroup Pending Deletion
* @apiVersion 0.0.1
*
* @apiParam {Number} [limit=20] Number of Pending Deletions to be returned
* @apiParam {String="date_asc", "date_desc", "random"} [order="date_asc"]
* @apiParam {Number} [page=1] Page to be returned
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Deletion
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being deleted
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of deletion the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against deleting the Artist
* @apiSuccess (200 OK) {String}  added_by  ID of the user who deleted the Artist
*
* @apiExample Getting the more recent Pedding Deletion:
*             GET /pending_deletion/limit=1&order=data_desc
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "pending_deletions": [
*         {
*             id": ​8,
*             "artist_name": "Brazen Abbot",
*             "positive_votes": ​1,
*             "negative_votes": ​0,
*             "added_by": "102503863489106",
*             "creation_time": "2016-05-03 14:29:34"
*         },
*         {
*             "id": ​9,
*             "artist_name": "Gabriel Fliflet",
*             "positive_votes": ​0,
*             "negative_votes": ​1,
*             "added_by": "102503863489106",
*             "creation_time": "2016-05-02 12:59:18"
*         },
*         (...)
*     ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <pending_deletions>
*     <pending_deletion>
*         <id>8</id>
*         <artist_name>Brazen Abbot</artist_name>
*         <positive_votes>1</positive_votes>
*         <negative_votes>0</negative_votes>
*         <added_by>10201440175723123</added_by>
*         <creation_time>2016-05-03 14:29:34</creation_time>
*     </pending_deletion>
*     <pending_deletion>
*         <id>​9</id>
*         <artist_name>Gabriel Fliflet</artist_name>
*         <positive_votes>0</positive_votes>
*         <negative_votes>1</negative_votes>
*         <added_by>10201440175723123</added_by>
*         <creation_time>2016-05-02 12:59:18</creation_time>
*     </pending_deletion>
*     (...)
* </pending_deletions>
*/

/**
* @api {get} /pending_deletion/:pending_deletion_id Get info about Pending Deletion
* @apiName GetPendingDeletion
* @apiGroup Pending Deletion
* @apiVersion 0.0.1
*
* @apiParam {String} pending_deletion_id ID of the Pending Deletion
*
* @apiSuccess (200 OK) {Number} id   ID of the Pending Deletion
* @apiSuccess (200 OK) {String} artist_name Name of the Artist being deleted
* @apiSuccess (200 OK) {Number} positive_votes Number of votes in favor of deletion the Artist
* @apiSuccess (200 OK) {Number} negative_votes Number of votes against deletion the Artist
* @apiSuccess (200 OK) {String}  added_by  ID of the user who asked for the deletion of the Artist
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*    "id": ​9,
*    "artist_name": "Gabriel Fliflet",
*    "positive_votes": ​0,
*    "negative_votes": ​1,
*    "added_by": "102503863489106",
*    "creation_time": "2016-05-03 14:29:34"
* }
*
* @apiSuccessExample {xml} Success Response (XML)
*     <pending_deletion>
*         <id>​9</id>
*         <artist_name>Gabriel Fliflet</artist_name>
*         <positive_votes>0</positive_votes>
*         <negative_votes>1</negative_votes>
*         <added_by>10201440175723123</added_by>
*         <creation_time>2016-05-03 14:29:34</creation_time>
*     </pending_deletion>
*/

/**
* @api {post} /pending_deletion/:pending_deletion_id/:type_of_vote Vote on one Pending Deletion
* @apiName VotePendingDeletion
* @apiGroup Pending Deletion
* @apiVersion 0.0.1
*
* @apiParam {String} pending_deletion_id ID of the Pending Deletion
* @apiParam {String = "positive_vote", "negative_vote"} type_of_vote Type of vote you want to add
*
*/
