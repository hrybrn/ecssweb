function submit(){
    //upload the text
    $.ajax({
        url: "/cv/application.php",
		type: 'get',
		data: {
            'name': $('#name').val(),
            'year': $('#year').find(':selected').val(),
            'course': $('#course').val()
        },
		dataType: 'json',
		success: function(result){
            //upload the files
            if(result.status){
                $('#submit').replaceWith("<p id='finalMessage'>" + result.message + "</p>")
                var done = {
                    'CV' : false,
                    'Cover' : false
                };
                uploadDoc(done, "CV", result.applicationID);
                uploadDoc(done, "Cover", result.applicationID);
            }
        }
    });
}

function uploadDoc(done, type, applicationID){
    var data = {};

    data.applicationID = applicationID;
    data.type = type;

    $("#" + type).upload(
        "/cv/documentUpload.php",
        data,
        function(success){
            $('#' + type).replaceWith("<p>" + success.message + "</p>");
            done[type] = true;

            $.each(done, function(){
                if(!this){
                    return;
                }
            });

            $('#finalMessage').html("All files uploaded successfully!");
        },
        $("#prog" + type));
}