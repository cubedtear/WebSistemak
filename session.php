<?php

require_once "db.php";
session_start();

abstract class Rol
{
    const User = 0;
    const Teacher = 1;
}

function redirect($url) {
    header('location: '. get_link($url));
    die();
}

function get_user_id() {
    if (!is_logged_in()) return null;
    return $_SESSION["uid"];
}

function get_link($url) {
    return $url;
//    if (isset($_GET["token"])) {
//        if (strpos($url, "?") === false) {
//            return $url . "?token=" . $_GET["token"];
//        } else {
//            return $url . "&token=" . $_GET["token"];
//        }
//    } else {
//        return $url;
//    }
}

function log_in_as($rol, $uid) {
    $_SESSION["rol"] = $rol;
    $_SESSION["uid"] = $uid;
}

function is_logged_in() {
    return isset($_SESSION["uid"]);
}

function is_user() {
    return is_logged_in() && $_SESSION["rol"] == Rol::User;
}

function is_teacher() {
    return is_logged_in() && $_SESSION["rol"] == Rol::Teacher;
}

function log_out() {
    if (is_logged_in()) {
        session_destroy();
    }
}
