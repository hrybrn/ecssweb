$(document).ready(function () {
    $('#navbarLogo').mouseover(function () {
        var box = $(this)
        $(box).addClass('hovered');

        setTimeout(function () {
            $(box).removeClass('hovered');
        }, 5000);
    });
});