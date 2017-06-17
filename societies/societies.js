function showMember(id){
    
    var name = $('#button' + id).text();
    
    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function(member){
            //image setup
            $('#memberImage').prop('src',relPath + member.Image);
            
            //table data
            var html = "";

            delete member.Image;
            
            var links= "<tr>";
            
            $.each(member, function(key,value){
                if(value.substring(0,4) == "http"){
                    links += '<td><a href="' + value + '">' + key + '</a></td>';
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