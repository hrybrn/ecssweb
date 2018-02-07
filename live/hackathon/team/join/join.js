function submit(){
    var token = $('#teamtoken').val();

    if(token == ""){
        return;
    }

    $.ajax({
        url: "/hackathon/team/join/join.php",
		type: 'post',
		data: {
            'token': token
        },
		dataType: 'json',
		success: function(result){
            if(!result.status){
                return false;
            }
            var nextsteps = "<p>" + result.message + "</p>";
            nextsteps += '<button onclick=\'window.location.href="/hackathon/team/manage"\'>Manage Team</button>';
            $('#submit').html(nextsteps);
        }
    });
}