function showMember(id){
    
    var name = $('#button' + id).text();
    
    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function(member){
            //image setup
            $('#societyImage').prop('src',relPath + member.Image);
            
            //table data
            var html = "";

            delete member.Image;
            
            var links= "<tr>";
            
            $.each(member, function(key,value){
                if(key == "Name"){
                    html += '<tr><td>\n\
                                <h1>' + value + '</h1>\n\
                             </td></tr>'
                }
                
                else if(value.substring(0,4) == "http" || value.substring(0,6) == "mailto"){
                    links += '<tr><td><a href="' + value + '">' + key + '</a></td></tr>';
                }
                else {
                    html += '\
                            <tr>\n\
                                <td>' + key + '</td>\n\
                                <td>' + value + '</td>\n\
                            </tr>';
                }
            });
            
            links += "</tr>";
            
            html += links;
            $('#societyTable').html(html);
        }   
    });
}