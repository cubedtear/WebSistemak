<?php


require_once __DIR__ . "/session.php";
require_once "plantilla.php";
require_once "util.php";


if (isset($_GET["myquestions"])) {


    if (!is_logged_in()) {
        echo "You are not logged in";
        die();
    }

    $my_questions = get_my_question_count(get_user_id());
    $total = get_questions_count();

    echo $my_questions . " / " . $total;
    die();
}

if (isset($_GET["user_count"])) {
    // TODO User count
    echo "";
    die();
}

if (!is_logged_in() || !is_user()) {
    redirect("/login.php");
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
        header("Location: addQuestionWithImages.php?error=" . urlencode("All fields marked with * are required"));
        die();
    }

    if (!preg_match("/[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s/", $email)) {
        redirect("addQuestionWithImages.php?error=" . urlencode("Invalid email"));
        die();
    }
    if (strlen($galdera) < 10) {
        redirect("addQuestionWithImages.php?error=" . urlencode("The question must be at least 10 characters long."));
        die();
    }
    if (intval($zailtasuna) < 1 || intval($zailtasuna) > 5) {
        redirect("addQuestionWithImages.php?error=" . urlencode("The difficulty must be between 1 and 5."));
        die();
    }


    require_once "db.php";

    $stmt = $mysqli->stmt_init();
    $stmt->prepare("INSERT INTO Quiz.Questions (email, question, correct_answer, wrong_answer1, wrong_answer2, wrong_answer3, difficulty, topic, img) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $argazki = get_default_quiz_image();
    if (strlen($_FILES["file"]["name"]) > 0) {
        $data = resize_image($_FILES["file"]);
        $argazki = base64_encode($data);
    }
    $stmt->bind_param("ssssssiss", $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia, $argazki);

    if (!$stmt->execute()) {
        orri_sinple("<h3>Your question cannot be added, wanna try again? <a href='/addQuestionWithImages.php'>Do it here!</a></h3>");
    } else {
        orri_sinple("<h3>Your question has been recorded successfully.<br>If you want to see all the questions, <br><ul><li><a href='/showQuestionsWithImages.php'>From the DB!</a></li></h3>");
    }

    die();
}
?>

<!DOCTYPE html>
<html>
<head>

    <?php
    require_once "parts/head.php";
    ?>
    <script language="JavaScript" src="js/lab5.js" defer></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        .input-field label {
            width: 100%;
        }

        .input-field label:after {
            padding-top: 4px;
        }
    </style>
</head>
<body>
<?php

require_once "parts/header.php";

?>


<main>
    <div class="row">
        <form class="col s4 push-s1" action="/addQuestionWithImages.php" id="galderenF" name="galderenF" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col s12 center-align">

                    <h2>Add a quiz!</h2>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    echo "<div class='col s12 center-align'><h4>Error: " . $_GET["error"] . "</h4></div>";
                }
                ?>
                <div class="input-field col s12">
                    <input name="email" placeholder="xxxxx123@ikasle.ehu.es" readonly="readonly" id="email" type="text" value="<?= get_user_email(get_user_id()) ?>"><!--class="validate" required pattern="[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s"-->
                    <label for="email" data-error="Wrong email">Email *</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="galdera" name="galdera" class="materialize-textarea validate" required minlength="10"></textarea><!---->
                    <label for="galdera" data-error="At least 10 characters">Question *</label>
                </div>
                <div class="input-field col s6">
                    <input name="erantzun_zuzena" id="erantzun_zuzena" type="text" class="validate" required pattern=".*[^ ].*"><!---->
                    <label for="erantzun_zuzena" data-error="This field is required!">Correct answer *</label>
                </div>
                <div class="input-field col s6">
                    <input name="erantzun_okerra1" id="erantzun_okerra1" type="text" class="validate" required pattern=".*[^ ].*"><!---->
                    <label for="erantzun_okerra1" data-error="This field is required!">Wrong answer 1 *</label>
                </div>
                <div class="input-field col s6">
                    <input name="erantzun_okerra2" id="erantzun_okerra2" type="text" class="validate" required pattern=".*[^ ].*"><!---->
                    <label for="erantzun_okerra2" data-error="This field is required!">Wrong answer 2 *</label>
                </div>
                <div class="input-field col s6">
                    <input name="erantzun_okerra3" id="erantzun_okerra3" type="text" class="validate" required pattern=".*[^ ].*"><!---->
                    <label for="erantzun_okerra3" data-error="This field is required!">Wrong answer 3 *</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" name="zailtasuna" id="zailtasuna" value="1" class="validate" min="1" max="5" step="1" required><!---->
                    <label for="zailtasuna" data-error="Must be a number between 1 and 5">What is the difficulty of this question?</label>
                </div>
                <div class="input-field col s6">
                    <input id="gaia" name="gaia" type="text" class="validate" required pattern=".*[^ ].*"><!---->
                    <label for="gaia" data-error="This field is required!">What is the topic of this quiz? *</label>
                </div>
                <div class="input-field col s12 file-field">
                    <div class="btn">
                        <span>Related image</span>
                        <input name="file" type="file" id="fitxategia" accept="image/*">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text">
                    </div>
                </div>
                <div class="col s6 push-s3">
                    <img id="aurreikusi" src="/favicon.ico" style="max-width:100%;display: none">
                </div>
                <div class="col s12 center-align" id="emaitza"></div>
                <div class="col s4 push-s2">
                    <button class="btn waves-effect waves-light" id="reset" type="reset" name="reset">Reset
                        <i class="material-icons right">settings_backup_restore</i>
                    </button>
                </div>
                <div class="col s4 push-s2">
                    <button class="btn waves-effect waves-light" id="submit" type="submit" name="submit">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
        <div class="col s4 push-s2">
            <div class="row">
                <div class="col s12 center-align">
                    <h2>Questions</h2>
                </div>
            </div>
            <div class="row">
                <div class="col s4 push-s4 center-align">
                    <button class="btn waves-effect waves-light" id="load_questions" type="button" name="submit">Load
                        <i class="material-icons right">autorenew</i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col s12 center-align">
                    My questions / Total questions: <span id="question_count"></span>
                </div>
                <div class="col s12 center-align">
                    Users logged in: <span id="user_count"></span>
                </div>
            </div>
            <div class="row">
                <table class="col s12 bordered highlight quizzes" style="background-color: rgba(212, 212, 212, 0.5); border-radius: 16px">
                    <thead>
                    <tr>
                        <th>Question</th>
                    </tr>
                    </thead>
                    <tbody id="question-wrapper"></tbody>
                </table>
            </div>
        </div>

    </div>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>
