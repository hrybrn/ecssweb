function submit(){
    $('.error').remove();
    var name = $('#name').val();
    var matchmaking = $('#matchmaking').is(":checked");

    if(name == ""){
        fail('name', 'no name given');
        return;
    }

    $.ajax({
        url: "/hackathon/submit/new.php",
		type: 'post',
		data: {
            type: 'team',
            params: {
                hackathonName: name,
                hackathonMatchmaking: matchmaking
            }
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

function fail(failedElement, failure){
    $('#' + failedElement).parent().append("<label class='error'>" + failure + "</label>");
}