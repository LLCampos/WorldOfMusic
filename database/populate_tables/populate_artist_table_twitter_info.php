<?php

require "/home/aw008/database/twitter_api/twitter_functions.php";

$twitter_auth = twitterAuthenticaton();

function addTwitterURLToArtistInDB($artist_name, $twitter_auth) {

    $url = getTwitterPageURL($artist_name, $twitter_auth);

    # If artist has Twitter page
    if ($url) {

        include "/home/aw008/database/connect_to_database.php";

        $artist_name = $conn->quote($artist_name);
        $url = $conn->quote($url);

        $sql = "UPDATE Artist
                SET twitter_url = $url
                WHERE name = $artist_name;";

        try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n" . $sql;
        }

        include "/home/aw008/database/disconnect_database.php";
    }
}

function addTwitterURLToAllArtistsInDB($twitter_auth) {

    include "/home/aw008/database/utility_functions/artist_utility_functions.php";

    $list_of_artists = arrayOfAllArtist();

    $n = 0;

    foreach ($list_of_artists as $artist) {

        addTwitterURLToArtistInDB($artist, $twitter_auth);

        # Control rate of calls to Twitter API
        $n = $n + 1;
        if ($n > 150) {
            echo $n;
            $n = 0;
            sleep(900);
        }
    }
}


addTwitterURLToAllArtistsInDB($twitter_auth);


?>
