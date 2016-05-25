<?php

function simpleResponse($response, $outputType, $code) {
    http_response_code($code);
    if ($outputType == "xml") {
        echo "<message>$response</message>";
    } else {
        $json = json_encode(Array("message" => $response));
        $output = checkIfCallback($json);
        echo $output;
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
    $json_array = Array();
    foreach($array as $key=>$value) {
      $json_array[$key] = $value;
    }

    $json = json_encode($json_array);

    $output = checkIfCallback($json);

    echo $output;
}

function checkIfCallback($json) {

    if (array_key_exists('callback', $_GET)) {
        $callback = $_GET['callback'];
        return $callback . '(' . $json . ')';
    } else {
        return $json;
    }
}

?>
