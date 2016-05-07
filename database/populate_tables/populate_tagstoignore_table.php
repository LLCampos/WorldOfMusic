<?php

function countryTagsToIgnore() {
    # Returns an array of country tags to ignore

    $url = "https://en.wikipedia.org/wiki/List_of_adjectival_and_demonymic_forms_for_countries_and_nations";

    $html = file_get_contents($url);

    $doc = new DOMDocument();

    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXpath($doc);

    $list = $xpath->query('//td/a');

    $country_tags_to_ignore = array();

    foreach ($list as $word) {
        $country_tags_to_ignore[] = strtolower($word->nodeValue);
    }

    return $country_tags_to_ignore;
}


function addTagToIgnore($tag) {
    # inserts the value in $tag to the table TagsToIgnore

    include "../connect_to_database.php";

    $sql = "insert into TagsToIgnore (tag)
            values ({$conn->quote($tag)});";

    try {
        $conn->exec($sql);
        echo "sucess";
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    include "../disconnect_database.php";
}

function addAllCountriesAndDenonymesToTagsToIgnore() {
    $tags_to_ignore = countryTagsToIgnore();

    foreach ($tags_to_ignore as $tag) {
        addTagToIgnore($tag);
    }
}


?>
