<?php

require_once "db.php";
require_once "session.php";

if (!is_logged_in()) {
    redirect("/login.php");
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
    <div class="row" style="margin-top: 16px">
        <div class="col s10 offset-s1">
            <table class="bordered highlight quizzes">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Question</th>
                    <th>Correct Answer</th>
                    <th>Wrong answer 1</th>
                    <th>Wrong answer 2</th>
                    <th>Wrong answer 3</th>
                    <th>Difficulty</th>
                    <th>Topic</th>
                    <th style="text-align: center">Image</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach (get_questions() as &$question) {
                    echo "<tr>";

                    echo "<td>" . $question['email'] . "</td>";
                    echo "<td>" . $question['galdera'] . "</td>";
                    echo "<td>" . $question['ez'] . "</td>";
                    echo "<td>" . $question['eo1'] . "</td>";
                    echo "<td>" . $question['eo2'] . "</td>";
                    echo "<td>" . $question['eo3'] . "</td>";
                    echo "<td>" . $question['zailtasuna'] . "</td>";
                    echo "<td>" . $question['gaia'] . "</td>";
                    echo "<td><img src='/img.php?qid=" . $question['id'] . "' style='max-width: 200px; max-height: 200px'></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>