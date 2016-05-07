<?php

require_once "/home/aw008/database/addition_deletion_edition_tables/deletion_table.php";
require_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";

function GETPendingDeletions($outputType) {

    $legal_params = array('limit', 'order');

    checksLegalityOfParametersGiven($_GET, $legal_params);

    $limit = checksLimitParameter();

    # Checks if client specified some specific order to receive the ouput.
    if (isset($_GET['order'])) {
        if ($_GET['order'] == 'random') {
            $order_sql_string = " ORDER BY RAND()";
        } elseif ($_GET['order'] == 'date_asc') {
            $order_sql_string = " ORDER BY AD.deletion_creation_date";
        } elseif ($_GET['order'] == 'date_desc') {
            $order_sql_string = " ORDER BY AD.deletion_creation_date DESC";
        } else {
            $response = "You didn't use one of the mandatory values for order.";
            simpleResponse($response, $outputType, 400);
        }
    } else {
        require "/home/aw008/variables/business_logic_variables.php";
        $order_sql_string = $default_order_get_deletions;
    }

    $pending_deletions = getInformationAboutAllPendingDeletions($limit, $order_sql_string);

    if ($outputType == "xml") {
        echo '<?xml version="1.0"?>';
        echo '<pending_deletions>';

        foreach ($pending_deletions as $pending_deletion) {
            buildSimpleXMLOutput('pending_deletion', $pending_deletion);
        }

        echo '</pending_deletions>';

    } else {
        $json = array();
        foreach ($pending_deletions as $pending_deletion) {
            $json['pending_deletions'][] = $pending_deletion;
        }
        echo json_encode($json);
    }
}

function GETOnePendingDeletion($deletion_id, $outputType) {
    $pending_deletion = getInformationAboutOnePendingDeletion($deletion_id);

    if ($outputType == "xml") {
        buildSimpleXMLOutput('pending_deletion', $pending_deletion);
    } else {
        buildSimpleJSONOutput($pending_deletion);
    }
}


function POSTPendingDeletion($deletion_id, $user_id, $type_of_vote) {

    if (userAlreadyVoteDeletion($user_id, $deletion_id)) {
        $response = "You already voted on this artist/music group.";
        simpleResponse($response, $outputType, 403);
    } else {
        if ($type_of_vote == 'positive_vote') {
            insertDeletionVote($deletion_id, $user_id, 'positive');
            addDeletionPositiveVote($deletion_id);
        } elseif ($type_of_vote == 'negative_vote') {
            insertDeletionVote($deletion_id, $user_id, 'negative');
            addDeletionNegativeVote($deletion_id);
        } else {
            $response = "Type of vote must be 'positive_vote' or 'negative_vote'";
            simpleResponse($response, $outputType, 400);
        }
        checkDeletionVotes($deletion_id);
    }
}

?>
