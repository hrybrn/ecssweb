$(document).ready(function(){
    //set dropdowns
    $('#gyear').val(previous.hackathonPersonGraduation);
    $('#tshirtsize').val(previous.hackathonPersonTShirtSize);


    //set diet section
    var diet = '';

    if(previous.hackathonPersonDietComments.startsWith("None ")){
        diet = previous.hackathonPersonDietComments.substring(5);
        $('#dietnone').prop("checked", true);
    } else if(previous.hackathonPersonDietComments.startsWith("Vegetarian ")){
        diet = previous.hackathonPersonDietComments.substring(11);
        $('#dietveg').prop("checked", true);
    } else if(previous.hackathonPersonDietComments.startsWith("Vegan ")){
        diet = previous.hackathonPersonDietComments.substring(6);
        $('#dietvegan').prop("checked", true);
    } else {
        diet = previous.hackathonPersonDietComments;
        $('#other').prop("checked", true);
    }
    $('#dietmessage').val(diet);
});

function update(){
    $('.error').remove();
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

    params.hackathonPersonDietComments = dietMessage + $('#dietmessage').val();

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

    var same = true;

    var sending = {};

    $.each(params, function(key, value){
        if(value != previous[key]){
            same = false;
            sending[key] = value;
        }
    });

    if(same){
        fail('submit', 'no changes made');
        failed = true;
    }
    
    if(failed){
        return;
    }

    $.ajax({
        url: "/hackathon/submit/change.php",
		type: 'post',
		data: {
            type: 'person',
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
            $('#submit').html(nextsteps);
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