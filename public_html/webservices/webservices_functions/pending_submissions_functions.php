<?php

require_once "/home/aw008/database/addition_deletion_edition_tables/addition_table.php";
require_once "/home/aw008/database/addition_deletion_edition_tables/deletion_table.php";
require_once "/home/aw008/database/addition_deletion_edition_tables/submission_table.php";
require_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
include_once "/home/aw008/public_html/webservices/webservices_functions/webservices_utility_functions.php";


function GETPendingSubmissions($submission_type, $outputType) {

    # $submission_type is "addition" or "deletion", $outputType must be "xml" or "json".

    require '/home/aw008/variables/business_logic_variables.php';

    $legal_params = $legal_params_pending_submission;

    checksLegalityOfParametersGiven($_GET, $legal_params);

    $limit = checkLimitParameter($_GET);

    $page = checkPageParameter($_GET);

    # Checks if client specified some specific order to receive the ouput.
    if (isset($_GET['order'])) {
        if ($_GET['order'] == 'random') {
            $order_sql_string = " ORDER BY RAND()";
        } elseif ($_GET['order'] == 'date_asc') {
            $order_sql_string = " ORDER BY S." . $submission_type . "_creation_date";
        } elseif ($_GET['order'] == 'date_desc') {
            $order_sql_string = " ORDER BY S." . $submission_type . "_creation_date DESC";
        } else {
            $response = "You didn't use one of the mandatory values for order.";
            simpleResponse($response, $outputType, 400);
        }
    } else {
        $order_sql_string = ${'default_order_get_' . $submission_type . 's'};
    }

    $pending_submissions = getInformationAboutPendingSubmissions($submission_type, $limit, $order_sql_string, $page);

    if ($outputType == "xml") {
        echo '<?xml version="1.0"?>';
        echo '<pending_' . $submission_type . 's>';

        foreach ($pending_submissions as $pending_submission) {
            buildSimpleXMLOutput('pending_' . $submission_type, $pending_submission);
        }

        echo '</pending_' . $submission_type . 's>';

    } else {
        $json = array();
        foreach ($pending_submissions as $pending_submission) {
            $json['pending_'. $submission_type . 's'][] = $pending_submission;
        }
        echo json_encode($json);
    }
}

function GETOnePendingSubmission($submission_type, $submission_id, $outputType) {

    $pending_submission = getInformationAboutOnePendingSubmission($submission_type, $submission_id);

    if ($outputType == "xml") {
        buildSimpleXMLOutput('pending_' . $submission_type, $pending_submission);
    } else {
        buildSimpleJSONOutput($pending_submission);
    }
}

function POSTPendingAddition($addition_id, $user_id, $type_of_vote) {

    if (userAlreadyVotedInSubmission('addition', $user_id, $addition_id)) {
        $response = "You already voted on this artist/music group.";
        simpleResponse($response, $outputType, 403);
    } else {
        if ($type_of_vote == 'positive_vote') {
            insertSubmissionVote('addition', $addition_id, $user_id, 'positive');
            addSubmissionPositiveVote('addition', $addition_id);
        } elseif ($type_of_vote == 'negative_vote') {
            insertSubmissionVote('addition', $addition_id, $user_id, 'negative');
            addSubmissionNegativeVote('addition', $addition_id);
        } else {
            $response = "Type of vote must be 'positive_vote' or 'negative_vote'";
            simpleResponse($response, $outputType, 400);
        }
        checkAdditionVotes($addition_id);
    }
}

function POSTPendingDeletion($deletion_id, $user_id, $type_of_vote) {

    if (userAlreadyVotedInSubmission('deletion', $user_id, $deletion_id)) {
        $response = "You already voted on this artist/music group.";
        simpleResponse($response, $outputType, 403);
    } else {
        if ($type_of_vote == 'positive_vote') {
            insertSubmissionVote('deletion', $deletion_id, $user_id, 'positive');
            addSubmissionPositiveVote('deletion', $deletion_id);
        } elseif ($type_of_vote == 'negative_vote') {
            insertSubmissionVote('deletion', $deletion_id, $user_id, 'negative');
            addSubmissionNegativeVote('deletion', $deletion_id);
        } else {
            $response = "Type of vote must be 'positive_vote' or 'negative_vote'";
            simpleResponse($response, $outputType, 400);
        }
        checkDeletionVotes($deletion_id);
    }
}

?>
