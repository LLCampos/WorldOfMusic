<?php


function serverSideAuth($access_token) {
    # Returns user ID if user authenthicated in our app. False otherwise.

    require_once("/home/aw008/libraries/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php");
    require_once "/home/aw008/variables/sensible_info.php";

    # Special token to test the app
    if ($access_token == $special_token ) {
      return '42';

    } else {
      $fb = new Facebook\Facebook ([
        'app_id' => $client_id,
        'app_secret' => $client_secret,
        'default_graph_version' => 'v2.5',
      ]);

      try {
          $fb_response = $fb->get('/me?fields=id', $access_token);
          $id = (string) $fb_response->getDecodedBody()['id'];
          return $id;
      } catch (Exception $e) {
          return false;
      }
    }
  }

?>
