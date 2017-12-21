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

?>


<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <img style="margin-top: 16px" src="img/icons/apple-touch-icon-120x120.png">
                <h1>Quiz-Mania</h1>
                <h5>Welcome to the best quizzes of the interwebs!</h5>
            </div>
        </div>
        <div class="row">
            <div class="col s4 push-s2">
                <a class="btn btn-block" href="OnePlay.php">One-Play
                    <i class="material-icons right">videogame_asset</i>
                </a>
            </div>
            <div class="col s4 push-s2">
                <a class="btn btn-block" href="BySubject.php">Playing by subject
                    <i class="material-icons left">videogame_asset</i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s6 push-s3 center-align">
                <h3>Top Quizzers!</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s6 push-s3">
                <table class="bordered highlight quizzes">
                    <thead>
                    <tr>
                        <th>Position</th>
                        <th>Nickname</th>
                        <th>Points</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $index = 1;
                    foreach (get_top_users() as $user) {
                        echo "<tr>";
                        echo "<td>" . $index . "</td>";
                        echo "<td>" . $user['nick'] . "</td>";
                        echo "<td>" . $user['points'] . "</td>";
                        echo "</tr>";
                        $index++;
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
