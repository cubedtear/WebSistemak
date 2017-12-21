<?php

require_once "db.php";

$ids = $_GET["ids"];
$erantzunak = $_GET["erantzunak"];
$nick = trim($_GET["nick"]);

if (count($ids) != count($erantzunak)) die();

$zuzenak = 0;
$zailtasunak = 0;

foreach(array_combine($ids, $erantzunak) as $id => $erantzuna) {
    $question = get_question_data($id);
    if ($question == null) continue;

    if ($question["ez"] == $erantzuna) {
        $zuzenak++;
        if (!empty($nick)) {
            user_responded_correctly($id, $nick);
        }
    }
    $zailtasunak += $question["zailtasuna"];
}

$zailtasunak /= count($ids);

echo json_encode(array("zuzenak" => $zuzenak, "zailtasuna" => $zailtasunak));

