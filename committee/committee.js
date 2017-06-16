function showMember(id){
    var xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function(){
        if(this.status == 200){
            var member = JSON.parse(this.responseText);
            
            var html = "";
            $.each(member, function(key,value){
                html += '<tr>\n\
                            <td>' + key + '</td>\n\
                            <td>' + value + '</td>\n\
                         </tr>';
            });
            
            $('#memberTable').html(html);
        } 
    };
    
    xhttp.open('GET','getMember.php?name=' + id,true);
    xhttp.send();
}