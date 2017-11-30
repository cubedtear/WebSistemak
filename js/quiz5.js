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

    if (typeof init_function === 'function') {
        init_function();
    }
});