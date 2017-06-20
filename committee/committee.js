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
                    <h1>' + member.Name + '</h1>\n\
                    <p>' + member.Manifesto + '</p>\n\
                    <a href="' + member.Facebook + '">Facebook</a><br>\n\
                    <a href="mailto:' + member.Email + '">Email</a><br>\n\
                    <i>' + member.RoleDescription + '</i>'

            $('#memberTable').html(html);
        }
    });
}