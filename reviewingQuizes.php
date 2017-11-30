<?php

require_once "session.php";

if (isset($_GET["id"])) {
    if (!is_logged_in() || !is_teacher()) die();

    if (!ctype_digit($_GET["id"])) die(); // Not a positive number

    echo json_encode(get_question_data($_GET["id"]));
    die();
}

if (!is_logged_in() || !is_teacher()) redirect("/login.php");

if (isset($_GET["delete"])) {
    $galdera = $_GET["delete"];
    remove_question($galdera);
}

if (isset($_POST["id"]) && isset($_POST['email']) && isset($_POST['galdera']) && isset($_POST['erantzun_zuzena']) &&
    isset($_POST['erantzun_okerra1']) && isset($_POST['erantzun_okerra2']) && isset($_POST['erantzun_okerra3']) &&
    isset($_POST['zailtasuna']) && isset($_POST['gaia'])) {

    $id = $_POST['id'];
    $email = trim($_POST['email']);
    $galdera = trim($_POST['galdera']);
    $erantzun_zuzena = trim($_POST['erantzun_zuzena']);
    $erantzun_okerra1 = trim($_POST['erantzun_okerra1']);
    $erantzun_okerra2 = trim($_POST['erantzun_okerra2']);
    $erantzun_okerra3 = trim($_POST['erantzun_okerra3']);
    $zailtasuna = trim($_POST['zailtasuna']);
    $gaia = trim($_POST['gaia']);

    if (empty($email) || empty($gaia) || empty($erantzun_zuzena) || empty($erantzun_okerra1) ||
        empty($erantzun_okerra2) || empty($erantzun_okerra3) || empty($zailtasuna) || empty($gaia)) {
        $error = "All fields marked with * are required";
        die();
    }

    if (!preg_match("/[a-zA-Z]{2,}[0-9]{3}@(ikasle\.)?ehu\.eu?s/", $email)) {
        $error = "Invalid email";
        die();
    }
    if (strlen($galdera) < 10) {
        $error = "The question must be at least 10 characters long.";
        die();
    }
    if (intval($zailtasuna) < 1 || intval($zailtasuna) > 5) {
        $error = "The difficulty must be between 1 and 5.";
        die();
    }
    if (update_question($id, $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia)) {
        $result = "Correctly updated!";
    } else {
        $error = "Could not update the database";
    }
}


?>


<!DOCTYPE html>
<html>
<head>
    <?php
    require_once "parts/head.php";
    ?>
    <script language="JavaScript">
        function init_function() {
            $('.modal').modal();
            $('#question_select').material_select();
            $('#question_select').change(function (event) {
                var target = $(event.target);
                $.getJSON("reviewingQuizes.php",
                    {
                        id: target.val()
                    }
                ).done(function (data) {
                    $('#email').val(data.email);
                    $('#galdera').val(data.galdera);
                    $('#erantzun_zuzena').val(data.ez);
                    $('#erantzun_okerra1').val(data.eo1);
                    $('#erantzun_okerra2').val(data.eo2);
                    $('#erantzun_okerra3').val(data.eo3);
                    $('#gaia').val(data.gaia);
                    $('#zailtasuna').val(data.zailtasuna);
                    $('#delete').attr("href", "reviewingQuizes.php?delete=" + target.val());
                    $('#hidden_form').show();
                    Materialize.updateTextFields();
                });
            });
        }
    </script>
</head>
<body>

<?php
require_once "parts/header.php"
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h2>Review a question!</h2>
            </div>
        </div>
        <div class="row">
            <form class="col s6 push-s3" action="/reviewingQuizes.php" id="galderenF" name="galderenF" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <select id="question_select" name="id">
                            <option value="" disabled selected>Choose your option</option>
                            <?php
                            foreach (get_question_id_and_texts() as $question) {
                                ?>
                                <option value="<?= $question["id"] ?>"><?= $question["galdera"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <label for="question_select">Materialize Select</label>
                    </div>
                </div>

                <?php
                if (isset($result)) {
                    echo "<div class='col s12 center-align'><h4>" . $result . "</h4></div>";
                }
                ?>

                <div style="<?= isset($error) ? "" : "display: none" ?>" id="hidden_form">
                    <div class="row">

                        <?php
                        if (isset($error)) {
                            echo "<div class='col s12 center-align'><h4>Error: " . $error . "</h4></div>";
                        }
                        ?>
                        <div class="input-field col s12">
                            <input name="email" placeholder="xxxxx123@ikasle.ehu.es" readonly="readonly" id="email" type="text" value=""><!--class="validate" required pattern="[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s"-->
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
                            <a class="btn waves-effect waves-light red modal-trigger" href="#modal1">Delete
                                <i class="material-icons right">delete</i>
                            </a>
                        </div>
                        <div class="col s4 push-s2">
                            <button class="btn waves-effect waves-light" id="submit" type="submit">Submit
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>


<div id="modal1" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this question?</p>
    </div>
    <div class="modal-footer">
        <a id="delete" href="#!" class="btn waves-effect waves-light red">Delete</a>
        <a href="#!" class="modal-close btn waves-effect waves-light green">Keep</a>
    </div>
</div>

<?php
require_once "parts/footer.php";
?>
</body>
</html>
