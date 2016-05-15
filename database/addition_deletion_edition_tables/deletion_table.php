<?php

function countPositiveDeletionVotes($deletion_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT yes FROM Deletion WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $yes = $query->fetch()[0];

    return $yes;

    require "/home/aw008/database/disconnect_database.php";
}

function countNegativeDeletionVotes($deletion_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT no FROM Deletion WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $no = $query->fetch()[0];

    return $no;

    require "/home/aw008/database/disconnect_database.php";
}

function getUserIDFromDeletionID($deletion_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id FROM Deletion WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $user_id = $query->fetch()[0];

    return $user_id;

    require "/home/aw008/database/disconnect_database.php";
}

function getArtistIDFromDeletionID($deletion_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT artist_id FROM Deletion WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $artist_id = $query->fetch()[0];

    return $artist_id;

    require "/home/aw008/database/disconnect_database.php";
}


function checkDeletionVotes($deletion_id) {

    if (countPositiveDeletionVotes($deletion_id) > 4) {
        require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
        require_once "/home/aw008/database/users/user_table_functions.php";


        $artist_id = getArtistIDFromDeletionID($deletion_id);
        makeArtistInvisible($artist_id);

        $user_id = getUserIDFromDeletionID($deletion_id);
        subtractPendingDeletion($user_id);
        addSuccessfulDeletion($user_id);
        makeDeletionNonPending($deletion_id);

    } elseif (countNegativeDeletionVotes($deletion_id) > 4) {
        require_once "/home/aw008/database/users/user_table_functions.php";

        $user_id = getUserIDFromDeletionID($deletion_id);
        subtractPendingDeletion($user_id);
        addUnsuccessfulDeletion($user_id);
        makeDeletionNonPending($deletion_id);
    }
}


function makeDeletionNonPending($deletion_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Deletion SET pending = 0 WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}


function isTherePendingDeletionOnArtist($artist_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT artist_id FROM Deletion WHERE artist_id = :artist_id AND pending = 1");

    try {
        $query->execute(array(':artist_id' => $artist_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    if ($query->rowCount()) {
        require "/home/aw008/database/disconnect_database.php";
        return true;
    } else {
        require "/home/aw008/database/disconnect_database.php";
        return false;
    }
}


function didUserAlreadyTriedToDeleteArtist($artist_id, $user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id, artist_id FROM Deletion WHERE artist_id = :artist_id AND user_id = :user_id");

    try {
        $query->execute(array(':artist_id' => $artist_id, ':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    if ($query->rowCount()) {
        require "/home/aw008/database/disconnect_database.php";
        return true;
    } else {
        require "/home/aw008/database/disconnect_database.php";
        return false;
    }
}

?>
