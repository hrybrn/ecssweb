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

function showMember(id) {

    var relPath = "../"
    var name = $('#button' + id).text();
    
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
        success: function(member){
            //image setup
            $('#societyImage').prop('src',relPath + member.Image);
            
            //table data
            var html = "<table>";

            delete member.Image;
            
            var links= '<tr><td>Links</td><td><div>';
            
            $.each(member, function(key,value){
                if(key == "Name"){
                    html += '<tr><td colspan="2">\n\
                                <h1>' + value + '</h1>\n\
                             </td></tr>'
                }
                else if(value.substring(0,4) == "http"){
                    links += '<a id="societyLink" href="' + value + '">' + key + '</a>';
                }
                else if(value.substring(0,6) == "mailto"){
                    html += '<td>' + key + '</td>\n\
                             <td>\n\
                                <a id="societyLink" href="' + value + '">' + value.substring(7,50) + '</a>\n\
                             </td>';
                }
                else {
                    html += '\
                            <tr>\n\
                                <td>' + key + '</td>\n\
                                <td>' + value + '</td>\n\
                            </tr>';
                }
            });
            
            links += "</div></td></tr>";
           // html += "</table>";
            html += links;
            $('#societyTable').html(html);
        }   
    });
}