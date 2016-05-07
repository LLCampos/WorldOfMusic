<?php

function createDeletionTable() {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE Deletion (
            id int AUTO_INCREMENT PRIMARY KEY,
            artist_id int(11),
            user_id varchar(255),
            yes int(2) default 0,
            no int(2) default 0,
            pending int(1) default 1,
            deletion_creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_artist_deletion FOREIGN KEY (artist_id) REFERENCES Artist(id),
            CONSTRAINT fk_user_deletion FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function createDeletionVoteTable() {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE DeletionVote (
            deletion_id int,
            user_id varchar(255),
            type_of_vote varchar(20),
            vote_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (deletion_id, user_id),
            CONSTRAINT fk_deletion_deletion_vote FOREIGN KEY (deletion_id) REFERENCES Deletion(id),
            CONSTRAINT fk_user_deletion_vote FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertDeletion($artist_id, $user_id) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO Deletion (artist_id, user_id) VALUES (:artist_id, :user_id)");

    $sql->bindParam(':artist_id', $artist_id);
    $sql->bindParam(':user_id', $user_id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertDeletionVote($deletion_id, $user_id, $type_of_vote) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO DeletionVote (deletion_id, user_id, type_of_vote) VALUES (:deletion_id, :user_id, :type_of_vote)");

    $sql->bindParam(':deletion_id', $deletion_id);
    $sql->bindParam(':user_id', $user_id);
    $sql->bindParam(':type_of_vote', $type_of_vote);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertDeletionVoteFromArtistID($artist_id, $user_id) {
    $deletion_id = getDeletionIDFromArtistID($artist_id);
    insertDeletionVote($deletion_id, $user_id);
}

function getDeletionIDFromArtistID($artist_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM Deletion WHERE artist_id = :artist_id");

    try {
        $query->execute(array(':artist_id' => $artist_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

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

function getInformationAboutAllPendingDeletions($limit, $order_sql_string = False) {

    require "/home/aw008/database/connect_to_database.php";

    $params = array();
    $params[":limit"] = $limit;

    $query_string = "SELECT AD.id AS id, AR.name AS artist_name, C.name AS country_name, AD.yes AS positive_votes, AD.no as negative_votes, AD.user_id as added_by, AD.deletion_creation_date as creation_time
            FROM Deletion AD, Artist AR, Country C
            WHERE AR.id = AD.artist_id AND AR.country_fk = C.id AND AD.pending = 1";

    if ($order_sql_string) {
        $query_string = $query_string . $order_sql_string;
    }

    $query_string = $query_string . " LIMIT :limit";

    $query =$conn->prepare($query_string);

    try {
        $query->execute($params);
        $query->execute();
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;

    require "/home/aw008/database/disconnect_database.php";
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
