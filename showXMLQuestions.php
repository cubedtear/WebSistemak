<!DOCTYPE html>
<html>
<head>

    <?php
    require_once "parts/head.php";
    ?>
</head>
<body>

<?php
require_once "parts/header.php";

$xml = new SimpleXMLElement(file_get_contents("xml/questions.xml"));
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h2>List of quizzes!</h2>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <table class="bordered highlight quizzes">
                    <thead>
                    <tr>
                        <th>Question</th>
                        <th>Difficulty</th>
                        <th>Topic</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($xml->children() as $child) {
                        echo "<tr>";
                        echo "<td>" . $child->itemBody->p[0] . "</td>";
                        echo "<td>" . $child["complexity"] . "</td>";
                        echo "<td>" . $child["subject"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
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