<?php

require_once "session.php";

log_out();

$xml = new SimpleXMLElement(file_get_contents("xml/counter.xml"));
$xml[0] = intval($xml[0])-1;
$xml->asXML("xml/counter.xml");

header("Location: /layout.php");
