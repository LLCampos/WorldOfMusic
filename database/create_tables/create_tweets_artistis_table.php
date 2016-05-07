<?php

function createTweetsArtistsTable() {
    include "../connect_to_database.php";


	$sql_create_table = 
		"create table TweetsArtist (
		id int auto_increment primary key,
		artist varchar(200),
		created_at datetime,
		profile_image_url varchar(500),
		text varchar(200),
		source varchar(500));";


    try {
        $conn->exec($sql_create_table);
    } catch(PDOException $e) {
        echo "Erro!" . $e->getMessage();
    }

    include "../disconnect_database.php";
}

createTweetsArtistsTable()

?>