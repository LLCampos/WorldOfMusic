<?php

require "../connect_to_database.php";

$sql_create_table =
            "create table TagsToIgnore (
            tag varchar(200)
            );";

try {
    $conn->exec($sql_create_table);

} catch(PDOException $e) {
    echo "Erro!" . $e->getMessage();
}



require "../disconnect_database.php";

?>
