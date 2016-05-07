<?php

function removeArtistWithLowNumberOfLastFMListeners($n) {

    include "/home/aw008/database/connect_to_database.php";

    $sql = "UPDATE Artist
            SET facebook_id = NULL, number_of_facebook_likes = NULL
            WHERE (number_of_lastfm_listeners < 1000 AND (number_of_facebook_likes - number_of_lastfm_listeners) > 100000)
                OR (number_of_lastfm_listeners < 10000 AND (number_of_facebook_likes - number_of_lastfm_listeners) > 5000000)";

    try {
        $conn->exec($sql);
    } catch(PDOException $e) {
        echo $sql . $e->getMessage();
    }

    include "/home/aw008/database/disconnect_database.php";
}



?>
