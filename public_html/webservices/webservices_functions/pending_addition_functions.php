<?php

require_once "/home/aw008/database/addition_deletion_edition_tables/addition_table.php";
require_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";

function GETPendingAdditions($outputType) {

    $legal_params = array('limit', 'order');

    checksLegalityOfParametersGiven($_GET, $legal_params);

    $limit = checksLimitParameter();

    # Checks if client specified some specific order to receive the ouput.
    if (isset($_GET['order'])) {
        if ($_GET['order'] == 'random') {
            $order_sql_string = " ORDER BY RAND()";
        } elseif ($_GET['order'] == 'date_asc') {
            $order_sql_string = " ORDER BY AD.addition_creation_date";
        } elseif ($_GET['order'] == 'date_desc') {
            $order_sql_string = " ORDER BY AD.addition_creation_date DESC";
        } else {
            $response = "You didn't use one of the mandatory values for order.";
            simpleResponse($response, $outputType, 400);
        }
    } else {
        require "/home/aw008/variables/business_logic_variables.php";
        $order_sql_string = $default_order_get_additions;
    }

    $pending_additions = getInformationAboutAllPendingAdditions($limit, $order_sql_string);

    if ($outputType == "xml") {
        echo '<?xml version="1.0"?>';
        echo '<pending_additions>';

        foreach ($pending_additions as $pending_addition) {
            buildSimpleXMLOutput('pending_addition', $pending_addition);
        }

        echo '</pending_additions>';

    } else {
        $json = array();
        foreach ($pending_additions as $pending_addition) {
            $json['pending_additions'][] = $pending_addition;
        }
        echo json_encode($json);
    }
}

function GETOnePendingAddition($addition_id, $outputType) {

    $pending_addition = getInformationAboutOnePendingAddition($addition_id);

    if ($outputType == "xml") {
        buildSimpleXMLOutput('pending_addition', $pending_addition);
    } else {
        buildSimpleJSONOutput($pending_addition);
    }
}


function POSTPendingAddition($addition_id, $user_id, $type_of_vote) {

    if (userAlreadyVoteAddition($user_id, $addition_id)) {
        $response = "You already voted on this artist/music group.";
        simpleResponse($response, $outputType, 403);
    } else {
        if ($type_of_vote == 'positive_vote') {
            insertAdditionVote($addition_id, $user_id, 'positive');
            addAdditionPositiveVote($addition_id);
        } elseif ($type_of_vote == 'negative_vote') {
            insertAdditionVote($addition_id, $user_id, 'negative');
            addAdditionNegativeVote($addition_id);
        } else {
            $response = "Type of vote must be 'positive_vote' or 'negative_vote'";
            simpleResponse($response, $outputType, 400);
        }
        checkAdditionVotes($addition_id);
    }
}

?>
