<?php
require_once "session.php";
require_once "db.php";

if (isset($_GET["topic"])) {
    $topic = $_GET["topic"];
    $questions = get_3_random_questions_with_topic($topic);
    echo json_encode($questions);
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
        function shuffle(a) {
            var j, x, i;
            for (i = a.length - 1; i > 0; i--) {
                j = Math.floor(Math.random() * (i + 1));
                x = a[i];
                a[i] = a[j];
                a[j] = x;
            }
        }

        function check() {
            var correct = 0;
            var ids = [];
            var erantzunak = [];
            var nick = $('#nick').val();
            $("#results").hide();
            $('input:checked').each(function (index, element) {
                ids.push($(element).attr("name").substring(7));
                erantzunak.push($(element).val());
            });
            $.getJSON("checkAnswers.php", {ids: ids, erantzunak: erantzunak, nick: nick}, function (data) {
                $("#correct_count").html(data.zuzenak);
                $("#difficulty").html(data.zailtasuna);
                $("#results").show();
            });
        }

        function init_function() {
            $('#send').click(check);

            $('#search').on("input", function () {
                var topic = ($('#search').val()).trim();
                $("#results").hide();
                $("#hidden").hide();
                $("#nick_parent").hide();
                if (topic) {
                    $('#quiz-parent').empty();
                    $.getJSON("BySubject.php", {topic: topic}, function (result) {
                        var total = result.length;
                        result.forEach(function (galdera) {
                            var responses = [galdera.ez, galdera.eo1, galdera.eo2, galdera.eo3];
                            shuffle(responses);

                            var txt1 = $('<input type="hidden" value="' + galdera.id + '" id="question_id">\
                                          <div class="row">\
                                              <div class="col s12 center-align">\
                                                  <h4>' + galdera.galdera + '</h4>\
                                              </div>\
                                          </div>');

                            var txt4 = $('<div class="row">\
                                          </div>');

                            var txt5 = $('<div class="col s6"></div>');

                            responses.forEach(function (response, i) {
                                var txt2 = $('<div class="row">\
                                                  <div class="col s10 push-s2">\
                                                      <input name="answers' + galdera.id + '" type="radio" id="radio' + galdera.id + "_" + i + '" class="with-gap" value="' + response + '"/>\
                                                      <label for="radio' + galdera.id + "_" + i + '">' + response + '</label>\
                                                  </div>\
                                              </div>');
                                txt5.append(txt2);
                            });

                            var txt3 = $('<div class="col s6">\
                                              <img src="/img.php?qid=' + galdera.id + '" style="max-width: 160px; max-height: 160px">\
                                          </div>');

                            txt4.append(txt5, txt3);
                            $('#quiz-parent').append(txt1, txt4);
                        });
                        if (result.length > 0) {
                            $("#hidden").show();
                            $("#nick_parent").show();
                        }
                    });
                }
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
            <form>
                <div class="col s6 push-s3 input-field">
                    <input onmouseover="focus();" type="search" id="search" style="" list="topics">
                    <label class="label-icon" for="search" style="margin-left: -32px"><i class="material-icons">search</i></label>
                    <datalist id="topics">
                        <?php
                        foreach (get_topics() as $topic) {
                            echo "<option>$topic</option>";
                        }
                        ?>
                    </datalist>
                </div>
            </form>
        </div>
        <div class="row" style="margin-top: 16px; display: none;" id="nick_parent">
            <div class="col s6 push-s3 center-align input-field">
                <input type="text" name="nick" id="nick">
                <label for="nick">Choose a nickname</label>
            </div>
        </div>
        <div class="row">
            <div class="col s6 push-s3" id="quiz-parent">
                <!-- Hemen gehituko dira galderak -->
            </div>
        </div>
        <div class="row" id="hidden" style="display: none;">
            <div class="col s6 push-s3 center-align">
                <a class="btn btn-block" href="#" id="send">Check!</a>
            </div>
        </div>
        <div class="row" style="display: none" id="results">
            <div class="col s6 push-s3 center-align">
                Correct answers: <span id="correct_count"></span> <br>
                Average difficulty: <span id="difficulty"></span>
            </div>
        </div>
        <h5 id="emaitza"></h5>
    </div>
</main>


<?php
require_once "parts/footer.php";
?>
</body>
</html>
