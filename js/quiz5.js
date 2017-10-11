$(function () {
    $('#fitxategia').change(function (event) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#aurreikusi').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(event.target.files[0]);
    });
});