$(function () {

    $.fn.goTo = function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top + 'px'
        }, 'fast');
        return this; // for chaining...
    };

    $('select').material_select();

    $('#fitxategia').change(function (event) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#aurreikusi').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(event.target.files[0]);
    });

    function begiratuHutsakDauden(idAk) {
        var emaitza = true;
        var firstErrored = null;

        idAk.forEach(function (e) {
            var label = $(e).parent().children().last();
            if (!$(e).val()) {
                $(e).addClass('invalid');
                if (!label.attr('data-back')) {
                    label.attr('data-back', label.attr('data-error'));
                }
                label.attr('data-error', 'You must fill this field');
                emaitza = false;
                if (firstErrored === null) firstErrored = $(e)
            } else {
                $(e).removeClass('invalid');
                if (label.attr('data-back')) {
                    label.attr('data-error', label.attr('data-back'));
                }
            }
        });
        if (firstErrored) firstErrored.goTo().focus();
        return emaitza;
    }

    $('#galderenF').submit(function (event) {
        // if (!$('#email').val() || !$('#zailtasuna').val() || !$('#gaia').val()  || !$('#galdera').val() || !$('#erantzun_zuzena').val() || !$('#erantzun_okerra1').val() || !$('#erantzun_okerra2').val() || !$('#erantzun_okerra3').val()) {
        //     alert("You must fill all the required fields!\nThey are marked with an asterisk (*)");
        //     return false;
        // }

        var emaitza = begiratuHutsakDauden(['#email', '#galdera', '#erantzun_zuzena', '#erantzun_okerra1', '#erantzun_okerra2', '#erantzun_okerra3', '#zailtasuna', '#gaia']);

        var emailRegex = /\w+\d\d\d@ikasle\.ehu\.eu?s/;
        if (emailRegex.test($('#email').val())) {
            $('#email').removeClass('invalid');
        } else {
            $('#email').addClass('invalid').goTo().focus();
            return false;
        }

        switch (parseInt($('#zailtasuna').val())) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                break;
            default:
                $('#zailtasuna').addClass('invalid').goTo().focus();
                return false;
        }

        if ($('#galdera').val().length < 10) {
            $('#galdera').addClass('invalid').goTo().focus();
            return false;
        } else {
            $('#galdera').removeClass('invalid');
        }
        if (!emaitza) return false;
        return true;
    })

});