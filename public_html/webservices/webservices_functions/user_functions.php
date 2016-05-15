<?php

include_once '/home/aw008/database/users/user_table_functions.php';
include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";

function POSTUser($request, $outputType) {

    $id = $request['id'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $gender = $request['gender'];
    $email = $request['email'];
    $locale = $request['locale'];
    $age_range = $request['age_range'];
    $timezone = $request['timezone'];
    $updated_time = $request['updated_time'];

    if (userAlreadyOnTable($id)) {
        $response = "User already on database.";
        simpleResponse($response, $outputType, 409);
    } else {
        addNewUserToUserTable($id, $first_name, $last_name, $gender, $email, $locale, $age_range, $timezone, $updated_time);
        header("Location: appserver.di.fc.ul.pt/~aw008/webservices/user/id/$id");
        http_response_code(201);
        exit;
    }
}

function GETUser($id, $outputType) {

    $user_array = selectUserByID($id);

    # Don't send the email
    unset($user_array['email']);
    unset($user_array['updated_time']);

    if ($outputType == "xml") {
      echo '<?xml version="1.0"?>';
      buildSimpleXMLOutput('user', $user_array);
    } else {
      buildSimpleJSONOutput($user_array);
    }
    exit;
}

function GETUserVotes($user_id, $outputType) {
    require_once "/home/aw008/database/addition_deletion_edition_tables/submission_table.php";

    $additions = getSubmissionVotesFromUser('addition', $user_id);
    $deletions = getSubmissionVotesFromUser('deletion', $user_id);
    #$editions = getEditionVotesFromUser($user_id);


    if ($outputType == "xml") {
        echo '<?xml version="1.0"?>';
        echo '<votes>';

            if ($additions) {
                echo '<addition_votes>';
                foreach ($additions as $addition_vote) {
                    buildSimpleXMLOutput('addition_vote', $addition_vote);
                }
                echo '</addition_votes>';
            }

            if ($deletions) {
                echo '<deletion_votes>';
                foreach ($deletions as $deletion_vote) {
                    buildSimpleXMLOutput('deletion_vote', $deletion_vote);
                }
                echo '</deletion_votes>';
            }

            if ($editions) {
                echo '<edition_votes>';
                foreach ($editions as $edition_vote) {
                    buildSimpleXMLOutput('edition_vote', $edition_vote);
                }
                echo '</edition_votes>';
            }

        echo '</votes>';
    } else {

        $json = array();
        if ($additions) {
            $json['addition_votes'] = $additions;
        }
        if ($deletions) {
            $json['deletion_votes'] = $deletions;
        }
        if ($editions) {
            $json['edition_votes'] = $editions;
        }

        echo json_encode($json);
    }
    exit;
}


?>
