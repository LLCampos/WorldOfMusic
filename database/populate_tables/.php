<?php


function getListofAllTweetsOfTheArtist($artist) {


	include "twitteroauth/twitteroauth.php";

	$consumer_key = "zAKSAZU2vlmqldzT62a4HR2MJ";
	$consumer_secret = "wnDoJrVTVzUtOMdcTqAxWeKsek13f89Z9Hx0BCkpQaxSKuolm0";
	$access_token = "714436267392544768-lbrgtzeteE2i7X6xkVUydcSQRo5saqS";
	$access_token_secret = "2pEGzexup0DLTjtRVT84zrKZpf3W7Q2QD0STayqbzuxE7";
	
	$twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

    $tweets = $twitter->get("https://api.twitter.com/1.1/search/tweets.json?q='$artist'&result_type=recent&count=15");


    $tweets_info = json_decode($rsp);

    $array_tweet = array();

    foreach ($tweets_info->statuses as $t) {
    	$data = $t->created_at;
    	$tweet = $t->text;
    	$image = $t->profile_image_url;
    	$hastags = $t->hastags;
    	$array_tweet[] = array('date' => $data, "tweet" => $tweet, "profile_pic" => $image, "hastags" => $hastags);
    }


    return $array_tweet;
}

function populateTweetsArtistsTable($artist, $array_of_tweet) {

    include "/home/aw008/database/connect_to_database.php";

    $artist = $conn->quote($artist);
    $data = $conn->quote($array_of_tweet['date']);
    $tweet = $conn->quote($array_of_tweet['tweet']);
    $profile_pic = $conn->quote($array_of_tweet['profile_pic']);
    $hastags = $conn->quote($array_of_tweet['hastags']);


     $sql = "insert into TweetsArtist(artist, created_at, profile_image_url, text, hastags) values ($artist, $data, $profile_pic, $tweet,
     $hastags);";


    try {
        $conn->exec($sql);
        return true;
    } catch(PDOException $e) {
        echo $artist_array['name'] . " " . $e->getMessage() . "\n";
    }

    include "/home/aw008/database/disconnect_database.php";
}


echo getListofAllTweetsOfTheArtist("Oasis");
?>
