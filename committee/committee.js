function showMember(id) {

    var name = $('#button' + id).text();

    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function (member) {
            //image setup
            $('#memberImage').prop('src', relPath + member.Image);

            //table data
            var html = "";
            
            html += 
                    '<h3>' + member.RoleDisplayName + '</h3>\n\
                    <i>' + member.RoleDescription + '</i>\n\
                    <h1>' + member.Name + '\n\
                    <a href="' + member.Facebook + '"><img id="linkIcon" src="' + relPath + 'images/icons/facebook-circle.png"></a>\n\
                    <a href="mailto:' + member.Email + '"><img id="linkIcon" src="' + relPath + 'images/icons/email-circle.png"></a></h1>\n\
                    <p>' + member.Manifesto + '</p>';

            $('#memberTable').html(html);
        }
    });
}