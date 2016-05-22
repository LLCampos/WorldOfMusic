<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/user_functions.php";
include_once '/home/aw008/database/users/user_table_functions.php';


$outputType = checkClientAcceptMIME();
$path_params = getPathParams();
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
header("access-control-allow-origin: *");

if ($REQUEST_METHOD == 'POST') {

    # POST /user
    if (sizeof($path_params) === 0) {
        $id = checkAuthentication($_REQUEST, $outputType);
        POSTUser($_REQUEST, $outputType);
    };

} elseif ($REQUEST_METHOD == 'GET') {

    if (sizeof($path_params) > 0) {
        $id = $path_params[1];
        if (!userAlreadyOnTable($id)) {
            $response = "User not found.";
            simpleResponse($response, $outputType, 404);
        }
    }

    # GET /user/{id}
    if (sizeof($path_params) === 2) {
        GETUser($id, $outputType);

    # GET /user/{id}/votes
    } elseif (sizeof($path_params) === 3) {
        if ($path_params[2] == 'votes') {
            GETUserVotes($id, $outputType);
        }
    }
}

include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
$response = "Some error has ocurred. Check your syntax.";
simpleResponse($response, $outputType, 400);

/**
* @api {get} /user/:id Get information about User
* @apiName GetUser
* @apiGroup User
* @apiVersion 0.0.1
*
* @apiParam {String} id User ID
*
* @apiSuccess (200 OK) {String} id User ID
* @apiSuccess (200 OK) {String} first_name User's first name
* @apiSuccess (200 OK) {String} last_name  User's last name
* @apiSuccess (200 OK) {String} gender     User's gender
* @apiSuccess (200 OK) {String} locale User country
* @apiSuccess (200 OK) {Number} age_range -
* @apiSuccess (200 OK) {Number} timezone The User's current timezone offset from UTC
* @apiSuccess (200 OK) {Number} pending_ Number of User pending submissions
* @apiSuccess (200 OK) {Number} successful_ Number of User successful submissions
* @apiSuccess (200 OK) {Number} unsuccessful_ Number of User unsuccessful submissions
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "id": "10201440175723123",
*     "first_name": "Luis",
*     "last_name": "Campos",
*     "gender": "male",
*     "locale": "pt_PT",
*     "age_range": ​21,
*     "timezone": ​1,
*     "pending_additions": ​0,
*     "successful_additions": ​1,
*     "unsuccessful_additions": ​2,
*     "pending_deletions": ​0,
*     "successful_deletions": ​0,
*     "unsuccessful_deletions": ​0,
*     "pending_editions": ​0,
*     "successful_editions": ​0,
*     "unsuccessful_editions": ​0
* }
*
* @apiSuccessExample {xml} Success Response (XML)
* <user>
*     <id>10201440175723123</id>
*     <first_name>Luis</first_name>
*     <last_name>Campos</last_name>
*     <gender>male</gender>
*     <locale>pt_PT</locale>
*     <age_range>21</age_range>
*     <timezone>1</timezone>
*     <pending_additions>0</pending_additions>
*     <successful_additions>1</successful_additions>
*     <unsuccessful_additions>2</unsuccessful_additions>
*     <pending_deletions>0</pending_deletions>
*     <successful_deletions>0</successful_deletions>
*     <unsuccessful_deletions>0</unsuccessful_deletions>
*     <pending_editions>0</pending_editions>
*     <successful_editions>0</successful_editions>
*     <unsuccessful_editions>0</unsuccessful_editions>
* </user>
*/

/**
* @api {get} /user/:id/votes Get User votes
* @apiName GetUserVotes
* @apiGroup User
* @apiVersion 0.0.1
*
* @apiParam {String} id User ID
*
* @apiSuccess (200 OK) {Number} addition_id ID of the addition
* @apiSuccess (200 OK) {String} type_of_votes If the vote was negative or positive. If null, it means that the user was the one who requested the addition/deletion/edition
*
*
* @apiSuccessExample {json} Success Response (JSON):
* {
*     "addition_votes": [
*         {
*             "addition_id": ​12,
*             "type_of_vote": "negative"
*         },
*         {
*             "addition_id": ​14,
*             "type_of_vote": "positive"
*         },
*         {
*             "addition_id": ​18,
*             "type_of_vote": null
*         }
*     ]
*     "deletion_votes": [
*          {
*              "deletion_id": ​8,
*              "type_of_vote": "positive"
*          },
*          {
*              "deletion_id": ​9,
*              "type_of_vote": "negative"
*          }
*    ]
* }
*
* @apiSuccessExample {xml} Success Response (XML)
*
* <votes>
*     <addition_votes>
*         <addition_vote>
*             <addition_id>12</addition_id>
*             <type_of_vote>negative</type_of_vote>
*         </addition_vote>
*         <addition_vote>
*             <addition_id>14</addition_id>
*             <type_of_vote>positive</type_of_vote>
*         </addition_vote>
*         <addition_vote>
*             <addition_id>18</addition_id>
*             <type_of_vote/>
*         </addition_vote>
*         <addition_vote>
*             <addition_id>19</addition_id>
*             <type_of_vote/>
*         </addition_vote>
*         <addition_vote>
*             <addition_id>20</addition_id>
*             <type_of_vote>positive</type_of_vote>
*         </addition_vote>
*     </addition_votes>
*     <deletion_votes>
*         <deletion_vote>
*             <deletion_id>8</deletion_id>
*             <type_of_vote>positive</type_of_vote>
*         </deletion_vote>
*         <deletion_vote>
*             <deletion_id>9</deletion_id>
*             <type_of_vote>negative</type_of_vote>
*         </deletion_vote>
*     </deletion_votes>
* </votes>
*
*
*
*/

?>
