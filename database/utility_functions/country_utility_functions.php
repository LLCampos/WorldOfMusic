<?php

function outPutCountryCodesList(){

    require "/home/aw008/database/connect_to_database.php";

    $query = "SELECT C.name_alpha2 FROM Country C";

    $result = $conn->query($query, PDO::FETCH_ASSOC) or die("Query failed: " . $conn->errorInfo());

    $result_array = $result->fetchAll(PDO::FETCH_COLUMN);

    require "/home/aw008/database/disconnect_database.php";

    return $result_array;

}

function outPutCountriesList(){

    require "/home/aw008/database/connect_to_database.php";

    $query = "SELECT C.name FROM Country C";

    $result = $conn->query($query, PDO::FETCH_ASSOC) or die("Query failed: " . $conn->errorInfo());

    $result_array = $result->fetchAll(PDO::FETCH_COLUMN);

    require "/home/aw008/database/disconnect_database.php";

    return $result_array;

}

function getIDFromNameofCountry($name_of_country) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM Country WHERE name = :name_of_country");

    try {
        $query->execute(array(':name_of_country' => $name_of_country));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

function getIDFromCountryCode($country_code) {

    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT id FROM Country WHERE name_alpha2 = :country_code");

    try {
        $query->execute(array(':country_code' => $country_code));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $id = $query->fetch()[0];

    return $id;

    require "/home/aw008/database/disconnect_database.php";
}

function getCodeFromNameOfCountry($name_of_country) {
    require "/home/aw008/database/connect_to_database.php";

    $query = $conn->prepare("SELECT name_alpha2 FROM Country WHERE name = :name_of_country");

    try {
        $query->execute(array(':name_of_country' => $name_of_country));
    } catch(PDOException $e) {
        echo $query . " " . $e->getMessage() . "\n";
    }

    $name_alpha2 = $query->fetch()[0];

    return $name_alpha2;

    require "/home/aw008/database/disconnect_database.php";
}

function countryExists($country_code) {
    $countries_list = outPutCountryCodesList();

    return in_array($country_code, $countries_list);
}


?>
