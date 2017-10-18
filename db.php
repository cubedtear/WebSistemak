<?php

include_once "dbpassword.php";
if (!isset($dbPassword)) {
    echo "Error with the DB";
    die();
}
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
}