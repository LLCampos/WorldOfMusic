<?php

require "../connect_to_database.php";

$sql_create_table =
            "create table Country (
            id int auto_increment primary key,
            name varchar(200) unique,
            name_alpha2 varchar(5),
            flag_img_url varchar(500),
            capital varchar(200),
            population int,
            region varchar(200),
            subregion varchar(200),
            description_of_music text
            );";

try {
    $conn->exec($sql_create_table);

} catch(PDOException $e) {
    echo "Erro!" . $e->getMessage();
}



require "../disconnect_database.php";

?>
