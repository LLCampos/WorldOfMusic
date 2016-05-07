<?php

function getAllMusicbrainzArtistFromCountry($country_code) {
    // Returns an array with musicbrainz ids and names of the artists of que country of the $country_code.
    // Examples of $country_code: 'pt', 'ru'


    # Creates array that will be the output.
    $artists_array = array();

    # Ask for 100 results.
    $url = "http://musicbrainz.org/ws/2/artist?query=country:$country_code&limit=100";
    $response = file_get_contents($url);

    # If there was a problem with the connection wait 1 second an then try again
    while (!$response) {
        sleep(1);
        $response = file_get_contents($url);
    }


    $xml = simplexml_load_string($response);

    $artist_list_xml = $xml->children()[0];

    # Gets the number of artists of the country we want that are on the database
    $number_of_artists = (string) $artist_list_xml->attributes()->count;

    # Enquanto a lista não tiver completa.
    while (sizeof($artists_array) < $number_of_artists) {

        sleep(1);

        # Se a lista já não tiver vazia.
        if (sizeof($artists_array) !== 0) {
            $url = "http://musicbrainz.org/ws/2/artist?query=country:$country_code&limit=100&offset=" . sizeof($artists_array);
            $response = file_get_contents($url);

            # If there was a problem with the connection wait 1 second an then try again
            while (!$response) {
                sleep(1);
                $response = file_get_contents($url);
            }

            $xml = simplexml_load_string($response);
            $artist_list_xml = $xml->children()[0];
        }

        foreach ($artist_list_xml as $artist) {
            $artist_array = array();
            $artist_array['name'] = (string) $artist->name;
            $artist_array['mbid'] = (string) $artist->attributes()->id;
            $artist_array['country'] = (string) $artist->area->name;
            $artists_array[] = $artist_array;
        }
    }

    print_r("Done with getting list of artists of" . $country_code);
    return $artists_array;
}

?>
