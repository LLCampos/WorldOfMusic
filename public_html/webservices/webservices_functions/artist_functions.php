<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";

  function GETArtist($artist_name, $outputType) {

    include_once "/home/aw008/database/utility_functions/artist_utility_functions.php";

    if (alreadyInTable($artist_name)) {

      require "/home/aw008/database/connect_to_database.php";

      $query = $conn->prepare("SELECT a.name, a.style, c.name as country, a.picture_url, a.lastfm_url, a.number_of_lastfm_listeners,
                       a.bibliography, a.music_video, a.facebook_id, a.number_of_facebook_likes, a.twitter_url,
                       a.number_of_twitter_followers, a.musicbrainz_id
                FROM Artist as a, Country as c
                WHERE a.name = :artist_name AND c.id = a.country_fk");

      $query->execute(array(':artist_name' => $artist_name)) or die("Query failed: " . $query->errorInfo());

      # Fetch the only row of the result
      $artist_info = $query->fetch(PDO::FETCH_ASSOC);

      if ($outputType == "xml") {
        buildSimpleXMLOutput('artist', $artist_info);
      } else {
        buildSimpleJSONOutput($artist_info);
      }

      require "/home/aw008/database/disconnect_database.php";
    } else {
        include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";
        $response = 'Artist/Group is not in the database.';
        simpleResponse($response, $outputType, 400);
    }
  }

  function PUTArtist($artist_name, $request, $user_id, $outputType) {

    require_once "/home/aw008/database/populate_tables/populate_artist_table.php";
    require_once "/home/aw008/database/addition_deletion_edition_tables/submission_table.php";
    require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
    require_once "/home/aw008/database/users/user_table_functions.php";

    $editable_params = array('facebook_url', 'style', 'country_code');

    foreach ($request as $key => $value) {
      if (in_array($key, $editable_params)) {

        if ($key == 'facebook_url') {
            $attribute_to_change = 'facebook_id';
            include_once "/home/aw008/database/facebook_api/facebook_api_functions.php";
            $new_value = getIDFromURL($value);

        } else if ($key == 'country_code') {
            $attribute_to_change = 'country_fk';
            require_once "/home/aw008/database/utility_functions/country_utility_functions.php";
            $new_value = getIDFromCountryCode($value);

        } else {
            $attribute_to_change = $key;
            $new_value = $value;
        }

        break;
      }
    }

    if (!isset($attribute_to_change)) {
      $response = "You did not send all the information needed for the edition. Check the documentation for more information.";
      simpleResponse($response, $outputType, 404);
    }

    $current_artist_record_id = artistNameToID($artist_name);

    if (isTherePendingSubmitionOnArtist('edition', $current_artist_record_id)) {
      $response = "There is already a pending edition on that artist.";
      simpleResponse($response, $outputType, 409);
    }

    $new_artist_record_id = addEditedArtist($current_artist_record_id, $attribute_to_change, $new_value);

    $edition_id = insertEdition($current_artist_record_id, $new_artist_record_id, $user_id, $attribute_to_change);
    insertSubmissionVote('edition', $edition_id, $user_id);

    # Adds one pending edition to user
    addPendingSubmission("edition", $user_id);

    $response = "Request submitted.";
    simpleResponse($response, $outputType, 200);
  }


  function DELETEArtist($artist_name, $user_id, $outputType) {
    require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
    require_once "/home/aw008/database/users/user_table_functions.php";
    require_once "/home/aw008/database/addition_deletion_edition_tables/submission_table.php";

    $artist_id = artistNameToID($artist_name);

    # If there isn't a non-deleted artist with the name $artist_name on our database.
    if (!$artist_id) {

      $response = 'There is no such artist in our database.';
      simpleResponse($response, $outputType, 404);

    } else {

        if (isTherePendingSubmitionOnArtist('deletion', $artist_id)) {

          $response = "Someone has already tried to delete that artist. Check pending deletions.";
          simpleResponse($response, $outputType, 409);

        } elseif (didUserAlreadyTriedToDeleteArtist($artist_id, $user_id)) {

          $response = "You already tried to delete this artist.";
          simpleResponse($response, $outputType, 409);

        } else {

          # Cria um novo recurso pending deletion
          insertSubmission('deletion', $artist_id, $user_id);
          insertVoteFromArtistID('deletion', $artist_id, $user_id);

          # Adds one pending deletion to user
          addPendingSubmission("deletion", $user_id);

          $response = "Request submitted.";
          simpleResponse($response, $outputType, 200);
        }
    }

  }

  function updateParam($artist_name, $param_to_edit, $new_data) {
    // Update parameter in Artist table. $artist_name is the name of the artist which parameter we which to edit
    // $param_to_edit is a string with the name of the parameter to edit and $new_data is the new data we want
    // to insert in that parameter.

    require "/home/aw008/database/connect_to_database.php";

    if ($param_to_edit == "facebook_id") {

      include "/home/aw008/database/facebook_api/facebook_api_functions.php";

      # transform URL in ID
      $new_data = getIDFromURL($new_data);

      # Also update the number of Facebook likes.
      $likes = numberOfFacebookLikes($new_data);
      updateParam($artist_name, 'number_of_facebook_likes', $likes);
    }

    if ($param_to_edit == "country") {
      include "/home/aw008/database/utility_functions/country_utility_functions.php";

      $param_to_edit = "country_fk";
      $new_data = getIDFromNameofCountry($new_data);
    }

    $sql = $conn->prepare("UPDATE Artist
            SET $param_to_edit = :new_data
            WHERE name= :artist_name");

    $sql->bindParam(':new_data', $new_data);
    $sql->bindParam(':artist_name', $artist_name);

    try {
      $sql->execute();
    } catch(PDOException $e) {
      echo $e->getMessage() . "\n" . $sql;
    }

    require "/home/aw008/database/disconnect_database.php";

  }

function POSTArtist($artist_name, $outputType, $user_id, $request) {

    require_once "/home/aw008/database/populate_tables/populate_artist_table.php";
    require_once "/home/aw008/database/utility_functions/artist_utility_functions.php";
    require_once "/home/aw008/database/addition_deletion_edition_tables/submission_table.php";
    require_once "/home/aw008/database/users/user_table_functions.php";
    require_once "/home/aw008/variables/business_logic_variables.php";

    if (!array_key_exists('country', $request)) {
        $response = "You have to send a 'country' parameter.";
        simpleResponse($response, $outputType, 400);
    }

    include_once "/home/aw008/database/utility_functions/country_utility_functions.php";

    $country_code = $request['country'];
    $country_code = strtolower($country_code);

    if (!countryExists($country_code)) {
        $response = 'Country does not exist';
        simpleResponse($response, $outputType, 404);
    }


    if (getNumberOfPendingSubmissions('addition', $user_id) >= $maximum_submission_of_each_type) {
        $response = "You reached the limit of additions submitted.";
        simpleResponse($response, $outputType, 403);
    }

    # If the artist is already on the Artist table, send message saying that.
    if (alreadyInTable($artist_name)) {

        $artist_id = artistNameToID($artist_name);

        if ($artist_id) {
            $response = "Artist is already in database.";
        } else {
            $response = "Someone has already tried to add that artist.";
        }

        simpleResponse($response, $outputType, 409);

    # If not, inserts Artist in table.
    } else {
        $artist_id = insertArtistInTable($artist_name, $country_code, 1);

        if ($artist_id) {

            # Cria um novo recurso pending_addition
            insertSubmission('addition', $artist_id, $user_id);
            insertVoteFromArtistID('addition', $artist_id, $user_id);
            # Adds one pended addition to user
            addPendingSubmission('addition', $user_id);

            $response = "Request submitted.";
            simpleResponse($response, $outputType, 200);
        } else {
            $response = "Failed.";
            simpleResponse($response, $outputType, 400);
        }
    }

}

?>
