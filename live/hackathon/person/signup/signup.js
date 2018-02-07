function submit(){
    var fields = {
        hackathonPersonName : 'name',
        hackathonPersonCourse :'course',
        hackathonPersonGraduation : 'gyear',
        hackathonPersonTShirtSize : 'tshirtsize'
    };

    var params = {};

    //work out general values
    $.each(fields, function(key, value){
        params[key] = $('#' + value).val();
    });

    //work out diet message
    var dietMessage = "";

    if($('#dietnone').is(":checked")){
        dietMessage += "None ";
    }

    if($('#dietveg').is(":checked")){
        dietMessage += "Vegetarian ";
    }

    if($('#dietvegan').is(":checked")){
        dietMessage += "Vegan ";
    }

    params['hackathonPersonDietComments'] = dietMessage + $('#dietmessage').val();

    var failed = false;
    //check for invalid form entries
    if(params.hackathonPersonName == ""){
        fail('name', 'no name given');
        failed = true;
    }

    if(params.hackathonPersonCourse == ""){
        fail('course', 'no course given');
        failed = true;
    }

    if(params.hackathonPersonTShirtSize == 'select'){
        fail('tshirtsize', 'not selected');
        failed = true;
    }

    if(params.hackathonPersonGraduation == 'select'){
        fail('gyear', 'not selected');
        failed = true;
    }

    if(params.hackathonPersonDietComments == ''){
        fail('dietnone', 'no info given');
    }
    
    if(failed){
        return;
    }

    $.ajax({
        url: "/hackathon/submit/new.php",
		type: 'post',
		data: {
            type: 'person',
            params: params
        },
		dataType: 'json',
		success: function(result){
            if(!result.status){
                return false;
            }
            var nextsteps = "<p>" + result.message + "</p>";
            nextsteps += '<button onclick=\'window.location.href="/hackathon/team/signup"\'>Team Sign Up</button>';
            nextsteps += '<button onclick=\'window.location.href="/hackathon/team/join"\'>Join Existing Team</button>';
            nextsteps += '<button onclick=\'window.location.href="/hackathon/person/manage"\'>Manage Individual</button>';
            $('#submit').html(nextsteps);
        }
    });
}

function fail(failedElement, failure){
    $('#' + failedElement).parent().append("<label class='error'>" + failure + "</label>");
}