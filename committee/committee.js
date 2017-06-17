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

            /*        
            html += html += '<tr>\n\
                            <td>' + member.RoleDisplayName + '</td>\n\
                            <td>' + member.RoleDescription + '</td>\n\
                         </tr>';

            delete member.RoleDescription;
            delete member.RoleDisplayName;
            delete member.Image;

            var links = "<tr>";

            $.each(member, function (key, value) {
                if (value.substring(0, 4) == "http") {
                    links += '<td><a href="' + value + '">' + key + '</a></td>';
                } else {

                    if (key == "Email") {
                        links += '<td><a href="mailto:' + value + '">' + key + '</a></td>';
                    } else {
                        html += '\
                            <tr>\n\
                                <td>' + key + '</td>\n\
                                <td>' + value + '</td>\n\
                            </tr>';
                    }
                }
            });
            

            links += "</tr>";

            html += links;
            */
            $('#memberTable').html(html);
        }
    });
}