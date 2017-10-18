$(function () {

    $.fn.goTo = function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top-20 + 'px'
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

    $('#galderenF').submit(function () {
        if ($.trim($('#galdera').val()).length < 10) {
            $('#galdera').addClass("invalid").goTo();
            return false;
        } else {
            $('#galdera').removeClass("invalid");
        }
        return true;
    })
});