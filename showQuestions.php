<?php

require_once "db.php";

$stmt = $mysqli->stmt_init();
$stmt->prepare("SELECT * FROM Quiz.Questions ORDER BY id ASC");

if ($result = $stmt->execute()) {
    $stmt->store_result();
    $stmt->bind_result($id, $email, $galdera, $erantzun_zuzena, $erantzun_okerra1, $erantzun_okerra2, $erantzun_okerra3, $zailtasuna, $gaia, $img);
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
            <table class="bordered highlight">
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
                    <th>Image</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo "<tr>";

                    echo "<td>" . $email . "</td>";
                    echo "<td>" . $galdera . "</td>";
                    echo "<td>" . $erantzun_zuzena . "</td>";
                    echo "<td>" . $erantzun_okerra1 . "</td>";
                    echo "<td>" . $erantzun_okerra2 . "</td>";
                    echo "<td>" . $erantzun_okerra3 . "</td>";
                    echo "<td>" . $zailtasuna . "</td>";
                    echo "<td>" . $gaia . "</td>";
                    echo "<td><img src='" . $img . "' style='max-width: 400px; max-height: 300px'></td>";

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php
    require_once "parts/footer.php";
    ?>
    </body>
    </html>
    <?php
}