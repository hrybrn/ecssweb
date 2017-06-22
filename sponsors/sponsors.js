function showMember(id) {
    var name = $('#button' + id).text();

    $.ajax({
        url: 'sponsors/getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function (sponsor) {
            $('#sponsorImage').prop('src', relPath + sponsor.Image);

            //table data
            var html = "";
            
            html += '<tr><td colspan="2">\n\
                                <h1>' + sponsor.Name + '</h1>\n\
                             </td></tr>';
            
            delete sponsor.Name;
            delete sponsor.Image;
            
            $.each(sponsor, function(key,val){
                html += '<tr><td>' + key + '</td><td>' + val + '</td></tr>';
            });
            
            $('#sponsorTable').html(html);
        }
    });
}