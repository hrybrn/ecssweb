function load(){
	$('#groupSelect').change(function(){
		showGroupEntries($('#groupSelect').find(':selected').val());
	});

	$('#taskSelect').change(function(){
		showTaskEntries($('#taskSelect').find(':selected').val());
	});
}

function showGroupEntries(id){
	$.ajax({
		url: '/jumpstart/results/getGroup.php',
		type: 'get',
        data: {'groupID': id, 'lang': lang},
        dataType: 'json',
        success: function (tasks) {
        	$('#tasks').remove();
        	$('body').append(tasks.data);
        }
	});
}

function showTaskEntries(id){
	$.ajax({
		url: '/jumpstart/results/getTask.php',
		type: 'get',
        data: {'taskID': id, 'lang': lang},
        dataType: 'json',
        success: function (tasks) {
        	$('#tasks').remove();
        	$('body').append(tasks.data);
        }
	});
}