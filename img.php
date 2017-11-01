<?php

require_once "db.php";
require_once "util.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $img = base64_decode(get_user_image($id));

    $finfo = new finfo(FILEINFO_MIME);
    header('Content-Type: ' . $finfo->buffer($img));

    echo $img;
} else if (isset($_GET["qid"])) {
    $id = $_GET["qid"];

    $img = base64_decode(get_quiz_image($id));

    $finfo = new finfo(FILEINFO_MIME);
    header('Content-Type: ' . $finfo->buffer($img));

    echo $img;
}