<?php


function userAlreadyVoteDeletion($user_id, $deletion_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id, deletion_id FROM DeletionVote WHERE user_id = :user_id AND deletion_id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id, ':user_id' => $user_id));
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

function addDeletionPositiveVote($deletion_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Deletion SET yes = yes + 1 WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addDeletionNegativeVote($deletion_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Deletion SET no = no + 1 WHERE id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

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


function getInformationAboutOnePendingDeletion($deletion_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query =$conn->prepare("SELECT AD.id AS id, AR.name AS artist_name, C.name AS country_name, AD.yes AS positive_votes, AD.no as negative_votes, AD.user_id as added_by, AD.deletion_creation_date as creation_time
            FROM Deletion AD, Artist AR, Country C
            WHERE AR.id = AD.artist_id AND AR.country_fk = C.id AND AD.pending = 1 AND AD.id = :deletion_id");

    try {
        $query->execute(array(':deletion_id' => $deletion_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetch(PDO::FETCH_ASSOC);

    return $array;

    require "/home/aw008/database/disconnect_database.php";
}

function arrayOfAllPendingDeletionsIDs() {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "SELECT id
            FROM Deletion
            WHERE pending = 1;";

    $query_result = $conn->query($sql);

    $list_of_ids = $query_result->fetchAll(PDO::FETCH_COLUMN);

    include "/home/aw008/database/disconnect_database.php";

    return $list_of_ids;
}

function deletionIDExists($deletion_id) {
    # Returns true if $deletion_id is an id of a pending deletion. False otherwise.

    $list_of_deletions_ids = arrayOfAllPendingDeletionsIDs();

    return in_array($deletion_id, $list_of_deletions_ids);
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

function getDeletionVotesFromUser($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query =$conn->prepare("SELECT AV.deletion_id, AV.type_of_vote
                            FROM DeletionVote AV
                            WHERE user_id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;

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
