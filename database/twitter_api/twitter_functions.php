<?php

require "/home/aw008/variables/sensible_info.php";

function twitterAuthenticaton() {
    // Returns a TwitterOAuth object that we can use to make requests to twitter API.

    include "twitteroauth/twitteroauth.php";
    require "/home/aw008/variables/sensible_info.php";

    $consumer_key = $twitter_consumer_key;
    $consumer_secret = $twitter_consumer_secret;
    $access_token = $twitter_access_token;
    $access_token_secret = $twitter_access_token_secret;

    $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

    return $twitter;
}

function getTwitterPageURL($thing, $TwitterOAuth) {
    // Returns the probable Twitter page URL of $thing, $thing being a string.

    $thing = urlencode($thing);

    $url = "https://api.twitter.com/1.1/users/search.json?q=$thing&count=1";

    $results = $TwitterOAuth->get("https://api.twitter.com/1.1/users/search.json?q=$thing&count=1");

    # If there are no results or if there are some problem with the API call, returns false.
    if (empty($results) or !is_array($results)) {
        return false;
    } else {
        $screen_name = $results[0]->screen_name;
        return "https://twitter.com/$screen_name";
    }
}

function getNumberOfTwitterFollowers($url,  $TwitterOAuth) {
    // Returns the number of followers of a Twitter page with the url $url,
    // $url being a string in the format "https://twitter.com/{screen_name}"

    # Divides $url in the "/"
    $url_divided = preg_split("/\//", $url);

    # Gets the last element of the array, which is the screen name.
    $screen_name = end($url_divided);

    $results = $TwitterOAuth->get("https://api.twitter.com/1.1/users/show.json?screen_name=$screen_name");

    $followers_count = $results->followers_count;

    return $followers_count;
}

?>
