<?php

require "/home/aw008/database/facebook_api/facebook_api_functions.php";

function addFacebookIDToArtistInDB($artist_name) {

    $id = obtainFacebookPageID($artist_name);

    # If artist has Facebook page
    if ($id) {

        include "/home/aw008/database/connect_to_database.php";

        $artist_name = $conn->quote($artist_name);

        $sql = "UPDATE Artist
                SET facebook_id = $id
                WHERE name = $artist_name;";

        try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n" . $sql;
        }

        include "/home/aw008/database/disconnect_database.php";
    }

}

function addFacebookIDToAllArtistsInDB() {

    include "/home/aw008/database/utility_functions/artist_utility_functions.php";

    $list_of_artists = arrayOfAllArtist();

    foreach ($list_of_artists as $artist) {
        addFacebookIDToArtistInDB($artist);
    }
}

function addNumberOfLikesToArtist($artist_name, $fb_access_token) {

    include "/home/aw008/database/connect_to_database.php";

    $artist_name = $conn->quote($artist_name);

    $query_id = "SELECT facebook_id
                 FROM Artist
                 WHERE name = $artist_name;";

    $results = $conn->query($query_id);
    $first_result_array = $results->fetchAll()[0];
    $id = $first_result_array['facebook_id'];

    if ($id) {
        $number_of_likes = numberofFacebookLikes($id, $fb_access_token);

        $update_table = "UPDATE Artist
                         SET number_of_facebook_likes = $number_of_likes
                         WHERE name = $artist_name";

        try {
            $conn->exec($update_table);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n" . $update_table;
        }
    }

    include "/home/aw008/database/disconnect_database.php";
}

function addNumberOfLikesToAllArtists() {

    $fb_access_token = generateAppAccessToken();

    include "/home/aw008/database/utility_functions/artist_utility_functions.php";

    $list_of_artists = arrayOfAllArtist();

    foreach ($list_of_artists as $artist) {
        addNumberOfLikesToArtist($artist, $fb_access_token);
    }

}

?>
