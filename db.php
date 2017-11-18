<?php

require_once "dbpassword.php";
require_once "util.php";

if (!isset($dbPassword)) {
    echo "Error with the DB";
    die();
}
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
}

function get_questions()
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id, email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic FROM Quiz.Questions ORDER BY id ASC");

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($id, $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia);
        $res = array();
        while ($stmt->fetch()) {
            $res[] = array(
                "id" => $id,
                "email" => $email,
                "galdera" => $galdera,
                "ez" => $erantzun_zuzena,
                "eo1" => $erantzun_okerra1,
                "eo2" => $erantzun_okerra2,
                "eo3" => $erantzun_okerra3,
                "zailtasuna" => $zailtasuna,
                "gaia" => $gaia
            );
        }
        return $res;
    }
    return null;
}

function check_credentials($email, $pass)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id FROM Quiz.Users WHERE email = ? AND password = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $pass);
    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id);
            $stmt->fetch();
            return $id;
        }
    }
    return null;
}

function sign_up($email, $name, $username, $pass, $image)
{
    global $mysqli;

    $password = $pass; // TODO Encrypt

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO Users (user, password, email, name, image) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $username, $password, $email, $name, $image);

    if ($result = $stmt->execute()) {
        return $mysqli->insert_id;
    }
    return null;
}

function generateRandomString($length = 80)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_user_token($userid)
{
    global $mysqli;

    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT token FROM Sessions WHERE userid = ? AND ip_addr = ?");
    $stmt->bind_param("is", $userid, $ip);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($token);
            $stmt->fetch();
            return $token;
        }
    }

    $token = generateRandomString();

    $stmt2 = $mysqli->stmt_init();
    $stmt2->prepare("INSERT INTO Sessions (userid, token, ip_addr) VALUES (?, ?, ?)");
    $stmt2->bind_param("iss", $userid, $token, $ip);

    if ($result = $stmt2->execute()) {
        return $token;
    }
    return null;
}

function get_user_from_token($token)
{
    global $mysqli;

    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT userid FROM Sessions WHERE token = ? AND ip_addr = ?");
    $stmt->bind_param("ss", $token, $ip);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($userid);
            $stmt->fetch();
            return $userid;
        }
    }
    return null;
}

function is_token_valid($token)
{
    return !is_null(get_user_from_token($token));
}

function get_user_image($userid)
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT image FROM Users WHERE id = ?");
    $stmt->bind_param("i", $userid);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($img);
            $stmt->fetch();
            return $img;
        }
    }
    return null;
}

function get_user_email($userid)
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT email FROM Users WHERE id = ?");
    $stmt->bind_param("i", $userid);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($email);
            $stmt->fetch();
            return $email;
        }
    }
    return null;
}

function remove_token($token)
{
    global $mysqli;

    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("DELETE FROM Sessions WHERE ip_addr = ? AND token = ?");
    $stmt->bind_param("ss", $ip, $token);

    $stmt->execute();
}

function get_quiz_image($id)
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT img FROM Questions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($img);
            $stmt->fetch();
            return $img;
        }
    }
    return null;
}

function get_my_question_count($token)
{
    global $mysqli;

    $email = get_user_email(get_user_from_token($token));

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id FROM Questions WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        return $stmt->num_rows;
    }
    return 0;
}

function get_questions_count()
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id FROM Questions");

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        return $stmt->num_rows;
    }
    return 0;
}

function get_question_for_soap($id)
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT question, correct_answer, difficulty FROM Questions WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($question, $correct, $diff);
            $stmt->fetch();
            return array("testua" => $question, "zuzena" => $correct, "zailtasuna" => $diff);
        }
    }
    return null;
}