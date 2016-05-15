<?php


################################################################ Table Creation #####################################################

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

function createEditionTable() {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE Edition (
            id int AUTO_INCREMENT PRIMARY KEY,
            old_artist_id int(11),
            new_artist_id int(11),
            user_id varchar(255),
            attribute_changing varchar(255),
            yes int(2) default 0,
            no int(2) default 0,
            pending int(1) default 1,
            edition_creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_edition_old_artist_to_artist_table FOREIGN KEY (old_artist_id) REFERENCES Artist(id),
            CONSTRAINT fk_edition_new_artist_to_artist_table FOREIGN KEY (new_artist_id) REFERENCES Artist(id),
            CONSTRAINT fk_user_edition FOREIGN KEY (user_id) REFERENCES User(id)
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

    $sql = "CREATE TABLE " . ucfirst($submition_type) . "Vote (
            " . $submition_type . "_id int,
            user_id varchar(255),
            type_of_vote varchar(20),
            vote_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (" . $submition_type . "_id, user_id),
            CONSTRAINT fk_" . $submition_type . "_" . $submition_type . "_vote FOREIGN KEY (" . $submition_type . "_id) REFERENCES " . ucfirst($submition_type) . "(id),
            CONSTRAINT fk_user_" . $submition_type . "_vote FOREIGN KEY (user_id) REFERENCES User(id)
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

############################# Table Edition ######################################

function addSubmissionPositiveVote($submission_type, $submission_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE " . ucfirst($submission_type) . " SET yes = yes + 1 WHERE id = :" . $submission_type . "_id");

    try {
        $query->execute(array(":" . $submission_type . "_id" => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addSubmissionNegativeVote($submission_type, $submission_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE " . ucfirst($submission_type) . " SET no = no + 1 WHERE id = :" . $submission_type . "_id");

    try {
        $query->execute(array(":" . $submission_type . "_id" => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function makeSubmissionNonPending($submission_type, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE ". ucfirst($submission_type) . " SET pending = 0 WHERE id = :submission_id");

    try {
        $query->execute(array(':submission_id' => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}


############################  Table Insertion ###################################################

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


function insertEdition($old_artist_id, $new_artist_id, $user_id, $attribute_changing) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO Edition (old_artist_id, new_artist_id, user_id, attribute_changing)
                           VALUES (:old_artist_id, :new_artist_id, :user_id, :attribute_changing)");

    $sql->bindParam(':old_artist_id', $old_artist_id);
    $sql->bindParam(':new_artist_id', $new_artist_id);
    $sql->bindParam(':user_id', $user_id);
    $sql->bindParam(':attribute_changing', $attribute_changing);

    try {
        $sql->execute();
        $id = $conn->lastInsertId();
        include "/home/aw008/database/disconnect_database.php";
        return $id;
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
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


function insertVoteFromArtistID($submission_type, $artist_id, $user_id) {
    $submission_id = getSubmissionIDFromArtistID($submission_type, $artist_id);
    insertSubmissionVote($submission_type, $submission_id, $user_id);
}


############################### Table Queries ##############################################


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

function userAlreadyVotedInSubmission($submission_type, $user_id, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id, " . $submission_type . "_id FROM ". ucfirst($submission_type) . "Vote WHERE user_id = :user_id AND " . $submission_type . "_id = :" . $submission_type . "_id");

    try {
        $query->execute(array(":" . $submission_type . "_id" => $submission_id, ":user_id" => $user_id));
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


function countPositiveSubmissionVotes($submission_type, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT yes FROM " . ucfirst($submission_type) . " WHERE id = :" . $submission_type . "_id");

    try {
        $query->execute(array(":" . $submission_type . "_id" => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $yes = $query->fetch()[0];

    return $yes;

    require "/home/aw008/database/disconnect_database.php";
}

function countNegativeSubmissionVotes($submission_type, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT no FROM " . ucfirst($submission_type) . " WHERE id = :" . $submission_type . "_id");

    try {
        $query->execute(array(":" . $submission_type . "_id" => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $no = $query->fetch()[0];

    return $no;

    require "/home/aw008/database/disconnect_database.php";
}

function getUserIDFromSubmissionID($submission_type, $submission_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT user_id FROM " . ucfirst($submission_type) . " WHERE id = :submission_id");

    try {
        $query->execute(array(':submission_id' => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $user_id = $query->fetch()[0];

    return $user_id;

    require "/home/aw008/database/disconnect_database.php";
}

function getArtistIDFromSubmissionID($submission_type, $submission_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT artist_id FROM " . ucfirst($submission_type) . " WHERE id = :submission_id");

    try {
        $query->execute(array(':submission_id' => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $artist_id = $query->fetch()[0];

    return $artist_id;

    require "/home/aw008/database/disconnect_database.php";
}

function getInformationAboutOnePendingSubmission($submission_type, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT * FROM " . ucfirst($submission_type) . " WHERE id = :submission_id");

    try {
        $query->execute(array(':submission_id' => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetch(PDO::FETCH_ASSOC);

    require "/home/aw008/database/disconnect_database.php";

    return $array;

}

function getInformationAboutOnePendingSubmissionPrettyArray($submission_type, $submission_id) {

    require "/home/aw008/database/connect_to_database.php";

    if ($submission_type == 'edition') {

        $attribute_changing = getInformationAboutOnePendingSubmission($submission_type, $submission_id)['attribute_changing'];

        $query = $conn->prepare("SELECT S.id AS id, AR1.name AS artist_name, S.attribute_changing, AR1." . $attribute_changing . " AS old_value, AR2." . $attribute_changing . " AS new_value, S.yes AS positive_votes, S.no as negative_votes, S.user_id as added_by, S." . $submission_type . "_creation_date as creation_time
        FROM " . ucfirst($submission_type) . " S, Artist AR1, Artist AR2, Country C
        WHERE AR1.id = S.old_artist_id AND AR2.id = S.new_artist_id AND S.pending = 1 AND S.id = :submission_id");

    } else {

        $query = $conn->prepare("SELECT AD.id AS id, AR.name AS artist_name, C.name AS country_name, AD.yes AS positive_votes, AD.no as negative_votes, AD.user_id as added_by, AD." . $submission_type . "_creation_date as creation_time
                FROM " . ucfirst($submission_type) . " AD, Artist AR, Country C
                WHERE AR.id = AD.artist_id AND AR.country_fk = C.id AND AD.pending = 1 AND AD.id = :submission_id");
    }

    try {
        $query->execute(array(':submission_id' => $submission_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetch(PDO::FETCH_ASSOC);

    require "/home/aw008/database/disconnect_database.php";

    return $array;

}


function getSubmissionVotesFromUser($submission_type, $user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query =$conn->prepare("SELECT AV." . $submission_type . "_id, AV.type_of_vote
                            FROM " . ucfirst($submission_type) . "Vote AV
                            WHERE user_id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    require "/home/aw008/database/disconnect_database.php";

    return $array;
}

function arrayOfAllPendingSubmissionsIDs($submission_type) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "SELECT id
            FROM ". ucfirst($submission_type) . "
            WHERE pending = 1;";

    $query_result = $conn->query($sql);

    $list_of_ids = $query_result->fetchAll(PDO::FETCH_COLUMN);

    include "/home/aw008/database/disconnect_database.php";

    return $list_of_ids;
}

function submissionIDExists($submission_type, $submission_id) {
    # Returns true if $addition_id is an id of a pending addition. False otherwise.

    $list_of_submissions_ids = arrayOfAllPendingSubmissionsIDs($submission_type);

    return in_array($submission_id, $list_of_submissions_ids);
}


function isTherePendingSubmitionOnArtist($submission_type, $artist_id) {

    require "/home/aw008/database/connect_to_database.php";

    if ($submission_type == 'edition') {
        $query = $conn->prepare("SELECT old_artist_id FROM " . ucfirst($submission_type) . " WHERE old_artist_id = :artist_id AND pending = 1");
    } else {
        $query = $conn->prepare("SELECT artist_id FROM " . ucfirst($submission_type) . " WHERE artist_id = :artist_id AND pending = 1");
    }

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

isTherePendingSubmitionOnArtist('edition', 10210);


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


############## Others ####################################


function checkSubmissionVotes($submission_type, $submission_id) {

    if (countPositiveSubmissionVotes($submission_type, $submission_id) > 4) {
        require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
        require_once "/home/aw008/database/users/user_table_functions.php";

        if ($submission_type == 'edition') {

            // NOT TESTED //

            $info = getInformationAboutOnePendingSubmission($submission_type, $submission_id);
            $old_artist_record_id = $info['old_artist_id'];
            $new_artist_record_id = $info['new_artist_id'];

            makeArtistInvisible($old_artist_record_id);
            makeArtistVisible($new_artist_record_id);

        } else {
            $artist_id = getArtistIDFromSubmissionID($submission_type, $submission_id);

            if ($submission_type == 'deletion') {
                makeArtistInvisible($artist_id);
            } else if ($submission_type == 'addition') {
                makeArtistVisible($artist_id);
            }
        }

        $user_id = getUserIDFromSubmissionID($submission_type, $submission_id);
        subtractPendingSubmission($submission_type, $user_id);
        addSuccessfulSubmission($submission_type, $user_id);
        makeSubmissionNonPending($submission_type, $submission_id);

    } elseif (countNegativeSubmissionVotes($submission_type, $submission_id) > 4) {
        require_once "/home/aw008/database/users/user_table_functions.php";

        $user_id = getUserIDFromSubmissionID($submission_type, $submission_id);
        subtractPendingSubmission($submission_type, $user_id);
        addUnsuccessfulSubmission($submission_type, $user_id);
        makeSubmissionNonPending($submission_type, $submission_id);
    }
}

?>
