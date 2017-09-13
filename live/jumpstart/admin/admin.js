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

	var done = [];

	//upload files
	$('input:file').each(function(){
		var progress = '#prog' + this.id.replace("task", "");

		done.push(this.id);

		$(this).upload("fileUpload.php", data, function(success){
			done.pop();
		}, $(progress));
	});

	$.ajax({
		url: 'save.php',
		type: 'get',
		data: {'changes': changes, 'groupID': groupID, 'time': time},
		dataType: 'json',
		success: function(){
			
			while(done.length > 0){
				sleep(500);
			}

			location.reload();
		}
	});
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}