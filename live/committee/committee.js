function showMember(id) {

    var name = $('#button' + id).text();
    //var lang = $.get("lang");

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

    var lang = findGetParameter("lang");
    if(lang === null) {
        lang = "en";
    }
    console.log(lang);

    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: {'name' : name, 'lang' : lang},
        dataType: 'json',
        success: function (member) {
            //image setup
            $('#memberImage').prop('src', relPath + member.Image);

            //table data
            var html = "";

            html +=
                    '<h1>' + member.Name + '\n\
                    <a href="' + member.Facebook + '"><img id="linkIcon" src="' + relPath + 'images/icons/facebook-circle.png"></a>\n\
                    <a href="mailto:' + member.Email + '"><img id="linkIcon" src="' + relPath + 'images/icons/email-circle.png"></a></h1>\n\
                    <h3>' + member.RoleDisplayName + '</h3>\n\
                    <i>' + member.RoleDescription + '</i>\n\
                    <p>' + member.Manifesto + '</p>';

            $('#memberTable').html(html);
        }
    });
}