<!-- Tweets of artists webservice client -->

<!DOCTYPE html>
<html>
<head>
    <title>Tweets of artists!</title>
    <meta charset="UTF-8" content="text/html">
</head>
<body>

<?php

$artist = $_GET['artist'];

# Makes a call to webservice and stores response in variable.
$url = "http://appserver.di.fc.ul.pt/~aw008/webservices/tweets_artist.php/$artist";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: text/xml"));
$response = curl_exec($ch) or die(curl_error($ch));
curl_close($ch);

$xml = simplexml_load_string($response);

#vai buscar cenas à base de dados
# If what is asked for is a page just to show the tweets of the artist.
if ($_GET['type'] == 'show') {


    echo "<h3 id='tweet_artist_name'><a href=" . $xml->source . ">" . $xml->name . "</a></h3>";
    echo "<p><b>Artist: </b>" . $xml->artist . "</p>";
    echo "<img src='" . $xml->profile_image_url ."'>";
    echo "<p><b>Tweet: </b>" . $xml->text . "</p>";
    echo "<p><b>Time: </b>" . $xml->created_at . "</p>";

    }


#if(!empty($artist)) {
#	//enviar os tweets
#	//fazer função para ir buscar os tweets do $artist
#	$tweets = get_tweets($artist);
#}
#	if(empty($tweets)) {
#		echo "<p>This artist don't have tweets!</p>"
#	}
#else {
#	echo "<p>We don't know this artist, yet</p>"
#}

?>



