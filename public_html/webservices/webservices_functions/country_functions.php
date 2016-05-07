<?php

include_once "/home/aw008/public_html/webservices/webservices_functions/responses_utility_functions.php";

// Para ter a lista de artistas
function outPutListOfArtists($country, $outputType) {
    # Outputs a list of artists of the country $country

    require "/home/aw008/database/connect_to_database.php";

    $legal_params = array('limit', 'order');

    checksLegalityOfParametersGiven($_GET, $legal_params);

    $limit = checksLimitParameter();

    $query = "SELECT A.name
              FROM Artist A, Country C
              WHERE C.id = A.country_fk AND C.name_alpha2 = :country AND A.deleted = 0";


    # Checks if client specified some specific order to receive the ouput.
    if (isset($_GET['order'])) {
        if ($_GET['order'] == 'likes') {
            $query = $query . " ORDER BY A.number_of_facebook_likes DESC";
        } elseif ($_GET['order'] == 'random'){
            $query = $query . " ORDER BY RAND()";
        } else {
            $response = "You didn't use one of the mandatory values for order.";

            simpleResponse($response, $outputType, 400);
        }
    }

    $query = $query . " LIMIT :limit";

    $prepared_query = $conn->prepare($query);

    $prepared_query->bindValue(':country', $country);
    $prepared_query->bindValue(':limit',  $limit);

    $prepared_query->execute() or die("Query failed: " . $conn->errorInfo());

    # Coloca os valors da única coluna num array
    $result_array = array();

    $results = $prepared_query->fetchAll();

    foreach ($results as $row) {
        $result_array[] = $row['name'];
    }

    if ($outputType == "xml") {
        outPutListOfArtistsXML($result_array);
    } else {
        outPutListOfArtistsJSON($result_array);
    }

    require "/home/aw008/database/disconnect_database.php";
}

function outPutListOfArtistsXML($result_array) {

    echo "<artists>";

    foreach ($result_array as $artist) {
        $artist =  htmlspecialchars($artist);
        echo "<artist>";
        echo "<name>" . $artist . "</name>";
        echo "</artist>";
    }

    echo "</artists>";
}

function outPutListOfArtistsJSON($result_array) {

    $json = array("artist" => array());

    foreach ($result_array as $artist) {
        $json['artist'][] = array('name' => $artist);
    }

    echo json_encode($json);
}


//Para ter lista de paises
function outPutCountriesListService($outputType){

    require "/home/aw008/database/connect_to_database.php";
    include_once "/home/aw008/database/utility_functions/country_utility_functions.php";

    $country_codes_array = outPutCountryCodesList();
    $country_names_array = outPutCountriesList();

    if ($outputType == "xml") {
        outPutListOfCountriesXML($country_codes_array, $country_names_array);
    } else {
        outPutListOfCountriesJSON($country_codes_array, $country_names_array);
    }

    require "/home/aw008/database/disconnect_database.php";

}

function outPutListOfCountriesXML($country_codes_array, $country_names_array){

    $number_of_countries = sizeof($country_codes_array);

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo "<countries>";

    foreach (range(0, $number_of_countries-1) as $i) {

        $code = $country_codes_array[$i];
        $name = $country_names_array[$i];

        $name =  htmlspecialchars($name);

        echo "<country>";
        echo "<code>" . $code . "</code>";
        echo "<name>" . $name . "</name>";
        echo "<url>" . "/country/$name" . "</url>";
        echo "</country>";
    }

    echo "</countries>";

}

function outPutListOfCountriesJSON($country_codes_array, $country_names_array) {

    $number_of_countries = sizeof($country_codes_array);

    $json = array("countries" => array());

    foreach (range(0, $number_of_countries-1) as $i) {
        $code = $country_codes_array[$i];
        $name = $country_names_array[$i];

        $json['countries'][] = array('code' => $code, 'name' => $name, "url" => "/country/$code");
    }
    echo json_encode($json);
}


//Para ter info sobre pais em particular
function outPutCountryInfo($country_code, $outputType){

    require "/home/aw008/database/connect_to_database.php";

    $country_code = $conn->quote($country_code);

    $query = "SELECT C.name, C.name_alpha2, C.flag_img_url, C.capital, C.population, C.region, C.subregion, C.description_of_music
            FROM Country C
            WHERE C.name_alpha2 = $country_code";

    $result = $conn->query($query, PDO::FETCH_ASSOC) or die("Query failed: " . $conn->errorInfo());

    $result_array = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($outputType == "xml") {
        outPutCountryInfoXML($result_array);
    }
    else {
        outPutCountryInfoJSON($result_array);
    }

    require "/home/aw008/database/disconnect_database.php";
}

function outPutCountryInfoXML($result_array){

    echo '<?xml version="1.0"?>';
    echo "<country>";

    foreach ($result_array as $info) {
        //Creating the needed variables
        $name = htmlspecialchars($info['name']);
        $name_alpha2 = $info['name_alpha2'];
        $flag_img_url = $info['flag_img_url'];
        $capital = $info['capital'];
        $population = $info['population'];
        $region = $info['region'];
        $subregion = $info['subregion'];
        $description_of_music = $info['description_of_music'];

        echo "<name>" . $name . "</name>";
        echo "<name_alpha2>" . $name_alpha2 . "</name_alpha2>";
        echo "<flag_img_url>" . $flag_img_url . "</flag_img_url>";
        echo "<capital>" . $capital . "</capital>";
        echo "<population>" . $population . "</population>";
        echo "<region>" . $region . "</region>";
        echo "<subregion>" . $subregion . "</subregion>";
        echo "<description_of_music>" . $description_of_music . "</description_of_music>";
    }

    echo "</country>";
}

function outPutCountryInfoJSON($country) {

    foreach($country as $info){
        //Creating the needed variables
        $name = $info['name'];
        $name_alpha2 = $info['name_alpha2'];
        $flag_img_url = $info['flag_img_url'];
        $capital = $info['capital'];
        $population = $info['population'];
        $region = $info['region'];
        $subregion = $info['subregion'];
        $description_of_music = $info['description_of_music'];

        $json = array('name'=>$name, 'name_alpha2' =>$name_alpha2,'flag_img_url'=>$flag_img_url,'capital'=>$capital, 'population'=>$population,
        'region'=>$region, 'subregion'=>$subregion, 'description_of_music'=>$description_of_music);
    }

    echo json_encode($json);
}



?>