<?php

function createUserTable() {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "CREATE TABLE User (
            id varchar(255) primary key,
            first_name varchar(255),
            last_name varchar(255),
            gender varchar(255),
            email varchar(255),
            locale varchar(255),
            age_range int(3),
            timezone int,
            updated_time datetime,
            pending_additions int default 0,
            successful_additions int default 0,
            unsuccessful_additions int default 0,
            pending_deletions int default 0,
            successful_deletions int default 0,
            unsuccessful_deletions int default 0,
            pending_editions int default 0,
            successful_editions int default 0,
            unsuccessful_editions int default 0,
            registed_since timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            )";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function userAlreadyOnTable($id) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("SELECT id FROM User WHERE id = :id");

    $sql->bindParam(':id', $id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $sql . " " . $e->getMessage() . "\n";
    }

    if ($sql->rowCount()) {
        return true;
    } else {
        return false;
    }

    include "/home/aw008/database/disconnect_database.php";
}

function addNewUserToUserTable($id, $first_name, $last_name, $gender, $email, $locale, $age_range, $timezone, $updated_time) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("INSERT INTO User (id, first_name, last_name, gender, email, locale, age_range, timezone, updated_time) VALUES (:id, :first_name, :last_name, :gender, :email, :locale, :age_range, :timezone, :updated_time)");

    $sql->bindParam(':id', $id);
    $sql->bindParam(':first_name', $first_name);
    $sql->bindParam(':last_name', $last_name);
    $sql->bindParam(':gender', $gender);
    $sql->bindParam(':email', $email);
    $sql->bindParam(':locale', $locale);
    $sql->bindParam(':age_range', $age_range);
    $sql->bindParam(':timezone', $timezone);
    $sql->bindParam(':updated_time', $updated_time);

    try {
        $sql->execute();
        return true;
    } catch(PDOException $e) {
        echo $sql . " " . $e->getMessage() . "\n";
    }

    include "/home/aw008/database/disconnect_database.php";
}

function selectUserByID($id) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("SELECT * From User WHERE id = :id");

    $sql->bindParam(':id', $id);

    try {
        $sql->execute();
    } catch(PDOException $e) {
        echo $sql . " " . $e->getMessage() . "\n";
    }

    $user_row = $sql->fetch(PDO::FETCH_ASSOC);

    include "/home/aw008/database/disconnect_database.php";

    return $user_row;
}

function getPendingAdditions($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT pending_additions FROM User WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";

    return $query->fetch()[0];
}

function addPendingAddition($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET pending_additions = pending_additions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function subtractPendingAddition($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET pending_additions = pending_additions - 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addSuccessfulAddition($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET successful_additions = successful_additions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addUnsuccessfulAddition($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET unsuccessful_additions = unsuccessful_additions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function getPendingDeletions($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT pending_deletions FROM User WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";

    return $query->fetch()[0];
}

function addPendingDeletion($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET pending_deletions = pending_deletions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function subtractPendingDeletion($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET pending_deletions = pending_deletions - 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addSuccessfulDeletion($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET successful_deletions = successful_deletions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

function addUnsuccessfulDeletion($user_id) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("UPDATE User SET unsuccessful_deletions = unsuccessful_deletions + 1 WHERE id = :user_id");

    try {
        $query->execute(array(':user_id' => $user_id));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    require "/home/aw008/database/disconnect_database.php";
}

# addNewUserToUserTable(2, 'afdadf', 'afasdf', 'asdfsadf', 'asdfsadf', 'asdfsadf', 12, 1, '1000-01-01 00:00:00')

?>
