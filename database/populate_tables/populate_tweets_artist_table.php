<?php


function getListofAllTweetsOfTheArtist($artist) {
    date_default_timezone_set("Europe/Lisbon");

    include "twitteroauth/twitteroauth.php";
    require "/home/aw008/variables/sensible_info.php";

    $consumer_key = $twitter_consumer_key;
    $consumer_secret = $twitter_consumer_secret;
    $access_token = $twitter_access_token;
    $access_token_secret = $twitter_access_token_secret;

    $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

    $tweets = $twitter->get("https://api.twitter.com/1.1/search/tweets.json?q='$artist'&result_type=recent&count=15");


    $array_tweet = array();

    foreach ($tweets->statuses as $t) {
        $created_at = $t->created_at;
        $created_at = strtotime($created_at);
        $mysqldate = date('Y-m-d H:i:s',$created_at);
        $tweet = $t->text;
        $image = $t->user->profile_image_url;
        $source = $t->source;
        $array_tweet[] = array('date' => $mysqldate, "tweet" => $tweet, "profile_pic" => $image, "source" => $source);
    }


    return $array_tweet;
}



function populateOneTweetArtistsTable($artist, $array_of_tweet) {

    include "/home/aw008/database/connect_to_database.php";

    $artist = $conn->quote($artist);
    $data = $conn->quote($array_of_tweet['date']);
    $tweet = $conn->quote($array_of_tweet['tweet']);
    $profile_pic = $conn->quote($array_of_tweet['profile_pic']);
    $source = $conn->quote($array_of_tweet['source']);


    $sql = "insert into TweetsArtist(artist, created_at, profile_image_url, text, source) values ($artist, $data, $profile_pic, $tweet, $source);";


    try {
        $conn->exec($sql);
        return true;
    } catch(PDOException $e) {
        echo $artist_array['name'] . " " . $e->getMessage() . "\n";
    }

    include "/home/aw008/database/disconnect_database.php";
}

function getSubarrayTweet($artist) {


    $array_of_tweets = getListofAllTweetsOfTheArtist($artist);

    foreach ($array_of_tweets as $subarray){
        populateOneTweetArtistsTable($artist, $subarray);

    }
}

#print_r (getListofAllTweetsOfTheArtist("Biffy Clyro"));

#print_r (populateOneTweetArtistsTable("Biffy Clyro",getListofAllTweetsOfTheArtist("Biffy Clyro"));

print_r (getSubarrayTweet("Biffy Clyro"));
?>
