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

function remove_question($id)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("DELETE FROM Questions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function get_topics()
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT DISTINCT topic FROM Questions");
    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($gaia);
        $res = array();
        while ($stmt->fetch()) {
            $res[] = $gaia;
        }
        return $res;
    }
    return null;
}

function get_questions()
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id, email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic FROM Questions ORDER BY id ASC");

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

function get_3_random_questions_with_topic($topic)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic FROM Questions WHERE topic = ? ORDER BY RAND() LIMIT 3");
    $stmt->bind_param("s", $topic);
    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($id, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia);
        $res = array();
        while ($stmt->fetch()) {
            $res[] = array(
                "id" => $id,
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

function get_random_question($except)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $query = "SELECT id, email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic FROM Questions WHERE id NOT IN ( ? ) ORDER BY RAND() ASC LIMIT 1";
    $all_ids = "'" . implode($except, "', '") . "'";
    $query = str_replace("?", $all_ids, $query);
    $stmt->prepare($query);

    $stmt->bind_param("s", $all_ids);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($id, $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {
            return array(
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
    }
    return null;
}

function generate_recovery_token($userid)
{
    global $mysqli;

    $token = null;
    do {
        $token = generateRandomString();

        $stmt = $mysqli->stmt_init();
        $stmt->prepare("SELECT id FROM PasswordRecovery WHERE token = ?");
        $stmt->bind_param("s", $token);

        if (!($result = $stmt->execute())) {
            return null;
        }
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO PasswordRecovery (userid, token) VALUES (?, ?)");

    $stmt->bind_param("is", $userid, $token);

    if ($result = $stmt->execute()) {
        return $token;
    }
    return null;
}

function generate_unlock_token($userid)
{
    global $mysqli;

    $token = null;
    do {
        $token = generateRandomString();

        $stmt = $mysqli->stmt_init();
        $stmt->prepare("SELECT id FROM UnlockAccount WHERE token = ?");
        $stmt->bind_param("s", $token);

        if (!($result = $stmt->execute())) {
            return null;
        }
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO UnlockAccount (userid, token) VALUES (?, ?)");

    $stmt->bind_param("is", $userid, $token);

    if ($result = $stmt->execute()) {
        return $token;
    }
    return null;
}

function get_user_id_from_recovery_token($token)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT userid FROM PasswordRecovery WHERE token = ? AND TIMESTAMPDIFF(MINUTE, NOW(), generated_time) < 15 LIMIT 1");
    $stmt->bind_param("s", $token);
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

function get_user_id_from_unlock_token($token)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT userid FROM UnlockAccount WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
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

function change_password($userid, $pass)
{
    global $mysqli;

    $password = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("UPDATE Users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $userid);
    if ($result = $stmt->execute()) {
        return true;
    }
    return false;
}

function unlock_account($userid)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("UPDATE Users SET login_attempts = 0 WHERE id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
}

function get_userid_from_email($email)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id FROM Users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
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

function check_credentials($email, $pass)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();

    $stmt->prepare("SELECT id, password, login_attempts FROM Users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    if ($result = $stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $password, $login_attempts);
            $stmt->fetch();

            if ($login_attempts >= 3) {
                return 0;
            }

            if (password_verify($pass, $password)) {
                unlock_account($id);
                return $id;
            } else {
                $stmt = $mysqli->stmt_init();
                $stmt->prepare("UPDATE Users SET login_attempts = login_attempts+1 WHERE id = ?");
                $stmt->bind_param("s", $id);
                $stmt->execute();
                if ($login_attempts + 1 >= 3) {
                    $userid = get_userid_from_email($email);
                    $token = generate_unlock_token($userid);

                    $to = $email;
                    $subject = "Account locked";
                    $message = "Click the following link to unlock your account <br><a href='https://$_SERVER[HTTP_HOST]/login.php?token=$token'>Unlock!</a>";
                    $headers = 'From: aritzhack@gmail.com' . "\r\n" .
                        'Reply-To: aritzhack@gmail.com' . "\r\n" .
                        "Content-Type:text/html;charset=utf-8";
                    mail($to, $subject, $message, $headers);
                    return 0;
                }
                return -1;
            }
        }
    }
    return -1;
}

function sign_up($email, $name, $username, $pass, $image)
{
    global $mysqli;

    $password = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO Users (user, password, email, name, image) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $username, $password, $email, $name, $image);

    if ($result = $stmt->execute()) {
        return $mysqli->insert_id;
    }
    return null;
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

function get_my_question_count($uid)
{
    global $mysqli;

    $email = get_user_email($uid);

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

function get_question_id_and_texts()
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT id, question FROM Questions ORDER BY id ASC");

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($id, $galdera);
        $res = array();
        while ($stmt->fetch()) {
            $res[] = array(
                "id" => $id,
                "galdera" => $galdera,
            );
        }
        return $res;
    }
    return null;
}

function get_question_data($id)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic FROM Questions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($result = $stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia);
        $stmt->fetch();
        return array(
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
    return null;
}

function user_responded_correctly($qid, $nick)
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    // Sartu erabiltzailea bakarrik dagoeneko ez baldin badago taulan
    // Honetarako `nick` zutabea UNIQUE izan behar du.
    $stmt->prepare("INSERT IGNORE INTO Nicks (nick) VALUES (?)");
    $stmt->bind_param("s", $nick);
    $stmt->execute();

    $stmt = $mysqli->stmt_init();
    // Lortu erabiltzailearen ID-a
    $stmt->prepare("SELECT id FROM Nicks WHERE nick = ?");
    $stmt->bind_param("s", $nick);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nid);
    $stmt->fetch();

    $stmt = $mysqli->stmt_init();
    // Sartu zuzen erantzun duela
    $stmt->prepare("INSERT IGNORE INTO Nicks2Questions (nid, qid) VALUES (?, ?)");
    $stmt->bind_param("ii", $nid, $qid);
    $stmt->execute();
}

function get_top_users()
{
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("SELECT nick, COUNT(qid) AS points FROM Nicks2Questions JOIN Nicks ON Nicks2Questions.nid = Nicks.id GROUP BY (nid) ORDER BY points DESC LIMIT 10");
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nick, $points);
    $result = array();
    while ($stmt->fetch()) {
        $result[] = array("nick" => $nick, "points" => $points);
    }
    return $result;
}

function update_question($id, $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia)
{
    global $mysqli;

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("UPDATE Questions SET email=?, question=?, correct_answer=?, wrong_answer1=?, wrong_answer2=?, wrong_answer3=?, difficulty=?, topic=? WHERE id=?");

    $stmt->bind_param("ssssssisi", $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia, $id);

    return $stmt->execute();
}