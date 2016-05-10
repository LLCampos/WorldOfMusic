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

?>
