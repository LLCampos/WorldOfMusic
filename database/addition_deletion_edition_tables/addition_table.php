<?php

function createAdditionTable() {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE Addition (
            id int AUTO_INCREMENT PRIMARY KEY,
            artist_id int(11),
            user_id varchar(255),
            yes int(2) default 0,
            no int(2) default 0,
            pending int(1) default 1,
            addition_creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_artist FOREIGN KEY (artist_id) REFERENCES Artist(id),
            CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function createAdditionVoteTable() {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE AdditionVote (
            addition_id int,
            user_id varchar(255),
            type_of_vote varchar(20),
            vote_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (addition_id, user_id),
            CONSTRAINT fk_addition_addition_vote FOREIGN KEY (addition_id) REFERENCES Addition(id),
            CONSTRAINT fk_user_addition_vote FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertAddition($artist_id, $user_id) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO Addition (artist_id, user_id) VALUES (:artist_id, :user_id)");

    $sql->bindParam(':artist_id', $artist_id);
    $sql->bindParam(':user_id', $user_id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertAdditionVote($addition_id, $user_id, $type_of_vote) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO AdditionVote (addition_id, user_id, type_of_vote) VALUES (:addition_id, :user_id, :type_of_vote)");

    $sql->bindParam(':addition_id', $addition_id);
    $sql->bindParam(':user_id', $user_id);
    $sql->bindParam(':type_of_vote', $type_of_vote);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertAdditionVoteFromArtistID($artist_id, $user_id) {
    $addition_id = getAdditionIDFromArtistID($artist_id);
    insertAdditionVote($addition_id, $user_id);
}

function getAdditionIDFromArtistID($artist_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM Addition WHERE artist_id = :artist_id");

    try {
        $query->execute(array(':artist_id' => $artist_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

function userAlreadyVoteAddition($user_id, $addition_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id, addition_id FROM AdditionVote WHERE user_id = :user_id AND addition_id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id, ':user_id' => $user_id));
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

function addAdditionPositiveVote($addition_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Addition SET yes = yes + 1 WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addAdditionNegativeVote($addition_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE Addition SET no = no + 1 WHERE id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

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


function getInformationAboutAllPendingAdditions($limit, $order_sql_string = False) {

    require "/home/aw008/database/connect_to_database.php";

    $params = array();
    $params[":limit"] = $limit;

    $query_string = "SELECT AD.id AS id, AR.name AS artist_name, C.name AS country_name, AD.yes AS positive_votes, AD.no as negative_votes, AD.user_id as added_by, AD.addition_creation_date as creation_time
            FROM Addition AD, Artist AR, Country C
            WHERE AR.id = AD.artist_id AND AR.country_fk = C.id AND AD.pending = 1";

    if ($order_sql_string) {
        $query_string = $query_string . $order_sql_string;
    }

    $query_string = $query_string . " LIMIT :limit";

    $query =$conn->prepare($query_string);

    try {
        $query->execute($params);
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    require "/home/aw008/database/disconnect_database.php";

    return $array;
}


function getInformationAboutOnePendingAddition($addition_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query =$conn->prepare("SELECT AD.id AS id, AR.name AS artist_name, C.name AS country_name, AD.yes AS positive_votes, AD.no as negative_votes, AD.user_id as added_by, AD.addition_creation_date as creation_time
            FROM Addition AD, Artist AR, Country C
            WHERE AR.id = AD.artist_id AND AR.country_fk = C.id AND AD.pending = 1 AND AD.id = :addition_id");

    try {
        $query->execute(array(':addition_id' => $addition_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetch(PDO::FETCH_ASSOC);

    return $array;

    require "/home/aw008/database/disconnect_database.php";
}

function arrayOfAllPendingAdditionsIDs() {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "SELECT id
            FROM Addition
            WHERE pending = 1;";

    $query_result = $conn->query($sql);

    $list_of_ids = $query_result->fetchAll(PDO::FETCH_COLUMN);

    include "/home/aw008/database/disconnect_database.php";

    return $list_of_ids;
}

function additionIDExists($addition_id) {
    # Returns true if $addition_id is an id of a pending addition. False otherwise.

    $list_of_additions_ids = arrayOfAllPendingAdditionsIDs();

    return in_array($addition_id, $list_of_additions_ids);
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

function getAdditionVotesFromUser($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query =$conn->prepare("SELECT AV.addition_id, AV.type_of_vote
                            FROM AdditionVote AV
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

?>
