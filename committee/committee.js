function showMember(id){
    
    var name = $('#button' + id).text();
    
    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function(member){
            var html = "";
            
            html+= html += '<tr>\n\
                            <td>' + member.RoleDisplayName + '</td>\n\
                            <td>' + member.RoleDescription + '</td>\n\
                         </tr>';
           
            $.each(member, function(key,value){
                html += '<tr>\n\
                            <td>' + key + '</td>\n\
                            <td>' + value + '</td>\n\
                         </tr>';
            });
            
            $('#memberTable').html(html);
        }   
    });
}