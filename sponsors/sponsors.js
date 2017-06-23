function showMember(id) {
    var name = $('#button' + id).text();

    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: 'name=' + name,
        dataType: 'json',
        success: function (sponsor) {
            $('#sponsorImage').prop('src', relPath + sponsor.Image);

            //table data
            var html = "";
            var links = "";

            delete sponsor.Image;

            $.each(sponsor, function (key, value) {
                if (key == "Name") {
                    html += '<tr><td colspan="2">\n\
                                <h1>' + value + '</h1>\n\
                             </td></tr>';
                } else {
                    if (value.includes("http")) {
                        links += '<a href="' + value + '">' + key + '</a>';
                    } else {
                        if (value.includes("@")) {
                            links += '<a href="mailto:' + value + '">' + key + '</a>';
                        } else {
                            html += '<tr><td>' + key + '</td><td>' + value + '</td></tr>';
                        }
                    }
                }
            });

            $('#sponsorTable').html(html);
            $('#links').html(links);
        }
    });
}