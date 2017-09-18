function scrollToCityChallenge(e) {
    e.preventDefault();
    var offset = $('#cityChallenge').offset();
    $('html, body').animate({
        scrollTop: offset.top - 15,
        scrollLeft: offset.left - 15
    });
    window.location.hash = 'cityChallenge';
}