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
            
            $('.link').each(function(){
                $(this).attr("href", changeLanguage($(this).attr("href"), lang));
            })
            
            
            $('.actionLink').each(function(){
                $(this).attr("action", changeLanguage($(this).attr("action"), lang));
            });
        }
    });
}

function changeLanguage(link,lang){
    var currentLink = link.split("?");
                
    $(currentLink).each(function(){
        if(this.substr(0,4) === "lang"){
            this = "lang=" + lang;
        }
        if(this !== currentLink[0]){
            this = "?" + this;
        }
    });
                
    return currentLink.join();
}