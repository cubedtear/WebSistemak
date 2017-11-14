<?php

require_once "session.php";

if (!is_logged_in()) {
    die();
}


$xml = new SimpleXMLElement(file_get_contents("xml/questions.xml"));

foreach ($xml->children() as $child) {
    echo "<tr>";
    echo "<td>" . $child->itemBody->p[0] . "</td>";
    echo "</tr>";
}