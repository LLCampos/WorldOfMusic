<!--Country webservice client -->

<ul>

<?php

# Gets name of country from input
$country = $_GET['country'];
$order = $_GET['order'];

$country = urlencode($country);

# Makes a call to webservice and stores response in variable.
$url = "http://appserver.di.fc.ul.pt/~aw008/webservices/country.php/$country/artists?order=$order";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: text/xml"));
$response = curl_exec($ch) or die(curl_error($ch));
curl_close($ch);

$xml = simplexml_load_string($response);

#Sends list of artists.
foreach ($xml->artist as $artist) {
    echo "<li>" . $artist->name . "</li>";
}

?>

</ul>
