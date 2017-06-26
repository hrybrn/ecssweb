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
                    '<h1>' + member.Name + '\n\
                    <a href="' + member.Facebook + '"><img id="linkIcon" src="' + relPath + 'images/icons/facebook-circle.png"></a>\n\
                    <a href="mailto:' + member.Email + '"><img id="linkIcon" src="' + relPath + 'images/icons/email-circle.png"></a></h1>\n\
                    <h3>' + member.RoleDisplayName + '</h3>\n\
                    <i>' + member.RoleDescription + '</i>\n\
                    <p>' + member.Manifesto + '</p>';

            $('#memberTable').html(html);

            var buttons = ($(":button").get());
            var width = "123";
            var i;
            //width += buttons[0].css();
            for (i = 0; i < buttons.length; i++) {
                //width += parseInt(buttons[i].width) + parseInt(buttons[i].margin);
                //width += buttons[i].style.getWidth() + buttons[i].style.getMargin();
            }
            html += width.toString();

            width -= parseInt(buttons[0].margin);
            width += "px";
            html += width;
            $('#memberTable').css('max-width', width);

            $('#memberTable').html(html);


            $('#buttonGroup').css('background-color', 'red');
        }
    });
}