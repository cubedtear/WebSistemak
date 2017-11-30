$(function () {

    $("#load_questions").click(function () {
        xhro = new XMLHttpRequest();
        xhro.onreadystatechange = function () {
            if (xhro.readyState == 4) { // Ready
                var erantzuna = xhro.responseText;
                $("#question-wrapper").html(erantzuna);
            }
        };

        xhro.open("GET", "/showQuestionsAJAX.php");
        xhro.send(null);
    });

    $("#galderenF").submit(function (t) {
        $("#emaitza").html("");
        $.post('addQuestionAJAX.php', $('#galderenF').serialize()).done(function (result) {
            $("#emaitza").html(result);
        });
        return false;
    });

    $.get('handlingQuizes.php?myquestions').done(function (result) {
        $("#question_count").html(result);
    });

    $.get('handlingQuizes.php?user_count').done(function (result) {
        $("#user_count").html(result);
    });

    setInterval(function () {
        $.get('handlingQuizes.php?myquestions').done(function (result) {
            $("#question_count").html(result);
        });
        $.get('handlingQuizes.php?user_count').done(function (result) {
            $("#user_count").html(result);
        });
    }, 20000)
});