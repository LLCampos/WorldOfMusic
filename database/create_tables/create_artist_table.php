<?php

function createArtistsTable() {
    include "../connect_to_database.php";

    $sql_create_table =
            "create table Artist (
            id int auto_increment primary key,
            name varchar(200) unique,
            country varchar(200),
            picture_url varchar(500),
            biography text,
            style varchar(200),
            music_video varchar(500),
            lastfm_url varchar(500),
            number_of_lastfm_listeners int,
            facebook_id bigint,
            number_of_facebook_likes int,
            twitter_url varchar(500),
            number_of_twitter_followers int,
            musicbrainz_id varchar(50),
            Deleted int,
            insertionDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        );";

    try {
        $conn->exec($sql_create_table);
    } catch(PDOException $e) {
        echo "Erro!" . $e->getMessage();
    }

    include "../disconnect_database.php";
}

function addDeletedColumn() {
    # Adds a "Deleted" column to Artist table. This columns if a flag that indicates if the artist was deleted (1) or not (0).
    include "../connect_to_database.php";

    $sql_add_column =
            "ALTER TABLE Artist
            ADD Deleted int;";

    try {
        $conn->exec($sql_add_column);
    } catch(PDOException $e) {
        echo "Erro!" . $e->getMessage();
    }

    include "../disconnect_database.php";
}

?>
