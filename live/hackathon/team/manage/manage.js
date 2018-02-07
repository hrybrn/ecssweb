$(document).ready(function(){
    if(previous.hackathonMatchmaking != "false"){
        $('#matchmaking').prop("checked", true);
    }
});

function genToken(){
    $.ajax({
        url: "/hackathon/team/manage/generateToken.php",
		type: 'post',
		data: {},
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            } 
        }
    });
}

function leaveTeam(){
    if(!confirm("Are you sure you want to leave this team?")){
        return;
    }
    $.ajax({
        url: "/hackathon/team/manage/leaveTeam.php",
		type: 'post',
		data: {},
		dataType: 'json',
		success: function(result){
            if(result.status){
                location = "/hackathon";
            } 
        }
    });
}

function disbandTeam(){
    if(!confirm("Are you sure you want to disband this team?")){
        return;
    }
    $.ajax({
        url: "/hackathon/submit/delete.php",
		type: 'post',
		data: {
            type: 'team'
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location = "/hackathon";
            } 
        }
    });
}

function fail(failedElement, failure){
    if(failedElement == 'submit'){
        $('#' + failedElement).append("<label class='error'>" + failure + "</label>");
    } else {
        $('#' + failedElement).parent().append("<label class='error'>" + failure + "</label>");
    }
}

function expire(hashID){
    $.ajax({
        url: "/hackathon/team/manage/expire.php",
		type: 'post',
		data: {
            hashID : hashID
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            }
        }
    });
}

function kick(email){
    $.ajax({
        url: "/hackathon/team/manage/kick.php",
		type: 'post',
		data: {
           email : email
        },
		dataType: 'json',
		success: function(result){
            if(result.status){
                location.reload();
            }
        }
    });
}

function update(){
    $('.error').remove();

    var changed = false;
    var sending = {};

    if($('#matchmaking').is(":checked") && previous.hackathonMatchmaking == 'false'){
        sending.hackathonMatchmaking = $('#matchmaking').is(":checked");
        changed = true;
    }

    if(!$('#matchmaking').is(":checked") && previous.hackathonMatchmaking == 'true'){
        sending.hackathonMatchmaking = $('#matchmaking').is(":checked");
        changed = true;
    }

    if($('#name').val() != previous.hackathonName){
        sending.hackathonName = $('#name').val();
        changed = true;
    }

    if(!changed){
        fail('submit', 'no changes made');
        return;
    }

    if($('#name').val() == ''){
        fail('name', 'cannot have an empty name');
        return;
    }

    $.ajax({
        url: "/hackathon/submit/change.php",
		type: 'post',
		data: {
            type: 'team',
            params: sending
        },
		dataType: 'json',
		success: function(result){
            if(!result.status){
                return false;
            }
            var nextsteps = "<p>" + result.message + "</p>";
            nextsteps += '<button onclick=\'window.location.href="/hackathon"\'>Hackathon Home</button>';
            nextsteps += '<button onclick="update()">Update</button>';
            nextsteps += "<button onclick='genToken()'>Generate Token</button>";
            $('#submit').html(nextsteps);
        }
    });
}