<?php

function buildXMLOutput($tweets_artist) {
  # Tells browser that message is in xml format
  header("Content-Type: text/xml");

  # Builds xml
  echo "<tweets_artist>";

      foreach($tweets_artist as $key=>$value) {
          # Transforma of caracteres do $value para caracteres legais de XML
          $value = htmlentities($value, ENT_XML1, 'UTF-8');
          echo "<$key>$value</$key>";
      }

  echo "</tweets_artist>";
}

function buildJSONOutput($tweets_artist) {
  # Tells browser that message is in json format
  header("Content-Type: application/json");

  # Builds JSON
  $json = Array("tweets_artist" => Array());
  foreach($tweets_artist as $key=>$value) {
    $json['tweets_artist'][$key] = $value;
  }

  echo json_encode($json); 
}

require "/home/aw008/database/connect_to_database.php";

# Gets what's after the ".php" on the url
# Vai estar (...)/tweets_artist.php e o PATH_INFO vai buscar o que está depois
$path = $_SERVER['PATH_INFO'];

# Decodes special characters
$path_decoded = urldecode($path);

# Divide os vários parâmetros e coloca-os num array
if ($path != null) {
    $path_params = preg_split("/\//", $path);
}



$tweet_artist_name = $conn->quote($path_params[1]);

# Ser o método utilizado para aceder a este webservice fôr o GET, devolve
# informação sobre o tweet do artista no url.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    # Se o url tiver o formato (...)/artist.php/{name_of_artist}/tweet (?)
    if (sizeof($path_params) == 2) {

      $query = "SELECT ta.artist, ta.created_at, ta.profile_image_url, ta.text, ta.source
                FROM TweetsArtist as ta
                WHERE ta.artist = $tweet_artist_name;";

      $result = $conn->query($query, PDO::FETCH_ASSOC) or die("Query failed: " . $conn->errorInfo());
      #Fetch the only row of the result
      #$tweet_info = $result->fetch();

      $tweet_info = mysql_fetch_row($result);

      $all_tweets = $tweet_info[0] . " " . $tweet_info[1];

      #$all_results = array();
      #while ($row = mysql_fetch_assoc($result)) {
      #     // Append all rows to an array
      #  $all_results[] = $row;
      #  print_r($all_results[0]);
#
#      #  for ($i = 0; $i < 16; $i++){
#      #    $artistPart = $all_results[$i]['artist'];
#      #    $datePart = $all_results[$i]['created_at'];
#      #    $imagePart = $all_results[$i]['profile_image_url'];
#      #    $sourcePart = $all_results[$i]['source'];
#
#
#
#      #    $tweet_info = $artistPart . " " . $datePart . " " . $imagePart . " " . $sourcePart;
#
          
          #if ($_SERVER['HTTP_ACCEPT'] == 'text/xml') {
          #  buildXMLOutput($tweet_info);
          #} else {
          #  buildJSONOutput($tweet_info);
          #}
        }
      }

     
      if ($_SERVER['HTTP_ACCEPT'] == 'text/xml') {
        buildXMLOutput($all_tweets);
      } else {
        buildJSONOutput($all_tweets);
      }


    }
}

require "/home/aw008/database/disconnect_database.php";
?>

