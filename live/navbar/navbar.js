function showDropdown() {
    var state = $('#child').prop('hidden');
    
    $('#child').prop('hidden',!state);
}

function updateFlag(lang){
    $.ajax({
        url: 'getFlag.php',
        type: 'get',
        data: 'lang=' + lang,
        dataType: 'json',
        success: function (imageSource) {
            $('#langIcon').attr("src", imageSource);
        }
    });
}