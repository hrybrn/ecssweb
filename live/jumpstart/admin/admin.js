function save(){
	var changes = [];

	Object.keys(previousEntries).forEach(function(taskID) {
		var oldValue = previousEntries[taskID];

    	var newValue = $('#task' + taskID).val();

		if(newValue != oldValue && newValue != ""){
			changes[taskID] = newValue;
		}
	});

	var time = new Date().toString();

	$.ajax({
		url: 'save.php',
		type: 'get',
		data: {'changes': changes, 'groupID': groupID, 'time': time},
		dataType: 'json',
		success: function (response) {
			location.reload();
		}
	});

	
}