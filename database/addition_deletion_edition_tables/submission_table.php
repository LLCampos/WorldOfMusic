<?php

function createSubmissionTable($submition_type) {
    // Submission type is "addition" or "deletion"

    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE " . ucfirst($submition_type) . " (
            id int AUTO_INCREMENT PRIMARY KEY,
            artist_id int(11),
            user_id varchar(255),
            yes int(2) default 0,
            no int(2) default 0,
            pending int(1) default 1," .
            $submition_type . "_creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_artist_ " . $submition_type .  "FOREIGN KEY (artist_id) REFERENCES Artist(id),
            CONSTRAINT fk_user_ " . $submition_type .  "FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}


function createSubmissionVoteTable($submition_type) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE" . ucfirst($submition_type) . "Vote (
            " . $submition_type . "_id int,
            user_id varchar(255),
            type_of_vote varchar(20),
            vote_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (" . $submition_type . "_id, user_id),
            CONSTRAINT fk_" . $submition_type . "_" . $submition_type . "_vote FOREIGN KEY (" . $submition_type . "_id) REFERENCES" . strtolower($submition_type) . "(id),
            CONSTRAINT fk_user_" . $submition_type . "_vote FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertSubmission($submition_type, $artist_id, $user_id) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO " . ucfirst($submition_type) . " (artist_id, user_id) VALUES (:artist_id, :user_id)");

    $sql->bindParam(':artist_id', $artist_id);
    $sql->bindParam(':user_id', $user_id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertSubmissionVote($submission_type, $submission_id, $user_id, $type_of_vote) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO " . ucfirst($submission_type) . "Vote (" . $submission_type . "_id, user_id, type_of_vote) VALUES (:" . $submission_type . "_id, :user_id, :type_of_vote)");

    $sql->bindParam(':' . $submission_type . '_id', $submission_id);
    $sql->bindParam(':user_id', $user_id);
    $sql->bindParam(':type_of_vote', $type_of_vote);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function getInformationAboutPendingSubmissions($submission_type, $limit, $order_sql_string, $page) {

    require "/home/aw008/database/connect_to_database.php";

    $params = array();

    $query_string = "SELECT S.id AS id, AR.name AS artist_name, C.name AS country_name, S.yes AS positive_votes, S.no as negative_votes, S.user_id as added_by, S." . $submission_type . "_creation_date as creation_time
            FROM ". ucfirst($submission_type) . " S, Artist AR, Country C
            WHERE AR.id = S.artist_id AND AR.country_fk = C.id AND S.pending = 1";

    if ($order_sql_string) {
        $query_string = $query_string . $order_sql_string;
    }

    $query_string = $query_string . " LIMIT :beg, :limit";

    $beg = 0 + (($page - 1) * $limit);

    $params[":beg"] = $beg;
    $params[":limit"] = $limit;

    $query = $conn->prepare($query_string);

    try {
        $query->execute($params);
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    require "/home/aw008/database/disconnect_database.php";

    return $array;
}


function insertVoteFromArtistID($submission_type, $artist_id, $user_id) {
    $submission_id = getSubmissionIDFromArtistID($submission_type, $artist_id);
    insertSubmissionVote($submission_type, $submission_id, $user_id);
}

function getSubmissionIDFromArtistID($submission_type, $artist_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM " . ucfirst($submission_type) . " WHERE artist_id = :artist_id");

    try {
        $query->execute(array(':artist_id' => $artist_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

?>
