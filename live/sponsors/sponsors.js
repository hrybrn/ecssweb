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
                var links = "<tr><td>";

                delete sponsor.Image;

                $.each(sponsor, function (key, value) {
                    if (key === "Name") {
                        html += '<tr><td colspan="2">\n\
                                <h1>' + value + '</h1><img id="medalIcon" src="' + relPath + 'images/icons/medal-' + sponsor.Type + '.png"><div id="medaltext">' + sponsor.Type + ' sponsor</div>\n\
                             </td></tr>';
                    } else if (value.includes("http")) {
                        links += '<a href="' + value + '">' + key + '</a>';
                    } else if (key === "Info") {
                        html += '<tr><td colspan="2">' + value + '</td></tr>';
                    } else if (value.includes("@")) {
                        links += '<a href="mailto:' + value + '">' + key + '</a>';
                    }
                });
                links += "</td></tr>";
                html += links;
                $('#sponsorTable').html(html);
                //$('#links').html(links);
                }
    });
}