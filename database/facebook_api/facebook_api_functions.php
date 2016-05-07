<?php


function generateAppAccessToken() {

    require "/home/aw008/variables/sensible_info.php";

    $params = array(
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                    "grant_type" => "client_credentials"
        );

    $encoded_params = array();

    foreach ($params as $k => $v){
      $encoded_params[] = urlencode($k).'='.urlencode($v);
    }

    $url = "https://graph.facebook.com/oauth/access_token?".implode('&', $encoded_params);

    $rsp = file_get_contents($url);

    $app_access_token = explode('=', $rsp)[1];

    return $app_access_token;
}

function obtainFacebookPageID($thing) {
    # Makes a Facebook search for $thing, which is a string, and returns the first page that comes as a result or
    # returns false if there is no result.

    $app_access_token = generateAppAccessToken();

    $params = array(
                    "q" => $thing,
                    "type" => "page",
                    "access_token" => $app_access_token
    );

    $encoded_params = array();

    foreach ($params as $k => $v){
      $encoded_params[] = urlencode($k).'='.urlencode($v);
    }

    $url = "https://graph.facebook.com/search?".implode('&', $encoded_params);

    $rsp = file_get_contents($url);
    $rsp_obj = json_decode($rsp);

    $rsp_array = $rsp_obj->data;

    if (empty($rsp_array)) {

        return false;
    } else {
        $id = $rsp_array[0]->id;
        return $id;
    }


}

function obtainFacebookPageURL($thing) {
    # Makes a Facebook search for $thing, which is a string, and returns the first page that comes as a result or
    # returns false if there is no result..

    $id = obtainFacebookPageID($thing);

    if ($id) {
        return "https://facebook.com/" . $id;
    }
}

function numberofFacebookLikes($id) {
    // Returns the number of likes of the Facebook page with the if $id

    $app_access_token = generateAppAccessToken();

    $url = "https://graph.facebook.com/$id?fields=likes&access_token=$app_access_token";

    $rsp = file_get_contents($url);
    $rsp_obj = json_decode($rsp);

    return $rsp_obj->likes;
}

function getIDFromURL($page_url) {
    // Returns the ID of the Facebook page url given in $url
    // URL is in format https://www.facebook.com/{name of artist} or http://www.facebook.com/{name of artist}

    $app_access_token = generateAppAccessToken();

    $url = str_replace("www.facebook.com", "graph.facebook.com", $page_url);
    $url = str_replace("http://", "https://", $url);

    # strips off parameters like the "?fref=ts" that sometimes appear on the url
    $url = strtok($url, '?');

    $url = $url . "?access_token=$app_access_token";

    $rsp = file_get_contents($url);
    $rsp_obj = json_decode($rsp);

    return $rsp_obj->id;

}

?>
