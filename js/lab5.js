$(function () {

    $("#load_questions").click(function () {
        xhro = new XMLHttpRequest();
        xhro.onreadystatechange = function () {
            if (xhro.readyState == 4) { // Ready
                var erantzuna = xhro.responseText;
                $("#question-wrapper").html(erantzuna);
            }
        };

        xhro.open("GET", "/showQuestionsAJAX.php?token=" + token);
        xhro.send(null);
    });

    $("#galderenF").submit(function (t) {
        $("#emaitza").html("");

        console.log($('#galderenF').serialize());
        $.post('addQuestionAJAX.php?token=' + token, $('#galderenF').serialize()).done(function (result) {
            $("#emaitza").html(result);
        });

        return false;
    });


    $.get('handlingQuizes.php?myquestions&token=' + token).done(function (result) {
        $("#question_count").html(result);
    });

    $.get('handlingQuizes.php?user_count&token=' + token).done(function (result) {
        $("#user_count").html(result);
    });

    setInterval(function () {
        $.get('handlingQuizes.php?myquestions&token=' + token).done(function (result) {
            $("#question_count").html(result);
        });
        $.get('handlingQuizes.php?user_count&token=' + token).done(function (result) {
            $("#user_count").html(result);
        });
    }, 20000)
});