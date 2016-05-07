<?php

require "/home/aw008/variables/sensible_info.php";

# Server FCUL
$username = "aw008";
$password = $database_password;
$hostname = "localhost";
$database = "aw008";

try {
    $conn = new PDO("mysql:host=$hostname; dbname=$database", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
