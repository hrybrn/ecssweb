$(document).ready(function(){
    getComments($('#showAll').is(":checked"));

    $('#showAll').click(function(){
        getComments($('#showAll').is(":checked"));
    })
});

var commentHistory = {};

function getComments(read){
    $.ajax({
        url: '/comment/committee/search.php',
        type: 'get',
        data: {'read': read},
        dataType: 'json',
        success: function (comments) {
            commentHistory = {};
            var tableHtml = "<tr><th>Comment</th><th>Response</th><th>Responder</th></tr>";

            $.each(comments, function(id, val){
                tableHtml += "<tr><td>" + this.commentMessage + "</td>";
                tableHtml += "<td><textarea rows='4' cols='50' id='response" + id + "'>" + this.adminResponse + "</textarea></td>"
                tableHtml += "<td>" + this.adminName + "</td>";

                commentHistory[id] = this.adminResponse;
            });

            $('#responseTable').html(tableHtml);
        }
    });
}

function save(){
    var changes = {};

    $.each(commentHistory, function(commentID, previousValue){
        if($('#response' + commentID).val() != previousValue){
            changes[commentID] = $('#response' + commentID).val();
        }
    })

    $.ajax({
        url: '/comment/committee/save.php',
        type: 'get',
        data: {'changes': changes},
        dataType: 'json',
        success: function () {
            location.reload();
        }
    });
}