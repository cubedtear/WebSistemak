<?php

require_once "db.php";

function redirect($url) {
    header('location: '. get_link($url));
    die();
}

function get_link($url) {
    if (isset($_GET["token"])) {
        if (strpos($url, "?") === false) {
            return $url . "?token=" . $_GET["token"];
        } else {
            return $url . "&token=" . $_GET["token"];
        }
    } else {
        return $url;
    }
}

function is_logged_in() {
    if (!isset($_GET["token"])) return false;
    return is_token_valid($_GET["token"]);
}

function log_out() {
    if (is_logged_in()) {
        remove_token($_GET["token"]);
    }
}
