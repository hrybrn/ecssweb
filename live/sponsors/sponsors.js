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

function showMemberWithName(name) {
    var lang = findGetParameter("lang");
    if (lang === null) {
        lang = "en";
    }
    console.log(lang);


    $.ajax({
        url: 'getMember.php',
        type: 'get',
        data: {'name': name, 'lang': lang},
        dataType: 'json',
        success: function (sponsor) {
            $('#sponsorImage').prop('src', relPath + sponsor.Image);

            //show data
            var html = "";
            var links = '<div class="sponsorInfoSection">';

            delete sponsor.Image;

            $.each(sponsor, function (key, value) {
                if (key === "Name") {
                    if (sponsor.Type == "Default") {
                        html += '<div class="sponsorInfoSection"><h1>' + value + '</h1></div>';
                    } else {
                        html += '<div class="sponsorInfoSection"><h1>' + value + '</h1><img id="medalIcon" src="' + relPath + 'images/icons/medal-' + sponsor.Type + '.png"><div id="medaltext">' + sponsor.Type + ' sponsor</div></div>';
                    }
                } else if (value.includes("http") && key != "Info") {
                    links += '<a class="spacedLink" href="' + value + '">' + key + '</a>';
                } else if (key === "Info") {
                    html += '<div class="sponsorInfoSection">' + value + '</div>';
                } else if (value.includes("@")) {
                    links += '<a href="mailto:' + value + '">' + key + '</a>';
                }
            });
            links += "</div>";
            html += links;
            $('#sponsorTable').html(html);
        }
    });
}

function showMember(id) {

    var name = $('#button' + id).text();

    // update url hash
    window.location.hash = name.replace(/ /g, "_");

    showMemberWithName(name);
}

$(document).ready(function () {
    $('#button0').prop("hidden",true);
    if (window.location.hash) {
        var name = window.location.hash.substr(1);
        name = name.replace(/_/g, " ");
        showMemberWithName(name);
    } else {
        showMember("0");
    }
});