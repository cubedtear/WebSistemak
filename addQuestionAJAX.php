<?php


require_once __DIR__ . "/session.php";
require_once "util.php";

if (!is_logged_in()) {
    echo "You must be logged in";
    die();
}

if (isset($_POST['email']) && isset($_POST['galdera']) && isset($_POST['erantzun_zuzena']) &&
    isset($_POST['erantzun_okerra1']) && isset($_POST['erantzun_okerra2']) && isset($_POST['erantzun_okerra3']) &&
    isset($_POST['zailtasuna']) && isset($_POST['gaia'])) {

    $email = trim(get_user_email(get_user_id()));
    $galdera = trim($_POST['galdera']);
    $erantzun_zuzena = trim($_POST['erantzun_zuzena']);
    $erantzun_okerra1 = trim($_POST['erantzun_okerra1']);
    $erantzun_okerra2 = trim($_POST['erantzun_okerra2']);
    $erantzun_okerra3 = trim($_POST['erantzun_okerra3']);
    $zailtasuna = trim($_POST['zailtasuna']);
    $gaia = trim($_POST['gaia']);

    if (empty($email) || empty($gaia) || empty($erantzun_zuzena) || empty($erantzun_okerra1) ||
        empty($erantzun_okerra2) || empty($erantzun_okerra3) || empty($zailtasuna) || empty($gaia)) {
        echo "All the fields are required";
        die();
    }

    if (!preg_match("/[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s/", $email)) {
        echo "Invalid email";
        die();
    }
    if (strlen($galdera) < 10) {
        echo "The question must be at least 10 characters long.";
        die();
    }
    if (intval($zailtasuna) < 1 || intval($zailtasuna) > 5) {
        echo "The difficulty must be between 1 and 5.";
        die();
    }


    require_once "db.php";

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO Questions (email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic, img) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $argazki = get_default_quiz_image();
    $stmt->bind_param("ssssssiss", $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia, $argazki);

    $result = $stmt->execute();

    if ($result) {
        echo "Question added to the DB";
    } else {
        echo "Error adding the question to the DB";
    }

    die();
}
?>