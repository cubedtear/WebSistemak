<?php

require_once "session.php";

if (!is_logged_in()) {
    redirect("/login.php");
}

function get_question_data_soap($id)
{
    try {
        $bezeroa = new SoapClient("https://$_SERVER[HTTP_HOST]/getQuestionWZ.php?wsdl");
        $emaitza = $bezeroa->getQuestion($id);
        return $emaitza;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_GET["id"])) {
    $galdera = get_question_data_soap($_GET["id"]);
}

?>

<!DOCTYPE html>
<html>
<head>

    <?php
    require_once "parts/head.php";
    ?>
</head>
<body>

<?php
require_once "parts/header.php"
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h2>Find a question!</h2>
            </div>
        </div>
        <div class="row">
            <div class="col s4 push-s4 center-align">
                <form action="getQuestions.php" method="GET">
                    <div class="input-field">
                        <input type="number" id="id" name="id" value="<?= isset($_GET["id"]) ? $_GET["id"] : 48 ?>">
                        <label for="id">Question id: </label>
                    </div>
                    <button class="btn waves-effect waves-light" id="submit" type="submit">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col s6 push-s3 center-align">
                <table class="bordered highlight quizzes">
                    <thead>
                    <tr>
                        <th>Question</th>
                        <th>Correct answer</th>
                        <th>Difficulty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php

                        if (isset($_GET["id"])) {
                            echo "<td>$galdera->testua</td>";
                            echo "<td>$galdera->zuzena</td>";
                            echo "<td>$galdera->zailtasuna</td>";
                        }
                        ?>
                    </tr>
                    </tbody>
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
