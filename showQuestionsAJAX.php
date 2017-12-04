<?php

require_once "session.php";

if (!is_logged_in()) {
    die();
}

foreach (get_questions() as $question) {
    echo "<tr>";
    echo "<td>" . $question["galdera"] . "</td>";
    echo "</tr>";
}