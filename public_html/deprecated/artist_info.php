<!DOCTYPE html>

<!-- Artist webservice client -->

<?php

# Gets name of artist from input
$artist = $_GET['artist'];

# Makes a call to webservice and stores response in variable.
$url = "http://appserver.di.fc.ul.pt/~aw008/webservices/artist.php/$artist";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: text/xml"));
$response = curl_exec($ch) or die(curl_error($ch));
curl_close($ch);

$xml = simplexml_load_string($response);

# If what is asked for is a page just to show the info of the artist.
if ($_GET['type'] == 'show') {

    echo "<img class='artist-picture' alt='Image of Artist' property='image' src='$xml->picture_url'>";

    echo "<div id='artist_info_main_div'>";

        echo "<h2 id='artist_name'><span property='name'>$xml->name</span></h2>";
        echo "<p><b>Genre: </b><span property='genre'>" . ucfirst($xml->style) . "</span></p>";
        echo "<p><b>Country: </b><span property='foundingLocation'>$xml->country</span></p>";
        echo "<a href=" . $xml->lastfm_url . ">LastFM</a>";
        echo "<p><b>Number of LastFM listeners: </b>" . $xml->number_of_lastfm_listeners . "</p>";

        $facebook_id = $xml->facebook_id->__toString();
        if (empty($facebook_id)) {
            echo "<p>We don't know the Facebook page of this artist. Yet.</p>";
        } else {
            echo "<a href=https://facebook.com/" . $facebook_id . ">Facebook</a><br>";
            echo "<p><b>Number Of Likes: </b> $xml->number_of_facebook_likes</p>";
        }

        $twitter_url = $xml->twitter_url->__toString();
        if (empty($twitter_url)) {
            echo "<p>We don't know the Twitter page of this artist. Yet.</p>";
        } else {
            echo "<a href=" . $twitter_url . ">Twitter</a>";
            echo "<p><b>Number Of Followers: </b> $xml->number_of_twitter_followers</p>";
        }

        echo "<br><button type='button' id='edit_button'>Edit Info</button>";
        echo "<button type='button' id='delete_button'>Delete Artist</button>";

    echo "</div>";

    echo  "<div id='artist_youtube_video' property='video' typeof='VideoObject'><iframe width='420' height='315' src='http://www.youtube.com/embed/$xml->music_video'></iframe></div>";

    # echo "<div id='artist_tweets'><h2>Last tweets</h2></div>";

# If what is asked for is an edition page.
} elseif ($_GET['type'] == 'edit') {

    echo "<img class='artist-picture' src='" . $xml->picture_url ."'>";
    echo "<h3 id='artist_name'><a href=" . $xml->lastfm_url . ">" . $xml->name . "</a></h3>";

    # Form to edit artist's country
    echo "<form><b>Country: </b>";
    echo "<input type='text' name='country' value='$xml->country'>";
    echo "<input type='submit' value='Edit Country'>";
    echo "</form>";

    echo "<p><b>Number of LastFM listeners: </b>" . $xml->number_of_lastfm_listeners . "</p>";

    # Form to edit artist's style
    echo "<form><b>Style: </b>";
    echo "<input type='text' name='style' value='" . ucfirst($xml->style) . "''>";
    echo "<input type='submit' value='Edit Style'>";
    echo "</form>";

    # Form to edit facebook's page
    echo "<form><b>Facebook Page: </b>";
    echo "<input type='url' name='facebook_id' value='Enter the link to the artist Facebook page'>";
    echo "<input type='submit' value='Edit Facebook Page'>";
    echo "</form>";

    # Form to edit twitter's page
    echo "<form><b>Twitter Page: </b>";
    echo "<input type='url' name='twitter_url' value='Enter the link to the artist Twitter page'>";
    echo "<input type='submit' value='Edit Twitter Page'>";
    echo "</form>";

}
?>
