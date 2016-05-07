<?php

function simpleResponse($response, $outputType, $code) {
    http_response_code($code);
    if ($outputType == "xml") {
        echo "<message>$response</message>";
    } else {
        echo json_encode(Array("message" => $response));
    }
    exit;
}

function buildSimpleXMLOutput($entity, $array) {
    # Builds a simple XML. $entity is the name o the root element. $array is an array in which each key is the
    # name of each child element and the value corresponds to it's values. Like so:
    #
    # <$entity>
    #   <key1>Value1</key1>
    #   <key2>Value2</key2>
    #   (...)
    # </$entity>

    echo "<$entity>";

        foreach($array as $key=>$value) {
            # Transforma of caracteres do $value para caracteres legais de XML
            $value = htmlentities($value, ENT_XML1, 'UTF-8');
            echo "<$key>$value</$key>";
        }

    echo "</$entity>";
}

function buildSimpleJSONOutput($array) {
    $json = Array();
    foreach($array as $key=>$value) {
      $json[$key] = $value;
    }

    echo json_encode($json);
}

?>
