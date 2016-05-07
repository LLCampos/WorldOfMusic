<?php

function artistNameToID($artist_name) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM Artist WHERE name = :artist_name");

    try {
        $query->execute(array(':artist_name' => $artist_name));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

function arrayOfAllArtist() {
    # Returns an array with the name of all artist in the database.

    include "/home/aw008/database/connect_to_database.php";

    $sql = "SELECT name
            FROM Artist;";

    $query_result = $conn->query($sql);

    $list_of_artists = $query_result->fetchAll(PDO::FETCH_COLUMN);

    include "/home/aw008/database/disconnect_database.php";

    return $list_of_artists;
}

function allArtistsNonDeleted() {
    # Set all values of the Deleted column of the Artist table to 0, as in "Non deleted"

    include "/home/aw008/database/connect_to_database.php";

    $sql = "UPDATE Artist SET Deleted = 0";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo "Erro!" . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function alreadyInTable($artist_name) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("SELECT name FROM Artist WHERE name = :artist_name");

    $sql->execute(array('artist_name' => $artist_name));

    if ($sql->rowCount()) {
        return true;
    } else {
        return false;
    }

    include "/home/aw008/database/disconnect_database.php";
}

function isArtistVisible($id) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = $conn->prepare("SELECT Deleted FROM Artist WHERE id = :id");

    $sql->execute(array('id' => $id));

    $visible = $sql->fetch();

    if ($visible['Deleted'] === 0) {
        return true;
    } else {
        return false;
    }

    include "/home/aw008/database/disconnect_database.php";
}

function makeArtistVisible($id) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "UPDATE Artist
           SET Deleted = 0
           WHERE id = $id";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function makeArtistInvisible($id) {
    include "/home/aw008/database/connect_to_database.php";

    $sql = "UPDATE Artist
           SET Deleted = 1
           WHERE id = $id";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}

function insertArtistInTableByUser($artist_name, $country_code) {
    # Inserts artist in table, but makes it invisible to users.

    require_once "/home/aw008/database/populate_tables/populate_artist_table.php";

    if (insertArtistInTable($artist_name, $country_code)) {
        $id = artistNameToID($artist_name);
        makeArtistInvisible($id);
        return true;
    } else {
        return false;
    }
}


?>
