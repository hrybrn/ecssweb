var taskScoreID = 0;

function showTask(id){
	$.ajax({
		url: '/jumpstart/committee/getTask.php',
		type: 'get',
        data: {'taskScoreID': id, 'lang': lang},
        dataType: 'json',
        success: function (tasks) {
        	taskScoreID = id;
        	$('#scores').remove();
        	$('body').append(tasks.data);
        }
	});
}

function load(){
	$('#taskSelect').change(function(){
		showTask($('#taskSelect').find(':selected').val());
	});
}

function save(){
	saveData = {};

	var needToSave = false;

	$('.score').each(function(){
		if($(this).val() != ""){
			saveData[$(this).data('groupid')] = $(this).val();

			needToSave = true;
		}
	});

	if(needToSave){
		$.ajax({
			url: '/jumpstart/committee/saveScores.php',
			type: 'get',
	        data: {'taskScoreID': taskScoreID, 'scores': saveData},
	        dataType: 'json',
	        success: function(result){
	        	if(!result.status){
	        		return false;
	        	}

	        	showTask(taskScoreID);
	        }
		});
	}
}