<?php

function getListOfArtistsFromDBPediaFromCountry($country, $n) {
    # Returns an array of names of artists from the country with the name $country, obtained by DBPedia.
    # $n is the max number of artists.

    require_once("/home/aw008/libraries/sparqllib.php");
    require_once("/home/aw008/database/utility_functions/country_utility_functions.php");

    $country = str_replace(' ', '_', $country);

    $db = sparql_connect( "http://dbpedia.org/sparql" );

    if( !$db ) {
        echo sparql_errno() . ": " . sparql_error(). "\n";
    } else {

        $sparql = "SELECT DISTINCT ?thing, ?name
                    WHERE {?thing rdfs:label ?name .
                    FILTER (lang(?name) = 'en')
                    FILTER (EXISTS {?thing dbo:hometown dbr:$country} ||
                            EXISTS {?thing dbp:origin dbr:$country} ||
                            EXISTS {?thing dbp:origin ?origin .
                                    ?origin dbo:country dbr:$country} ||
                            EXISTS {?thing dbo:hometown ?hometown .
                                    ?hometown dbo:country dbr:$country} ||
                            EXISTS {?thing dbo:birthPlace dbr:$country})
                    FILTER (EXISTS {?thing a schema:MusicGroup} ||
                            EXISTS {?thing a dbo:MusicalArtist})
                    }
                    LIMIT $n";

        $result = sparql_query($sparql);

        if( !$result ) {
            echo sparql_errno() . ": " . sparql_error(). "\n";
        } else {

            $artists_array = array();

            foreach ($result->rows as $row) {
                $artist_name = $row['name']['value'];

                # Transforms strings like "Marisa (singer) to "Marisa"
                $position_of_parenthesis_opening = strpos($artist_name, '(', 0);

                if ($position_of_parenthesis_opening !== FALSE) {
                    $artist_name = substr($artist_name, 0, $position_of_parenthesis_opening-1);
                }

                $artists_array[] = $artist_name;
            }

            return $artists_array;
        }
    }
}





?>
