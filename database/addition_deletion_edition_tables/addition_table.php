<?php

function countPositiveAdditionVotes($addition_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT yes FROM Addition WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $yes = $query->fetch()[0];

    return $yes;

    require "/home/aw008/database/disconnect_database.php";
}

function countNegativeAdditionVotes($addition_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT no FROM Addition WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $no = $query->fetch()[0];

    return $no;

    require "/home/aw008/database/disconnect_database.php";
}

function getUserIDFromAdditionID($addition_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id FROM Addition WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $user_id = $query->fetch()[0];

    return $user_id;

    require "/home/aw008/database/disconnect_database.php";
}

function getArtistIDFromAdditionID($addition_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT artist_id FROM Addition WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $artist_id = $query->fetch()[0];

    return $artist_id;

    require "/home/aw008/database/disconnect_database.php";
}


function checkAdditionVotes($addition_id) {

    if (countPositiveAdditionVotes($addition_id) > 4) {
        require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
        require_once "/home/aw008/database/users/user_table_functions.php";

        $artist_id = getArtistIDFromAdditionID($addition_id);
        makeArtistVisible($artist_id);

        $user_id = getUserIDFromAdditionID($addition_id);
        subtractPendingAddition($user_id);
        addSuccessfulAddition($user_id);
        makeAdditionNonPending($addition_id);
    } elseif (countNegativeAdditionVotes($addition_id) > 4) {
        require_once "/home/aw008/database/users/user_table_functions.php";

        $user_id = getUserIDFromAdditionID($addition_id);
        subtractPendingAddition($user_id);
        addUnsuccessfulAddition($user_id);
        makeAdditionNonPending($addition_id);
    }
}

function makeAdditionNonPending($addition_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Addition SET pending = 0 WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

?>
