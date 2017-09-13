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
		var index = this.id.replace("task", "");

		var progress = '#prog' + index;

		done.push(this.id);

		$(this).upload("fileUpload.php", data, function(success){
			var imageID = '#img' + index;
			$(imageID).remove();
			$('#para' + index).after("<img class='taskimg' src='" + success + "' id='" + imageID + "'>");
		},
		$(progress));
	});

	if(changes.length > 0){
			$.ajax({
			url: 'save.php',
			type: 'get',
			data: {'changes': changes, 'groupID': groupID, 'time': time},
			dataType: 'json',
		});
	}
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}