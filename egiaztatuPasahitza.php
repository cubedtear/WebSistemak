<?php

require_once "vendor/nusoap/nusoap.php";
//require_once "vendor/nusoap/class.wsdlcache.php";

$server = new soap_server;

// To generate WSDL
$server->configureWSDL('PasswordCheckerService', "http://$_SERVER[HTTP_HOST]/egiaztatuPasahitza.php");
$server->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';

$server->register('check_pass', // Function name
    array('pass' => 'xsd:string'), // Parameters
    array('valid' => 'xsd:string'), // Return value
    'http://soapinterop.org/'); // Namespace

function check_pass($pass)
{
    $lines = explode("\n", file_get_contents("toppasswords.txt"));
    if (in_array($pass, $lines)) {
        return "BALIOGABEA";
    } else {
        return "BALIOZKOA";
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service(file_get_contents("php://input"));
