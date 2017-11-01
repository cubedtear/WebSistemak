$(function () {

    $.fn.goTo = function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top - 20 + 'px'
        }, 'fast');
        return this; // for chaining...
    };

    $('#fitxategia').change(function (event) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#aurreikusi').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(event.target.files[0]);
    });

    $('#signupform').submit(function () {
        var password = $('#password');
        var confirm_password = $('#password2');
        if(password.val() == confirm_password.val()) {
            password.removeClass("invalid");
            confirm_password.removeClass("invalid");
            return true;
        } else {
            password.addClass("invalid");
            confirm_password.addClass("invalid").focus();
            return false;
        }
    });

    /*    $('#galderenF').submit(function () {
            if ($.trim($('#galdera').val()).length < 10) {
                $('#galdera').addClass("invalid").goTo();
                return false;
            } else {
                $('#galdera').removeClass("invalid");
            }
            return true;
        })*/
});