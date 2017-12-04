<?php

require_once "vendor/nusoap/nusoap.php";
require_once "db.php";
//require_once "vendor/nusoap/class.wsdlcache.php";

$server = new soap_server;

// To generate WSDL
$server->configureWSDL('getQuestion', "http://$_SERVER[HTTP_HOST]/getQuestionWZ.php");
$server->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';


$server->wsdl->addComplexType(
// name
    'QuestionData',
    // typeClass (complexType|simpleType|attribute)
    'complexType',
    // phpType: currently supported are array and struct (php assoc array)
    'struct',
    // compositor (all|sequence|choice)
    'all',
    // restrictionBase namespace:name (http://schemas.xmlsoap.org/soap/encoding/:Array)
    '',
    // elements = array ( name = array(name=>'',type=>'') )
    array(
        'testua' => 'xsd:string',
        'zuzena' => 'xsd:string',
        'zailtasuna' => 'xsd:string'
    )
);


$server->register('getQuestion', // Function name
    array('id' => 'xsd:integer'), // Parameters
    array('return' => 'tns:QuestionData'), // Return value
    'http://soapinterop.org/'); // Namespace

function getQuestion($id)
{
    $emaitza = get_question_for_soap($id);
    if ($emaitza != null) return $emaitza;
    else return array(
        'testua' => '',
        'zuzena' => '',
        'zailtasuna' => 0
    );
}

$server->service(file_get_contents("php://input"));
