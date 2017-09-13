function save(){
	//save all text inputs
	var changes = [];

	Object.keys(previousEntries).forEach(function(taskID) {
		var oldValue = previousEntries[taskID];

    	var newValue = $('#task' + taskID).val();

		if(newValue != oldValue && newValue != ""){
			changes[taskID] = newValue;
		}
	});

	var time = new Date().toString();

	//generate post data
	var data = {};
	data.groupID = groupID;
	data.time = time;
	data.submit = true;

	//upload files
	$('input:file').each(function(){
		var progress = '#prog' + this.id.replace("task", "");

		$(this).upload("fileUpload.php", data, function(success){
			console.log(success);
		}, $(progress));
	});

	$.ajax({
		url: 'save.php',
		type: 'get',
		data: {'changes': changes, 'groupID': groupID, 'time': time},
		dataType: 'json',
		success: function(){
			//location.reload();
		}
	});
}
