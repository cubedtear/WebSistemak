<?php

require_once "session.php";

if (isset($_GET["id"]) && isset($_GET["selected"]) && isset($_GET["nick"])) {
    $id = $_GET["id"];
    $selected = $_GET["selected"];
    $nick = trim($_GET["nick"]);
    if (!isset($_SESSION["except"])) {
        $_SESSION["except"] = array($id);
    } else {
        $_SESSION["except"][] = $id;
    }

    $question = get_question_for_soap($id);
    if ($selected === $question["zuzena"]) {
        if (!empty($nick)) {
            user_responded_correctly($id, $nick);
        }
        echo "Ondo";
    } else {
        echo "Gaizki";
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
    <script language="JavaScript">
        function init_function() {
            $('#send').click(function () {
                $('#result').hide();
                var selected = $("input[name=answers]:checked").val();
                var id = $('#question_id').val();
                var nick = $('#nick').val();
                $.get("OnePlay.php", {id: id, selected: selected, nick: nick}, function (response) {
                    if (response === "Ondo") {
                        $('#result_message').html("You are correct!");
                        $('#result_message').css("color", "darkgreen");
                    } else {
                        $('#result_message').html("Try again!");
                        $('#result_message').css("color", "red");
                    }
                    $('#result').show();
                    $('#new_question').show();
                });
            });
        }
    </script>
</head>
<body>
<?php
require_once "parts/header.php";
?>


<main>
    <div class="container">
        <div class="row">
            <form class="col s6 push-s3">
                <?php
                if (!isset($_SESSION["except"])) {
                    $except = array();
                } else {
                    $except = $_SESSION["except"];
                }
                $question = get_random_question($except);
                if (is_null($question)) {
                    ?>
                    <div class="row" style="margin-top: 16px">
                        <div class="col s12 center-align input-field">
                            <h4>There are no more questions for you!</h4>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 16px">
                        <div class="col s12 center-align input-field">
                            <h4><a href="login.php">Maybe create a new one?</a></h4>
                        </div>
                    </div>
                    <?php
                } else {
                    $erantzunak = array($question["ez"], $question["eo1"], $question["eo2"], $question["eo3"]);
                    shuffle($erantzunak);
                    ?>
                    <input type="hidden" value="<?= $question["id"] ?>" id="question_id">
                    <div class="row" style="margin-top: 16px">
                        <div class="col s12 center-align input-field">
                            <input type="text" name="nick" id="nick">
                            <label for="nick">Choose a nickname</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 center-align">
                            <h4><?= $question["galdera"] ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <?php
                            $i = 0;
                            foreach ($erantzunak as $erantzuna) {
                                ?>
                                <div class="row">
                                    <div class="col s10 push-s2">
                                        <input name="answers" type="radio" id="radio<?= $i ?>" class="with-gap" value="<?= $erantzuna ?>"/>
                                        <label for="radio<?= $i ?>"><?= $erantzuna ?></label>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                            ?>
                        </div>
                        <div class="col s6">
                            <img src='/img.php?qid=<?= $question['id'] ?>' style='max-width: 160px; max-height: 160px'>
                        </div>
                    </div>

                    <div class="row" id="result" style="display: none">
                        <div class="col s6 push-s3 center-align">
                            <h5 id="result_message"></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s6 push-s3 center-align">
                            <a class="btn btn-block green" href="#" id="send">Check</a>
                        </div>
                    </div>

                    <div class="row" id="new_question" style="display: none;">
                        <div class="col s6 push-s3 center-align">
                            <a class="btn btn-block" href="OnePlay.php" id="send">Get another question</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
</main>


<?php
require_once "parts/footer.php";
?>
</body>
</html>
