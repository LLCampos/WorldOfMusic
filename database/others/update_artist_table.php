<?php

# USED TO SUBSTITUE NAME OF COUNTRIES BY FOREIGN KEYS
#include "/home/aw008/database/utility_functions/artist_utility_functions.php";
#include "/home/aw008/database/utility_functions/country_utility_functions.php";
#include "/home/aw008/database/connect_to_database.php";
#
#$list_of_artists = arrayOfAllArtist();
#
#foreach ($list_of_artists as $artist) {
#
#    $artist = $conn->quote($artist);
#
#    $query_country = "SELECT country FROM Artist WHERE name = $artist";
#
#    $country = $conn->query($query_country)->fetch()[0];
#
#    $country_fk = getIDFromNameofCountry($country);
#
#    $query_update = "UPDATE Artist SET country = $country_fk WHERE name = $artist";
#
#    try {
#        $conn->exec($query_update);
#    } catch(PDOException $e) {
#        echo $query_update . " " . $e->getMessage() . "\n";
#    }
#
#}
#
#include "/home/aw008/database/disconnect_database.php";


?>
