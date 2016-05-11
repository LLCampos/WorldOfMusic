<?php

function checkClientAcceptMIME() {
    $HTTP_ACCEPT = $_SERVER['HTTP_ACCEPT'];

    if (strpos($HTTP_ACCEPT, 'text/xml') === FALSE) {
        header("Content-Type: application/json");
        $outputType = "json";
    } else {
        header("Content-Type: text/xml");
        $outputType = "xml";
    }

    return $outputType;
}

function getPathParams() {
    # Se no url há alguma info a seguir ao ".php", colocar essa info numa variável
    if (array_key_exists('PATH_INFO', $_SERVER)) {
        $path = $_SERVER['PATH_INFO'];
    }
    else {
        $path = null;
    }

    if (substr($path, -1) == '/') {
        $path = substr($path, 0, -1);
    }

    # Divide os vários parâmetros e coloca-os num array
    if ($path != null) {
        $path_params = preg_split("/\//", $path);
    }
    else {
        $path_params = array();
    }

    return $path_params;
}

function checkAuthentication($request, $outputType) {
    require_once('/home/aw008/server_side_auth/php_sdk.php');

    if (array_key_exists('access_token', $request) && $id = serverSideAuth($request['access_token'])) {
        return $id;
    } else {
        $response = "You need to be authenticated to use this resource.";
        simpleResponse($response, $outputType, 403);
    }
}

# I'm not sure if this works with other global variable other than $_GET
function checksLegalityOfParametersGiven($request_global_variable, $legal_params) {
    # $request_global_variable is a variable like $_GET. $legal_params is an array listing the legal parameters

    $params_given = array_keys($request_global_variable);

    # If ilegal parameters were given
    if (array_diff($params_given, $legal_params)) {
        $response = "Ilegal parameters given.";
        simpleResponse($response, $outputType, 400);
    }
}

function checksNumberParameter($parameter_name) {
    // verifies if parameter value given is a number

    if (isset($_GET[$parameter_name])) {
        if (is_numeric($_GET[$parameter_name])) {
            return $_GET[$parameter_name];
        } else {
            $response = $parameter_name . " parameter has to be a number";
            simpleResponse($response, $outputType, 400);
        }
    }
}

function checkPageParameter($parameters) {
    if (isset($_GET['page'])) {
        return checksNumberParameter('page');
    } else {
        return 1;
    }
}

function checkLimitParameter($parameters) {
    require '/home/aw008/variables/business_logic_variables.php';

    if (isset($_GET['limit'])) {
       return checksNumberParameter('limit');
    } else {
       return $default_value_limit_param;
    }
}

?>
